<?php

namespace flipbox\transformer\modules\element;

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
     * @return services\Transformer
     */
    public function getTransformer()
    {
        return $this->get('transformer');
    }

}