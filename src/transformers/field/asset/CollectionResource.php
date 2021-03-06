<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field\asset;

use craft\elements\db\AssetQuery;
use craft\fields\Assets as AssetsField;
use flipbox\transformer\transformers\element\asset\CollectionResource as AssetCollectionResource;
use flipbox\transformer\transformers\field\FieldTrait;
use flipbox\transformer\transformers\field\FieldTransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property AssetsField $field
 */
class CollectionResource extends AssetCollectionResource implements FieldTransformerInterface
{

    use FieldTrait;

    /**
     * @param AssetsField $field
     * @param array       $config
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
