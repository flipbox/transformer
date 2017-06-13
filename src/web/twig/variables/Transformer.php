<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\web\twig\variables;

use yii\di\ServiceLocator;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends ServiceLocator
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {

        parent::__construct(
            array_merge(
                $config,
                [
                'components' => [
                    'element' => Element::class
                ]
                ]
            )
        );

    }

    /**
     * Sub-Variables that are accessed 'craft.transformer.element'
     *
     * @return Element
     */
    public function getElement()
    {
        return $this->get('element');
    }

}