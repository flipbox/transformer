<?php

namespace flipbox\transformer\transformers\traits;

use flipbox\transformer\helpers\Object as ObjectHelper;
use flipbox\transform\Scope;
use flipbox\transform\transformers\TransformerInterface;
use yii\base\Object;

trait ResolveTransformer
{

    /**
     * @param $transformer
     * @return TransformerInterface|callable
     */
    protected function resolveTransformer($transformer)
    {


        if(is_callable($transformer)) {

            return $transformer;

        }

        if(is_object($transformer) && $transformer instanceof TransformerInterface) {

            return $transformer;

        }

        if (is_string($transformer) && is_subclass_of($transformer, TransformerInterface::class)) {

            $transformer = [
                'class' => $transformer
            ];

        }

        $class = ObjectHelper::checkConfig(
            $transformer,
            TransformerInterface::class
        );

        $object = new $class();

        ObjectHelper::configure(
            $object,
            $transformer
        );

        return $object;

    }

}