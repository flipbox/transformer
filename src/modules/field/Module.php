<?php

namespace flipbox\transformer\modules\field;

use flipbox\transformer\Plugin;
use yii\base\Module as BaseModule;

/**
 * Class Module
 * @package flipbox\transformer\modules\field
 *
 * @property Plugin $module
 */
class Module extends BaseModule
{




    /**
     * @return services\Transform
     */
    public function getTransform()
    {
        return $this->get('transform');
    }

    /**
     * @return services\Transformer
     */
    public function getTransformer()
    {
        return $this->get('transformer');
    }

    /**
     * @return services\Resource
     */
    public function getResource()
    {
        return $this->get('resource');
    }

}