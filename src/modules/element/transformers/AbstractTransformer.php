<?php

namespace flipbox\transformer\modules\element\transformers;

use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\elements\Entry as EntryElement;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractResourceTransformer;
use flipbox\transform\transformers\AbstractTransformer as BaseAbstractTransformer;
use flipbox\transform\transformers\ResourceTransformerInterface;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use flipbox\transformer\transformers\Dates;

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
     * @param ElementInterface $element
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
        if (!$transform = Plugin::getInstance()->getField()->getTransform()->find(
            'default',
            $field
        )
        ) {

//            var_dump($field);
            var_dump("FIELD NOT FOUND");
            return null;

        }

        // Set data on resource transformers
        if ($transform instanceof ResourceTransformerInterface) {

            // Resource transformers can modify the data at this point
            $transform->setData(
                $element
            );

        }

        return $transform;

    }

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {

        if(!$data instanceof ElementInterface) {
            return null;
        }

        return parent::__invoke($data, $scope, $identifier);

    }

}