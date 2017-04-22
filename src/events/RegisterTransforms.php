<?php

namespace flipbox\transformer\events;

use craft\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\helpers\Object as ObjectHelper;
use yii\base\Event;

class RegisterTransforms extends Event
{

    const EVENT = 'registerTransforms';

    /**
     * @var callable[]|ResourceInterface[]
     */
    protected $transforms = [];

    /**
     * @param string $identifier
     * @param ResourceInterface|TransformerInterface|callable $transform
     * @return $this
     */
    public function addTransform(string $identifier, $transform)
    {

        if (is_callable($transform) || $transform instanceof ResourceInterface) {

            $this->transforms[$identifier] = $transform;

            return $this;

        }

        if (is_string($transform) && is_subclass_of($transform, ResourceInterface::class)) {

            $transform = [
                'class' => $transform
            ];

        }

        $this->transforms[$identifier] = $this->createTransform($transform);

        return $this;

    }

    /**
     * @param array $transforms
     * @return $this
     */
    public function setTransforms($transforms = [])
    {

        $this->transforms = [];

        if (empty($transforms)) {
            return $this;
        }

        // Ensure we have a valid array
        if (!ArrayHelper::isAssociative($transforms)) {
            $transforms = [$transforms];
        }

        foreach ($transforms as $identifier => $transform) {

            $this->addTransform($identifier, $transform);

        }

        return $this;

    }

    /**
     * @return callable[]|ResourceInterface[]
     */
    public function getTransforms()
    {
        return $this->transforms;
    }

    /**
     * @param $config
     * @return callable|ResourceInterface
     */
    protected function createTransform(array $config)
    {

        $class = ObjectHelper::checkConfig($config);

        return new $class($config);

    }

}
