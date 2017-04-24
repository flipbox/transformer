<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\model\events;

use craft\base\FieldInterface;
use flipbox\transformer\events\RegisterTransformers as BaseRegisterTransformers;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property FieldInterface $sender
 */
class RegisterTransformers extends BaseRegisterTransformers
{
}
