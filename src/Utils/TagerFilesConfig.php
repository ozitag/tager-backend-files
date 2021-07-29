<?php

namespace OZiTAG\Tager\Backend\Files\Utils;

class TagerFilesConfig
{
    private static function config($param = null, $default = null)
    {
        return \config('tager-files' . (empty($param) ? '' : '.' . $param), $default);
    }

    public static function getUserFileScenario()
    {
        return self::config('userfile-scenario');
    }
}
