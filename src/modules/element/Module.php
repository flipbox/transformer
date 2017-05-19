<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\element;

use craft\base\ElementInterface;
use flipbox\transform\Factory;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\helpers\Transformer;
use flipbox\transformer\Transformer as TransformerPlugin;
use yii\base\Module as BaseModule;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Transformer $module
 */
class Module extends BaseModule
{

    /**
     * @param ElementInterface $element
     * @param string $transformer
     * @param array $config
     * @return array|null
     */
    public function item(ElementInterface $element, $transformer = 'default', array $config = [])
    {

        if (!$transformer = $this->_resolveTransformer($transformer, $element)) {
            return null;
        }

        return Factory::item($config)->transform(
            $transformer,
            $element
        );

    }

    /**
     * @param ElementInterface $element
     * @param string $transformer
     * @param array $config
     * @return array|null
     */
    public function collection(ElementInterface $element, $transformer = 'default', array $config = [])
    {

        $transformer = $this->_resolveTransformer($transformer, $element);

        return Factory::collection($config)->transform(
            $transformer,
            $element
        );

    }

    /*******************************************
     * SERVICES
     *******************************************/

    /**
     * @return services\Transformer
     */
    public function getTransformer()
    {
        return $this->get('transformer');
    }


    /**
     * @param $transformer
     * @param ElementInterface $element
     * @return TransformerInterface|callable|null
     */
    private function _resolveTransformer($transformer, ElementInterface $element)
    {

        if (Transformer::isTransformer($transformer)) {

            return $transformer;

        }

        if (Transformer::isTransformerClass($transformer)) {

            return new $transformer();

        }

        if (is_string($transformer)) {

            return $this->getTransformer()->find($transformer, $element);

        }

        return null;

    }

}
