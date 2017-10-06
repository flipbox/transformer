<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\helpers;

use craft\helpers\ArrayHelper;
use Flipbox\Transform\Transformers\TransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer
{
    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformer($transformer)
    {
        return is_callable($transformer) || $transformer instanceof TransformerInterface;
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformerClass($transformer)
    {
        return is_string($transformer) && is_subclass_of($transformer, TransformerInterface::class);
    }

    /**
     * @param $transformer
     * @return bool
     */
    public static function isTransformerConfig($transformer)
    {
        if(!is_array($transformer)) {
            return false;
        }

        if($class = ArrayHelper::getValue($transformer, 'class')) {
           return false;
        }

        return static::isTransformerClass($class);
    }
}
