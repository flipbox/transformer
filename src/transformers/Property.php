<?php

namespace flipbox\transformer\transformers;

use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer;

class Property extends AbstractTransformer
{

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $data->{$identifier};
    }

}