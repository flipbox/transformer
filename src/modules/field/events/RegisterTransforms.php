<?php

namespace flipbox\transformer\modules\field\events;

use craft\base\FieldInterface;
use flipbox\transformer\events\RegisterTransforms as BaseRegisterTransforms;

/**
 * @package flipbox\transformer\modules\field\events
 *
 * @property FieldInterface $sender
 */
class RegisterTransforms extends BaseRegisterTransforms
{
}
