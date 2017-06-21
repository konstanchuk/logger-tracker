<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Block\Adminhtml\LogList\Listing;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;


class Info implements ButtonProviderInterface
{
    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [
            'label' => __('Info'),
            'class' => 'default',
            'on_click' => 'return false',
            'sort_order' => 100,
            'data_attribute' => [
                'mage-init' => [
                    'Konstanchuk_LoggerTracker/js/log-info' => [],
                ],
            ]
        ];
        return $data;
    }
}