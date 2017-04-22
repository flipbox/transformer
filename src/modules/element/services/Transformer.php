<?php

namespace flipbox\transformer\modules\element\services;

use craft\base\Component;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\Entry;
use craft\elements\User;
use flipbox\spark\helpers\ArrayHelper;
use flipbox\transform\transformers\TransformerInterface;
use flipbox\transformer\modules\element\events\RegisterTransformers;
use flipbox\transformer\modules\element\transformers\entry\Entry as EntryTransformer;
use flipbox\transformer\modules\element\transformers\user\User as UserTransformer;
use yii\base\Exception;

class Transformer extends Component
{

    /**
     * @param ElementInterface|Element $element
     * @return mixed
     */
    public function getAll(ElementInterface $element)
    {

        // This could be transformers loaded outside events
        $transforms = $this->_firstParty($element);

        $event = new RegisterTransformers([
            'transformers' => $transforms
        ]);

        $element->trigger(
            RegisterTransformers::EVENT,
            $event
        );

        return $event->getTransformers();

    }

    /**
     * @param string $identifier
     * @param ElementInterface $element
     * @return TransformerInterface
     */
    public function find(string $identifier, ElementInterface $element)
    {

        return ArrayHelper::getValue(
            $this->getAll($element),
            $identifier
        );

    }

    /**
     * @param string $identifier
     * @param ElementInterface $element
     * @return TransformerInterface
     * @throws Exception
     */
    public function get(string $identifier, ElementInterface $element)
    {

        if (!$transformer = $this->find($identifier, $element)) {
            throw new Exception("Transformer not found");
        }

        return $transformer;

    }

    /**
     * @param ElementInterface $element
     * @return TransformerInterface[]
     */
    private function _firstParty(ElementInterface $element)
    {

        $transformers = [];

        switch (get_class($element)) {

            case User::class: {
                $transformers['default'] = new UserTransformer();
                break;

            }

            case Entry::class: {
                $transformers['default'] = new EntryTransformer();
                break;

            }

        }

        return $transformers;

    }

}