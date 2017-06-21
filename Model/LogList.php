<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Model;

use Konstanchuk\LoggerTracker\Api\Data\LogListInterface;
use Magento\Framework\Model\AbstractModel;


class LogList extends AbstractModel implements LogListInterface
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Konstanchuk\LoggerTracker\Model\Resource\LogList');
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->_getData('level');
    }

    /**
     * @param int $level
     * @return $this
     */
    public function setLevel($level)
    {
        return $this->setData('level', $level);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->_getData('text');
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        return $this->setData('text', $text);
    }

    /**
     * @return int
     */
    public function getNotification()
    {
        return $this->_getData('notification');
    }

    /**
     * @param int $notification
     * @return $this
     */
    public function setNotification($notification)
    {
        return $this->setData('notification', (bool)$notification);
    }

    /**
     * @return bool
     */
    public function getReadMark()
    {
        return (bool)$this->_getData('read_mark');
    }

    /**
     * @param bool $mark
     * @return $this
     */
    public function setReadMark($mark)
    {
        return $this->setData('read_mark', (bool)$mark);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    public function beforeSave()
    {
        $return = parent::beforeSave();
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(time());
        }
        if ($this->getNotification() === null) {
            $this->setNotification(false);
        }
        if ($this->getReadMark() === null) {
            $this->setReadMark(false);
        }
        return $return;
    }
}