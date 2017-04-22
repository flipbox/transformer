<?php

namespace flipbox\transformer\services;

use yii\base\Component;
use craft\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transformer\events\RegisterResources;
use yii\base\Exception;

class Resources extends Component
{

    /**
     * @param Component $component
     * @param string $context
     * @return ResourceInterface[]
     */
    public function getAll(Component $component, string $context = 'global')
    {

        // This could be transformers loaded outside events
        $resources = [];

        $event = new RegisterResources([
            'resources' => $resources
        ]);

        $component->trigger(
            RegisterResources::eventName($component, $context),
            $event
        );

        return $event->getResources();

    }

    /**
     * @param Component $component
     * @param string $identifier
     * @param string $context
     * @return mixed
     */
    public function find(Component $component, string $identifier, string $context = 'global')
    {

        return ArrayHelper::getValue(
            $this->getAll($component, $context),
            $identifier
        );

    }

    /**
     * @param Component $component
     * @param string $identifier
     * @param string $context
     * @return ResourceInterface
     * @throws Exception
     */
    public function get(Component $component, string $identifier, string $context = 'global')
    {

        if(!$resource = $this->find($component, $identifier, $context)) {
            throw new Exception("Resource not found");
        }

        return $resource;

    }

}