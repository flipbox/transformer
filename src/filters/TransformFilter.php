<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\filters;

use Craft;
use Flipbox\Transform\Factory;
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\helpers\Transformer as TransformerHelper;
use yii\base\ActionEvent;
use yii\base\ActionFilter;
use yii\base\Behavior;
use yii\base\Exception;
use yii\data\DataProviderInterface;
use yii\web\Controller;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TransformFilter extends ActionFilter
{

    /**
     * The default data transformer.  If a transformer cannot be resolved via an action mapping,
     * this transformer will be used.
     *
     * @var string|callable|TransformerInterface
     */
    public $transformer;

    /**
     * @var array this property defines the transformers for each action.
     * Each action that should only support one transformer.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => SomeClass::class,
     *   'update' => 'transformerHandle',
     *   'delete' => function() { return ['foo' => 'bar'] },
     *   '*' => SomeOtherClass::class,
     * ]
     * ```
     */
    public $actions = [];

    /**
     * @var string
     */
    public $fieldsParam = 'fields';

    /**
     * @var string
     */
    public $includesParam = 'includes';

    /**
     * @var string
     */
    public $excludesParam = 'excludes';

    /**
     * @var string the name of the envelope (e.g. `items`) for returning the resource objects in a collection.
     * This is used when serving a resource collection. When this is set and pagination is enabled, the serializer
     * will return a collection in the following format:
     *
     * ```php
     * [
     *     'data' => [...],  // assuming collectionEnvelope is "data"
     * ]
     * ```
     *
     * If this property is not set, the resource arrays will be directly returned without using envelope.
     * The pagination information as shown in `_links` and `_meta` can be accessed from the response HTTP headers.
     */
    public $collectionEnvelope = 'data';

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return array|null|DataProviderInterface
     */
    public function afterAction($action, $result)
    {
        return $this->transform($result);
    }

    /**
     * @param $data
     * @return array|DataProviderInterface|null
     */
    protected function transform($data)
    {
        if ($data instanceof DataProviderInterface) {
            return $this->transformDataProvider($data);
        }

        return $this->transformData($data);
    }

    /**
     * @param $data
     * @return array|null
     */
    protected function transformData($data)
    {
        if (Craft::$app->getRequest()->getIsHead()) {
            return null;
        } else {
            if (!$transformer = $this->transformer()) {
                return $data;
            }

            return Factory::item(
                $this->resolveTransformer($transformer),
                $data,
                $this->getTransformConfig()
            );
        }
    }

    /**
     * Serializes a data provider.
     * @param DataProviderInterface $dataProvider
     * @return array|DataProviderInterface the array representation of the data provider.
     */
    protected function transformDataProvider(DataProviderInterface $dataProvider)
    {
        if (Craft::$app->getRequest()->getIsHead()) {
            return null;
        } else {
            if (!$transformer = $this->transformer()) {
                return $dataProvider;
            }

            // The transformed data
            $models = Factory::collection(
                $this->resolveTransformer($transformer),
                $dataProvider->getModels(),
                $this->getTransformConfig()
            );

            if ($this->collectionEnvelope === null) {
                return $models;
            } else {
                return [
                    $this->collectionEnvelope => $models,
                ];
            }
        }
    }

    /**
     * @return callable|TransformerInterface|null
     * @throws Exception
     */
    protected function transformer()
    {
        // The requested action
        $action = Craft::$app->requestedAction->id;

        // Default transformer
        $transformer = $this->transformer;

        // Look for definitions
        if (isset($this->actions[$action])) {
            $transformer = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $transformer = $this->actions['*'];
        }

        return $transformer;
    }

    /**
     * @param $transformer
     * @return mixed
     * @throws Exception
     */
    protected function resolveTransformer($transformer)
    {
        if (TransformerHelper::isTransformer($transformer)) {
            return $transformer;
        }

        if (TransformerHelper::isTransformerClass($transformer)) {
            return new $transformer();
        }

        if (TransformerHelper::isTransformerConfig($transformer)) {
            return Craft::createObject($transformer);
        }

        return null;
    }

    /**
     * @return array
     */
    protected function getTransformConfig(): array
    {
        return [
            'includes' => $this->getRequestedIncludes(),
            'excludes' => $this->getRequestedExcludes(),
            'fields' => $this->getRequestedFields()
        ];
    }

    /**
     * @return array
     */
    protected function getRequestedFields(): array
    {
        return $this->normalizeRequest(
            Craft::$app->getRequest()->get($this->fieldsParam)
        );
    }

    /**
     * @return array
     */
    protected function getRequestedIncludes(): array
    {
        return $this->normalizeRequest(
            Craft::$app->getRequest()->get($this->includesParam)
        );
    }

    /**
     * @return array
     */
    protected function getRequestedExcludes(): array
    {
        return $this->normalizeRequest(
            Craft::$app->getRequest()->get($this->excludesParam)
        );
    }

    /**
     * @param $value
     * @return array
     */
    private function normalizeRequest($value): array
    {
        return is_string($value) ? preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY) : [];
    }
}
