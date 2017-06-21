<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Controller\Adminhtml;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\App\Action\Context;
use Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface;
use Konstanchuk\LoggerTracker\Model\Resource\LogList\CollectionFactory;
use Magento\Backend\App\Action;


abstract class AbstractMassAction extends AbstractLogAction
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        Context $context,
        LogListRepositoryInterface $logListRepository,
        Filter $filter,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context, $logListRepository);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }
}
