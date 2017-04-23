<?php

namespace flipbox\transformer\modules\element\transformers\category;

use craft\base\ElementInterface;
use craft\elements\Category as CategoryElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

class Category extends AbstractTransformer
{

    /**
     * @param ElementInterface $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return array_merge(
            parent::transformElement($element),
            $this->transformCategory($element)
        );

    }

    /**
     * @param CategoryElement $category
     * @return array
     */
    protected function transformCategory(CategoryElement $category): array
    {

        return [
            'name' => $category->title
        ];

    }

}