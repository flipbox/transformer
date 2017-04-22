<?php

namespace flipbox\transformer\modules\element\transformers\entry;

use craft\base\ElementInterface;
use craft\elements\Entry;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Entry();
    }

}