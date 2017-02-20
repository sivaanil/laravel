"use strict"

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

// Allows $(selector).c2Menu
// functionality
(function ( $ ) {
    $.fn.c2tree = function(config) {
        var options = $.extend({
            // Default options set here
            elm: this,
            adapterType: 'jqx'
        }, config);

        return new c2.C2Tree(options);
    };
}(jQuery));

c2.C2Tree = class C2Tree {

    get proxyFunctions() {
        // define all of the function names that need to be callable here
        return [
            'addBefore', 'addAfter', 'addTo',
            'clear', 'checkAll', 'checkItem', 'collapseAll',
            'collapseItem',
            'destroy', 'disableItem',
            'ensureVisible', 'enableItem', 'expandAll', 'expandItem',
            'focus',
            'getCheckedItems', 'getItems', 'getSelectedItem', 'getPrevItem',
            'getNextItem',
            'hitTest',
            'removeItem', 'render', 'refresh',
            'selectItem',
            'uncheckAll', 'uncheckItem', 'updateitem',
            'val'
        ];
    }

    get proxyEvents() {
        return [
            'added',
            'checkChange',
            'collapse',
            'dragStart',
            'dragEnd',
            'expand',
            'initialized',
            'itemClick',
            'removed',
            'select'
        ];
    }

    get selectedItems() {
        return this.adapter.selectedItems;
    }
    set selectedItems(selectedItems) {
        this.adapter.setSelected(selectedItems);
    }
    get checkedItems() {
        return this.adapter.checkedItems;
    }
    set checkedItems(checkedItems) {
        this.adapter.setChecked(checkedItems);
    }

    get adapter() {
        return this._adapter;
    }

    set adapter(adapter) {
        this._adapter = adapter;
    }

    constructor(options) {
        this.adapter = c2.TreeAdapter.generate(options);
        return this;
    }

    initProxyFunctions() {
        for (var i = 0; i < this.proxyEvents.length; ++i) {
            var ename = this.proxyEvents[i];
            c2.c2Tree[this.proxyEvents[i]] = function() {
                return this.adapter.interceptProxyFunction(...arguments);
            };
        }

        for (var i = 0; i < this.proxyFunctions.length; ++i) {
            // Add the passthru function to our prototype
            var mname = this.proxyFunctions[i];
            c2.C2Tree[this.proxyFunctions[i]] = function() {
                return this.adapter.interceptProxyFunction(...arguments);
            };
        }
    }

    /**
     * Filter tree elements based on search criteria:str
     */
    filter(criteria) {
        this.adapter.filter(criteria);
    }

}


c2.TreeAdapter = class TreeAdapter extends c2.C2Adapter {

    static generate(options) {
        var classname = options.adapterType + "TreeAdapter";
        return new c2[classname](options);
    }

    setChecked(checkedItems) {
        console.log("setChecked is not supported in this adapter");
    }
    setSelected(selectedItems) {
        console.log("selSelected is not supported in this adapter");
    }

    get selectedItems() {
        console.log("get:selectedItems is not supported in this adapter");
    }
    get checkedItems() {
        console.log("get:checkedItems is not supported in thie adapter");
    }

    render(options) {
        console.log("The render(options) method is not supported in this adapter");
    }

    interceptProxyFunction(fname, args) {
        console.log("InterceptProxyFunctions is not implemented in this adapter");
    }

    filter(criteria) {
        console.log("The filter(criteria) method is not supported in this adapter");
    }

    filterByDeviceType(deviceType) {
        console.log("The filterbyDeviceType(deviceType) method is not supported in this adapter");
    }

    /*
     * Binds action callback to the tree's exapnd event
     */
    onExpand(action) {
        console.log("The onExpand(action) method is not supported in this adapter");
    }

    removeItem(item) {
        console.log("The removeItem(item) method is not supported in this adapter");
    }

    addNodes(parentElm, children) {
        console.log("The addNodes(parentElm, children) method is not supported in this adapter");
    }

    constructor(options) {
        super(options);
        options = this.filterOptions(options);
    }

}

c2.jqxTreeAdapter = class jqxTreeAdapter extends c2.TreeAdapter {

    constructor(options) {
        super(options);
        this.componentFn = 'jqxTree';
        options = this.filterOptions(options);
        // Our options are filtered, these are for the actual tree
        this.options = options;
        if (options.source) {
            this.render(options);
        }
    }

    removeItem(item) {
        this.elm[this.componentFn]('removeItem', item);
    }

    render(options) {
        // Rendering
        console.log("Rendering with the following options:");
        console.log(options);
        this.elm[this.componentFn](options);
    }

    addNodes(parentElm, children) {
        this.elm[this.componentFn]('addTo', children, parentElm);

    }

    /* Filter the elements in the tree based on search criteria:str
     */
    filter(criteria) {
        var items = this.elm[this.componentFn]("getItems");
        this.elm[this.componentFn]("collapseAll");
        var found = false;
        for (var i = 0; i < items.length; ++i) {
            if (new RegExp(criteria).test(items[i].label)) {
                this.elm[this.componentFn]("expandItem", items[i].parentElement);
                if (found == false) {
                    this.elm[this.componentFn]("selectItem", items[i]);
                    found = true;
                }
            }
        }
    }

    setChecked(checkedItems) {
        var items = this.elm[this.componentFn]("getItems");

        for (var i = 0; i < items.length; ++i) {
            if (checkedItems.indexOf(items[i].value) > -1) {
                console.log("Found " + items[i].value);
                this.elm[this.componentFn]("checkItem", items[i]);
            }
        }
    }

    on(evt, action) {
        this.elm.on(evt, action);
    }

    /**
     * Returns a list of the items currently selected in the tree
     */
    get checkedItems() {
        var items = this.elm[this.componentFn]("getItems");
        var checkedItems = [];
        for (var i = 0; i < items.length; ++i) {
            if (items[i].checked) {
                checkedItems.push(items[i].value);
            }
        }
        return checkedItems;
    }

    /*
     * Filter nodes in the tree by device type
     */
    filterByDeviceType(deviceType) {
        // Get all of the selected items in the current tree, so we can maintain consistency

        // Create the new data source for the device tree
        var checkedItems = this.checkedItems;
        var that = this;
        $.ajax({
            url: '/networkTree/loadFirstLevel',
            method: "GET",
            data: {
                dtf: deviceType
            },
            dataType: "json",
            success: function(data) {
                // Reset the tree data source here
                that.elm[that.componentFn]({source: data.nodes});
                that.setChecked(checkedItems);
            }
        });
    }

    filterOptions(original) {
        var output = original;

        var toDelete = ['elm', 'adapterType', 'inputType', 'change'];

        for (var key in output) {
            if (toDelete.includes(key)) {
                delete output[key];
            }
        }
        return output;
    }

    getItem(element) {
        return this.elm[this.componentFn]('getItem', element);
    }

    interceptProxyFunction(fname, args) {
        if (this.proxyEvents.contains(fname)) {
            return this.elm[this.componentFn].on(fname, args);
        }

        // Interpret function here.
        // Calls the function as a jqxfunction with syntax: elm.jqxTree('itemclick'. args);
        return this.elm[this.componentFn](fname, args);

    }

}
