<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Model\LogList;

use Magento\Framework\Data\OptionSourceInterface;


class ReadMark implements OptionSourceInterface
{
    const READ = 1;
    const UNREAD = 0;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::READ,
                'label' => __('read'),
            ], [
                'value' => self::UNREAD,
                'label' => __('unread'),
            ]
        ];
        return $options;
    }
}