<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\configuration\services;

use Craft;
use craft\models\EntryType;
use craft\models\Section;
use flipbox\transformer\modules\configuration\events\RegisterSources;
use flipbox\transformer\Transformer;
use yii\base\Component;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Source extends Component
{

    /**
     * @var string[]
     */
    protected $_cacheAll;

    /**
     * @return string[]
     */
    public function findAll()
    {

        if (null === $this->_cacheAll) {

            $event = new RegisterSources(
                [
                'sources' => $this->_firstParty()
                ]
            );

            $configuration = Transformer::getInstance()->getConfiguration();

            $configuration->trigger(
                $configuration::EVENT_REGISTER_SOURCES,
                $event
            );

            $this->_cacheAll = $event->sources;

        }

        return $this->_cacheAll;

    }

    /**
     * @return string[]
     */
    private function _firstParty()
    {
        return array_merge(
            $this->_firstPartyElements(),
            $this->_firstPartyFields(),
            $this->_firstPartyModels()
        );
    }

    /**
     * @return string[]
     */
    private function _firstPartyElements()
    {
        return Craft::$app->getElements()->getAllElementTypes();
    }

    /**
     * @return string[]
     */
    private function _firstPartyFields()
    {
        return Craft::$app->getFields()->getAllFieldTypes();
    }

    /**
     * @return string[]
     */
    private function _firstPartyModels()
    {
        return [
            Section::class,
            EntryType::class
        ];
    }

}
