<?php

namespace App\Http\Controllers\FileManager;

use Illuminate\Support\Str;
use UniSharp\LaravelFilemanager\Handlers\ConfigHandler as BaseHandler;

class CustomConfigHandler extends BaseHandler
{

    public function userField()
    {
        return self::getFolderName();
    }

    public static function getFolderName()
    {
        $userData = getUserData();
        $store = $userData['store'] ?? "";

        $folder = self::getStoreFolderName($store);

        return $folder ?? 'uploads';
    }

    public static function getStoreFolderName($store = null)
    {
        if (isset($store) && isset($store->name) && $store->name != "") {
            return Str::slug($store->name);
        }

        return null;
    }


}
