"use strict"

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

// Allows $(selector).c2Menu
// functionality
(function ( $ ) {
    $.fn.c2input = function(config) {
        var options = $.extend({
            // Default options set here
            elm: this,
            adapterType: 'jqx',
            inputType: 'input'
        }, config);

        return new c2.C2Input(options);
    };
}(jQuery));

c2.C2Input = class C2Input {

    constructor(options) {
        this.adapter = c2.InputAdapter.generate(options);
        return this;
    }

}

c2.InputAdapter = class C2InputAdapter extends c2.C2Adapter {

    set change(fn) {
        this._change = fn;
    }

    get change() {
        return this._change;
    }

    get acceptedTypes() {
        return [
            'input',
			'datetime',
			'format',
			'mask',
			'number',
            'password',
            'textarea'
        ];
    }

    get inputType() {
        return this._inputType;
    }

    set inputType(inputType) {
        if (this.acceptedTypes.includes(inputType)) {
            this._inputType = inputType;
        } else {
            var e = new Error(`Invalid input type specified '${inputType}'`);
        }
    }

    constructor(options) {
        super(options);
        this.inputType = options.inputType;
    }

    static generate(options) {
        var classname = options.adapterType +  "InputAdapter";
        return new c2[classname](options);
    }

}

c2.jqxInputAdapter = class jqxInputAdapter extends c2.InputAdapter {

    set keypress(keypressFn) {
        this._keypress = keypressFn;
    }
    get keypress() {
        return this._keypress;
    }

    constructor(options) {
        super(options);

        // Strip out non-jqx properties
        this.keypress = options.hasOwnProperty("keypress") ? options.keypress : undefined;
        this.change   = options.hasOwnProperty('change') ? options.change : undefined;

        var toDelete = ['elm', 'adapterType', 'inputType', 'change', 'keypress'];
        options = this.filterOptions(options, toDelete);

        var mnameFunction = c2.jqxInputAdapter.prototype.getMethodName;
        var methodName = mnameFunction.call(this, this.inputType);
        this.componentFn = methodName;
        this.elm[methodName](options);
        if (typeof this.keypress !== "undefined") {
            this.elm.keydown(this.keypress);
        }
        if (typeof this.change !== "undefined") {
            this.elm.on('change', this.change);
        }
    }

    getMethodName(type) {
        switch (type) {
            default:
            case 'input':
                return 'jqxInput';
            break;
            case 'datetime':
                return 'jqxDateTimeInput';
            break;
            case 'format':
                return 'jqxFormattedInput';
            break;
            case 'mask':
                return 'jqxMaskedInput';
            break;
            case 'number':
                return 'jqxNumberInput';
            case 'password':
                return 'jqxPasswordInput';
            break;
            case 'textarea':
                return 'jqxTextArea';
            break;
        }
    }

}






