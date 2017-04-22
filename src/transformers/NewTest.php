<?php

namespace flipbox\transformer\transformers;

use craft\elements\Entry;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer;

class NewTest extends AbstractTransformer
{

    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry): array
    {

        return [
            'name' => $entry->title,
            'dates' => $this->item($entry, new Dates()),
        ];

    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [
            'dates'
        ];

    }

}