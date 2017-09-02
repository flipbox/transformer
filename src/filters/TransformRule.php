<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\filters;

use Closure;
use craft\helpers\ArrayHelper;
use craft\web\User;
use yii\base\Action;
use yii\base\InvalidConfigException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TransformRule extends Transform
{
    /**
     * List of action IDs that this rule applies to. The comparison is case-sensitive.
     * If not set or empty, it means this rule applies to all actions.
     *
     * @var array
     */
    public $actions;

    /**
     * List of roles that this rule applies to (requires properly configured User component).
     * Two special roles are recognized, and they are checked via [[User::isGuest]]:
     *
     * - `?`: matches a guest user (not authenticated yet)
     * - `@`: matches an authenticated user
     *
     * If you are using RBAC (Role-Based Access Control), you may also specify role or permission names.
     * In this case, [[User::can()]] will be called to check access.
     *
     * If this property is not set or empty, it means this rule applies to all roles.
     *
     * @var array
     * @see $roleParams
     */
    public $roles;

    /**
     * Parameters to pass to the [[User::can()]] function for evaluating
     * user permissions in [[$roles]].
     *
     * If this is an array, it will be passed directly to [[User::can()]]. For example for passing an
     * ID from the current request, you may use the following:
     *
     * ```php
     * ['postId' => Yii::$app->request->get('id')]
     * ```
     *
     * You may also specify a closure that returns an array. This can be used to
     * evaluate the array values only if they are needed, for example when a model needs to be
     * loaded like in the following code:
     *
     * ```php
     * 'rules' => [
     *     [
     *         'allow' => true,
     *         'actions' => ['update'],
     *         'roles' => ['updatePost'],
     *         'roleParams' => function($rule) {
     *             return ['post' => Post::findOne(Yii::$app->request->get('id'))];
     *         },
     *     ],
     * ],
     * ```
     *
     * A reference to the [[AccessRule]] instance will be passed to the closure as the first parameter.
     *
     * @var array|Closure
     * @see $roles
     */
    public $roleParams = [];

    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     */
    public function init()
    {
        $this->actions = $this->resolveActions($this->actions);
        parent::init();
    }

    /**
     * Checks whether the Web user is allowed to perform the specified action.
     *
     * @param Action $action the action to be performed
     * @param User|false $user the user object or `false` in case of detached User component
     * @return bool|null `true` if the user is allowed, `false` if the user is denied, `null` if the rule does not apply to the user
     */
    public function matches(Action $action, $user)
    {
        if ($this->matchAction($action) &&
            $this->matchRole($user)) {
            return true;
        }

        return false;
    }

    /**
     * @param Action $action the action
     * @return bool whether the rule applies to the action
     */
    protected function matchAction($action)
    {
        return empty($this->actions) || isset($this->actions['*']) || array_key_exists($action->id, $this->actions);
    }

    /**
     * @param User $user the user object
     * @return bool whether the rule applies to the role
     * @throws InvalidConfigException if User component is detached
     */
    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true;
        }
        if ($user === false) {
            throw new InvalidConfigException('The user application component must be available to specify roles in AccessRule.');
        }
        foreach ($this->roles as $role) {
            if ($role === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($role === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
            } else {
                if (!isset($roleParams)) {
                    $roleParams = $this->roleParams instanceof Closure ? call_user_func($this->roleParams, $this) : $this->roleParams;
                }
                if ($user->can($role, $roleParams)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $actions
     * @return array
     */
    private function resolveActions(array $actions = [])
    {
        $result = [];
        foreach ($actions as $i => $action) {
            if (!is_string($i)) {
                $i = $action;
                $action = null;
            }

            $result[$i] = $action;
        }

        return $result;
    }
}
