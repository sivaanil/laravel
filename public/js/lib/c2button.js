"use strict"

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

// Allows $(selector).c2Menu
// functionality
(function ( $ ) {
    $.fn.c2button = function(config) {
        var options = $.extend({
            // Default options set here
            elm: this,
            adapterType: 'jqx',
            buttonType: 'button'
        }, config);

        return new c2.C2Button(options);
    };
}(jQuery));

c2.C2Button = class C2Button {

    get adapter() {
        return this._adapter;
    }

    set adapter(adapter) {
        this._adapter = adapter;
    }

    constructor(options) {
        // based on the type, create the right button adapter
        // (button, link, toggle, switch, group)
        this.adapter = c2.ButtonAdapter.generate(options);
        return this;
    }

    prop(name, value = undefined) {
        if (typeof(value) === "undefined") {
            return this.adapter.getProp(name);
        } else {
            return this.adapter.setProp(name, value);
        }
    }

}

c2.ButtonAdapter = class C2ButtonAdapter extends c2.C2Adapter {

    set callback(fn) {
        this._callback = fn;
    }

    get callback() {
        return this._callback;
    }

    get buttonType() {
        return this._buttonType;
    }
    set buttonType(type) {
        this._buttonType = type;
    }

    constructor(options) {
        super(options);
        this._buttonType      = options.buttonType;
        this._callback  = options.callback;
    }


    static generate(options) {
       var classname   = options.adapterType + "ButtonAdapter";
        return new c2[classname](options);
    }
}

c2.jqxButtonAdapter = class JQXButtonAdapter extends c2.ButtonAdapter {

    constructor(options) {
        super(options);

        // Remove incompatible options before creating the button
        delete options['buttonType'];
        delete options['adapterType'];
        delete options['elm'];
        delete options['callback'];

        // Create the button from options
        let mnameFunction = c2.jqxButtonAdapter.prototype.getMethodName;
        var methodName = mnameFunction.call(this, this.buttonType);
        this.elm[methodName](options);

        if (typeof(this.callback) !== "undefined") {
            var eventName = 'click';
            switch(this.type) {
                case 'switch':
                    eventName = 'change';
             break;
                case 'group':
                    eventName = 'buttonclick';
                break;
            }
            this.elm.on(eventName, this.callback);
        }
    }



    getMethodName(type) {
        if (typeof this.componentFn !== "undefined") {
            return this.componentFn;
        }
        var output = "";
        switch (type) {
            case 'button':
                output = 'jqxButton';
            break;
            case 'link':
                output = 'jqxLinkButton';
            break;
            case 'toggle':
                output = 'jqxToggleButton';
            break;
            case 'switch':
                output = 'jqxSwitchButton';
            break;
            case 'group':
                output = 'jqxButtonGroup';
            break;
            default:
                output = 'jqxButton';
            break;
        }
        this.componentFn = output;
        return output;
    }

}

