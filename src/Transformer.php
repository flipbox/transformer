<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer;

use Craft;
use craft\base\ElementInterface;
use craft\base\FieldInterface;
use craft\base\Plugin;
use craft\db\Query;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use craft\web\UrlManager;
use flipbox\spark\models\Model;
use Flipbox\Transform\Factory;
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\helpers\Transformer as TransformerHelper;
use flipbox\transformer\services\traits\ElementTransformer;
use flipbox\transformer\services\traits\FieldTransformer;
use flipbox\transformer\records\Transformer as TransformerRecord;
use flipbox\transformer\services\traits\ModelTransformer;
use flipbox\transformer\web\twig\variables\Transformer as TransformerVariable;
use yii\base\Component;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transformer extends Plugin
{

    use ElementTransformer, ModelTransformer, FieldTransformer;

    /**
     * The array context transformer.  This context identifies all transformers that should
     * transform and output an array.
     */
    const CONTEXT_ARRAY = 'array';

    /**
     * The object context transformer.  This context identifies all transformers that should
     * transform and output an object.
     */
    const CONTEXT_OBJECT = 'object';

    /**
     * The event that gets called on the element when registering a transformer
     */
    const EVENT_REGISTER_TRANSFORMERS = 'registerTransformers';

    /**
     * @param string $scope
     * @param string $context
     * @return string
     */
    public static function eventName(string $scope = 'default', string $context = self::CONTEXT_ARRAY): string
    {
        return self::EVENT_REGISTER_TRANSFORMERS.':'.$scope.':'.$context;
    }

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
     * @param $transformer
     * @param string      $class
     * @param string      $scope
     * @param string      $context
     * @return TransformerInterface|callable|null
     */
    public function resolveTransformer(
        $transformer,
        string $class,
        string $scope = 'global',
        string $context = self::CONTEXT_ARRAY
    ) {
        if (TransformerHelper::isTransformer($transformer)) {
            return $transformer;
        }

        if (TransformerHelper::isTransformerClass($transformer)) {
            return new $transformer();
        }

        if (is_string($transformer)) {
            return $this->findTransformer($transformer, $class, $scope, $context);
        }

        return null;
    }

    /**
     * @param string   $identifier
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return callable|TransformerInterface
     */
    public function getTransformer(
        string $identifier,
        string $class,
        string $scope = 'global',
        string $context = self::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        return $this->transformer()->get($identifier, $class, $scope, $context, $siteId);
    }

    /**
     * @param string   $identifier
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return callable|TransformerInterface|null
     */
    public function findTransformer(
        string $identifier,
        string $class,
        string $scope = 'global',
        string $context = self::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        return $this->transformer()->find($identifier, $class, $scope, $context, $siteId);
    }

    /**
     * @param string   $class
     * @param string   $scope
     * @param string   $context
     * @param int|null $siteId
     * @return \callable[]|TransformerInterface[]
     */
    public function findAll(
        string $class,
        string $scope = 'global',
        string $context = self::CONTEXT_ARRAY,
        int $siteId = null
    ) {
        return $this->transformer()->findAll($class, $scope, $context, $siteId);
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
     * @param string $context
     * @param array  $config
     * @return array|null
     */
    public function item(
        $data,
        $transformer = 'default',
        string $scope = 'global',
        string $context = self::CONTEXT_ARRAY,
        array $config = []
    ) {
        if (!$transformer = $this->resolveTransformer($transformer, $data, $scope, $context)) {
            return null;
        }

        return Factory::item(
            $transformer,
            $data,
            $config
        );
    }

    /**
     * @param $data
     * @param string $transformer
     * @param string $scope
     * @param string $context
     * @param array  $config
     * @return array|null
     */
    public function collection(
        $data,
        $transformer = 'default',
        string $scope = 'global',
        string $context = self::CONTEXT_ARRAY,
        array $config = []
    ) {
        if (!$transformer = $this->resolveTransformer($transformer, ArrayHelper::firstValue($data), $scope, $context)) {
            return [];
        }

        return Factory::collection(
            $transformer,
            $data,
            $config
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
    public function transformer()
    {
        return $this->get('transformer');
    }

    /*******************************************
     * SUB-MODULES
     *******************************************/

    /**
     * @return modules\configuration\Module
     */
    public function configuration()
    {
        return $this->getModule('configuration');
    }

    /**
     * Logs an informative message.
     *
     * @param $message
     * @param string  $category
     */
    public static function info($message, $category = 'transformer')
    {
        Craft::info($message, $category);
    }

    /**
     * Logs a warning message.
     *
     * @param $message
     * @param string  $category
     */
    public static function warning($message, $category = 'transformer')
    {
        Craft::warning($message, $category);
    }

    /**
     * Logs an error message.
     *
     * @param $message
     * @param string  $category
     */
    public static function error($message, $category = 'transformer')
    {
        Craft::error($message, $category);
    }
}
