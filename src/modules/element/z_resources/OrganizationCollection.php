<?php

namespace flipbox\transformer\modules\element\resources;

use craft\base\ElementInterface;
use flipbox\organization\elements\Organization;

class OrganizationCollection extends AbstractCollectionResource
{

    /**
     * @return ElementInterface
     */
    protected function element(): ElementInterface
    {
        return new Organization();
    }


}