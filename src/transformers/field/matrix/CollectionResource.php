<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field\matrix;

use craft\elements\db\MatrixBlockQuery;
use craft\fields\Matrix as MatrixField;
use flipbox\transformer\transformers\element\matrix\CollectionResource as MatrixCollectionResource;
use flipbox\transformer\transformers\field\FieldTrait;
use flipbox\transformer\transformers\field\FieldTransformerInterface;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property MatrixField $field
 */
class CollectionResource extends MatrixCollectionResource implements FieldTransformerInterface
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
