<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Model\System\Config;

use Magento\Framework\Data\OptionSourceInterface;


class ErrorCompareTypes implements OptionSourceInterface
{
    const FROM_START_TEXT = 1;
    const FROM_END_TEXT = 2;
    const IN_TEXT = 3;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::FROM_START_TEXT,
                'label' => __('from start'),
            ],
            [
                'value' => self::FROM_END_TEXT,
                'label' => __('from end'),
            ],
            [
                'value' => self::IN_TEXT,
                'label' => __('in text'),
            ],
        ];
        return $options;
    }
}