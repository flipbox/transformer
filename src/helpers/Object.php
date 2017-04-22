<?php

namespace flipbox\transformer\helpers;

use flipbox\spark\helpers\ObjectHelper;
use flipbox\transform\helpers\Object as TransformObjectHelper;

class Object extends TransformObjectHelper
{

    /**
     * @param $config
     * @param string|null $instanceOf
     * @return mixed
     */
    public static function create($config, string $instanceOf = null)
    {

        // Get class from config
        $class = static::checkConfig($config, $instanceOf);

        /** @var \yii\base\Object $object */
        $object = new $class();

        // Populate
        if ($config) {

            // Set properties
            foreach ($config as $name => $value) {

                if ($object->canSetProperty($name)) {
                    $object->$name = $value;
                }

            }

        }

        return $object;

    }

    /**
     * @param $config
     * @param string|null $instanceOf
     * @param bool $removeClass
     * @return string
     */
    public static function checkConfig(&$config, string $instanceOf = null, bool $removeClass = true): string
    {
        return ObjectHelper::checkConfig($config, $instanceOf);
    }

}