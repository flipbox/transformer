<?php

namespace flipbox\transformer\controllers\view;

use Craft;
use craft\helpers\UrlHelper;
use flipbox\transformer\web\assets\tree\Tree;

class TransformerController extends AbstractViewController
{

    /** The template base path */
    const TEMPLATE_BASE = AbstractViewController::TEMPLATE_BASE . DIRECTORY_SEPARATOR . 'transformer';

    /**
     * The index view template path
     */
    const TEMPLATE_INDEX = self::TEMPLATE_BASE . DIRECTORY_SEPARATOR . 'index';

    /**
     * The index view template path
     */
    const TEMPLATE_VIEW = self::TEMPLATE_BASE . DIRECTORY_SEPARATOR . 'view';

    /**
     * @return string
     */
    public function actionIndex()
    {

        // Empty variables for template
        $variables = [];

        // apply base view variables
        $this->baseVariables($variables);

        $variables['transformers'] = $this->findAll();

        return $this->renderTemplate(
            static::TEMPLATE_INDEX,
            $variables
        );

    }

    /**
     * @param $handle
     * @return \yii\web\Response
     */
    public function actionView($handle)
    {

        // Empty variables for template
        $variables = [];

        // apply base view variables
        $this->baseVariables($variables);

        // Register our assets
        Craft::$app->getView()->registerAssetBundle(Tree::class);

        // Element
        $componentClass = Craft::$app->getRequest()->getRequiredParam('component');
        $component = new $componentClass;

        // Scope
        $scope = Craft::$app->getRequest()->getParam('scope', 'global');

        // Transformer
        $transformer = $this->module->getTransformer()->get(
            $handle,
            $component,
            $scope
        );

        // Data (to transform)
        $data = $this->module->getConfiguration()->getData()->findAll($component, $scope);

        $variables['transformer'] = $transformer;
        $variables['component'] = $component;
        $variables['handle'] = $handle;
        $variables['scope'] = $scope;
        $variables['data'] = $data;

        return $this->renderTemplate(
            static::TEMPLATE_VIEW,
            $variables
        );

    }

    /**
     * @return array
     */
    private function findAll(): array
    {

        $elementsByScope = [];

        $elementsByScope['global'] = $this->findAllByScope();

        foreach ($this->module->getConfiguration()->getScope()->findAll() as $scope) {

            $elementsByScope[$scope] = $this->findAllByScope($scope);

        }

        return $elementsByScope;

    }

    /**
     * @param string|null $scope
     * @return array
     */
    private function findAllByScope(string $scope = 'global'): array
    {

        $elements = [];

        foreach (Craft::$app->getElements()->getAllElementTypes() as $type) {

            $elements[$type] = $this->module->getTransformer()->findAll(
                new $type,
                $scope
            );

        }

        return $elements;

    }

    /**
     * Set base variables used to generate template views
     *
     * @param array $variables
     */
    protected function baseVariables(array &$variables = [])
    {

        // Get base variables
        parent::baseVariables($variables);

        // Breadcrumbs
        $variables['crumbs'][] = [
            'label' => $variables['title'],
            'url' => UrlHelper::url($variables['baseCpPath'])
        ];

    }

}
