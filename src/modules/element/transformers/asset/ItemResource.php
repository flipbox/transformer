<?php

namespace flipbox\transformer\modules\element\transformers\asset;

use craft\base\ElementInterface;
use craft\elements\Asset;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Asset();
    }

}
