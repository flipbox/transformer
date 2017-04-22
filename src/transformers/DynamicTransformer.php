<?php

namespace flipbox\transformer\transformers;

use craft\elements\Entry;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\resources\traits\ResolveResource;
use flipbox\transform\helpers\Object as ObjectHelper;

class DynamicTransformer extends AbstractTransformer
{

    use ResolveResource;

    public $resources = [];

    public $context = 'global';

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        ObjectHelper::configure($this, $config);
    }

    /**
     * @param mixed $data
     * @return array
     */
    public function transform($data): array
    {
        return $this->prepareResources($data);
    }

    /**
     * @param $data
     * @return array
     */
    protected function prepareResources($data)
    {

        $resources = [];

        foreach($this->resources as $key => $val) {

            $resources[$key] = $this->resolveResource($val, $data, $this->context);

        }

        return $resources;

    }

}