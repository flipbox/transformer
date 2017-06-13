<?php

namespace flipbox\transformer\controllers\view;

use Craft;
use craft\helpers\UrlHelper;
use craft\web\Controller;
use flipbox\transformer\Transformer;

/**
 * @property Transformer $module
 */
abstract class AbstractController extends Controller
{

    /**
     * The index view template path
     */
    const TEMPLATE_BASE = 'transformer' . DIRECTORY_SEPARATOR . '_cp';

    /*******************************************
     * VARIABLES
     *******************************************/

    /**
     * @return string
     */
    protected function getBaseActionPath(): string
    {
        return Transformer::getInstance()->getUniqueId();
    }

    /**
     * @return string
     */
    protected function getBaseCpPath(): string
    {
        return Transformer::getInstance()->getUniqueId();
    }

    /**
     * @inheritdoc
     */
    protected function baseVariables(array &$variables = [])
    {
        $module = Transformer::getInstance();

        // Guardian settings
        $variables['settings'] = $module->getSettings();

        // Page title
        $variables['title'] = Craft::t('transformer', "Transformers");

        // Selected tab
        $variables['selectedTab'] = '';

        // Path to controller actions
        $variables['baseActionPath'] = $this->getBaseActionPath();

        // Path to CP
        $variables['baseCpPath'] = $this->getBaseCpPath();

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = $this->getBaseCpPath();

        // Breadcrumbs
        $variables['crumbs'][] = [
            'label' => $variables['title'],
            'url' => UrlHelper::url($variables['baseCpPath'])
        ];
    }
}
