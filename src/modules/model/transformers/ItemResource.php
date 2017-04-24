<?php

namespace flipbox\transformer\modules\model\transformers;

use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use flipbox\transformer\transformers\AbstractItemResource;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class ItemResource
 * @package flipbox\transformer\modules\model\transformers
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
     * @param string $handle
     * @return TransformerInterface
     * @throws Exception
     */
    protected function resolveTransformerByHandle(string $handle): TransformerInterface
    {

        $transformer = Plugin::getInstance()->getModel()->getTransformer()->find(
            $handle,
            $this->data
        );

        if (null === $transformer) {

            throw new Exception(sprintf(
                "Transformer '%s' does not exist on resource '%s'.",
                (string)$handle,
                (string)get_called_class()
            ));

        }

        return $transformer;

    }

}
