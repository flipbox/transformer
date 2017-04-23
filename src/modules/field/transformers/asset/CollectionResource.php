<?php

namespace flipbox\transformer\modules\field\transformers\asset;

use craft\elements\db\AssetQuery;
use craft\fields\Assets as AssetsField;
use flipbox\transformer\modules\element\transformers\asset\CollectionResource as AssetCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property AssetsField $field
 */
class CollectionResource extends AssetCollectionResource
{

    use FieldTrait;

    /**
     * @param AssetsField $field
     * @param array $config
     */
    public function __construct(AssetsField $field, array $config = [])
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
     * @return AssetQuery
     */
    protected function prepareData($data): AssetQuery
    {
        return $this->fieldData($data);
    }

}
