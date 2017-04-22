<?php

namespace flipbox\transformer\modules\element\transformers\entry;

use craft\base\ElementInterface;
use craft\elements\Entry as EntryElement;
use flipbox\transformer\resources\Item;
use flipbox\transformer\transformers\Dates;
use flipbox\transformer\modules\element\transformers\AbstractTransformer;

class Entry extends AbstractTransformer
{

    /**
     * @param ElementInterface $element
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
            'section' => 'SECTION',
            'type' => 'TYPE',
//            'dates' => new Item([
//                'data' => $entry,
//                'transformer' => new Dates()
//            ])
        ];
    }

}