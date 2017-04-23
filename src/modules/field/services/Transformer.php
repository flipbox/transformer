<?php

namespace flipbox\transformer\modules\field\services;

use craft\base\Component;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Entries;
use craft\fields\Matrix;
use craft\fields\PlainText;
use craft\fields\Tags;
use craft\fields\Users;
use craft\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\modules\field\events\RegisterTransformers;
use flipbox\transformer\modules\field\transformers\asset\CollectionResource as AssetCollectionResource;
use flipbox\transformer\modules\field\transformers\category\CollectionResource as CategoryCollectionResource;
use flipbox\transformer\modules\field\transformers\entry\CollectionResource as EntryCollectionResource;
use flipbox\transformer\modules\field\transformers\matrix\CollectionResource as MatrixCollectionResource;
use flipbox\transformer\modules\field\transformers\tag\CollectionResource as TagCollectionResource;
use flipbox\transformer\modules\field\transformers\User as UserTransformer;
use flipbox\transformer\modules\field\transformers\user\CollectionResource as UserCollectionResource;
use flipbox\transformer\modules\field\transformers\PlainText as PlainTextTransformer;
use yii\base\Exception;

class Transformer extends Component
{

    /**
     * @param FieldInterface|Field $field
     * @return mixed
     */
    public function getAll(FieldInterface $field)
    {

        $event = new RegisterTransformers([
            'transformers' => $this->_firstParty($field)
        ]);

        $field->trigger(
            RegisterTransformers::EVENT,
            $event
        );

        return $event->getTransformers();

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

        if (!$transform = $this->find($identifier, $field)) {
            throw new Exception("Transformer not found");
        }

        return $transform;

    }

    /**
     * @param FieldInterface|Field $field
     * @return TransformerInterface[]|callable[]
     */
    private function _firstParty(FieldInterface $field)
    {

        $transformers = [];

        switch (get_class($field)) {

            case Assets::class: {
                $transformers['default'] = new AssetCollectionResource($field);
                break;

            }

            case Categories::class: {
                $transformers['default'] = new CategoryCollectionResource($field);
                break;

            }

            case Entries::class: {
                $transformers['default'] = new EntryCollectionResource($field);
                break;

            }

            case Matrix::class: {
                $transformers['default'] = new MatrixCollectionResource($field);
                break;

            }

            case PlainText::class: {
                $transformers['default'] = new PlainTextTransformer($field);
                break;

            }

            case Tags::class: {
                $transformers['default'] = new TagCollectionResource($field);
                break;

            }

            case Users::class: {
                $transformers['default'] = new UserCollectionResource($field);
                break;

            }

        }

        return $transformers;

    }

}