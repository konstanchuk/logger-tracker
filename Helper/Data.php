<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Monolog\Logger;
use Konstanchuk\LoggerTracker\Model\System\Config\ErrorCompareTypes;


class Data extends AbstractHelper
{
    const XML_PATH_LOG_LEVELS = 'dev/logger_tracker/log_levels';
    const XML_PATH_IGNORE_ERROR_TEMPLATES = 'dev/logger_tracker/ignore_err_templates';
    const XML_PATH_NOTIFICATION_RECIPIENT_NAME = 'dev/logger_tracker/notification_recipient_name';
    const XML_PATH_NOTIFICATION_RECIPIENT_EMAIL = 'dev/logger_tracker/notification_recipient_email';
    const XML_PATH_NOTIFICATION_SCHEDULE = 'dev/logger_tracker/notification_schedule';
    const XML_PATH_HOURS_BEFORE_REMOVAL = 'dev/logger_tracker/hours_before_removal';

    /** @var null|array */
    protected $ignoreErrorTemplates = null;

    public function getTrackerLogLevels()
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_LOG_LEVELS);
        return $value ? explode(',', $value) : [];
    }

    public function canSaveLogLevel($level, $message = null)
    {
        if (in_array($level, $this->getTrackerLogLevels())) {
            if ($message) {
                $message = (string)$message; //if message is exception
                $ignoreTemplates = $this->getIgnoreErrorTemplates();
                foreach ($ignoreTemplates as $item) {
                    $text = $item['text'];
                    if ($item['compare_type'] == ErrorCompareTypes::FROM_START_TEXT) {
                        if ((substr($message, 0, strlen($text)) === $text)) { //starts with
                            return false;
                        }
                    } else if ($item['compare_type'] == ErrorCompareTypes::FROM_END_TEXT) {
                        if (substr($message, -strlen($text)) === $text) { //ends with
                            return false;
                        }
                    } else {
                        if (mb_strpos($message, $text) !== false) { //in string
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function getCriticalLogLevels()
    {
        return [Logger::CRITICAL, Logger::ALERT, Logger::EMERGENCY];
    }

    public function getIgnoreErrorTemplates()
    {
        if (is_null($this->ignoreErrorTemplates)) {
            $this->ignoreErrorTemplates = [];
            $configTable = $this->scopeConfig->getValue(static::XML_PATH_IGNORE_ERROR_TEMPLATES);
            if ($configTable) {
                $ignoreErrorTemplates = $this->unserializeConfigTable($configTable, ['compare_type', 'text']);
                foreach ($ignoreErrorTemplates as $item) {
                    $text = trim($item['text']);
                    if ($text) {
                        $this->ignoreErrorTemplates[] = [
                            'compare_type' => $item['compare_type'],
                            'text' => $text,
                        ];
                    }
                }
            }
        }
        return $this->ignoreErrorTemplates;
    }

    public function getEmailSenderName()
    {
        return trim($this->scopeConfig->getValue('trans_email/ident_general/name'));
    }

    public function getEmailSenderEmail()
    {
        return trim($this->scopeConfig->getValue('trans_email/ident_general/email'));
    }

    public function getEmailRecipientName()
    {
        return trim($this->scopeConfig->getValue(self::XML_PATH_NOTIFICATION_RECIPIENT_NAME));
    }

    public function getEmailRecipientEmail()
    {
        return trim($this->scopeConfig->getValue(self::XML_PATH_NOTIFICATION_RECIPIENT_EMAIL));
    }

    public function getHoursBeforeRemoval()
    {
        $hours = (int)$this->scopeConfig->getValue(self::XML_PATH_HOURS_BEFORE_REMOVAL);
        return $hours <= 0 ? 1 : $hours;
    }

    public function getLogger()
    {
        return $this->_logger;
    }

    protected function unserializeConfigTable($configTable, array $columns)
    {
        $tableConfigResults = unserialize($configTable);
        $result = [];
        if (is_array($tableConfigResults)) {
            foreach ($tableConfigResults as $item) {
                $row = [];
                foreach ($columns as $column) {
                    $row[$column] = isset($item[$column]) ? $item[$column] : null;
                }
                $result[] = $row;
            }
        }
        return $result;
    }
}