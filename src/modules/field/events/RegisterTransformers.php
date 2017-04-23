<?php

namespace flipbox\transformer\modules\field\events;

use craft\base\FieldInterface;
use flipbox\transformer\events\RegisterTransformers as BaseRegisterTransformers;

/**
 * @package flipbox\transformer\modules\field\events
 *
 * @property FieldInterface $sender
 */
class RegisterTransformers extends BaseRegisterTransformers
{
}