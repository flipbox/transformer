<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\filters;

use Craft;
use craft\base\ElementInterface;
use craft\web\User;
use Flipbox\Transform\Factory;
use Flipbox\Transform\Transformers\TransformerInterface;
use flipbox\transformer\Transformer;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\base\Exception;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\data\Pagination;
use yii\di\Instance;
use yii\web\Controller;
use yii\web\Link;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class AccessTransform extends Transform
{
    /**
     * The user object representing the authentication status or the ID of the
     * user application component.  This can also be a configuration array for creating the object or you can set it
     * to `false` to explicitly switch this component support off for the filter.
     *
     * @var User|array|string|false
     */
    public $user = 'user';

    /**
     * The default configuration of access rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     *
     * @var array
     */
    public $ruleConfig = [
        'class' => TransformRule::class
    ];

    /**
     * A list of transform rule objects or configuration arrays for creating the rule objects.
     * If a rule is specified via a configuration array, it will be merged with [[ruleConfig]] first
     * before it is used for creating the rule object.
     *
     * @var array
     * @see ruleConfig
     *
     * @var TransformRule[]
     */
    public $rules = [];

    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     */
    public function init()
    {
        parent::init();
        if ($this->user !== false) {
            $this->user = Instance::ensure($this->user, User::class);
        }
        foreach ($this->rules as $i => $rule) {
            $this->rules[$i] = $this->resolveRule($rule);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterAction(ActionEvent $event)
    {
        $user = $this->user;
        foreach ($this->rules as $rule) {
            if ($rule->matches($event->action, $user)) {
                $event->result = $rule->transform($event->result);
                return;
            }
        }

        parent::afterAction($event);
    }

    /**
     * @param $rule
     * @return mixed
     */
    protected function resolveRule($rule)
    {
        if (is_array($rule)) {
            $rule = Craft::createObject(
                array_merge(
                    $this->ruleConfig,
                    [
                        'global' => $this->global,
                        'scope' => $this->scope,
                        'component' => $this->component,
                        'data' => $this->data
                    ],
                    $rule
                )
            );
        }

        return $rule;
    }
}
