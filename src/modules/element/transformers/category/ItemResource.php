<?php

namespace flipbox\transformer\modules\element\transformers\category;

use craft\base\ElementInterface;
use craft\elements\Category;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Category();
    }

}
