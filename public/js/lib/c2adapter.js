"use strict";

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

/**
 * Superclass for all c2 UI component adapters
 */
c2.C2Adapter = class C2Adapter {

    set adapterType(adapterType) {
        this._adapterType = adapterType;
    }

    get adapterType() {
        return this._adapterType;
    }

    set elm(elm) {
        this._elm = elm;
    }

    get elm() {
        return this._elm;
    }

    set options(opts) {
        this._options = opts;
    }

    get options() {
        return this._options;
    }

    get customOptions() {
        if (typeof this._customOptions === "undefined") {
            this._customOptions = {};
        }
        return this._customOptions;
    }

    set customOptions(opts) {
        this._customOptions = opts;
    }

    set componentFn(fnName) {
        this._componentFn = fnName;
    }
    get componentFn() {
        return this._componentFn;
    }

    constructor(options) {
        this.options     = options;
        this.adapterType = options.adapterType;
        this.elm         = options.elm;
    }

    setProp(name, value) {
        return this.elm[this.componentFn](name, value);
    }

    getProp(name, value) {
        return this.elm[this.componentFn](name);
    }

    /*
     * Removes options that are incompatible with the adapter's implementation class.
     * (Stores the deleted options in the customOptions{} property)
     */
    filterOptions(original, toDelete) {
        var output = original;

        for (var key in output) {
            if (toDelete.includes(key)) {
                this.customOptions[key] = output[key];
                delete output[key];
            }
        }
        return output;
    }
}

