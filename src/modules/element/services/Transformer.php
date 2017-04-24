<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\element\services;

use craft\base\Component;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\MatrixBlock;
use craft\elements\Tag;
use craft\elements\User;
use craft\helpers\ArrayHelper;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\modules\element\events\RegisterTransformers;
use flipbox\transformer\modules\element\transformers\asset\Asset as AssetTransformer;
use flipbox\transformer\modules\element\transformers\category\Category as CategoryTransformer;
use flipbox\transformer\modules\element\transformers\entry\Entry as EntryTransformer;
use flipbox\transformer\modules\element\transformers\ItemResource;
use flipbox\transformer\modules\element\transformers\matrix\Block as MatrixBlockTransformer;
use flipbox\transformer\modules\element\transformers\tag\Tag as TagTransformer;
use flipbox\transformer\modules\element\transformers\user\CollectionResource;
use flipbox\transformer\modules\element\transformers\user\User as UserTransformer;
use flipbox\transformer\Plugin;
use yii\base\Exception;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Component
{

    /**
     * @param ElementInterface $element
     * @param string $transformer
     * @return CollectionResource
     */
    public function collection(ElementInterface $element, $transformer = 'default'): CollectionResource
    {
        return new CollectionResource($element, ['transformer' => $transformer]);
    }

    /**
     * @param ElementInterface $element
     * @param string $transformer
     * @return ItemResource
     */
    public function item(ElementInterface $element, $transformer = 'default'): ItemResource
    {
        return new ItemResource($element, ['transformer' => $transformer]);
    }

    /**
     * @param ElementInterface|Element $element
     * @return TransformerInterface[]
     */
    public function getAll(ElementInterface $element)
    {

        $transformers = array_merge(
            $this->_firstParty($element),
            $this->_database($element)
        );

        $event = new RegisterTransformers([
            'transformers' => $transformers
        ]);

        $element->trigger(
            RegisterTransformers::EVENT,
            $event
        );

        return $event->getTransformers();

    }

    /**
     * @param string $identifier
     * @param ElementInterface $element
     * @return TransformerInterface|null
     */
    public function find(string $identifier, ElementInterface $element)
    {

        return ArrayHelper::getValue(
            $this->getAll($element),
            $identifier
        );

    }

    /**
     * @param string $identifier
     * @param ElementInterface $element
     * @return TransformerInterface
     * @throws Exception
     */
    public function get(string $identifier, ElementInterface $element)
    {

        if (!$transformer = $this->find($identifier, $element)) {
            throw new Exception("Transformer not found");
        }

        return $transformer;

    }

    /**
     * @param ElementInterface $element
     * @return TransformerInterface[]
     */
    private function _firstParty(ElementInterface $element)
    {

        $transformers = [];

        switch (get_class($element)) {

            case Asset::class: {
                $transformers['default'] = new AssetTransformer();
                break;

            }

            case Category::class: {
                $transformers['default'] = new CategoryTransformer();
                break;

            }

            case Entry::class: {
                $transformers['default'] = new EntryTransformer();
                break;

            }

            case MatrixBlock::class: {
                $transformers['default'] = new MatrixBlockTransformer();
                break;

            }

            case Tag::class: {
                $transformers['default'] = new TagTransformer();
                break;

            }

            case User::class: {
                $transformers['default'] = new UserTransformer();
                break;

            }

        }

        return $transformers;

    }

    /**
     * @param ElementInterface $element
     * @return TransformerInterface[]
     */
    private function _database(ElementInterface $element)
    {
        return Plugin::getInstance()->getTransformer()->findAllByTypeAndScope(
            get_class($element),
            RegisterTransformers::class
        );
    }

}