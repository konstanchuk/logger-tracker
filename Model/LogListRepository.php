<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface;


class LogListRepository implements LogListRepositoryInterface
{
    /** @var \Konstanchuk\LoggerTracker\Api\Data\LogListInterface[] */
    protected $entities = [];

    /** @var \Konstanchuk\LoggerTracker\Model\Resource\LogList */
    protected $resource;

    /** @var \Konstanchuk\LoggerTracker\Model\LogListFactory */
    protected $modelFactory;

    /** @var \Konstanchuk\LoggerTracker\Model\Resource\LogList\CollectionFactory */
    protected $collectionFactory;

    /** @var \Konstanchuk\LoggerTracker\Api\Data\LogListSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var \Magento\Framework\Api\SearchCriteriaBuilderFactory */
    protected $searchCriteriaBuilderFactory;

    /** @var \Magento\Framework\Api\SortOrderBuilderFactory */
    protected $sortOrderBuilderFactory;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var DataObjectProcessor */
    protected $dataObjectProcessor;

    /** @var \Konstanchuk\LoggerTracker\Helper\Data */
    protected $helper;

    public function __construct(
        \Konstanchuk\LoggerTracker\Model\Resource\LogList $resource,
        \Konstanchuk\LoggerTracker\Model\LogListFactory $modelFactory,
        \Konstanchuk\LoggerTracker\Model\Resource\LogList\CollectionFactory $collectionFactory,
        \Konstanchuk\LoggerTracker\Api\Data\LogListSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        \Magento\Framework\Api\SortOrderBuilderFactory $sortOrderBuilderFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        \Konstanchuk\LoggerTracker\Helper\Data $helper
    )
    {
        $this->resource = $resource;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->sortOrderBuilderFactory = $sortOrderBuilderFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->helper = $helper;
    }

    /**
     * @param \Konstanchuk\LoggerTracker\Api\Data\LogListInterface $model
     * @return int
     */
    public function save(\Konstanchuk\LoggerTracker\Api\Data\LogListInterface $model)
    {
        try {
            $this->resource->save($model);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $model->getId();
    }

    /**
     * @param $id
     * @return \Konstanchuk\LoggerTracker\Api\Data\LogListInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        if (isset($this->entities[$id])) {
            return $this->entities[$id];
        }
        /* @var $model \Konstanchuk\LoggerTracker\Model\LogList */
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Model does not exist'));
        }
        $this->entities[$id] = $model;
        return $model;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResults
     */
    public function getLogList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    $sortOrder->getDirection()
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $items = [];
        /** @var \Konstanchuk\LoggerTracker\Model\LogList $item */
        foreach ($collection as $item) {
            /** @var \Konstanchuk\LoggerTracker\Model\LogList $model */
            $model = $this->modelFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $model,
                $item->getData(),
                'Konstanchuk\LoggerTracker\Api\Data\LogListInterface'
            );
            $items[] = $model;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById($id)
    {
        $model = $this->get($id);
        return $this->delete($model);
    }

    public function delete(\Konstanchuk\LoggerTracker\Api\Data\LogListInterface $model)
    {
        try {
            $this->resource->delete($model);
            if (isset($this->entities[$model->getId()])) {
                unset($this->entities[$model->getId()]);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Unable to remove entity with id "%1"', $model->getId()),
                $exception
            );
        }
        return true;
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return bool true on success
     */
    public function addRecord($level, $message, array $context = [])
    {
        try {
            /** @var LogList $model */
            $model = $this->modelFactory->create();
            $model->setLevel($level);
            $model->setText((string)$message); //if message is exception
            $this->save($model);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param int|array|null $level
     * @return int
     */
    public function getCountUnreadRecord($level = null)
    {
        /** @var \Konstanchuk\LoggerTracker\Model\Resource\LogList\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('read_mark', false);
        if ($level) {
            if (is_array($level)) {
                $collection->addFieldToFilter('level', ['in' => $level]);
            } else {
                $collection->addFieldToFilter('level', $level);
            }
        }
        return $collection->getSize();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function deleteOldRecords()
    {
        $seconds = $this->helper->getHoursBeforeRemoval() * 3600;
        /** @var \Konstanchuk\LoggerTracker\Model\Resource\LogList\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('notification', true)
            ->getSelect()->where(
                new \Zend_Db_Expr('TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, `created_at`)) >= ' . $seconds)
            );
        $collection->walk('delete');
    }
}