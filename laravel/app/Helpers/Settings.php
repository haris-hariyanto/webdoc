<?php

namespace App\Helpers;

use App\Models\Setting;

class Settings
{
    private $settings = [];
    private $placeholdersKeys = [];
    private $placeholdersValues = [];

    public function __construct($type)
    {
        $appCode = config('app.code');

        try {
            if (is_array($type)) {
                $getSettings = Setting::where('website', $appCode)->where(function ($query) use ($type) {
                    foreach ($type as $typePart) {
                        $query->orWhere('type', $typePart);
                    }
                })->get();

                $getSettings = $getSettings->mapWithKeys(function ($setting) {
                    return [$setting->type . '.' . $setting->key => $setting->value];
                })->toArray();
            }
            else {
                $getSettings = Setting::where('website', $appCode)->where('type', $type)->get();

                $getSettings = $getSettings->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->value];
                })->toArray();
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            $getSettings = [];
        }

        $this->settings = $getSettings;
    }

    public function get($key, $defaultValue = false, $JSONDecode = false)
    {
        if (!empty($this->settings[$key])) {
            return $JSONDecode ? json_decode($this->settings[$key], true) : $this->settings[$key];
        }
        else {
            return $defaultValue;
        }
    }

    public function setPlaceholders($keys = [], $values = [])
    {
        $this->placeholdersKeys = $keys;
        $this->placeholdersValues = $values;
    }

    public function getFinal($key, $defaultValue = false, $override = false)
    {
        if ($override) {
            if (is_array($override)) {
                foreach ($override as $overrideItem) {
                    if (!empty($overrideItem)) {
                        return str_replace($this->placeholdersKeys, $this->placeholdersValues, $overrideItem);
                    }
                }
            }
            else {
                return str_replace($this->placeholdersKeys, $this->placeholdersValues, $override);
            }
        }

        if (!empty($this->settings[$key])) {
            return str_replace($this->placeholdersKeys, $this->placeholdersValues, $this->settings[$key]);
        }
        else {
            return str_replace($this->placeholdersKeys, $this->placeholdersValues, $defaultValue);
        }
    }
}