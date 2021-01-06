<?php

namespace OZiTAG\Tager\Backend\Files\Utils;

use OZiTAG\Tager\Backend\Files\Enums\TagerFileThumbnail;

class TagerImageScenario
{
    public static function wrap(array $scenarioConfig): array
    {
        if (!isset($scenarioConfig['thumbnails'])) {
            $scenarioConfig['thumbnails'] = [];
        }

        $scenarioConfig['thumbnails'][TagerFileThumbnail::AdminList] = ConfigHelper::thumb(null, 100, ConfigHelper::MODE_CROP, false, 75);

        return $scenarioConfig;
    }

    public static function wrapWithList(array $scenarioConfig): array
    {
        $scenarioConfig = static::wrap($scenarioConfig);

        $scenarioConfig['thumbnails'][TagerFileThumbnail::AdminView] = ConfigHelper::thumb(null, 220, ConfigHelper::MODE_CROP, false, 75);

        return $scenarioConfig;
    }
}
