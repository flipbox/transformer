<?php

namespace flipbox\transformer;

use craft\base\Plugin as BasePlugin;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use flipbox\organization\fields\User as OrganizationUserField;
use flipbox\organization\fields\Organization as OrganizationField;
use flipbox\organization\elements\Organization as OrganizationElement;
use flipbox\transform\Factory;
use flipbox\transform\resources\Collection;
use flipbox\transform\resources\ResourceInterface;
use flipbox\transformer\modules\element\events\RegisterTransformers;
use flipbox\transformer\modules\element\resources\EntryItem;
use flipbox\transformer\modules\field\resources\OrganizationCollection;
use flipbox\transformer\transformers\NestedItem;
use flipbox\transformer\resources\Item;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\events\RegisterResources;
use flipbox\transformer\modules\field\events\RegisterTransforms as RegisterFieldTransforms;
use flipbox\transformer\modules\field\events\RegisterResources as RegisterFieldResources;

use flipbox\transformer\modules\element\events\RegisterTransformers as RegisterElementTransformers;
use flipbox\transformer\resources\CallableProperty;
use flipbox\transformer\resources\DynamicCollection;
use flipbox\transformer\resources\DynamicItem;
use flipbox\transformer\transformers\Dates;
use flipbox\transformer\transformers\DynamicEntry;
use flipbox\transformer\transformers\fields\Organization as OrganizationTransformer;
use flipbox\transformer\transformers\NewTest;
use flipbox\transformer\transformers\Property;
use flipbox\transformer\transformers\Test;
use flipbox\transformer\web\twig\variables\Transformer as TransformerVariable;

class Plugin extends BasePlugin
{

    /**
     * @inheritdoc
     */
    public function defineTemplateComponent()
    {
        return TransformerVariable::class;
    }

    public function testNEW()
    {

        $resource = Factory::collection([
            'includes' => ['dates.created:format(Y/m/d)']
        ]);

        $data = $resource->transform(
            new NewTest(),
            Entry::find()
        );

        var_dump($data);
        exit;

    }

    public function test()
    {

//        /** @var Organization $field */
//        $field = \Craft::$app->getFields()->getFieldByHandle('organizations');
//
//        $organization = new \flipbox\organization\elements\Organization();
//        $orgQuery = $organization::find();
//
//        RegisterResources::on(
//            \flipbox\organization\elements\db\Organization::class,
//            RegisterResources::eventName($orgQuery),
//            function (RegisterResources $event) {
//                $event->addResource(
//                    'organizations',
//                    [
//                        'class' => DynamicCollection::class,
//                        'transformer' => [
//                            'class' => OrganizationTransformer::class
//                        ]
//                    ]
//                );
//            }
//        );
//
////        $orgQuery->on(
////            RegisterResources::eventName($orgQuery),
////            function (RegisterResources $event) {
////                $event->addResource(
////                    'organizations',
////                    [
////                        'class' => OrganizationTransformer::class
////                    ]
////                );
////            }
////        );
//////
//
        // Register third party field transformer
        RegisterFieldTransforms::on(
            OrganizationField::class,
            RegisterFieldTransforms::EVENT,
            function(RegisterFieldTransforms $event) {
                $event->addTransform(
                    'default',
                    new \flipbox\transformer\third\organization\field\transformers\organization\CollectionResource(
                        $event->sender
                    )
                );
            }
        );

        // Register third party field transformer
        RegisterFieldTransforms::on(
            OrganizationUserField::class,
            RegisterFieldTransforms::EVENT,
            function(RegisterFieldTransforms $event) {
                $event->addTransform(
                    'default',
                    new \flipbox\transformer\third\organization\field\transformers\user\CollectionResource(
                        $event->sender
                    )
                );
            }
        );
//
        // Register organization element
        RegisterElementTransformers::on(
            OrganizationElement::class,
            RegisterElementTransformers::EVENT,
            function(RegisterElementTransformers $event) {
                $event->addTransformer(
                    'default',
                    new \flipbox\transformer\third\organization\element\transformers\organization\Organization()
                );
            }
        );

        $resourceConfig = [
            'class' => DynamicItem::class, // Resource
            'transformer' => [
                'class' => DynamicEntry::class, // Transformer
//                'resources' => [
//                    'title' => Property::class,
//                    'dates' => [
//                        'class' => DynamicItem::class, // Resource
//                        'transformer' => Dates::class
//                    ]
//                ]
            ]
        ];



        $entry = \Craft::$app->getEntries()->getEntryById(8);

        $resource = Factory::item([
            'includes' => ['users:id(1|3)']
        ]);

        $data = $resource->transform(
            new \flipbox\transformer\modules\element\transformers\entry\Entry(),
            $entry
        );


//
//        $factory = new Factory([
//            'includes' => ['dates.created:format(Y/m/d)']
//        ]);
//
////        var_dump($resource);
//
//        $data = $factory->transform(
//            $test
//        );

        var_dump($data);

        exit;

//        $entry->getBehaviors()

//        RegisterElementResources::on(
//            Entry::class,
//            RegisterElementResources::EVENT,
//            function (RegisterElementResources $event) use ($resourceConfig) {
//                $event->addResource(
//                    'test',
//                    $resourceConfig
//                );
//            }
//        );

        $resource = new EntryItem();

        /** @var ResourceInterface $resource */
//        $resource = $this->getElement()->getElement()->get('test', $entry);
        $resource->setData($entry);

        $factory = new Factory([
            'includes' => ['users:status(null)'],
//            'excludes' => ['users'],
//            'fields' => ['dates' => 'created']
        ]);

//        var_dump($resource);

        $data = $factory->transform(
            $resource
        );

        var_dump($data);


//        $field = new Matrix();
//
//        $transformers = $this->getField()->getField()->getAllTransformers($field);
//
//        var_dump($transformers);
//
//        $event = new RegisterTransformer();
////
////
////
//
//
//        $field->trigger(
//            RegisterTransformer::eventName($field),
//            $event
//        );


        exit;

    }

    /*******************************************
     * SERVICES
     *******************************************/

    /**
     * @return services\Resources
     */
    public function getResources()
    {
        return $this->get('resources');
    }

    /*******************************************
     * SUB-MODULES
     *******************************************/

    /**
     * @return modules\field\Module
     */
    public function getField()
    {
        return $this->getModule('field');
    }

    /**
     * @return modules\element\Module
     */
    public function getElement()
    {
        return $this->getModule('element');
    }

}