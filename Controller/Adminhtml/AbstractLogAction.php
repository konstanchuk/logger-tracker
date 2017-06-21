<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface;


abstract class AbstractLogAction extends Action
{
    /** @var LogListRepositoryInterface  */
    protected $logListRepository;

    public function __construct(Action\Context $context, LogListRepositoryInterface $logListRepository)
    {
        parent::__construct($context);
        $this->logListRepository = $logListRepository;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Konstanchuk_LoggerTracker::list');
    }
}