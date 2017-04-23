<?php

namespace flipbox\transformer\modules\field\transformers\category;

use craft\elements\db\CategoryQuery;
use craft\fields\Categories as CategoriesField;
use flipbox\transformer\modules\element\transformers\category\CollectionResource as CategoryCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property CategoriesField $field
 */
class CollectionResource extends CategoryCollectionResource
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
     * @return CategoryQuery
     */
    protected function prepareData($data): CategoryQuery
    {
        return $this->fieldData($data);
    }

}
