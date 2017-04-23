<?php

namespace flipbox\transformer\modules\element\transformers\user;

use craft\base\ElementInterface;
use craft\elements\User;
use flipbox\transformer\modules\element\transformers\AbstractItemResource;

class ItemResource extends AbstractItemResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new User();
    }

}
