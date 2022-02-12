<?php

namespace OZiTAG\Tager\Backend\Files\Utils;

use Ozerich\FileStorage\Utils\ConfigHelper;

class TagerImageScenario
{
    public static function wrap(array $scenarioConfig): array
    {
        if (!isset($scenarioConfig['thumbnails'])) {
            $scenarioConfig['thumbnails'] = [];
        }

        if (isset($scenarioConfig['thumbnail'])) {
            $scenarioConfig['thumbnails']['default'] = $scenarioConfig['thumbnail'];
            unset($scenarioConfig['thumbnail']);
        }

        $scenarioConfig['thumbnails']['tager-admin-view'] = ConfigHelper::thumb(null, 220, ConfigHelper::MODE_AUTO, false, 75);

        return $scenarioConfig;
    }

    public static function wrapWithList(array $scenarioConfig): array
    {
        $scenarioConfig = static::wrap($scenarioConfig);

        $scenarioConfig['thumbnails']['tager-admin-list'] = ConfigHelper::thumb(null, 100, ConfigHelper::MODE_AUTO, false, 75);

        return $scenarioConfig;
    }
}
