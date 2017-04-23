<?php

namespace flipbox\transformer\modules\field\transformers\matrix;

use craft\elements\db\MatrixBlockQuery;
use craft\fields\Matrix as MatrixField;
use flipbox\transformer\modules\element\transformers\matrix\CollectionResource as CategoryCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property MatrixField $field
 */
class CollectionResource extends CategoryCollectionResource
{

    use FieldTrait;

    /**
     * @param MatrixField $field
     * @param array $config
     */
    public function __construct(MatrixField $field, array $config = [])
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
     * @return MatrixBlockQuery
     */
    protected function prepareData($data): MatrixBlockQuery
    {
        return $this->fieldData($data);
    }

}
