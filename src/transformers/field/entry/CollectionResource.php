<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field\entry;

use craft\elements\db\EntryQuery;
use craft\fields\Entries as EntriesField;
use flipbox\transformer\transformers\element\user\CollectionResource as EntryCollectionResource;
use flipbox\transformer\transformers\field\FieldTrait;
use flipbox\transformer\transformers\field\FieldTransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property EntriesField $field
 */
class CollectionResource extends EntryCollectionResource implements FieldTransformerInterface
{

    use FieldTrait;

    /**
     * @param EntriesField $field
     * @param array        $config
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
