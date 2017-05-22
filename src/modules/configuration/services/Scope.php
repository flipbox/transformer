<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\configuration\services;

use flipbox\transformer\modules\configuration\events\RegisterScopes;
use flipbox\transformer\Transformer;
use yii\base\Component;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Scope extends Component
{

    /**
     * @var string[]
     */
    protected $_cacheAll;

    /**
     * @return string[]
     */
    public function findAll()
    {

        if (null === $this->_cacheAll) {

            $event = new RegisterScopes();

            $configuration = Transformer::getInstance()->getConfiguration();

            $configuration->trigger(
                $configuration::EVENT_REGISTER_SCOPES,
                $event
            );

            $this->_cacheAll = $event->scopes;

        }

        return $this->_cacheAll;

    }

}