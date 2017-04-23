<?php

namespace flipbox\transformer\modules\field;

use flipbox\transformer\Plugin;
use yii\base\Module as BaseModule;

/**
 * @property Plugin $module
 */
class Module extends BaseModule
{

    /**
     * @return services\Transformer
     */
    public function getTransformer()
    {
        return $this->get('transformer');
    }

}