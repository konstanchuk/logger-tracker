<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;


class IgnoreErrorTemplates extends AbstractFieldArray
{
    /**
     * Grid columns
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Enable the "Add after" button or not
     *
     * @var bool
     */
    protected $_addAfter = true;

    /**
     * Label of add button
     *
     * @var string
     */
    protected $_addButtonLabel;


    /**
     * Error Compare Types Renderer
     *
     * @var string
     */
    protected $_errorCompareTypesRenderer = false;

    /**
     * Check if columns are defined, set template
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'compare_type', [
                'label' => __('Compare Type'),
                'renderer' => $this->getErrorCompareTypesRenderer(),
            ]
        );
        $this->addColumn('text', ['label' => __('Text')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     * @throws \Exception
     */
    public function renderCellTemplate($columnName)
    {
        if ($columnName == 'text') {
            $this->_columns[$columnName]['class'] = 'input-text required-entry';
        }
        return parent::renderCellTemplate($columnName);
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function getErrorCompareTypesRenderer()
    {
        if (!$this->_errorCompareTypesRenderer) {
            $this->_errorCompareTypesRenderer = $this->getLayout()->createBlock(
                '\Konstanchuk\LoggerTracker\Block\Adminhtml\Form\Field\ErrorCompare',
                '',
                [
                    'data' => [
                        'is_render_to_js_template' => true
                    ]
                ]
            );
        }
        return $this->_errorCompareTypesRenderer;
    }

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $compareType = $row->getCompareType();
        $options = [];
        if ($compareType) {
            $hash = $this->getErrorCompareTypesRenderer()->calcOptionHash($compareType);
            $options['option_' . $hash] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}