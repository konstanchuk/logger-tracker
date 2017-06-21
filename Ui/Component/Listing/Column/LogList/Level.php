<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Ui\Component\Listing\Column\LogList;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;
use Konstanchuk\LoggerTracker\Model\System\Config\LogLevels;


class Level extends Column
{
    /** @var Escaper  */
    protected $escaper;

    /** @var LogLevels  */
    protected $logLevels;

    public function __construct(ContextInterface $context,
                                UiComponentFactory $uiComponentFactory,
                                Escaper $escaper,
                                LogLevels $logLevels,
                                array $components = [],
                                array $data = [])
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->escaper = $escaper;
        $this->logLevels = $logLevels;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $options = array_column($this->logLevels->toOptionArray(), 'label', 'value');
            foreach ($dataSource['data']['items'] as &$item) {
                $class = 'grid-severity-critical logger-tracker-lvl-' . $item['level'];
                $text = isset($options[$item['level']]) ? $options[$item['level']] : $item['level'];
                $item[$fieldName . '_original'] = isset($item[$fieldName]) ? $item[$fieldName] : null;
                $item[$fieldName] = sprintf('<span class="%s"><span>%s</span></span>', $class, $this->escaper->escapeHtml($text));
            }
        }
        return $dataSource;
    }
}
