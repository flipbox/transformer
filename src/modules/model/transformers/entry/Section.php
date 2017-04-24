<?php

namespace flipbox\transformer\modules\model\transformers\entry;

use craft\models\Section as EntrySection;
use flipbox\transform\transformers\AbstractTransformer;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
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