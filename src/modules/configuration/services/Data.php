<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\modules\configuration\services;

use Craft;
use craft\base\ElementInterface;
use craft\base\FieldInterface;
use craft\elements\Entry;
use craft\models\EntryType;
use craft\models\EntryType as EntryTypeModel;
use craft\models\Section;
use craft\models\Section as SectionModel;
use flipbox\transformer\modules\configuration\events\RegisterData;
use flipbox\transformer\Transformer;
use yii\base\Component;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Data extends Component
{
    /**
     * @var string[]
     */
    protected $cacheAll;

    /**
     * @param Component $component
     * @param string    $scope
     * @return Component[]
     */
    public function findAll(Component $component, string $scope = 'global')
    {
        if (null === $this->cacheAll) {
            $event = new RegisterData(
                [
                'data' => $this->firstParty($component)
                ]
            );

            $configuration = Transformer::getInstance()->configuration();

            $configuration->trigger(
                $configuration::EVENT_REGISTER_DATA . ':' . get_class($component) . ':' . $scope,
                $event
            );

            $this->cacheAll = $event->data;
        }

        return $this->cacheAll;
    }

    /**
     * @param Component $component
     * @return Component[]
     */
    private function firstParty(Component $component)
    {
        if ($component instanceof ElementInterface) {
            return $this->firstPartyElements($component);
        }

        if ($component instanceof Model) {
            return $this->firstPartyModels($component);
        }

        if ($component instanceof FieldInterface) {
            return $this->firstPartyFields($component);
        }

        return [];
    }

    /**
     * @param ElementInterface $element
     * @return Component[]
     */
    private function firstPartyElements(ElementInterface $element)
    {
        if ($element = $element::findOne()) {
            return ['default' => $element::findOne()];
        }

        return [];
    }

    /**
     * @param FieldInterface $field
     * @return Component[]
     */
    private function firstPartyFields(FieldInterface $field)
    {
        return ['default' => $field->normalizeValue(null)];
    }

    /**
     * @param Model $model
     * @return Component[]
     */
    private function firstPartyModels(Model $model)
    {
        $transformers = [];

        switch (get_class($model)) {
            case SectionModel::class:
                $sections = Craft::$app->getSections()->getAllSections();
                $transformers['default'] = reset($sections);
                break;
            case EntryTypeModel::class:
                if ($entry = Entry::findOne()) {
                    /**
                    * @var Entry $entry
                    */
                    $transformers['default'] = $entry->getType();
                }
                break;
        }

        return $transformers;
    }
}
