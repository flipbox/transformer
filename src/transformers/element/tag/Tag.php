<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\element\tag;

use craft\base\ElementInterface;
use craft\elements\Tag as TagElement;
use flipbox\transformer\transformers\element\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Tag extends AbstractTransformer
{
    /**
     * @param ElementInterface|TagElement $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {
        return array_merge(
            parent::transformElement($element),
            $this->transformTag($element)
        );
    }

    /**
     * @param TagElement $tag
     * @return array
     */
    protected function transformTag(TagElement $tag): array
    {
        return [
            'name' => $tag->title
        ];
    }
}
