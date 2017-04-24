<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\model\services;

use craft\base\Component;
use craft\helpers\ArrayHelper;
use craft\models\EntryType;
use craft\models\Section;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\modules\model\events\RegisterTransformers;
use flipbox\transformer\modules\model\transformers\CollectionResource;
use flipbox\transformer\modules\model\transformers\entry\Section as EntrySectionTransformer;
use flipbox\transformer\modules\model\transformers\entry\Type as EntryTypeTransformer;
use flipbox\transformer\modules\model\transformers\ItemResource;
use flipbox\transformer\Plugin;
use yii\base\Exception;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Component
{

    /**
     * @param Model $data
     * @param string $transformer
     * @return CollectionResource
     */
    public function collection(Model $data, $transformer = 'default')
    {
        return new CollectionResource($data, ['transformer' => $transformer]);
    }

    /**
     * @param Model $data
     * @param string $transformer
     * @return ItemResource
     */
    public function item(Model $data, $transformer = 'default')
    {
        return new ItemResource($data, ['transformer' => $transformer]);
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function getAll(Model $model)
    {

        $transformers = array_merge(
            $this->_firstParty($model),
            $this->_database($model)
        );

        $event = new RegisterTransformers([
            'transformers' => $transformers
        ]);

        $model->trigger(
            RegisterTransformers::EVENT,
            $event
        );

        return $event->getTransformers();

    }

    /**
     * @param string $identifier
     * @param Model $model
     * @return TransformerInterface
     */
    public function find(string $identifier, Model $model)
    {

        return ArrayHelper::getValue(
            $this->getAll($model),
            $identifier
        );

    }

    /**
     * @param string $identifier
     * @param Model $model
     * @return TransformerInterface
     * @throws Exception
     */
    public function get(string $identifier, Model $model)
    {

        if (!$transformer = $this->find($identifier, $model)) {
            throw new Exception("Transformer not found");
        }

        return $transformer;

    }

    /**
     * @param Model $model
     * @return TransformerInterface[]
     */
    private function _firstParty(Model $model)
    {

        $transformers = [];

        switch (get_class($model)) {

            case Section::class:
                $transformers['default'] = new EntrySectionTransformer();
                break;

            case EntryType::class:
                $transformers['default'] = new EntryTypeTransformer();
                break;

        }

        return $transformers;

    }

    /**
     * @param Model $model
     * @return TransformerInterface[]
     */
    private function _database(Model $model)
    {
        return Plugin::getInstance()->getTransformer()->findAllByTypeAndScope(
            get_class($model),
            RegisterTransformers::class
        );
    }

}