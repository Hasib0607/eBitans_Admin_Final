<?php

namespace App\Http\Controllers\FileManager;


use App\Models\BuyModulus;
use Illuminate\Support\Facades\Log;
use UniSharp\LaravelFilemanager\Controllers\UploadController as BaseUploadController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;

class FilemanagerController extends BaseUploadController
{
    public function upload()
    {
        $uploaded_files = request()->file('upload');
        $error_bag = [];
        $new_filename = null;

        $userData = getUserData();
        $store_id = $userData['store_id'];

        if (!isset($store_id)) {
            $response = [
                'error' => ['message' => "Unauthorized action"]
            ];
            return response()->json($response);
        }

        $result = $this->countAllFiles();
        $totalFiles = $result['totalFiles'] ?? 0;
        $totalSizeMB = $result['totalMB'] ?? 0; // size in MB

        if (!checkUserFileslimit($totalFiles, $store_id)) {
            $response = [
                'error' => ['message' => "Your file upload limit reached. Upgrade your package for upload more files."]
            ];
            return response()->json($response);
        }

//        $imageError = $this->inputImageValidation($uploaded_files, $store_id);
//        if ($imageError) {
//            $response = [
//                'error' => ['message' => $imageError]
//            ];
//            return response()->json($response);
//        }

        foreach (is_array($uploaded_files) ? $uploaded_files : [$uploaded_files] as $file) {
            try {
                $this->lfm->validateUploadedFile($file);

                // Get the original extension before resizing
                $originalExtension = $file->getClientOriginalExtension();
                $imgExtension = strtolower($originalExtension);
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                // Validate original name for GIF/WEBP (no , or special characters except - and _)
                if (in_array($imgExtension, ['gif', 'webp'])) {
                    if (strpos($originalName, ',') !== false) {
                        $response = [
                            'error' => ['message' => "Invalid filename for {$imgExtension} file: '{$originalName}'. Commas (,) are not allowed in the filename."]
                        ];
                        return response()->json($response);
                    }

                }

                $originalName = $this->cleanFileName($originalName);

                $tempFilePath = null;

                if (!in_array($imgExtension, ['gif', 'webp'])) {
                    // Resize and encode to WebP
                    $webpContent = $this->resizeImageForUpload($file);

                    // Save to temporary file with original name but .webp extension
                    $tmpPath = storage_path('app/temp');
                    if (!file_exists($tmpPath)) {
                        mkdir($tmpPath, 0755, true);
                    }

                    $tempFileName = $originalName . '.webp';
                    $tempFilePath = $tmpPath . '/' . $tempFileName;

                    file_put_contents($tempFilePath, $webpContent);

                    // Create UploadedFile instance from temp WebP file
                    $file = new UploadedFile(
                        $tempFilePath,
                        $tempFileName,
                        'image/webp',
                        null,
                        true
                    );
                }

                // Upload using LFM
                $new_filename = $this->lfm->upload($file);

                // Clean up temp file
                if ($tempFilePath && file_exists($tempFilePath)) {
                    unlink($tempFilePath);
                }

            } catch (\Exception $e) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                array_push($error_bag, $e->getMessage());
            } catch (\Error $e) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                array_push($error_bag, 'Some error occured during uploading.');
            }
        }

        if (is_array($uploaded_files)) {
            $response = count($error_bag) > 0 ? $error_bag : parent::$success_response;
        } else { // upload via ckeditor5 expects json responses
            if (is_null($new_filename)) {
                $response = [
                    'error' => ['message' => $error_bag[0]]
                ];
            } else {
                $url = $this->lfm->setName($new_filename)->url();

                $response = [
                    'url' => $url,
                    'uploaded' => $url
                ];
            }
        }

        return response()->json($response);
    }

    public function resizeImageForUpload($image)
    {
        $extension = "webp";
        $image = Image::make($image->getRealPath(), 'imagick');

        // Resize logic: scale shortest side to 800px, maintain aspect ratio
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        $shortestSide = min($originalWidth, $originalHeight);
        $ratio = 800 / $shortestSide;

        $newWidth = intval($originalWidth * $ratio);
        $newHeight = intval($originalHeight * $ratio);

        // Resize and encode as WebP (quality 90)
        $webpContent = $image->resize($newWidth, $newHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode($extension, 90);

        return $webpContent;
    }

    public function countAllFiles($parent_dir = null)
    {
        if (is_null($parent_dir)) {
            $working_dir = $this->lfm->path('working_dir');
            $parent_dir = substr($working_dir, 0, strrpos($working_dir, '/'));
        }

        $totalFiles = 0;
        $totalSize = 0;
        $results = $this->getItems($parent_dir);

        if (!empty($results)) {
            foreach ($results as $result) {
                if (!isset($result['is_file'])) {
                    continue;
                }

                if ($result['is_file']) {
                    $totalFiles++;

                    // Convert human-readable size to bytes
                    $totalSize += $this->convertToBytes($result['size']);
                } else {
                    // Recursively count files in the folder
                    $folderPath = $result['url']; // Or whatever key has the folder path
                    $childResult = $this->countAllFiles($folderPath);
                    $totalFiles += $childResult['totalFiles'];
                    $totalSize += $childResult['totalSize'];
                }
            }
        }

        return [
            'totalFiles' => $totalFiles,
            'totalSize' => $totalSize, // in bytes
            'totalMB' => round($totalSize / (1024 * 1024), 2), // in MB
            'readableSize' => $this->formatSize($totalSize),
        ];
    }

    public function getItems($parent_dir)
    {
        $items = array_merge($this->lfm->dir($parent_dir)->folders(), $this->lfm->dir($parent_dir)->files());

        return array_map(function ($item) {
            return $item->fill()->attributes;
        }, array_slice($items, 0, count($items)));
    }

    public function inputImageValidation($uploaded_files, $store_id)
    {
        // Check image covert modules is active or not
        $imageModuleID = '107';
        $storeModulu = BuyModulus::where('modulus_id', $imageModuleID)->where('store_id', $store_id)->first();
        if (isset($storeModulu->status) && $storeModulu->status == 1) {
            $imageConvert = true;
        } else {
            $imageConvert = false;
        }

        foreach (is_array($uploaded_files) ? $uploaded_files : [$uploaded_files] as $key => $image) {
            $imgSize = $image->getSize();
            $imgSize = $imgSize / 1024;  // convert image size to kb

            // Check image converter module is active or not if active then check image size
            if ($imageConvert) {
                // Check image size if the size is greater than 600kb than throw an error.
                if ($imgSize > 6144) {
                    $msg = "Media must be lower than or equal to 6MB!";
                    return $msg;
                }
            } else {
                // Check image size if the size is greater than 200kb than throw an error.
                if ($imgSize > 200) {
                    $msg = "Media must be lower than or equal to 200kb.";
                    return $msg;
                }
            }
        }


        // Check mimeType
        $mimeType = getMimeTypes();
        foreach ($uploaded_files as $key => $image) {
            $imgExt = strtolower($image->getClientOriginalExtension());

            // Check input image mimeType
            if (!in_array($imgExt, $mimeType)) {
                return getMimeTypesValidationMessage();
            }
        }

        return false;
    }

    protected function convertToBytes($sizeStr)
    {
        if (!$sizeStr) return 0;

        $sizeStr = trim($sizeStr);
        preg_match('/([\d\.]+)\s*(B|kB|MB|GB|TB)/i', $sizeStr, $matches);

        if (count($matches) != 3) return 0;

        $size = (float)$matches[1];
        $unit = strtolower($matches[2]);

        $units = [
            'b' => 1,
            'kb' => 1024,
            'mb' => 1024 ** 2,
            'gb' => 1024 ** 3,
            'tb' => 1024 ** 4,
        ];

        return isset($units[$unit]) ? $size * $units[$unit] : 0;
    }

    protected function formatSize($bytes)
    {
        if ($bytes < 1024) return $bytes . ' B';
        $units = ['kB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i - 1];
    }

    private function cleanFileName($name)
    {
        // Replace spaces with underscores
        $name = str_replace(' ', '_', $name);

        // Remove commas only
        $name = str_replace(',', '', $name);

        // Optional: trim or limit length
        return strtolower($name ?: 'file');
    }


}
