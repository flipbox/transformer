<?php

namespace flipbox\transformer\web\twig\variables;

use yii\di\ServiceLocator;
use flipbox\transformer\Plugin;

class Transformer extends ServiceLocator
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        // Set the core components
        $config['components'] = [];

        parent::__construct($config);

    }


    public function test()
    {
        return Plugin::getInstance()->test();
    }

}