<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\element;

use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer as BaseAbstractTransformer;
use flipbox\transform\transformers\Collection as BaseCollection;
use flipbox\transform\transformers\Item as BaseItem;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Transformer as TransformerPlugin;
use flipbox\transformer\transformers\field\FieldTransformerInterface;
use yii\base\Exception;

//use flipbox\transform\transformers\ResourceTransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractTransformer extends BaseAbstractTransformer
{

    /**
     * @param ElementInterface $element
     * @return array
     */
    public function transform(ElementInterface $element): array
    {

        return array_merge(
            $this->transformElement($element),
            $this->transformFields($element)
        );

    }

    /**
     * @param ElementInterface|Element $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return [
            'id' => $element->id
        ];

    }

    /**
     * @param ElementInterface $element
     * @return ResourceInterface[]|TransformerInterface[]|callable[]
     */
    protected function transformFields(ElementInterface $element): array
    {

        $transforms = [];

        /** @var FieldInterface[] $fields */
        $fields = $element->getFieldLayout()->getFields();

        /** @var Field $field */
        foreach ($fields as $field) {

            $transform = $this->transformField(
                $field,
                $element
            );

            if (null !== $transform) {

                $transforms[$field->handle] = $transform;

            }

        }

        return $transforms;

    }

    /**
     * @param FieldInterface|Field $field
     * @param ElementInterface $element
     * @return ResourceInterface|TransformerInterface|callable
     */
    protected function transformField(FieldInterface $field, ElementInterface $element)
    {

        /** @var Field $field */

        // Look for field transform
        /** @var ResourceInterface|TransformerInterface|callable $resource */
        if (!$transform = TransformerPlugin::getInstance()->getTransformer()->find(
            'default',
            $field
        )
        ) {

            TransformerPlugin::warning(sprintf(
                "Transform not found for field: '%s'",
                $field->handle
            ));

            return null;

        }

        // Set allow resource and field transformers to manipulate data (ie, get the field value)
        if ($transform instanceof FieldTransformerInterface) {

            // Resource transformers can modify the data at this point
            $transform->setData(
                $element
            );

        }

        return $transform;

    }

    /**
     * @param mixed $data
     * @param callable|TransformerInterface $transformer
     * @return BaseItem
     * @throws Exception
     */
    protected function item($data, $transformer): BaseItem
    {
        throw new Exception("Item resource is not implemented.");
    }

    /**
     * @param mixed $data
     * @param callable|TransformerInterface $transformer
     * @return BaseCollection
     * @throws Exception
     */
    protected function collection($data, $transformer): BaseCollection
    {
        throw new Exception("Collection resource is not implemented.");
    }

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {

        if (!$data instanceof ElementInterface) {
            return null;
        }

        return parent::__invoke($data, $scope, $identifier);

    }

}
