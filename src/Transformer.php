<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer;

use craft\base\Plugin as BasePlugin;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends BasePlugin
{

    /*******************************************
     * SERVICES
     *******************************************/

    /**
     * @return services\Transformer
     */
    public function getTransformer()
    {
        return $this->get('transformer');
    }

    /*******************************************
     * SUB-MODULES
     *******************************************/

    /**
     * @return modules\field\Module
     */
    public function getField()
    {
        return $this->getModule('field');
    }

    /**
     * @return modules\element\Module
     */
    public function getElement()
    {
        return $this->getModule('element');
    }

    /**
     * @return modules\model\Module
     */
    public function getModel()
    {
        return $this->getModule('model');
    }

}