<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\transformers\field;

use craft\fields\Date as DateField;
use Flipbox\Transform\Scope;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property \DateTime|null $data
 */
class Date extends AbstractTransformer
{

    const DEFAULT_FORMAT = 'c';

    /**
     * @param DateField $field
     * @param array $config
     */
    public function __construct(DateField $field, array $config = [])
    {
        parent::__construct($field, $config);
    }

    /**
     * @inheritdoc
     */
    public function transform(Scope $scope, string $identifier = null)
    {

        if (null === $this->data) {
            return null;
        }

        return (string)$this->data->format(
            $this->getFormat($scope, $identifier)
        );

    }

    /**
     * @param Scope $scope
     * @param string $identifier
     * @return string
     */
    private function getFormat(Scope $scope, string $identifier)
    {

        $format = $scope->getParams($identifier)->get('format');

        if ($format) {
            $format = reset($format);
        }

        return $format ?: self::DEFAULT_FORMAT;

    }

}