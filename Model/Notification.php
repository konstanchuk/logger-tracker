<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Model;


class Notification
{
    /**
     * @var \Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface
     */
    protected $logListRepository;

    /**
     * @var \Konstanchuk\LoggerTracker\Model\Resource\LogList\CollectionFactory
     */
    protected $collectionFactoty;

    /**
     * @var \Konstanchuk\LoggerTracker\Helper\Data
     */
    protected $helper;

    /**
     * @var \Konstanchuk\LoggerTracker\Model\System\Config\LogLevels
     */
    protected $logLevels;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    public function __construct(\Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface $logListRepository,
                                \Konstanchuk\LoggerTracker\Model\Resource\LogList\CollectionFactory $collectionFactory,
                                \Konstanchuk\LoggerTracker\Helper\Data $helper,
                                \Konstanchuk\LoggerTracker\Model\System\Config\LogLevels $logLevels,
                                \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
                                \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation)
    {
        $this->logListRepository = $logListRepository;
        $this->collectionFactory = $collectionFactory;
        $this->helper = $helper;
        $this->logLevels = $logLevels;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
    }

    public function sendNotification()
    {
        /** @var \Konstanchuk\LoggerTracker\Model\Resource\LogList\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('notification', ['eq' => false]);
        $countData = [];
        /** @var \Konstanchuk\LoggerTracker\Api\Data\LogListInterface $item */
        foreach ($collection as $item) {
            $level = $item->getLevel();
            if (isset($countData[$level])) {
                ++$countData[$level];
            } else {
                $countData[$level] = 1;
            }
            $item->setNotification(true);
        }
        $logLevels = array_column($this->logLevels->toOptionArray(), 'value');
        $logTrackerLevels = $this->helper->getTrackerLogLevels();
        $data = [];
        foreach ($logLevels as $level) {
            if (in_array($level, $logTrackerLevels)) {
                if (isset($countData[$level])) {
                    $value = $countData[$level];
                } else {
                    $value = 0;
                }
            } else {
                if (isset($countData[$level])) {
                    $value = sprintf('%d (not observed)', $countData[$level]);
                } else {
                    $value = sprintf('(not observed)');
                }
            }
            $data['count_error_' . $level] = $value;
        }
        $data['sum_logs'] = array_sum($countData);
        $this->sendMail($data);
        $collection->save();
    }

    protected function sendMail($data)
    {
        $this->inlineTranslation->suspend();
        $emailData = new \Magento\Framework\DataObject();
        $emailData->setData($data);
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('konstanchuk_logger_tracker_notification')
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars(['data' => $emailData])
            ->setFrom([
                'name' => $this->helper->getEmailSenderName(),
                'email' => $this->helper->getEmailSenderEmail(),
            ])
            ->addTo($this->helper->getEmailRecipientEmail(), $this->helper->getEmailRecipientName())
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}