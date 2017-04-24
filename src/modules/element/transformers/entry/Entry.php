<?php

namespace flipbox\transformer\modules\element\transformers\entry;

use craft\base\ElementInterface;
use craft\elements\Entry as EntryElement;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;
use flipbox\transformer\Plugin;

class Entry extends AbstractTransformer
{

    /**
     * @param ElementInterface|EntryElement $element
     * @return array
     */
    protected function transformElement(ElementInterface $element): array
    {

        return array_merge(
            parent::transformElement($element),
            $this->transformEntry($element)
        );

    }

    /**
     * @param EntryElement $entry
     * @return array
     */
    protected function transformEntry(EntryElement $entry): array
    {
        return [
            'title' => $entry->title,
            'section' => Plugin::getInstance()->getModel()->getTransformer()->item(
                $entry->getSection()
            ),
            'type' => Plugin::getInstance()->getModel()->getTransformer()->item(
                $entry->getType()
            )
        ];
    }

}