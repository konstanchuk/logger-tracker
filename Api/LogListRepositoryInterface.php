<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Api;


interface LogListRepositoryInterface
{
    /**
     * @param \Konstanchuk\LoggerTracker\Api\Data\LogListInterface $logList
     * @return int
     */
    public function save(\Konstanchuk\LoggerTracker\Api\Data\LogListInterface $logList);

    /**
     * @param $logListId
     * @return \Konstanchuk\LoggerTracker\Api\Data\LogListInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($logListId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Konstanchuk\LoggerTracker\Api\Data\LogListSearchResultsInterface
     */
    public function getLogList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Konstanchuk\LoggerTracker\Api\Data\LogListInterface $logList
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Konstanchuk\LoggerTracker\Api\Data\LogListInterface $logList);

    /**
     * @param int $logListId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($logListId);

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return bool true on success
     */
    public function addRecord($level, $message, array $context = []);

    /**
     * @param int|array|null $level
     * @return int
     */
    public function getCountUnreadRecord($level = null);

    /**
     * @return void
     * @throws \Exception
     */
    public function deleteOldRecords();
}