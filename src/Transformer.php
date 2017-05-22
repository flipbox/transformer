<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\web\UrlManager;
use flipbox\transform\Factory;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\helpers\Transformer as TransformerHelper;
use flipbox\transformer\web\twig\variables\Transformer as TransformerVariable;
use yii\base\Component;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Plugin
{

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            [self::class, 'onRegisterCpUrlRules']
        );

    }

    /**
     * @inheritdoc
     */
    public function defineTemplateComponent()
    {
        return TransformerVariable::class;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse()
    {

        Craft::$app->getResponse()->redirect(
            UrlHelper::cpUrl('transformer/configuration')
        );

        Craft::$app->end();

    }

    /**
     * @param $data
     * @param string $transformer
     * @param string $scope
     * @param array $config
     * @return array|null
     */
    public function item($data, $transformer = 'default', string $scope = 'global', array $config = [])
    {

        if (!$transformer = $this->resolveTransformer($transformer, $data, $scope)) {
            return null;
        }

        return Factory::item($config)->transform(
            $transformer,
            $data
        );

    }

    /**
     * @param $data
     * @param string $transformer
     * @param string $scope
     * @param array $config
     * @return array|null
     */
    public function collection($data, $transformer = 'default', string $scope = 'global', array $config = [])
    {

        $transformer = $this->resolveTransformer($transformer, $data, $scope);

        return Factory::collection($config)->transform(
            $transformer,
            $data
        );

    }

    /*******************************************
     * EVENTS
     *******************************************/

    /**
     * @param RegisterUrlRulesEvent $event
     */
    public static function onRegisterCpUrlRules(RegisterUrlRulesEvent $event)
    {

        $event->rules = array_merge(
            $event->rules,
            [

                // ROOT
                'transformer' => 'transformer/view/transformer/index',
                'transformer/<handle:{handle}>' => 'transformer/view/transformer/view'

            ]
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

    /*******************************************
     * SUB-MODULES
     *******************************************/

    /**
     * @return modules\configuration\Module
     */
    public function getConfiguration()
    {
        return $this->getModule('configuration');
    }


    /**
     * Logs an informative message.
     *
     * @param $message
     * @param string $category
     */
    public static function info($message, $category = 'transformer')
    {
        Craft::info($message, $category);
    }

    /**
     * Logs a warning message.
     *
     * @param $message
     * @param string $category
     */
    public static function warning($message, $category = 'transformer')
    {
        Craft::warning($message, $category);
    }

    /**
     * Logs an error message.
     *
     * @param $message
     * @param string $category
     */
    public static function error($message, $category = 'transformer')
    {
        Craft::error($message, $category);
    }


    /**
     * @param $transformer
     * @param Component $component
     * @param string $scope
     * @return TransformerInterface|callable|null
     */
    private function resolveTransformer($transformer, Component $component, string $scope = 'global')
    {

        if (TransformerHelper::isTransformer($transformer)) {

            return $transformer;

        }

        if (TransformerHelper::isTransformerClass($transformer)) {

            return new $transformer();

        }

        if (is_string($transformer)) {

            return $this->getTransformer()->find($transformer, $component, $scope);

        }

        return null;

    }

}
