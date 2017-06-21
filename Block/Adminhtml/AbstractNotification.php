<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Block\Adminhtml;

use Konstanchuk\LoggerTracker\Helper\Data as Helper;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Template;
use Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface;


abstract class AbstractNotification extends Template
{
    /** @var LogListRepositoryInterface  */
    protected $_logListRepository;

    /** @var Helper  */
    protected $_helper;

    /** @var null|int */
    protected $_countUnreadRecord = null;

    public function __construct(Context $context,
                                LogListRepositoryInterface $logListRepository,
                                Helper $helper,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->_logListRepository = $logListRepository;
        $this->_helper = $helper;
    }

    public function getCountUnreadRecord()
    {
        if ($this->_countUnreadRecord == null) {
            $this->_countUnreadRecord = $this->_logListRepository->getCountUnreadRecord($this->_helper->getCriticalLogLevels());
        }
        return $this->_countUnreadRecord;
    }
}