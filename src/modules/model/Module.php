<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\model;

use flipbox\transformer\Plugin;
use yii\base\Module as BaseModule;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
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