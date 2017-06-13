<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\element\category;

use craft\base\ElementInterface;
use craft\elements\Category as CategoryElement;
use flipbox\transformer\transformers\element\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Category extends AbstractTransformer
{
    /**
     * @param ElementInterface|CategoryElement $element
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
