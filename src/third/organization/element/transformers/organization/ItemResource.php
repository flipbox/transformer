<?php

namespace flipbox\transformer\third\organization\element\transformers\organization;

use craft\base\ElementInterface;
use flipbox\organization\elements\Organization;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Organization();
    }

}
