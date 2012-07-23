/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


var cmc = new Class({

    initialize: function(form){

        this.placeholder(form);
        this.validate(form);
        this.ajax(form);
    },

    placeholder: function(form) {
        document.id(form).getElements('input').each(function(el, index) {
            if(!el.hasClass('phone')) {
                new OverText(el, {
                    wrap: true
                });
            }
        });
    },

    validate: function(form) {
//        this.validator = new Form.Validator.Inline(form);
    },

    ajax: function(form) {
        var form = document.id(form);
        var self = this;
        form.addEvent('submit', function() {
//            if(self.validator.validate()) {

                new Request.JSON({
                    url: form.get('action'),
                    data: form,
                    method: 'post',
                    onComplete: function(data) {

                    }
                }).send();

                return false;
//            }

        });
    }


});