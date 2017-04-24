<?php

namespace flipbox\transformer\modules\model\events;

use craft\base\FieldInterface;
use flipbox\transformer\events\RegisterTransformers as BaseRegisterTransformers;

/**
 * @package flipbox\transformer\modules\model\events
 *
 * @property FieldInterface $sender
 */
class RegisterTransformers extends BaseRegisterTransformers
{
}
