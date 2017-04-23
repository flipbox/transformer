<?php

namespace flipbox\transformer\modules\field\transformers\tag;

use craft\elements\db\TagQuery;
use craft\fields\Categories as CategoriesField;
use flipbox\transformer\modules\element\transformers\tag\CollectionResource as TagCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property CategoriesField $field
 */
class CollectionResource extends TagCollectionResource
{

    use FieldTrait;

    /**
     * @param CategoriesField $field
     * @param array $config
     */
    public function __construct(CategoriesField $field, array $config = [])
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
