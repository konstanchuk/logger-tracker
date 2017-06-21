<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Context;
use Konstanchuk\LoggerTracker\Model\System\Config\ErrorCompareTypes;


class ErrorCompare extends Select
{
    /**
     * methodList
     *
     * @var ErrorCompareTypes
     */
    protected $errorCompareTypes;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ErrorCompareTypes $errorCompareTypes
     * @param array $data
     */
    public function __construct(Context $context, ErrorCompareTypes $errorCompareTypes, array $data = [])
    {
        parent::__construct($context, $data);
        $this->errorCompareTypes = $errorCompareTypes;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = $this->errorCompareTypes->toOptionArray();
            foreach ($options as $option) {
                $this->addOption($option['value'], $option['label']);
            }
        }
        return parent::_toHtml();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
