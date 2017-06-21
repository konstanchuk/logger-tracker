<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Api\Data;


interface LogListInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level
     * @return $this
     */
    public function setLevel($level);

    /**
     * @return string
     */
    public function getText();

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text);

    /**
     * @return int
     */
    public function getNotification();

    /**
     * @param int $notification
     * @return $this
     */
    public function setNotification($notification);

    /**
     * @return bool
     */
    public function getReadMark();

    /**
     * @param bool $mark
     * @return $this
     */
    public function setReadMark($mark);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}