<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Cron;


class DeleteOldRecords
{
    /** @var \Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface  */
    protected $logListRepository;

    /** @var \Konstanchuk\LoggerTracker\Helper\Data  */
    protected $helper;

    public function __construct(\Konstanchuk\LoggerTracker\Api\LogListRepositoryInterface $logListRepository,
                                \Konstanchuk\LoggerTracker\Helper\Data $helper)
    {
        $this->logListRepository = $logListRepository;
        $this->helper = $helper;
    }

    public function execute()
    {
        try {
            $this->logListRepository->deleteOldRecords();
        } catch (\Exception $e) {
            $this->helper->getLogger()->error($e);
        }
        return $this;
    }
}