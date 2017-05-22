<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\base\Field;
use craft\base\FieldInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\AbstractItemResource;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Model $data
 * @property Field|FieldInterface $field
 */
class ItemResource extends AbstractItemResource
{

    use FieldTrait;

    /**
     * @param FieldInterface $field
     * @param array $config
     */
    public function __construct(FieldInterface $field, array $config = [])
    {
        $this->field = $field;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function setData($data)
    {
        $this->data = $this->prepareData($data);
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function prepareData($data)
    {
        return $this->fieldData($data);
    }

    /**
     * @inheritdoc
     */
    protected function resolveTransformerByHandle(string $handle): TransformerInterface
    {

        $transformer = TransformerPlugin::getInstance()->getTransformer()->find(
            $handle,
            $this->field
        );

        if (null === $transformer) {

            return parent::resolveTransformerByHandle($handle);

        }

        return $transformer;

    }

}
