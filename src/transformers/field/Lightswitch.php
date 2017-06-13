<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\Lightswitch as LightswitchField;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property LightswitchField $field
 * @property int $data
 */
class Lightswitch extends AbstractTransformer
{
    /**
     * @param LightswitchField $field
     * @param array            $config
     */
    public function __construct(LightswitchField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @inheritdoc
     */
    public function transform()
    {
        return (bool)$this->data;
    }
}
