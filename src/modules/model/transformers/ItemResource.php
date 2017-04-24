<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\model\transformers;

use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use flipbox\transformer\transformers\AbstractItemResource;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Model $data
 */
class ItemResource extends AbstractItemResource
{

    /**
     * @param Model $data
     * @param array $config
     */
    public function __construct(Model $data, array $config = [])
    {
        $this->data = $data;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    protected function resolveTransformerByHandle(string $handle): TransformerInterface
    {

        $transformer = Plugin::getInstance()->getModel()->getTransformer()->find(
            $handle,
            $this->data
        );

        if (null === $transformer) {

            return parent::resolveTransformerByHandle($handle);

        }

        return $transformer;

    }

}
