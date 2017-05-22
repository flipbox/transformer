<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers;

use flipbox\spark\helpers\ArrayHelper;
use flipbox\transform\transformers\Item as BaseItemResource;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\helpers\Transformer;
use flipbox\transformer\Transformer as TransformerPlugin;
use yii\base\Exception;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class AbstractItemResource extends BaseItemResource
{

    /**
     * @param string $handle
     * @return TransformerInterface
     * @throws Exception
     */
    protected function resolveTransformerByHandle(string $handle): TransformerInterface
    {
        throw new Exception(sprintf(
            "Transformer '%s' does not exist on resource '%s'.",
            (string)$handle,
            (string)get_called_class()
        ));
    }

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

        if (Transformer::isTransformer($transformer)) {

            return $transformer;

        }

        if (Transformer::isTransformerClass($transformer)) {

            return new $transformer();

        }

        if (is_string($transformer)) {

            return $this->resolveTransformerByHandle($transformer);

        }

        TransformerPlugin::warning([
            "Unknown transformer:",
            $transformer
        ]);

        return null;

    }

}
