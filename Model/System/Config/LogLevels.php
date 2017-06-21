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


class LogLevels implements OptionSourceInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [];
        $levels = array_flip(\Monolog\Logger::getLevels());
        foreach ($levels as $key => $value) {
            $options[] = [
                'value' => $key,
                'label' => $value,
            ];
        }
        return $options;
    }
}