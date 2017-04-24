<?php


namespace flipbox\transformer\transformers;

use flipbox\spark\helpers\ArrayHelper;
use flipbox\transform\transformers\Item as BaseItemResource;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use yii\base\Exception;

abstract class AbstractItemResource extends BaseItemResource
{

    /**
     * @param string $handle
     * @return TransformerInterface
     * @throws Exception
     */
    protected abstract function resolveTransformerByHandle(string $handle): TransformerInterface;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {

        // Ensure we have a valid transformer
        if ($transformer = ArrayHelper::remove($config, 'transformer', 'default')) {
            $config['transformer'] = $this->resolveTransformer($transformer);
        }

        parent::__construct($config);

    }

    /**
     * @param $transformer
     * @return TransformerInterface|callable
     */
    protected function resolveTransformer($transformer)
    {

        if (is_callable($transformer) || $transformer instanceof TransformerInterface) {

            return $transformer;

        }

        if (is_string($transformer)) {

            if (is_subclass_of($transformer, TransformerInterface::class)) {

                return $this->resolveTransformerByClass($transformer);

            }

            return $this->resolveTransformerByHandle($transformer);

        }

        // todo log this

        return function () {

            // empty callable

        };

    }

    /**
     * @param $class
     * @return TransformerInterface
     */
    protected function resolveTransformerByClass($class): TransformerInterface
    {
        return new $class();
    }

}
