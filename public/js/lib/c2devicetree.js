"use strict"

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

// Allows $(selector).c2Menu
// functionality
(function ( $ ) {
    $.fn.c2devicetree = function(config) {
        var options = $.extend({
            // Default options set here
            elm: this,
            adapterType: 'jqx'
        }, config);
        return new c2.DeviceTree(options);
    };
}(jQuery));



c2.DeviceTree = class C2DeviceTree extends c2.C2Tree {

    constructor(options) {
        super(options);
        this.endpoints = {
            firstLevel: '/networkTree/loadFirstLevel',
            nextLevel:  '/networkTree/loadNextLevel',
            allLevel:   '/networkTree/loadAllLevel'
        };
         /*
         * Filter types supported by the DeviceTree
         */
        this.validFilterTypes = [
            'DeviceTypeFilter',
            'DeviceNameFilter'
        ];

        // Set the initial data source
        options.source = this.getInitData();
        this.adapter.render(options);
        var that=this;
        // Set up the expand routine for this object
        this.adapter.on('expand', function(event) {
                that.expand(event);
            });
    }

    /*
     * URL's for loading devices into the tree
     */
        /*
     * Loads initial dataset for device selection tree
     */
    getInitData() {
        var that = this;
        var output = null;
        $.ajax({
            url: this.endpoints.firstLevel,
            method: "GET",
            dataType: "json",
            async: false,
            success: function(data) {
                // Reset the tree data source here
                output = data.nodes;
            }
        });
        return output;
    }

    /**
     * Expand the node into subnodes, respecting all filters
     */
    expand(event) {

        if (this.adapter.getItem(event.args.element) == null) {
            return;
        }

        var that = this;
        var $element = $(event.args.element);
        var loader = false;
        var loaderItem = null;
        var children = $element.find('li');
        $.each(children, function() {
            var item = that.adapter.getItem(this);
            if (item.label == 'Loading...') {
                loaderItem = item;
                loader = true;
                return false;
            }
        });
        if (loader) {
            var nodeId = this.adapter.getItem(event.args.element).value;
            // Load the next level underneath the expanded element
            $.ajax({
                url: this.endpoints.nextLevel,
                method: 'GET',
                dataType: 'json',
                data: {
                    nodeId: nodeId
                },
                success: function(data) {
                    console.log(data.nodes);
                    that.adapter.removeItem(loaderItem.element);
                    that.adapter.addNodes($element[0], data.nodes);
                }
            });
        }
    }


    /*
     * Adds a filter to the device tree
     *
     * @param filterType String One of the valid filter types defined in this class
     * @param value Mixed value for the specified filter.
     */
    addFilter(filterType, value) {
        if (typeof value == 'undefined') {
            return false;
        }

        if (this._filters.hasOwnProperty(filterType)) {
            this._filters[filterType].push(value);
        } else {
            this._filters[filterType] = [ value ];
        }
        return true;
    }
    clearFilters() {
        this._filters = {};
    }

    /**
     * Apply all filters to currently visible tree items.
     */
    applyFilters() {

    }

    /*
     * function to respond to the expansion of an element containing children (Loading the children)
     */
    loadChildren(nodeId) {
        /** Load the children of {{nodeId}} into the tree */

    }

}
