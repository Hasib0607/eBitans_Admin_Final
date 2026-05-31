<?php
namespace App\Logic\Providers;

class cPanelApi
{

    public function __construct($cpanelUrl, $cpaneluser, $cpanelPwd, $cpanelPort = '2083')
    {
        $this->cPanelUser = $cpaneluser;
        $this->cPanelPwd = $cpanelPwd;
        $this->cPanelUrl = $cpanelUrl;
        $this->cPanelPort = $cpanelPort;
    }

    /////////////// MYSQL CPANEL //////////////////

    public function createDataBaseMySQL($database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/create_database?name=$database";
        return $this->exe_cpanel($func);

    }

    public function createUserMySQL($user, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/create_user?name=$user&password=$password";
        return $this->exe_cpanel($func);

    }

    public function deleteDataBaseMySQL($database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/delete_database?name=$database";
        return $this->exe_cpanel($func);

    }

    public function deleteUserMySQL($user)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/delete_user?name=$user";
        return $this->exe_cpanel($func);

    }


    public function setPrivilegesMySQL($user, $database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/set_privileges_on_database?user=$user&database=$database&privileges=ALL";
        return $this->exe_cpanel($func);

    }

    public function setPasswordUserMySQL($user, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/set_password?user=$user&password=$password";
        return $this->exe_cpanel($func);

    }

    public function renameUserMySQL($userold, $usernew)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/rename_user?oldname=$userold&newname=$usernew";
        return $this->exe_cpanel($func);

    }

    public function renameDataBaseMySQL($dbold, $dbnew)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/rename_database?oldname=$dbold&newname=$dbnew";
        return $this->exe_cpanel($func);

    }

    public function checkDataBaseMySQL($database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/check_database?name=$database";
        return $this->exe_cpanel($func);

    }

    public function dumpDataBaseMySQL($database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Mysql/dump_database_schema?dbname=$database";
        return $this->exe_cpanel($func);

    }


    /////////////// END MYSQL CPANEL //////////////////


    /////////////// POSTGRESQL CPANEL //////////////////

    public function createDataBasePostgre($database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Postgresql/create_database?name=$database";
        return $this->exe_cpanel($func);

    }

    public function createUserPostgre($user, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Postgresql/create_user?name=$user&password=$password";
        return $this->exe_cpanel($func);

    }

    public function deleteDataBasePostgre($database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Postgresql/delete_database?name=$database";
        return $this->exe_cpanel($func);

    }

    public function deleteUserPostgre($user)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Postgresql/delete_user?name=$user";
        return $this->exe_cpanel($func);

    }

    public function allPrivilegesPostgre($user, $database)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Postgresql/grant_all_privileges?user=$user&database=$database";
        return $this->exe_cpanel($func);

    }

    /////////////// END POSTGRESQL CPANEL //////////////////


    /////////////// QUOTA CPANEL //////////////////

    public function getLocalQuota()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Quota/get_local_quota_info";
        return $this->exe_cpanel($func);

    }

    public function getInfoQuota()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Quota/get_quota_info";
        return $this->exe_cpanel($func);

    }

    /////////////// END QUOTA CPANEL //////////////////


    /////////////// GET SERVER INFO //////////////////

    public function getServerInfo()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/ServerInformation/get_information";
        return $this->exe_cpanel($func);

    }

    /////////////// END SERVER INFO //////////////////

    public function clearSpamBox()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/SpamAssassin/clear_spam_box";
        return $this->exe_cpanel($func);

    }

    public function getBandwidth($timezone)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Stats/get_bandwidth?timezone=$timezone";
        return $this->exe_cpanel($func);

    }

    public function getErrors()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Stats/get_site_errors?domain=$this->cPanelUrl&log=suexec&maxlines=250";
        return $this->exe_cpanel($func);

    }

    public function createSubdomain($subdomain, $folder = '')
    {

        if ($folder == '') {
            $folderdir = '%2Fpublic_html%2F' . $subdomain;
        } else {
            $folderdir = '%2Fpublic_html%2F' . $folder;
        }

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/SubDomain/addsubdomain?domain=$subdomain&rootdomain=$this->cPanelUrl&dir=$folderdir&disallowdot=0";
        return $this->exe_cpanel($func);

    }

    /////////// BACKUPS ///////////////

    public function ftpBackupFull($username, $password, $ftp, $emailnoty, $dirftp = 'public_ftp', $port = '21')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Backup/fullbackup_to_ftp?variant=active&username=$username&password=$password&host=$ftp&port=$port&directory=%2F$dirftp&email=$emailnoty";
        return $this->exe_cpanel($func);

    }

    public function createBackup()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Backup/fullbackup_to_homedir";
        return $this->exe_cpanel($func);

    }

    public function restaureBackup($dir)
    {

        // /home/cpuser/backup_cpuser_9-10-2019.tar.gz

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Backup/restore_files?backup=$dir&verbose=1&timeout-3600";
        return $this->exe_cpanel($func);

    }

    /////////// END BACKUPS ///////////////

    /////////// FTP ///////////////

    public function ftpCreate($user, $password, $quota = '1024')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Ftp/add_ftp?user=$user&pass=$password&quota=$quota";
        return $this->exe_cpanel($func);

    }

    public function ftpHomeDir($user, $homedir)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Ftp/set_homedir?user=$user&domain=$this->cPanelUrl&homedir=$homedir%2F";
        return $this->exe_cpanel($func);

    }

    public function ftpQuota($user, $quota)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Ftp/set_quota?user=$user&quota=$quota";
        return $this->exe_cpanel($func);

    }

    public function ftpSetPassword($user, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Ftp/passwd?user=$user&pass=$password";
        return $this->exe_cpanel($func);

    }

    public function ftpDelete($user)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Ftp/delete_ftp?user=$user&destroy=1";
        return $this->exe_cpanel($func);

    }

    /////////// END FTP ///////////////


    /////////// EMAIL ///////////////

    public function createEmail($usermail, $password, $quota = '0')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/add_pop?email=$usermail&password=$password&quota=$quota&domain=$this->cPanelUrl&send_welcome_email=1";
        return $this->exe_cpanel($func);

    }

    public function deleteEmail($usermail)
    {
        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/delete_pop?email=$usermail&domain=$this->cPanelUrl";
        //        return $this->exe_cpanel($func);
        $deleteResult = $this->exe_cpanel($func);

        $emailUsername = explode('@', $usermail)[0];
        $domain = $this->cPanelUrl;
        // 2. Delete the mail folder (using Fileman API)
        $this->deleteMailFolderCpanelApi($domain, $emailUsername);

        return $deleteResult;

    }

    protected function deleteMailFolderCpanelApi($domain, $emailUsername)
    {
        $folderPath = "/mail/$domain/$emailUsername";

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Fileman/delete_user_file?path=" . urlencode($folderPath);

        return $this->exe_cpanel($func);
    }


    public function listEmail($usermail)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/list_pops?regex=$usermail";
        return $this->exe_cpanel($func);

    }


    public function setPasswordEmail($usermail, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/passwd_pop?email=$usermail&password=$password&domain=$this->cPanelUrl";
        return $this->exe_cpanel($func);

    }


    public function addSpamFilter($email, $score = '8.0')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/add_spam_filter?required_score=$score&account=$email";
        return $this->exe_cpanel($func);

    }

    public function addForwarder($usermail, $emailfwd)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/add_forwarder?domain=$this->cPanelUrl&email=$usermail%40$this->cPanelUrl&fwdopt=fwd&fwdemail=$emailfwd";
        return $this->exe_cpanel($func);

    }

    public function suspendEmail($email)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/suspend_login?email=$email";
        return $this->exe_cpanel($func);

    }

    public function unsuspendEmail($email)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/unsuspend_login?email=$email";
        return $this->exe_cpanel($func);

    }

    public function verifyPasswordEmail($email, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/verify_password?email=$email&password=$password";
        return $this->exe_cpanel($func);

    }

    public function traceDeliveryEmail($email)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/trace_delivery?recipient=$email";
        return $this->exe_cpanel($func);

    }

    public function getSpamSettings($email)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/get_spam_settings?account=$email";
        return $this->exe_cpanel($func);

    }

    public function quotaEmail($usermail)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Email/get_pop_quota?email=$usermail&domain=$this->cPanelUrl&as_bytes=1";
        return $this->exe_cpanel($func);

    }

    /////////// END EMAIL ///////////////

    /////////// ADDONS ///////////////


    public function getUsages()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/ResourceUsage/get_usages";
        return $this->exe_cpanel($func);

    }

    public function getResellers()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Resellers/list_accounts";
        return $this->exe_cpanel($func);

    }

    public function setLocale($locale = 'en')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Locale/set_locale?locale=$locale";
        return $this->exe_cpanel($func);

    }

    public function getThemes()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Themes/get_theme_base";
        return $this->exe_cpanel($func);

    }

    public function setTheme($theme = 'paper_lantern')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Themes/update?theme=$theme";
        return $this->exe_cpanel($func);

    }

    public function emptyTrash($days = '31')
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Fileman/empty_trash?&older_than=$days";
        return $this->exe_cpanel($func);

    }

    /////////// SSL ///////////////

    public function autoSSL()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/SSL/start_autossl_check";
        return $this->exe_cpanel($func);

    }


    public function autoSSLProblems()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/SSL/get_autossl_problems";
        return $this->exe_cpanel($func);

    }

    public function autoSSLExclude($domain)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/SSL/add_autossl_excluded_domains?domains=$domain";
        return $this->exe_cpanel($func);

    }

    public function autoSSLRemoveExclude($domain)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/SSL/remove_autossl_excluded_domains?domains=$domain";
        return $this->exe_cpanel($func);

    }

    /////////// TOKEN LOGIN ///////////////

    public function createToken($nametoken, $time = '6')
    {
        $timetoken = strtotime("+$time hours"); // Default Hours
        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Tokens/create_full_access?name=$nametoken&expires_at=$timetoken";
        return $this->exe_cpanel($func);
    }

    public function revokeToken($nametoken)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/Tokens/revoke?name=$nametoken";
        return $this->exe_cpanel($func);

    }

    /////////// WORDPRESS FUNCTION ///////////////

    public function wordpressBackup()
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/WordPressBackup/start?site=$this->cPanelUrl";
        return $this->exe_cpanel($func);

    }

    public function wordpressSetPassword($wpid, $password)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/WordPressInstanceManager/change_admin_password?id=$wpid&password=$password";
        return $this->exe_cpanel($func);

    }

    public function wordpressRestaure($backupfile)
    {

        //PATCH DO BACK /home/user/public_html/backup.gz

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/WordPressRestore/start?site=$this->cPanelUrl&backup_path=$backupfile";
        return $this->exe_cpanel($func);

    }

    /////////// DOMAINS ///////////////

    public function listDomains()
    {
        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/DomainInfo/list_domains";
        return $this->exe_cpanel($func);
    }

    public function listDataDomains()
    {
        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/DomainInfo/domains_data?format=hash&return_https_redirect_status=1";
        return $this->exe_cpanel($func);
    }

    public function getDataDomain($domain)
    {

        $func = "https://$this->cPanelUrl:$this->cPanelPort/execute/DomainInfo/single_domain_data?domain=$domain&return_https_redirect_status=1";
        return $this->exe_cpanel($func);

    }

    public function addDomain($domain)
    {
        $new_domain = cleanDomain($domain); // Domain you want to add
        $subdomain = $new_domain; // Subdomain part of the domain
        $directory = $new_domain; // Directory where files will be stored

        $this->enableAutoSSL($new_domain);

        $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&cpanel_jsonapi_func=addaddondomain&newdomain=$new_domain&subdomain=$subdomain&dir=$directory";
        return $this->exe_cpanel($func);
    }

    public function addSubdomain($domain)
    {
        // Parent domain (main domain, e.g., "ebitans.com")
        $parentDomain = cleanDomain($domain);

        $subdomain = "admin." . $parentDomain;

        $this->enableAutoSSL($subdomain);

        // Document root for the subdomain
        $docRoot = env("ADMIN_DOCUMENT_ROOT", "/admin.ebitans.com");

        $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&cpanel_jsonapi_func=addaddondomain&newdomain=$subdomain&subdomain=$subdomain&dir=$docRoot";

        // Execute the API call
        return $this->exe_cpanel($func);
    }

    public function enableAutoSSL($domain)
    {
        // Subdomain or main domain for AutoSSL
        $targetDomain = cleanDomain($domain); // e.g., "admin.giveandtake.live"

        // cPanel API URL for installing SSL certificates (AutoSSL trigger)
        $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=SSL&cpanel_jsonapi_func=install_ssl&domain=$targetDomain";

        // Execute the cPanel API call
        return $this->exe_cpanel($func);
    }


    public function updateMxToWebmail($domain)
    {
        // Step 1: Fetch existing MX records
        $fetchUrl = "https://{$this->cPanelUrl}:{$this->cPanelPort}/json-api/cpanel?" . http_build_query([
            'cpanel_jsonapi_user' => $this->cPanelUser,
            'cpanel_jsonapi_apiversion' => 2,
            'cpanel_jsonapi_module' => 'ZoneEdit',
            'cpanel_jsonapi_func' => 'fetchzone_records',
            'domain' => $domain
        ]);

        $records = $this->exe_cpanel($fetchUrl);
        $responseData = json_decode($records);

        if (!isset($responseData->cpanelresult->data)) {
            return ['error' => 'Unable to fetch DNS zone records.'];
        }

        // Step 2: Find all MX record lines
        $mxZoneIds = [];
        foreach ($responseData->cpanelresult->data as $record) {
            if (
                $record->type === 'MX' &&
                rtrim($record->name, '.') === $domain &&
                isset($record->exchange)
            ) {
                $mxZoneIds[] = [
                    'line' => $record->line,
                    'ttl' => $record->ttl ?? 14400,
                    'priority' => $record->priority ?? 10
                ];
            }
        }

        if (empty($mxZoneIds)) {
            return ['error' => 'No MX records found for this domain.'];
        }

        // Step 3: Edit all existing MX records to point to webmail.domain.com
        foreach ($mxZoneIds as $mx) {
            $editUrl = "https://{$this->cPanelUrl}:{$this->cPanelPort}/json-api/cpanel?" . http_build_query([
                'cpanel_jsonapi_user' => $this->cPanelUrl,
                'cpanel_jsonapi_apiversion' => 2,
                'cpanel_jsonapi_module' => 'ZoneEdit',
                'cpanel_jsonapi_func' => 'edit_zone_record',
                'domain' => $domain,
                'line' => $mx['line'],
                'name' => rtrim($domain, '.') . '.',
                'type' => 'MX',
                'ttl' => $mx['ttl'],
                'preference' => 10,
                'exchange' => 'webmail.' . rtrim($domain, '.') . '.'
            ]);

            $editResponse = $this->exe_cpanel($editUrl);
            $editData = json_decode($editResponse);

            if (!isset($editData->cpanelresult->data[0]->result->status) || $editData->cpanelresult->data[0]->result->status !== 1) {
                return ['error' => 'Failed to edit MX record', 'details' => $editData];
            }
        }

        // Step 4: Set mail routing (if you have this method)
//        $mailRoutingResponse = $this->setLocalMailRouting($domain);
//        $mailRouting = json_decode($mailRoutingResponse);
//        if (!isset($mailRouting->status) || $mailRouting->status !== 1) {
//            return [
//                'warning' => 'MX records updated, but mail routing not set to local.',
//                'details' => $mailRouting
//            ];
//        }

        return ['success' => true];
    }


    public function setLocalMailRouting($domain)
    {
        $func = "https://{$this->cPanelUrl}:{$this->cPanelPort}/execute/Email/edit_mxcheck?" . http_build_query([
            'domain' => $domain,
            'mxcheck' => 'local' // 'auto', 'backup', 'remote' also allowed
        ]);


        return $this->exe_cpanel($func);
    }


    public function addZoneEditor($domain, $record_type, $record_value)
    {
        $addon_domain = cleanDomain($domain); // The addon domain for which you want to add records

        // Determine the record name based on the type of record
        if ($record_type == "CNAME") {
            // For CNAME records, we usually don't prefix with www, but you can adjust as needed
            $record_name = "www.$addon_domain."; // or just $addon_domain. if you want to create a CNAME for the root domain

            // Ensure the target has a trailing dot
            $record_value = rtrim($record_value, '.') . '.';

            $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=ZoneEdit&cpanel_jsonapi_func=add_zone_record&domain=$domain&type=CNAME&name=$record_name&cname=$record_value";

            return $this->exe_cpanel($func);
        } else if ($record_type == "A") {
            $record_name = "$addon_domain.";

            $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=ZoneEdit&cpanel_jsonapi_func=add_zone_record&domain=$addon_domain&name=$record_name&type=A&address=$record_value";

            return $this->exe_cpanel($func);
        } else {
            // Default for A records
            $record_name = "$addon_domain.";

            $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=ZoneEdit&cpanel_jsonapi_func=add_zone_record&domain=$addon_domain&name=$record_name&type=$record_type&address=$record_value";

            return $this->exe_cpanel($func);
        }
    }


    public function deleteDomainZoneEditorRecord($domain, $record_type)
    {
        $addon_domain = cleanDomain($domain); // e.g. watchtimebd.com
        $record_name = 'www';                // default record name

        $data = $this->getDomainZoneEditorRecordList($domain);
        $responseData = json_decode($data);

        // Find the specific record ID
        $record_id = null;

        if (isset($responseData->cpanelresult->data)) {
            foreach ($responseData->cpanelresult->data as $record) {

                if (!isset($record->name) || !isset($record->type)) {
                    continue;
                }

                // Default match: www.addon_domain.
                $matchNAme = "$record_name.$addon_domain.";

                // ✅ Apex match for A and AAAA: addon_domain.
                if ($record->type == "A" || $record->type == "AAAA") {
                    $matchNAme = "$addon_domain.";
                }

                // Only delete the requested type + matched name
                if ($record->name == $matchNAme && $record->type == $record_type) {
                    $record_id = $record->line;
                    break;
                }
            }
        }

        if ($record_id) {
            $delete_response = $this->deleteZoneEditorRecordByID($domain, $record_id);
            $deleteResponseData = json_decode($delete_response);

            if (isset($deleteResponseData->cpanelresult->error)) {
                return false;
            }
        }

        return true;
    }

    public function deleteZoneEditorRecordByID($domain, $record_id)
    {
        $domain = cleanDomain($domain);

        $func = "https://$this->cPanelUrl:$this->cPanelPort/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=ZoneEdit&cpanel_jsonapi_func=remove_zone_record&domain=$domain&line=$record_id";
        return $this->exe_cpanel($func);
    }

    public function getDomainZoneEditorRecordList($domain)
    {
        $domain = cleanDomain($domain);

        $func = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=ZoneEdit&cpanel_jsonapi_func=fetchzone_records&domain=$domain";
        return $this->exe_cpanel($func);
    }

    public function checkDomainExist($domain)
    {
        try {
            $domain = cleanDomain($domain);

            $checkDomainUrl = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&cpanel_jsonapi_func=listaddondomains";

            $addonDomainsResponse = $this->exe_cpanel($checkDomainUrl);
            $addonDomains = json_decode($addonDomainsResponse, true);

            $domainExists = false;
            if (isset($addonDomains['cpanelresult']['data']) && is_array($addonDomains['cpanelresult']['data'])) {
                foreach ($addonDomains['cpanelresult']['data'] as $addonDomain) {
                    if (isset($addonDomain['domain']) && $addonDomain['domain'] === $domain) {
                        //                        $subdomain = $addonDomain['domain'];
//                        $unparkDomainUrl = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Park&cpanel_jsonapi_func=unpark&domain=$subdomain";
//                        $rest = $this->exe_cpanel($unparkDomainUrl);

                        //
//                        $listParkedDomainsUrl = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Park&cpanel_jsonapi_func=listparkeddomains";
//                        $response = $this->exe_cpanel($listParkedDomainsUrl);
                        $domainExists = $addonDomain;
                        break;
                    }
                }
            }

            return $domainExists;
        } catch (\Exception $exception) {
            return false;
        }

    }

    public function deleteDomain($domain)
    {
        try {
            $result = $this->checkDomainExist($domain);
            $domain = $result['domain'] ?? null;
            $subdomain = $result['subdomain'] ?? null;

            $dataUpdate = true;

            if ($domain && $subdomain) {
                $deleteAddonDomainUrl = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&cpanel_jsonapi_func=deladdondomain&domain=$domain";
                $addonDomainResponse = $this->exe_cpanel($deleteAddonDomainUrl);

                if (!$addonDomainResponse || strpos($addonDomainResponse, '"status":1') === false) {
                    $dataUpdate = false;
                }

            } else {
                $dataUpdate = false;
            }

            return $dataUpdate;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function checkDomainFolderExist($domain)
    {
        $result = $this->checkDomainExist($domain);
        $docRoot = $result['dir'] ?? null;

        $folderExists = false;
        if ($docRoot) {
            $checkFolderUrl = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Fileman&cpanel_jsonapi_func=listfiles&dir=" . urlencode(dirname($docRoot));

            $folderResponse = $this->exe_cpanel($checkFolderUrl);
            $folderData = json_decode($folderResponse, true);

            if (isset($folderData['cpanelresult']['data']) && is_array($folderData['cpanelresult']['data'])) {
                foreach ($folderData['cpanelresult']['data'] as $file) {
                    if ($file['file'] === basename($docRoot) && $file['type'] === 'dir') {
                        $folderExists = $file;
                        break;
                    }
                }
            }
        }

        return $folderExists;
    }

    public function deleteDomainFolder($domain)
    {
        $result = $this->checkDomainFolderExist($domain);
        $docRoot = null;
        if ($result) {
            $docRoot = urlencode($result['fullpath']) ?? null;
        }

        $deleteFile = true;

        if ($docRoot) {
            $deleteFolderUrl = "https://$this->cPanelUrl:$this->cPanelPort/cpsess1235467/json-api/cpanel?cpanel_jsonapi_user=$this->cPanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Fileman&cpanel_jsonapi_func=fileop&op=unlink&sourcefiles=$docRoot";

            $folderResponse = $this->exe_cpanel($deleteFolderUrl);
            $folderResponse = json_decode($folderResponse, true);

            if (isset($folderResponse['cpanelresult']['error'])) {
                $deleteFile = false;
            } elseif (isset($folderResponse['cpanelresult']['data'][0]['result']) && $folderResponse['cpanelresult']['data'][0]['result'] == 1) {
                $deleteFile = true;
            } else {
                $deleteFile = false;
            }
        }

        return $deleteFile;
    }


    /////////// PASSWORDS ///////////////
    public function secure_password($length = 20)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789-=~!@#$%^&*()_+./<>?;:[]{}|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++)
            $str .= $chars[random_int(0, $max)];

        return $str;
    }

    public function simple_password($length = 20)
    {

        $password_string = '!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
        $password = substr(str_shuffle($password_string), 0, 12);
        return $password;
    }

    private function exe_cpanel($func = '')
    {
        $query = $func;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: Basic " . base64_encode($this->cPanelUser . ":" . $this->cPanelPwd) . "\n\r";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $query);
        $result = curl_exec($curl);
        if ($result == false) {
            error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $query");
        }
        curl_close($curl);
        return $result;
    }

}

?>