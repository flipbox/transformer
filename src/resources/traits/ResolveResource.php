<?php

namespace flipbox\transformer\resources\traits;

use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\helpers\Object as ObjectHelper;
use flipbox\transformer\Plugin;
use flipbox\transformer\resources\Dynamic;

trait ResolveResource
{

    /**
     * @param $resource
     * @return ResourceInterface|callable
     */
    protected function resolveResource(string $key, $resource, $data = null, string $context = 'global')
    {

        // Good to go
        if ($resource instanceof ResourceInterface || is_callable($resource)) {

            return $resource;

        }

        // Class name or handle
        if (is_string($resource)) {

            var_dump($resource, '__invoke');
            var_dump(is_callable([$resource, '()']));
            var_dump(is_subclass_of($resource, TransformerInterface::class));
            exit;

            // A class name
            if(is_subclass_of($resource, ResourceInterface::class)) {

                $resource = [
                    'class' => $resource
                ];

            } else {

                $resource = [
                    'class' => Dynamic::class,
                    'handle' => $resource
                ];

                var_dump("FIND BY HANDLE", $context);

            }

        }

        $class = ObjectHelper::checkConfig(
            $resource
        );

        // Append data/context
        $resource['data'] = $data;
        $resource['context'] = $context;

        return new $class($resource);

    }

}