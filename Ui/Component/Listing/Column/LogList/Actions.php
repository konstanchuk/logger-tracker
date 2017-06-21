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
use Magento\Framework\UrlInterface;


class Actions extends Column
{
    const URL_PATH_DELETE = 'logger_tracker/log/delete';
    const URL_PATH_READ_MARK = 'logger_tracker/log/readMark';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $idFieldName = $item['id_field_name'] ?? 'id';
                if (!isset($item[$idFieldName])) {
                    continue;
                }

                $item[$name]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['id' => $item[$idFieldName]]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete'),
                        'message' => __('Are you sure you wan\'t to delete a record?')
                    ]
                ];

                $readMark = $item['original_read_mark'] ?? $item['read_mark'] ?? false;
                $item[$name]['read_mark'] = [
                    'href' => $this->urlBuilder->getUrl(self::URL_PATH_READ_MARK, ['id' => $item[$idFieldName]]),
                    'label' => $readMark ? __('Mark as unread') : __('Mark as read'),
                    'confirm' => [
                        'title' => $readMark ? __('Mark as unread') : __('Mark as read'),
                        'message' => $readMark ? __('Are you sure you wan\'t to mark as unread record?') : __('Are you sure you wan\'t to mark as read record?'),
                    ]
                ];
            }
        }

        return $dataSource;
    }
}