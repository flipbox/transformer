<?php

namespace flipbox\transformer\transformers;

use craft\base\Plugin as BasePlugin;
use craft\elements\Entry;
use flipbox\transform\Collection;
use flipbox\transform\Factory;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\transform\transformers\TransformerInterface;

/**
 * Class Dates
 * @package flipbox\transformer\transformers
 */
class Dates extends AbstractTransformer
{

    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry)
    {
        return [
            'created' => $this->item($entry->dateCreated, new Date()),
            'updated' => $this->item($entry->dateUpdated, new Date()),
            'post' => $this->item($entry->postDate, new Date()),
        ];
    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [
            'post'
        ];
    }

}