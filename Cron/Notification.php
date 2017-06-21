<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Cron;


class Notification
{
    /** @var \Konstanchuk\LoggerTracker\Model\Notification  */
    protected $notification;

    /** @var \Konstanchuk\LoggerTracker\Helper\Data  */
    protected $helper;

    public function __construct(\Konstanchuk\LoggerTracker\Model\Notification $notification,
                                \Konstanchuk\LoggerTracker\Helper\Data $helper)
    {
        $this->notification = $notification;
        $this->helper = $helper;
    }

    public function execute()
    {
        try {
            $this->notification->sendNotification();
        } catch (\Exception $e) {
            $this->helper->getLogger()->critical($e);
        }
        return $this;
    }
}