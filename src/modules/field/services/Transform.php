<?php

namespace flipbox\transformer\modules\field\services;

use craft\base\Component;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Users;
use flipbox\spark\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\modules\field\events\RegisterResources;
use flipbox\transformer\modules\field\events\RegisterTransformer;
use flipbox\transformer\modules\field\events\RegisterTransforms;
use flipbox\transformer\modules\field\transformers\user\CollectionResource as UserCollectionResource;
use flipbox\transformer\modules\field\transformers\user\ItemResource as UserItemResource;
use flipbox\transformer\modules\field\transformers\User as UserTransformer;
use yii\base\Exception;

class Transform extends Component
{

    /**
     * @param FieldInterface|Field $field
     * @return mixed
     */
    public function getAll(FieldInterface $field)
    {

        // This could be transformers loaded outside events
        $transforms = $this->_firstParty($field);

        $event = new RegisterTransforms([
            'transforms' => $transforms
        ]);

        $field->trigger(
            RegisterTransforms::EVENT,
            $event
        );

        return $event->getTransforms();

    }

    /**
     * @param string $identifier
     * @param FieldInterface $field
     * @return ResourceInterface|TransformerInterface|callable
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
     * @return ResourceInterface|TransformerInterface|callable
     * @throws Exception
     */
    public function get(string $identifier, FieldInterface $field)
    {

        if(!$transform = $this->find($identifier, $field)) {
            throw new Exception("Transform not found");
        }

        return $transform;

    }


    /**
     * @param FieldInterface|Field $field
     * @return TransformerInterface[]|callable[]
     */
    private function _firstParty(FieldInterface $field)
    {

        $transforms = [];

        switch(get_class($field)) {

            case Users::class: {
                $transforms['default'] = new UserCollectionResource($field);
                break;

            }

        }

        return $transforms;

    }

}