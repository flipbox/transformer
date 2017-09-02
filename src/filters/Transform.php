<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\filters;

use Craft;
use craft\base\ElementInterface;
use Flipbox\Transform\Factory;
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\Transformer;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\base\Exception;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Link;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Transform extends Behavior
{
    /**
     * The pagination transformer identifier
     */
    const PAGINATION_IDENTIFIER = 'pagination';

    /**
     * The error transformer identifier
     */
    const ERROR_IDENTIFIER = 'error';

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
     * The default data transformer.  If a transformer cannot be resolved via an action mapping,
     * this transformer will be used.
     *
     * @var string|callable|TransformerInterface
     */
    public $data = 'data';

    /**
     * @var string|callable|TransformerInterface
     */
    public $error = self::ERROR_IDENTIFIER;

    /**
     * @var string|callable|TransformerInterface
     */
    public $pagination = self::PAGINATION_IDENTIFIER;

    /**
     * The global class that universal transformers may be registered under.
     *
     * @var string
     */
    public $global = Transformer::class;

    /**
     * The component class that is being transformed
     *
     * @var string
     */
    public $component = ElementInterface::class;

    /**
     * The scope that transformers are registered under.
     *
     * @var
     */
    public $scope = 'rest';

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
     * @var string the name of the HTTP header containing the information about total number of data items.
     * This is used when serving a resource collection with pagination.
     */
    public $totalCountHeader = 'X-Pagination-Total-Count';

    /**
     * @var string the name of the HTTP header containing the information about total number of pages of data.
     * This is used when serving a resource collection with pagination.
     */
    public $pageCountHeader = 'X-Pagination-Page-Count';

    /**
     * @var string the name of the HTTP header containing the information about the current page number (1-based).
     * This is used when serving a resource collection with pagination.
     */
    public $currentPageHeader = 'X-Pagination-Current-Page';

    /**
     * @var string the name of the HTTP header containing the information about the number of data items in each page.
     * This is used when serving a resource collection with pagination.
     */
    public $perPageHeader = 'X-Pagination-Per-Page';

    /**
     * @var string the name of the envelope (e.g. `items`) for returning the resource objects in a collection.
     * This is used when serving a resource collection. When this is set and pagination is enabled, the serializer
     * will return a collection in the following format:
     *
     * ```php
     * [
     *     'data' => [...],  // assuming collectionEnvelope is "items"
     *     'links' => {  // pagination links as returned by Pagination::getLinks()
     *         'self' => '...',
     *         'next' => '...',
     *         'last' => '...',
     *     },
     *     'meta' => {  // meta information as returned by Pagination::toArray()
     *         'total' => 100,
     *         'count' => 5,
     *         'current' => 1,
     *         'size' => 20,
     *     },
     * ]
     * ```
     *
     * If this property is not set, the resource arrays will be directly returned without using envelope.
     * The pagination information as shown in `_links` and `_meta` can be accessed from the response HTTP headers.
     */
    public $collectionEnvelope = 'data';

    /**
     * @var string the name of the envelope (e.g. `_links`) for returning the links objects.
     * It takes effect only, if `collectionEnvelope` is set.
     * @since 2.0.4
     */
    public $linksEnvelope = 'links';

    /**
     * @var string the name of the envelope (e.g. `_meta`) for returning the pagination object.
     * It takes effect only, if `collectionEnvelope` is set.
     * @since 2.0.4
     */
    public $metaEnvelope = 'meta';

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_AFTER_ACTION => 'afterAction'];
    }

    /**
     * @param ActionEvent $event
     */
    public function afterAction(ActionEvent $event)
    {
        $event->result = $this->transform($event->result);
    }

    /**
     * @param $data
     * @return array|null
     */
    protected function transform($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->transformModelErrors($data);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->transformDataProvider($data);
        } elseif (!is_array($data)) {
            return $this->transformData($data);
        }
        return $data;
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
            return Factory::item(
                $this->dataTransformer(),
                $data,
                $this->getTransformConfig()
            );
        }
    }

    /**
     * Serializes a data provider.
     * @param DataProviderInterface $dataProvider
     * @return array the array representation of the data provider.
     */
    protected function transformDataProvider(DataProviderInterface $dataProvider)
    {
        // The transformed data
        $models = Factory::collection(
            $this->dataTransformer(),
            $dataProvider->getModels(),
            $this->getTransformConfig()
        );

        if (($pagination = $dataProvider->getPagination()) !== false) {
            $this->addPaginationHeaders($pagination);
        }

        if (Craft::$app->getRequest()->getIsHead()) {
            return null;
        } elseif ($this->collectionEnvelope === null) {
            return $models;
        } else {
            $result = [
                $this->collectionEnvelope => $models,
            ];
            if ($pagination !== false) {
                return array_merge($result, $this->transformPagination($pagination));
            } else {
                return $result;
            }
        }
    }

    /**
     * Serializes a pagination into an array.
     * @param Pagination $pagination
     * @return array the array representation of the pagination
     * @see addPaginationHeaders()
     */
    protected function transformPagination($pagination)
    {
        return [
            $this->linksEnvelope => Link::serialize($pagination->getLinks(true)),
            $this->metaEnvelope => Factory::item(
                $this->paginationTransformer(),
                $pagination
            ),
        ];
    }

    /**
     * Serializes the validation errors in a model.
     * @param Model $model
     * @return array the array representation of the errors
     */
    protected function transformModelErrors(Model $model)
    {
        Craft::$app->getResponse()->setStatusCode(422, 'Data Validation Failed.');
        return Factory::collection(
            $this->errorTransformer(),
            $model->getErrors()
        );
    }

    /**
     * @return callable|TransformerInterface|null
     * @throws Exception
     */
    protected function dataTransformer()
    {
        // The requested action
        $action = Craft::$app->requestedAction->id;

        // Default transformer
        $transformer = $this->data;

        // Look for definitions
        if (isset($this->actions[$action])) {
            $transformer = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $transformer = $this->actions['*'];
        }

        return Transformer::getInstance()->resolveTransformer(
            $transformer,
            $this->component,
            $this->scope
        );
    }

    /**
     * @return callable|TransformerInterface|null
     * @throws Exception
     */
    protected function paginationTransformer()
    {
        return Transformer::getInstance()->resolveTransformer(
            $this->pagination,
            $this->global,
            $this->scope
        );
    }

    /**
     * @return callable|TransformerInterface|null
     * @throws Exception
     */
    protected function errorTransformer()
    {
        return Transformer::getInstance()->resolveTransformer(
            $this->error,
            $this->global,
            $this->scope
        );
    }

    /**
     * Adds HTTP headers about the pagination to the response.
     * @param Pagination $pagination
     */
    protected function addPaginationHeaders(Pagination $pagination)
    {
        $links = [];
        foreach ($pagination->getLinks(true) as $rel => $url) {
            $links[] = "<$url>; rel=$rel";
        }

        Craft::$app->getResponse()->getHeaders()
            ->set($this->totalCountHeader, $pagination->totalCount)
            ->set($this->pageCountHeader, $pagination->getPageCount())
            ->set($this->currentPageHeader, $pagination->getPage() + 1)
            ->set($this->perPageHeader, $pagination->pageSize)
            ->set('Link', implode(', ', $links));
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
