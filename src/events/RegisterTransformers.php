<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\events;

use craft\helpers\ArrayHelper;
use flipbox\spark\helpers\ObjectHelper;
use flipbox\transform\transformers\TransformerInterface;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RegisterTransformers extends Event
{

    const EVENT = 'registerTransformers';

    /**
     * @var callable[]|TransformerInterface[]
     */
    protected $transformers = [];

    /**
     * @param string $identifier
     * @param TransformerInterface|callable $transformer
     * @return $this
     */
    public function addTransformer(string $identifier, $transformer)
    {

        if (is_callable($transformer) || $transformer instanceof TransformerInterface) {

            $this->transformers[$identifier] = $transformer;

            return $this;

        }

        if (is_string($transformer) && is_subclass_of($transformer, TransformerInterface::class)) {

            $transformer = [
                'class' => $transformer
            ];

        }

        $this->transformers[$identifier] = $this->createTransformer($transformer);

        return $this;

    }

    /**
     * @param array $transformers
     * @return $this
     */
    public function setTransformers($transformers = [])
    {

        $this->transformers = [];

        if (empty($transformers)) {
            return $this;
        }

        // Ensure we have a valid array
        if (!ArrayHelper::isAssociative($transformers)) {
            $transformers = [$transformers];
        }

        foreach ($transformers as $identifier => $transformer) {

            $this->addTransformer($identifier, $transformer);

        }

        return $this;

    }

    /**
     * @return callable[]|TransformerInterface[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    /**
     * @param $config
     * @return callable|TransformerInterface
     */
    protected function createTransformer(array $config)
    {

        $class = ObjectHelper::checkConfig($config);

        return new $class($config);

    }

}
