<?php

namespace flipbox\transformer\modules\element\transformers\tag;

use craft\base\ElementInterface;
use craft\elements\Tag as TagElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

class Tag extends AbstractTransformer
{

    /**
     * @param ElementInterface $element
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