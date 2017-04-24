<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\field\transformers\category;

use craft\elements\db\CategoryQuery;
use craft\fields\Categories as CategoriesField;
use flipbox\transformer\modules\element\transformers\category\CollectionResource as CategoryCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
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
