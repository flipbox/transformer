<?php

namespace flipbox\transformer\transformers;

use craft\elements\Entry;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\resources\traits\ResolveResource;
use flipbox\transform\helpers\Object as ObjectHelper;
use craft\base\Element as BaseElement;

class Element extends AbstractTransformer
{

    use ResolveResource;

    /**
     * @var callable|ResourceInterface
     */
    public $resources = [];

    /**
     * @var string
     */
    public $context = 'global';

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        ObjectHelper::configure($this, $config);
    }

    /**
     * @param BaseElement $element
     * @return array
     */
    public function transform(BaseElement $element): array
    {
        return $this->prepareResources($element);
    }

    /**
     * @param BaseElement $element
     * @return array
     */
    protected function prepareResources(BaseElement $element)
    {

        $resources = [];

        foreach($this->resources as $key => $val) {

            $resources[$key] = $this->resolveResource($val, $element, $this->context);

        }

        return $resources;

    }

}