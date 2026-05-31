<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Banner;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Menu;
use App\Models\Staff;
use App\Models\File as UiFile;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Headersetting;
use App\Models\Design;
use App\Models\Designlist;
use App\Models\Toptool;
use App\Models\Testimonial;
use App\Models\Invoicepurchase;
use App\Logic\Providers\cPanelApi;
use Illuminate\Support\Facades\File;
use Session;
use ZipArchive;

class FileControlController extends Controller
{
    public function filecontrol()
    {
        $urls = "filecontrol";
        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));
        $data = json_decode($api->listDomains())->data;

        $files = UiFile::orderBy('id', 'desc')->paginate(10);
//        dd($files);
        return view('superadmin.filecontrol.index', compact('urls', 'data', 'files'));
    }

    public function testMY()
    {

        $zip = new ZipArchive;
        $res = $zip->open('/home/ebitans/admin.ebitans.com/public/Files/File.zip');
        if ($res === TRUE) {
            $zip->extractTo('/home/ebitans/kabir.ebitans.com');
            $zip->close();
            echo 'woot!';
        } else {
            echo 'doh!';
        }
    }

    public function fileuploads(Request $request)
    {
        if ($request->file('file_name') == null) {
            Session::flash('error', 'Failed');
            return back();
        }

        $fileName = $request->version_name . '_' . date('Y-m-d_H-i-s') . '.' . $request->file_name->extension();

        // Public Folder
        $request->file_name->move(public_path('UiFiles'), $fileName);

        $file = new UiFile();
        $file->version_name = $request->version_name;
        $file->build_css = $request->build_css;
        $file->build_js = $request->build_js;
        $file->file_name = $fileName;
        $file->save();
        // return $file;


        Session::flash('message', 'Successfully Uploaded File');
        return back();
    }

    public function deletefile($id)
    {
        $UiFile = UiFile::find($id);

        if (File::exists(public_path('Files/' . $UiFile->file_name))) {
            File::delete(public_path('Files/' . $UiFile->file_name));
        } else {
            Session::flash('message', 'File does not exists.');
        }

        $UiFile->delete();
        Session::flash('message', 'Successfully Deleted File');
        return back();
    }

    public function deletedataa($domain)
    {
        $url = $domain . "/" . "delete.php";
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $url);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_exec($ch1);
        curl_close($ch1);
        Session::flash('message', 'Successfully Deleted File');
        return back();
    }

    function rrmdir($src)
    {
        if (file_exists($src)) {
            $dir = opendir($src);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    $full = $src . '/' . $file;
                    if (is_dir($full)) {
                        rrmdir($full);
                    } else {
                        unlink($full);
                    }
                }
            }
            closedir($dir);
            rmdir($src);
        }
    }

    public function copyfile(Request $request)
    {
        if (empty($request->file)) {
            Session::flash('error', 'Please Select a File');
            return back();
        } else {
            $fil = UiFile::find($request->file);
            $filename = $fil->file_name;
            $domain = $request->domain;

            $zip = new ZipArchive;
            $res = $zip->open('/home/ebitans/admin.ebitans.com/public/UiFiles/' . $filename);
            if ($res === TRUE) {
                $zip->extractTo('/home/ebitans/' . $domain);
                $zip->close();
                Session::flash('message', 'Successfully Copy File');
            } else {
                Session::flash('message', 'Sorry brother Can"t Copy this File.');
            }


            // index file replace
            $ok = 'oke Done';

            $myfile = fopen("index.html", "w") or die("Unable to open file!");
            $txt = '
            <!doctype html>
            <html lang="en" classname="dark">

            <head>
              <meta charset="utf-8" />
              <link id="favicon" rel="icon" href="./favicon.ico" />
              <meta name="viewport" content="width=device-width,initial-scale=1" />
              <meta name="theme-color" content="#000000" />
              <meta name="title" content="EBitans | We Build You Sale" data-rh="true" />
              <meta property="type" content="website" />
              <meta property="fb:app_id" content="399283462300859" />
              <meta property="image:width" content="300" />
              <meta property="image:height" content="200" />
              <meta name="image" content="https://ebitans.com/Image/cover/eBitans-logo-mockup4.jpg" />
              <meta name="description"
                content="eBbitans is a platform where you can create an E-commerce website for your business with just a few clicks."
                data-rh="true" />
              <meta property="og:title" content="eBitans | We Build You Sale" data-rh="true" />
              <meta property="og:description"
                content="eBitans is a platform where you can create an E-commerce website for your business with just a few clicks."
                data-rh="true" />
              <meta property="og:type" content="website" />
              <meta property="fb:app_id" content="399283462300859" />
              <meta property="og:image:width" content="300" />
              <meta property="og:image:height" content="200" />
              <meta property="og:image" content="https://ebitans.com/Image/cover/eBitans-logo-mockup4.jpg" />
              <meta property="og:url" content="https://ebitans.com" />
              <link rel="apple-touch-icon" href="./favicon.ico" />
              <link rel="manifest" href="./manifest.json" />
              <link rel="preconnect" href="https://fonts.googleapis.com">
              <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
              <link href="https://fonts.googleapis.com/css2?family=Edu+VIC+WA+NT+Beginner&display=swap" rel="stylesheet">
              <script src="./TW-ELEMENTS-PATH/dist/js/index.min.js"></script>
              <base href="/">
              <script>!function (e, t, n, c, o, a, f) { e.fbq || (o = e.fbq = function () { o.callMethod ? o.callMethod.apply(o, arguments) : o.queue.push(arguments) }, e._fbq || (e._fbq = o), o.push = o, o.loaded = !0, o.version = "2.0", o.queue = [], (a = t.createElement(n)).async = !0, a.src = "https://connect.facebook.net/en_US/fbevents.js", (f = t.getElementsByTagName(n)[0]).parentNode.insertBefore(a, f)) }(window, document, "script"), fbq("init", "1312207246243465"), fbq("track", "PageView")</script>
              <noscript><img height="1" width="1" style="display:none"
                  src="https://www.facebook.com/tr?id=1312207246243465&ev=PageView&noscript=1" /></noscript>
              <title>' . $ok . '</title>
              <script defer="defer" src="./static/js/main.e50808c4.js"></script>
              <link href="./static/css/main.bba042e5.css" rel="stylesheet">
            </head>
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-X3YV7Q97C9"></script>
            <script>function gtag() { dataLayer.push(arguments) } window.dataLayer = window.dataLayer || [], gtag("js", new Date), gtag("config", "G-X3YV7Q97C9")</script>

            <body><noscript>You need to enable JavaScript to run this app.</noscript>
              <div id="root"></div>
              <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
              <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
            </body>

            </html>
            ';
            fwrite($myfile, $txt);

            fclose($myfile);
        }

        Session::flash('message', 'Please Select a File.');
        return back();
    }

    public function copydelete($dir)
    {
        $username = 'ebitans';
        $password = '@@{UuM.KgYpks@@WaveBox@Hasib@@01886515571@@unKown@@0^07199%01677515579@supersecrate@@0^07199%hh$%^hh@@`~';
        $cpanel_host = 'ebitans.com';
        $request_uri = "https://$cpanel_host:2083/execute/Fileman/upload_files";
        $upload_file = public_path("assets/images/file/delete.php");
        // dd($upload_file);
        $destination_dir = $dir;
        if (function_exists('curl_file_create')) {
            $cf = curl_file_create($upload_file);
        } else {
            $cf = "@/" . $upload_file;
        }
        $payload = array(
            'dir' => $destination_dir,
            'file-1' => $cf
        );
        // dd($payload);

        $ch = curl_init($request_uri);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($curl_response);
        // return $response;
        return $response;
    }

    public function copydefault($dir)
    {
        $username = 'ebitans';
        $password = '@@{UuM.KgYpks@@WaveBox@Hasib@@01886515571@@unKown@@0^07199%01677515579@supersecrate@@0^07199%hh$%^hh@@`~';
        $cpanel_host = 'ebitans.com';
        $request_uri = "https://$cpanel_host:2083/execute/Fileman/upload_files";
        $upload_file = public_path("assets/images/file/default.php");
        // dd($upload_file);
        $destination_dir = $dir;
        if (function_exists('curl_file_create')) {
            $cf = curl_file_create($upload_file);
        } else {
            $cf = "@/" . $upload_file;
        }
        $payload = array(
            'dir' => $destination_dir,
            'file-1' => $cf
        );
        // dd($payload);

        $ch = curl_init($request_uri);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($curl_response);
        // return $response;
        return $response;
    }

    public function extractff($dir, $filename)
    {
        $url = $dir . "/default.php";
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $url);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_exec($ch1);
        curl_close($ch1);
        // dd($ch1);
        return $ch1;
    }

    public function deletedomainsdata(Request $request)
    {
        // dd($request->all());
        $dat = explode(',', $request->text2);
        foreach ($dat as $domain) {
            $url = $domain . "/" . "delete.php";
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $url);
            curl_setopt($ch1, CURLOPT_HEADER, 0);
            curl_exec($ch1);
            curl_close($ch1);
        }
        Session::flash('message', 'Successfully Deleted File');
        return back();
    }


    public function copyfilemultiple(Request $request)
    {
        if (empty($request->file)) {
            Session::flash('error', 'Please Select a File');
            return back();
        } else {
            $fil = UiFile::find($request->file);
            $filename = $fil->file_name;
            $domains = explode(",", $request->domain);
            foreach ($domains as $key => $domainName) {
                $zip = new ZipArchive;
                $res = $zip->open('/home/ebitans/admin.ebitans.com/public/UiFiles/' . $filename);
                if ($res === TRUE) {
                    $zip->extractTo('/home/ebitans/' . $domainName);
                    $zip->close();
                    Session::flash('message', 'Successfully Copy File');
                } else {
                    Session::flash('message', 'Sorry brother Can"t Copy this File.');
                }
            }


            $myfile = fopen("ind.html", "a") or die("Unable to open file!");
            $txt = "Donald Duck\n";
            fwrite($myfile, $txt);
            $txt = "Goofy Goof\n";
            fwrite($myfile, $txt);
            fclose($myfile);

            // index file replace
            $ok = 'oke Done';

            $myfile = fopen("ind.html", "w") or die("Unable to open file!");
            $txt = 'safsaf';
            fwrite($myfile, $txt);

            fclose($myfile);
        }

        Session::flash('message', 'Please Select a File.');
        return back();
    }
}
