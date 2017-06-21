<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;


interface LogListSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Konstanchuk\LoggerTracker\Api\Data\LogListInterface[]
     */
    public function getItems();

    /**
     * @param \Konstanchuk\LoggerTracker\Api\Data\LogListInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}