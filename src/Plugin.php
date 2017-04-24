<?php

namespace flipbox\transformer;

use craft\base\Plugin as BasePlugin;
use craft\elements\Entry;
use flipbox\organization\elements\Organization as OrganizationElement;
use flipbox\organization\fields\Organization as OrganizationField;
use flipbox\organization\fields\User as OrganizationUserField;
use flipbox\transform\Factory;
use flipbox\transformer\modules\element\events\RegisterTransformers as RegisterElementTransformers;
use flipbox\transformer\modules\field\events\RegisterTransformers as RegisterFieldTransformers;
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

    public function test()
    {

        // Register third party field transformer
        RegisterFieldTransformers::on(
            OrganizationField::class,
            RegisterFieldTransformers::EVENT,
            function (RegisterFieldTransformers $event) {
                $event->addTransformer(
                    'default',
                    new \flipbox\transformer\third\organization\field\transformers\organization\CollectionResource(
                        $event->sender
                    )
                );
            }
        );

        // Register third party field transformer
        RegisterFieldTransformers::on(
            OrganizationUserField::class,
            RegisterFieldTransformers::EVENT,
            function (RegisterFieldTransformers $event) {
                $event->addTransformer(
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
            function (RegisterElementTransformers $event) {
                $event->addTransformer(
                    'default',
                    new \flipbox\transformer\third\organization\element\transformers\organization\Organization()
                );
            }
        );

        $entry = \Craft::$app->getEntries()->getEntryById(8);

        $resource = Factory::item([
            'includes' => ['users:id(1|3)'],
            'excludes' => ['users.name']
        ]);

        $data = $resource->transform(
            new \flipbox\transformer\modules\element\transformers\entry\Entry(),
            $entry
        );

        var_dump($data);

        exit;

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