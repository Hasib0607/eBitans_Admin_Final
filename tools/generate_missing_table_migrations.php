<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$default = config('database.default');
$driver = config("database.connections.$default.driver");
if ($driver !== 'mysql') {
    fwrite(STDERR, "This generator currently supports only mysql. Detected: $driver\n");
    exit(2);
}

$dbName = config("database.connections.$default.database");
if (! $dbName) {
    fwrite(STDERR, "DB database name is empty.\n");
    exit(2);
}

/** @var Illuminate\Database\Connection $db */
$db = Illuminate\Support\Facades\DB::connection($default);

$tables = $db->select(
    "select table_name as name from information_schema.tables where table_schema = ? and table_type = 'BASE TABLE' order by table_name",
    [$dbName]
);
$dbTables = array_map(fn ($r) => $r->name, $tables);

// Parse existing migrations for Schema::create('table')
$migrationFiles = glob(__DIR__ . '/../database/migrations/*.php');
$created = [];
$createRe = "/Schema::create\\(\\s*['\\\"]([^'\\\"]+)['\\\"]/i";
foreach ($migrationFiles as $file) {
    $contents = file_get_contents($file);
    if (preg_match_all($createRe, $contents, $m)) {
        foreach ($m[1] as $t) {
            $created[$t] = true;
        }
    }
}

$exclude = [
    'migrations' => true,
];

$missing = [];
foreach ($dbTables as $t) {
    if (isset($exclude[$t]) || isset($created[$t])) {
        continue;
    }
    $missing[] = $t;
}

if ($missing === []) {
    echo "No missing create migrations found.\n";
    exit(0);
}

$baseTs = time();
$i = 0;
foreach ($missing as $table) {
    $row = $db->selectOne("SHOW CREATE TABLE `{$table}`");
    // SHOW CREATE TABLE returns columns: Table, Create Table (case depends)
    $createSql = null;
    foreach ((array) $row as $k => $v) {
        if (is_string($k) && stripos($k, 'create table') !== false) {
            $createSql = $v;
            break;
        }
        if ($k === 'Create Table') {
            $createSql = $v;
            break;
        }
    }
    if (! $createSql) {
        // Fallback: pick the longest string field
        $vals = array_filter((array) $row, fn ($v) => is_string($v));
        usort($vals, fn ($a, $b) => strlen($b) <=> strlen($a));
        $createSql = $vals[0] ?? null;
    }
    if (! $createSql) {
        fwrite(STDERR, "Failed to read CREATE TABLE for {$table}\n");
        continue;
    }

    $ts = date('Y_m_d_His', $baseTs + $i);
    $i++;
    $fileName = __DIR__ . '/../database/migrations/' . $ts . '_create_' . $table . '_table.php';

    $php = <<<PHP
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('{$table}')) {
            return;
        }

        DB::statement(<<<'SQL'
{$createSql}
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
PHP;

    file_put_contents($fileName, $php);
    echo "Generated: database/migrations/" . basename($fileName) . "\n";
}
