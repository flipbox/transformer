<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\field\transformers\tag;

use craft\elements\db\TagQuery;
use craft\fields\Tags as TagsField;
use flipbox\transformer\modules\element\transformers\tag\CollectionResource as TagCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property TagsField $field
 */
class CollectionResource extends TagCollectionResource
{

    use FieldTrait;

    /**
     * @param TagsField $field
     * @param array $config
     */
    public function __construct(TagsField $field, array $config = [])
    {
        $this->field = $field;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function setData($data)
    {
        $this->data = $this->prepareData($data);
        return $this;
    }

    /**
     * @param $data
     * @return TagQuery
     */
    protected function prepareData($data): TagQuery
    {
        return $this->fieldData($data);
    }

}
