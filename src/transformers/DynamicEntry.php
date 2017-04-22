<?php

namespace flipbox\transformer\transformers;

use craft\base\Field;
use craft\base\FieldInterface;
use craft\elements\Entry;
use flipbox\spark\helpers\ArrayHelper;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transform\Scope;
use flipbox\transform\transformers\AbstractTransformer;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\Plugin;
use flipbox\transformer\resources\traits\ResolveResource;
use flipbox\transform\helpers\Object as ObjectHelper;
use flipbox\transformer\transformers\traits\ResolveTransformer;

class DynamicEntry extends AbstractTransformer
{

    use ResolveResource, ResolveTransformer;

    public $resources = [];

    public $context = 'global';

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

    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry): array
    {

        $resources = $this->prepareResources($entry);

        foreach($resources as $key => $resource) {

            // Nested resource
            if($resource instanceof ResourceInterface) {

                var_dump("nested resource");
                $resource->setData(
                    $entry
                );

            }

        }

        return $resources;

    }

    /**
     * @param $entry
     * @return array
     */
    protected function prepareResources(Entry $entry)
    {



        return $this->prepareFieldResources($entry);


    }

    /**
     * @param $entry
     * @return array
     */
    protected function prepareFieldResources(Entry $entry)
    {

        $resources = [];

        /** @var FieldInterface[] $fields */
        $fields = $entry->getFieldLayout()->getFields();

        /** @var Field $field */
        foreach($fields as $field) {

            // Look for field transformer
            /** @var ResourceInterface $resource */
            if($resource = Plugin::getInstance()->getField()->getTransform()->find(
                'default',
                $field
            )) {

                $resources[$field->handle] = $resource;

            } else {

                var_dump("FIELD NOT FOUND", $field);
                exit;

            }

        }

//        $resources = [];

//        foreach($this->resources as $key => $val) {
//
//            $resources[$key] = $this->resolveResource($key, $val, $entry, $this->context);
//
//        }

        return $resources;

    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [
            'dates'
        ];

    }

}