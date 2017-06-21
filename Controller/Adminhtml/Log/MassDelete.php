<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Controller\Adminhtml\Log;

use Konstanchuk\LoggerTracker\Controller\Adminhtml\AbstractMassAction;


class MassDelete extends AbstractMassAction
{
    /**
     * @return void
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $logListDeleted = 0;
        try {
            /** @var \Konstanchuk\LoggerTracker\Api\Data\LogListInterface $logList */
            foreach ($collection->getItems() as $logList) {
                $this->logListRepository->delete($logList);
                ++$logListDeleted;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) were deleted.', $logListDeleted)
        );

        $this->_redirect('*/*/list');
    }
}
