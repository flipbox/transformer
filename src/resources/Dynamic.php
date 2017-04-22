<?php

namespace flipbox\transformer\resources;

use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transformer\Plugin;
use yii\base\Object;

class Dynamic extends Object
{

    /**
     * @var string
     */
    public $property;

    /**
     * @var string
     */
    public $handle;

    /**
     * @param $data
     * @param Scope $scope
     * @return mixed
     */
    public function __invoke($data, Scope $scope)
    {

        if (null === $this->handle) {
            return null;
        }

        if (null === $this->property) {
            $this->property = $this->handle;
        }

        $data = $data->{$this->property};

        /** @var ResourceInterface $resource */
        $resource = Plugin::getInstance()->getResources()->find($data, $this->handle);
        $resource->setData($data);

        return $scope->getFactory()->transform(
            $resource,
            $this->handle,
            $scope
        );

    }

}