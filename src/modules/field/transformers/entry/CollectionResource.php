<?php

namespace flipbox\transformer\modules\field\transformers\entry;

use craft\elements\db\EntryQuery;
use craft\fields\Entries as EntriesField;
use flipbox\transformer\modules\element\transformers\user\CollectionResource as EntryCollectionResource;
use flipbox\transformer\modules\field\transformers\FieldTrait;

/**
 * @package flipbox\transformer\modules\field\transformers\entry
 *
 * @property EntriesField $field
 */
class CollectionResource extends EntryCollectionResource
{

    use FieldTrait;

    /**
     * @param EntriesField $field
     * @param array $config
     */
    public function __construct(EntriesField $field, array $config = [])
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
     * @return EntryQuery
     */
    protected function prepareData($data): EntryQuery
    {
        return $this->fieldData($data);
    }

}
