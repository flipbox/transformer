<?php

namespace flipbox\transformer\modules\element\transformers\tag;

use craft\base\ElementInterface;
use craft\elements\Tag;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Tag();
    }

}
