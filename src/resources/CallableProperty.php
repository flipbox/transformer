<?php

namespace flipbox\transformer\resources;

use flipbox\transform\Scope;
use yii\base\Object;

class CallableProperty extends Object
{

    /**
     * @inheritdoc
     */
    public function __invoke($data, Scope $scope, string $identifier = null)
    {
        return $data->{$identifier};
    }

}