<?php

namespace flipbox\transformer\modules\element\transformers;

use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer as BaseAbstractTransformer;
use flipbox\transform\transformers\ResourceTransformerInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\modules\field\transformers\FieldTransformerInterface;
use flipbox\transformer\Plugin;
use flipbox\transformer\transformers\Collection;
use flipbox\transform\transformers\Collection as BaseCollection;
use flipbox\transformer\transformers\Item;
use flipbox\transform\transformers\Item as BaseItem;

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

        // Look for field transform
        /** @var ResourceInterface|TransformerInterface|callable $resource */
        if (!$transform = Plugin::getInstance()->getField()->getTransformer()->find(
            'default',
            $field
        )
        ) {

            var_dump($field);
            var_dump("FIELD NOT FOUND");
            return null;

        }

        // Set allow resource and field transformers to manipulate data (ie, get the field value)
        if ($transform instanceof ResourceTransformerInterface ||
            $transform instanceof FieldTransformerInterface
        ) {

            // Resource transformers can modify the data at this point
            $transform->setData(
                $element
            );

        }

        return $transform;

    }

    /**
     * @param mixed $data
     * @param TransformerInterface|callable $transformer
     * @return BaseItem
     */
    protected function item($data, $transformer): BaseItem
    {
        return new Item(['data' => $data, 'transformer' => $transformer]);
    }

    /**
     * @param mixed $data
     * @param TransformerInterface|callable $transformer
     * @return BaseCollection
     */
    protected function collection($data, $transformer): BaseCollection
    {
        return new Collection(['data' => $data, 'transformer' => $transformer]);
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