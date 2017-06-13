<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\configuration;

use flipbox\transformer\Transformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Transformer $module
 */
class Module extends \yii\base\Module
{
    /**
     * The register scopes event name
     */
    const EVENT_REGISTER_SCOPES = 'registerScopes';

    /**
     * The register sources event name
     */
    const EVENT_REGISTER_SOURCES = 'registerSources';

    /**
     * The register sources event name
     */
    const EVENT_REGISTER_DATA = 'registerData';


    /*******************************************
     * SERVICES
     *******************************************/

    /**
     * @return services\Data
     */
    public function getData()
    {
        return $this->get('data');
    }

    /**
     * @return services\Scope
     */
    public function getScope()
    {
        return $this->get('scope');
    }

    /**
     * @return services\Source
     */
    public function getSource()
    {
        return $this->get('source');
    }
}
