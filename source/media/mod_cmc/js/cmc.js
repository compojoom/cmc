/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


var cmc = new Class({
    Implements: [Options],
    options: {},
    initialize: function(form, options){
        this.setOptions(options);
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
        this.validator = new Form.Validator.Inline(form);
    },

    ajax: function(form) {
        var form = document.id(form);
        var self = this;
        form.addEvent('submit', function() {
            if(self.validator.validate()) {

                new Request.JSON({
                    url: form.get('action'),
                    data: form,
                    method: 'post',
                    onRequest: function() {
                        new Fx.Morph(form, {
                            duration: 'long',
                            transition: Fx.Transitions.Sine.easeOut
                        }).start({
                            'height': 0, // Morphs the 'height' style from 10px to 100px.
                            'visibility': 'hidden'  // Morphs the 'width' style from 900px to 300px.
                        });

                        document.id(self.options.spinner).setStyle('display', 'block');
                    },
                    onComplete: function(data) {
                        document.id(self.options.spinner).setStyle('display', 'none');
                        if(data.error == true) {
                            form.getParent('div').set('html', data.html);
                        } else {
                            if(data.html == 'updated') {
                                form.getParent('div').set('html', self.options.language.updated);
                            } else {
                                form.getParent('div').set('html', self.options.language.saved);
                            }

                        }


                    }
                }).send();

                return false;
            }

        });
    }
});