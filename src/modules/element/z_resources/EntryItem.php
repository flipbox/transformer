<?php

namespace flipbox\transformer\modules\element\resources;

use craft\base\ElementInterface;
use craft\elements\Entry;
use flipbox\transform\transformers\Item as ItemTransformer;

class EntryItem extends ItemTransformer
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Entry();
    }

}