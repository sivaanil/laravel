"use strict"

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

// Allows $(selector).c2Menu
// functionality
(function ( $ ) {
    $.fn.c2radio = function(config) {
        var options = $.extend({
            // Default options set here
            elm: this,
            adapterType: 'jqx',
            inputType: 'radio'
        }, config);

        return new c2.C2Radio(options);
    };
}(jQuery));

c2.C2Radio = class C2Radio {

    constructor(options) {
        this.adapter = c2.C2RadioAdapter.generate(options);
        return this;
    }

};

c2.C2RadioAdapter = class C2RadioAdapter extends c2.C2Adapter {

    get acceptedTypes() {
        return [
            'checkbox',
            'radio'
        ];
    }

    set inputType(type) {
        if (this.acceptedTypes.includes(type)) {
            this._inputType = type;
        } else {
           var e = new Error(`Invalid input type specified (${type}). C2Radio accepts ('radio', 'checkbox').`);
        }
    }

    get inputType() {
        return this._inputType;
    }

    constructor(options) {
        super(options);
        this.inputType = options.inputType;
    }

    static generate(options) {
        var classname = options.adapterType +  "RadioAdapter";
        return new c2[classname](options);
    }


};

c2.jqxRadioAdapter = class jqxRadioAdapter extends c2.C2RadioAdapter {

    get typeMethods() {
        return {
            radio: 'jqxRadioButton',
            checkbox: 'jqxCheckBox'
        };
    }

    constructor(options) {
        super(options);

        var toDelete = ["inputType", "elm", "change", "checked", "unchecked", "indeterminate", "adapterType"];
        options = this.filterOptions(options, toDelete);

        var mnameFunction = c2.jqxRadioAdapter.prototype.getMethodName;
        var methodName = mnameFunction.call(this, this.customOptions.inputType);
        this.componentFn = methodName;
        this.customOptions['elm'][methodName](options);
        this.initEvents();

    }

    initEvents() {
        var events = [];
        if (this.customOptions.inputType == "radio") {
            events = ["change", "checked", "unchecked"];
        } else if (this.customOptions.inputType == "checkbox") {
            events = ["checked", "change", "indeterminate", "unchecked"];
        }
        for (var i = 0; i < events.length; ++i) {
            var evt = events[i];
            if (typeof this.customOptions[evt] !== "undefined") {
                this.customOptions.elm.on(evt, this.customOptions[evt]);
            }
        }

    }

    getMethodName(type) {
        if (this.acceptedTypes.includes(type)) {
            return this.typeMethods[type];
        } else {
            return undefined;
        }
    }

};


