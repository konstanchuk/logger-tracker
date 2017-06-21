<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Ui\Component\Listing\Column\LogList;

use Magento\Ui\Component\Listing\Columns\Column;


class Text extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[$fieldName])) {
                    if (strlen($item[$fieldName]) > 500) {
                        $shortText = substr($item[$fieldName], 0, 500) . ' ...';
                    } else {
                        $shortText = $item[$fieldName];
                    }
                    $item[$fieldName . '_short'] = $shortText;
                    $item[$fieldName . '_log_level'] = isset($item['level']) ? $item['level'] : null;
                }
            }
        }
        return $dataSource;
    }
}
