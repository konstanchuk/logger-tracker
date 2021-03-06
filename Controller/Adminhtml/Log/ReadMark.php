<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Controller\Adminhtml\Log;

use Magento\Framework\Exception\NoSuchEntityException;
use Konstanchuk\LoggerTracker\Controller\Adminhtml\AbstractLogAction;


class ReadMark extends AbstractLogAction
{
    /**
     * @return void
     */
    public function execute()
    {
        $logId = (int)$this->getRequest()->getParam('id');
        if ($logId) {
            try {
                $log = $this->logListRepository->get($logId);
                $log->setReadMark(!$log->getReadMark());
                $this->logListRepository->save($log);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This log no longer exists.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        $this->_redirect('*/*/list');
    }
}