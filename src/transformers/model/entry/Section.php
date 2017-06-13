<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\model\entry;

use craft\models\Section as EntrySection;
use flipbox\transformer\transformers\model\AbstractTransformer;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property EntrySection $model
 */
class Section extends AbstractTransformer
{
    /**
     * @param EntrySection $model
     * @return array
     */
    public function transform(EntrySection $model)
    {
        return [
            'id' => (int)$model->id,
            'name' => $model->name,
            'handle' => $model->handle
        ];
    }
}
