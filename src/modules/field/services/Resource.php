<?php

namespace flipbox\transformer\modules\field\services;

use craft\base\Component;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Users;
use flipbox\spark\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transformer\modules\field\events\RegisterResources;
use flipbox\transformer\modules\field\events\RegisterTransformer;
use flipbox\transformer\modules\field\resources\Collection;
use flipbox\transformer\modules\field\resources\UserCollection;
use flipbox\transformer\modules\field\transformers\User as UserTransformer;
use yii\base\Exception;

class Resource extends Component
{

    /**
     * @param FieldInterface|Field $field
     * @return mixed
     */
    public function getAll(FieldInterface $field)
    {

        // This could be transformers loaded outside events
        $resources = $this->_firstPartyFieldTypes($field);

        var_dump($resources);
//        exit;


        $event = new RegisterResources([
            'resources' => $resources
        ]);

        $field->trigger(
            RegisterResources::EVENT,
            $event
        );

        return $event->getResources();

    }

    /**
     * @param string $identifier
     * @param FieldInterface $field
     * @return ResourceInterface
     */
    public function find(string $identifier, FieldInterface $field)
    {

        return ArrayHelper::getValue(
            $this->getAll($field),
            $identifier
        );

    }

    /**
     * @param string $identifier
     * @param FieldInterface $field
     * @return ResourceInterface
     * @throws Exception
     */
    public function get(string $identifier, FieldInterface $field)
    {

        if(!$resource = $this->find($identifier, $field)) {
            throw new Exception("Resource not found");
        }

        return $resource;

    }

    private function _firstPartyFieldTypes(FieldInterface $field)
    {

        $resources = [];

        switch(get_class($field)) {

            case Users::class: {
                $resources['default'] = new UserCollection([
                        'transformer' => new UserTransformer(),
                        'identifier' => $field->handle
                ]);
                break;

            }

        }

        return $resources;

    }

}