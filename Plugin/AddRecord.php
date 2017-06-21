<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Plugin;

use Konstanchuk\LoggerTracker\Helper\Data as Helper;
use Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface;


class AddRecord
{
    /** @var Helper  */
    protected $_helper;

    /** @var LogListRepositoryInterface  */
    protected $_logListRepository;

    public function __construct(Helper $helper, LogListRepositoryInterface $logListRepository)
    {
        $this->_helper = $helper;
        $this->_logListRepository = $logListRepository;
    }

    public function beforeAddRecord(\Monolog\Logger $subject, $level, $message, array $context = [])
    {
        if ($this->_helper->canSaveLogLevel($level, $message)) {
            $this->_logListRepository->addRecord($level, $message, $context);
        }
        return [$level, $message, $context];
    }
}