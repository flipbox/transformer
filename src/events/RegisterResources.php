<?php

namespace flipbox\transformer\events;

use craft\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transformer\helpers\Object as ObjectHelper;
use yii\base\Component;
use yii\base\Event;

class RegisterResources extends Event
{

    const EVENT = 'registerResources';

    /**
     * @var ResourceInterface[]
     */
    protected $_resources = [];

    /**
     * @param Component $component
     * @param string|null $context
     * @return string
     */
    public static function eventName(Component $component, string $context = 'global')
    {
        return static::EVENT . ':' . $context . ':' . get_class($component);
    }

    /**
     * @param string $identifier
     * @param ResourceInterface|array $resource
     * @return $this
     */
    public function addResource(string $identifier, $resource)
    {

        if ($resource instanceof ResourceInterface) {

            $this->_resources[$identifier] = $resource;

            return $this;

        }

        if (is_string($resource) && is_subclass_of($resource, ResourceInterface::class)) {

            $resource = [
                'class' => $resource
            ];

        }

        $this->_resources[$identifier] = $this->createResource($resource);

        return $this;

    }

    /**
     * @param array $resources
     * @return $this
     */
    public function setResources($resources = [])
    {

        $this->_resources = [];

        if(empty($resources)) {
            return $this;
        }

        // Ensure we have a valid array
        if (!ArrayHelper::isAssociative($resources)) {
            $resources = [$resources];
        }

        foreach ($resources as $identifier => $resource) {

            $this->addResource($identifier, $resource);

        }

        return $this;

    }

    /**
     * @return ResourceInterface[]
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * @param $config
     * @return ResourceInterface
     */
    protected function createResource(array $config): ResourceInterface
    {

        $class = ObjectHelper::checkConfig($config, ResourceInterface::class);

        return new $class($config);

    }

}
