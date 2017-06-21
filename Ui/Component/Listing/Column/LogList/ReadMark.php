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


class ReadMark extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $read = $item['read_mark'] ?? false;
                $item['original_read_mark'] = $read;
                $item[$fieldName] = '<b style="font-size: 16px;">' . ($read ? '+' : '-') . '</b>';
            }
        }
        return $dataSource;
    }
}
