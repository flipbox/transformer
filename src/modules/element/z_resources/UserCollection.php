<?php

namespace flipbox\transformer\modules\element\resources;

use craft\base\ElementInterface;
use craft\elements\User;

class UserCollection extends AbstractCollectionResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new User();
    }

}