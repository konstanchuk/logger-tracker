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
use Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface;


class NotificationWindow extends AbstractNotification
{
    /**
     * Authentication
     *
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_authSession;

    /**
     * The property is used to define content-scope of block. Can be private or public.
     * If it isn't defined then application considers it as false.
     *
     * @var bool
     */
    protected $_isScopePrivate;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param LogListRepositoryInterface $logListRepository
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        LogListRepositoryInterface $logListRepository,
        Helper $helper,
        array $data = []
    ) {
        parent::__construct($context, $logListRepository, $helper, $data);
        $this->_authSession = $authSession;
        $this->_isScopePrivate = true;
    }

    /**
     * Render block
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShow()) {
            $this->setHeaderText($this->escapeHtml(__('Logger Tracker Message')));
            $this->setCloseText($this->escapeHtml(__('close')));
            $this->setReadDetailsText($this->escapeHtml(__('Read Details')));
            $this->setNoticeMessageText($this->escapeHtml(__('You have %1 error(s) that can harm the system.', $this->getCountUnreadRecord())));
            $this->setNoticeMessageUrl($this->escapeUrl($this->getUrl('logger_tracker/log/list')));
            $this->setSeverityText('critical');
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Check whether block should be displayed
     *
     * @return bool
     */
    public function canShow()
    {
        return $this->_authSession->isFirstPageAfterLogin() && $this->getCountUnreadRecord();
    }
}