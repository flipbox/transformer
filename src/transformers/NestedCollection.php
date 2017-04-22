<?php

namespace flipbox\transformer\transformers;

use craft\elements\Entry;
use craft\helpers\ArrayHelper;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\organization\elements\Organization as OrganizationElement;
use flipbox\transform\transformers\NestedCollection as BaseNestedCollection;
use flipbox\transformer\transformers\traits\ResolveTransformer;

class NestedCollection extends BaseNestedCollection
{

    use ResolveTransformer;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {

        // Ensure we have a valid transformer
        if ($transformer = ArrayHelper::remove($config, 'transformer')) {
            $config['transformer'] = $this->resolveTransformer($transformer);
        }

        parent::__construct($config);

    }
    
}