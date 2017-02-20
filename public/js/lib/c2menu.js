"use strict"

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

// Allows $(selector).c2Menu
// functionality
(function ( $ ) {
    $.fn.c2menu = function(config) {
        var options = $.extend({
            // Default options set here
            elm: this,
            type: 'jqx'
        }, config);

        return new c2.C2Menu(options);
    };
}(jQuery));

c2.C2Menu = class C2Menu {


    set adapter(obj) {
        this._adapter = obj;
    }

    get adapter() {
        return this._adapter;
    }

    /**
     * Cpnstructor sets options and initializes the menu
     */
    constructor(options) {
        this.adapter = c2.MenuAdapter.generate(options.type, options);
    }

};


/**
 * Generic MenuAdapyrt parent class
 */
c2.MenuAdapter = class MenuAdapter {

    /**
     * Generates an adapter for the menu based on options.type
     */
    static generate(type, options) {
        var output = null;
        try {
            var classname = type + "MenuAdapter";
            if (typeof(c2[classname]) != 'undefined') {
                output = new c2[classname](options);
            }
        } catch (e) {
            console.log(e.name + ":" + e.message);
        } finally {
            // Return output either way, it'll be null if
            // we couldn't find the class
            return output;
        }

    }
};

c2.jqxMenuAdapter = class jqxMenuAdapter {

    constructor(options) {
        // set up the data source for the menu
        var source = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'parentid'},
                {name: 'text'},
                {name: 'subMenuWidth'}
            ],
            id: 'id',
            localdata: options.items
        };
        var dataAdapter = new $.jqx.dataAdapter(source);
        dataAdapter.dataBind();
        var records = dataAdapter.getRecordsHierarchy(
            'id', 'parentid', 'items', [{name: 'text', map: 'label'}]
        );
        // render the menu
        options.elm.jqxMenu({source: records, height: options.height, width: options.width});
        // attach the item click callback
        options.elm.jqxMenu().on('itemclick', options.callback);
    }

};

