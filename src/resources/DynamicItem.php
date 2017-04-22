<?php

namespace flipbox\transformer\resources;

use craft\helpers\ArrayHelper;
use flipbox\transform\resources\Item;
use flipbox\transformer\transformers\traits\ResolveTransformer;

class DynamicItem extends Item
{

    use ResolveTransformer;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {

        // Ensure we have a valid transformer
        if ($transformer = ArrayHelper::remove($config, 'transformer', 'default')) {
            $config['transformer'] = $this->resolveTransformer($transformer);
        }

        parent::__construct($config);

    }

}