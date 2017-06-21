/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'text!Konstanchuk_LoggerTracker/template/log-info.html'
], function ($, modalPopup, htmlPopup) {
    'use strict';

    return function (config, element) {
        $(element).on('click', function (e) {
            e.preventDefault();
            var modalHtml = $('<div/>').html(htmlPopup);
            modalHtml.modal({
                title: $.mage.__('Logger Tracker Info'),
                innerScroll: true,
                modalClass: '_image-box',
                buttons: [{
                    text: $.mage.__('Ok'),
                    click: function () {
                        this.closeModal();
                    }
                }]
            }).trigger('openModal');
        });
    };
});
