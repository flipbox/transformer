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
    protected $cacheAll;

    /**
     * @return string[]
     */
    public function findAll()
    {
        if (null === $this->cacheAll) {
            $event = new RegisterSources(
                [
                'sources' => $this->firstParty()
                ]
            );

            $configuration = Transformer::getInstance()->configuration();

            $configuration->trigger(
                $configuration::EVENT_REGISTER_SOURCES,
                $event
            );

            $this->cacheAll = $event->sources;
        }

        return $this->cacheAll;
    }

    /**
     * @return string[]
     */
    private function firstParty()
    {
        return array_merge(
            $this->firstPartyElements(),
            $this->firstPartyFields(),
            $this->firstPartyModels()
        );
    }

    /**
     * @return string[]
     */
    private function firstPartyElements()
    {
        return Craft::$app->getElements()->getAllElementTypes();
    }

    /**
     * @return string[]
     */
    private function firstPartyFields()
    {
        return Craft::$app->getFields()->getAllFieldTypes();
    }

    /**
     * @return string[]
     */
    private function firstPartyModels()
    {
        return [
            Section::class,
            EntryType::class
        ];
    }
}
