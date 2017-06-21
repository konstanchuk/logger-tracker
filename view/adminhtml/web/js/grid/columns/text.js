/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define([
    'Magento_Ui/js/grid/columns/column',
    'jquery',
    'underscore',
    'Magento_Ui/js/modal/modal'
], function (Column, $, _) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        getHtml: function (row) {
            return this.getLabel(row);
        },
        getLabel: function (row) {
            var key = this.index + '_short';
            return _.has(row, key) ? row[key] : this.getFullText(row);
        },
        getFullText: function (row) {
            return row[this.index];
        },
        getLogLevel: function (row) {
            var key = this.index + '_log_level';
            return _.has(row, key) ? row[key] : $.mage.__('ERROR');
        },
        preview: function (row) {
            var levelHtml = $('<div/>').addClass('logger-tracker-level').html(this.getLogLevel(row));
            var textHtml = $('<div/>').addClass('logger-tracker-full-text').html($('<pre/>').text(this.getFullText(row)));
            var modalHtml = $('<div/>').addClass('logger-tracker-popup-text').append(levelHtml).append(textHtml);
            modalHtml.modal({
                title: '',
                innerScroll: true,
                modalClass: '_image-box',
                buttons: [{
                    text: $.mage.__('Ok'),
                    click: function () {
                        this.closeModal();
                    }
                }]
            }).trigger('openModal');
        },
        getFieldHandler: function (row) {
            return this.preview.bind(this, row);
        }
    });
});