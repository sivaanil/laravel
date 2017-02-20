"use strict";

// Namespacing into c2.*
if (typeof(c2) == 'undefined') {
    var c2 = {};
}

c2.C2Grid = class C2Grid {

    /**
     * Constructor for the C2Grid abstraction layer.
     *
     * @param Object config Configuration object
     * @throws error
     */
    constructor(config) {
        this.init();
        var ok = this.checkConfig(config);
        this._config = config;
        if (ok) {
            this.createGrid();
        } else {
            throw new Error('Missing Configuration');
        }
    }

    hideColumn(columnId) {
        this.adapter.hideColumn(columnId);
    }

    imageRender(row, datafield, value) {
        return this.adapter.imageRender(row, datafield, value);
    }

    /**
     * Set the height of the grid
     * @param String height (int + "px", or similar)
     */
    setHeight(height) {
        this.adapter.setHeight(height);
    }

    reload() {
        this.adapter.reload();
    }

    /**
     * Initialize instance variables, pass-through
     * functionality.
     */
    init() {
        this._requiredFields = [
            'elmId',
            'type',
        ];
        this.addButton              = false;
        this.firstColumnCheckboxes  = false;
    }

    /**
     * Initialize the UI components for the grid. This includes not only the grid itself, but
     * C2-specific interface components.
     */
    initUIComponents() {
        this.c2Setup();
    }

    c2Setup() {
        // Use the display element for the grid wrapper
        this.dispElm = $('#' + this.elmId);

        var gridConfigObj   = this.config;
        gridConfigObj.elmId = this.elmId + "_c2grid";


        console.log('Setting up UI wrapper');
        if (typeof(gridConfigObj.addButton) !== 'undefined') {
            var addButton = gridConfigObj.addButton;

            this.dispElm.append($('<div />')
                .click(addButton.action)
                .addClass("toolbarButton")
                .append($('<img />')
                    .attr('src', addButton.icon))
                .append($('<span />').html(addButton.text))
            );

        }


        // Create an element to display the grid
        var gridElm = this.dispElm.append($("<div />").prop('id', this.elmId + "_c2grid"));

        console.log('Setting adapter');
        this.adapter = c2.GridAdapter.generate(this.type, gridConfigObj);
        this.initToolbar();
    }

    /**
     * Figures out the toolbar buttons from a predefined set.
     * NOTE: Toolbar buttons shold have a caption in the toolbar.{buttonname}.caption
     * property. Determining the specification for what a toolbar item looks like is TODO
     */
    initToolbar() {
        if (typeof this.toolbar !== 'undefined') {
            for (var btn in toolbar) {
                this.adapter.addToolbarItem(btn);
            }
        }
    }

    get config() {
        return this._config;
    }

    set adapter(adapter) {
        this._adapter = adapter;
    }

    get adapter() {
        return this._adapter;
    }

    set displayElement(elmId) {
        this._elmId = elmId;
    }

    get displayElement() {
        return this._elmId;
    }

    bindEvent(eventname, callable) {
        this.adapter.bind(eventname, callable);
    }

    // Set a property, event, callback, etc for a grid
    set(name, value) {
        try {
            this.adapter.set(name, value);
        } catch (e) {
            console.log(e.name + ":" + e.message);
        }
    }

    /**
     * Get a property from the grid adapter
     * @param String name Name of the property to access
     * @return mixed
     */
    get(name) {
        return this.adapter.get(name);
    }

    // Determines if the required properties are set on the
    // config object.
    checkConfig(config) {
        for (var key in this.requiredFields) {
            if (typeof(config[key]) == 'undefined') {
                return false;
            }
        }

        // Copy config variables into the object
        for (var c in config) {
            this[c] = config[c];
        }
        return true;
    }

    createGrid() {
        this.initUIComponents();
        this.adapter.create();
    }

    /*************************************************
     *                                               *
     * SPECIFIC SUPPORTED FUNCTIONALITY - USE THESE! *
     *                                               *
     * Using the following API functions assures     *
     * compatibility with the grid adapter according *
     * to a standard interface.                      *
     * If you use the methods above, you will have   *
     * more granular functionality in your specific  *
     * grid type, but you WILL have to change your   *
     * code when grid components/libraries are       *
     * changed out/replaced.                         *
     *                                               *
     *************************************************/

    /**
     * Sets a data local source given columns and raw data
     * @param Array<Object> columns Column headers
     * @param Array<Mixed> data Grid data to display
     * @param String dataType One of ('xml', 'json', 'jsonp', 'tsv', 'csv', 'local', 'array', 'observablearray')
     *
     * @return void
     */
    setDataSource(columns, data, dataType) {

    }

    /**
     * Bind a data source to the grid by URL
     *
     * @param String url URL from which to request data
     * @param String dataType One of ('xml', 'json', 'jsonp', 'tsv', 'csv', 'array', 'observablearray')
     * @param Array<Object> columns Column definitions
     * @param String idField Field name of the record ID
     * @param Object dataFields Field names for data source.
     * @param [String type=GET] Request type (GET or POST)
     *
     * @return void
     */
    bindDataSource(url, dataType, columns, idField, dataFields, type) {
        if (typeof type === 'undefined') {
            type = 'GET';
        }
        this.adapter.bindDataSource(url, dataType, columns, idField, dataFields, type);
    }

    autoResizeColumns() {
        this.adapter.autoresizeColumns();
    }

    autoResizeColumn(columnName) {
        this.adapter.autoResizeColumn(columnName);
    }

    /**
     * Adds a filter to the grid
     *
     * @param String type Type of filter to add ('string', 'numeric', 'date')
     * @param String fieldId The ID of the data field to add the filter.
     * @param String condition Condition for filtering
     *  Filter conditions:
     *  For string:
     *      empty, not_empty, contains, contains_case_sensitive,
     *      does not contain, does_not_contain_case_sensitive,
     *      starts_with, starts_with_case_sensitive, ends_with,
     *      ends_with_case_sensitive, equal, equal_case_sensitive, null, not null
     *  For date:
     *      equal, not equal, less_than, less_than_or_equal, greater_than, greater_than_or_equal, null, not null
     *  For numeric:
     *      equal, not equal, less_than, less_than_or_equal, greater_than, greater_than_or_equal, null, not null
     * @param mixed value Starting value for the filter
     */
    addFilter(type, fieldId, condition, value) {
        // TODO: Sanity check the parameters
        console.log("Adding filter from the main adapter");

        this.adapter.addFilter(type, fieldId, condition, value);
    }

    addRow(rowObj) {

    }

    deleteSelectedRow() {
        this.adapter.deleteSelectedRow();
    }

}

/**
 * Encapsulates the properties of a row in the
 * grid
 */
c2.Row = class Row {

    constructor(properties) {
        this.properties = properties;
    }

    get properties() {
        return this._properties;
    }

    set properties(props) {
        this._properties = props;
    }

    prop(name, value) {

        if (typeof(value) == 'undefined') {
            return this.properties[name];
        } else {
            this.properties[name] = value;
        }
    }

    get columns() {
        return this._columns;
    }
}



c2.GridAdapter = class GridAdapter {

    constructor(config) {
        this.elmId  = config.elmId;
        this.config = config;
        this.copyConfig(config);

        // Translate properties from the c2Grid class into properties
        // for the specific grid type (This depends on each adapter to implement)
        this.config.grid = this.setupGridProperties();
    }

    setHeight(h) {
        console.log("This action is not yet implemented in this adapter");
    }

    reload() {
        console.log('This function is not yet implemented in this adapter');
    }

    hideColumn(columnId) {
        console.log('This function is not yet implemented in this adapter');
    }

    imageRender(row, datafield, value) {
        console.log("This function is not yet implemented in the adapter");
    }

    copyConfig(config) {
        for (var key in config) {
            this[key] = config[key];
        }
    }

    static generate(type, config) {
        var output = null;
        try {
            var classname = type + "GridAdapter";
            if (typeof(c2[classname]) != 'undefined') {
                output = new c2[classname](config);
            }
        } catch (e) {
            console.log(e.name + ":" + e.message);
        } finally {
            // Return output either way, it'll be null if
            // we couldn't find the class
            return output;
        }
    }

    set elmId(elmId) {
        this._elmId = elmId;
    }

    get elmId() {
        return this._elmId;
    }

    set grid(gridObject) {
        this._grid = gridObject;
    }

    get grid() {
        return this._grid;
    }

    set config(config) {
        this._config = config;
    }

    get config() {
        return this._config;
    }

    create() {
        console.log("This function is not yet implemented in the adapter");
    }

    /**
     * Generic set method, to set any property on the adapter.
     * How this is handled mostly depends on teh adapter itself,
     * which will react to the property changed event.
     */
    set(name, value) {
        this[name] = value;
        this.propertyChanged(name);
    }

    get(name) {
        if (typeof(this[name]) !== 'undefined') {
            return this[name];
        } else {
            // Return null if the property does not exist.
            console.log("Property " + name + " does not exist on this adapter");
            return null;
        }
    }

    call(methodName, args) {
        console.log("This funciton is currently not supported in this adapter");
    }

    bindDataSource(url, dataType, columns, idField, dataFields, type) {
        console.log("This function is currently not supported in this adapter");
    }

    addFilter(type, fieldId, condition, value) {
        console.log("This function is currently not supported in this adapte");
    }

    /**
     * binds an event to the grid component
     * @param String eventname The name of the event to bind
     */
    bind(eventname, callable) {
        console.log("Bind event is not currently supported in this adapter");
    }

    /**
     * Event handler for a property that has changed on the adapter.
     *
     * @param String name  The name of the property that changed.
     * @return void
     */
    propertyChanged(name) {
        // Empty for the superclass, but super.propertyChanged(name) should
        // always be called from implementing methods
    }

    whoami() {
        return "GridAdapter";
    }
}

c2.jqxGridAdapter = class jqxGridAdapter extends c2.GridAdapter {

    constructor(config) {
        super(config);

    }

    setHeight(h) {
        if ($('#' + this.elmId).length > 0) {
            $('#' + this.elmId).jqxGrid({height: h});
        }
    }

    setupGridProperties() {
        var output = {};

        if (typeof(this.config.columns) !== 'undefined') {
            output.columns = this.config.columns;
        }

        if (typeof(this.config.datafields) !== 'undefined') {
            output.datafields = this.config.datafields;
        }

        if (typeof(this.config.sortable) !== 'undefined') {
            output.sortable = this.config.sortable;
            output.sort = function() {
                $("#" + this.elmId).jqxGrid('updatebounddata', 'sort');
            };
        }

        if (typeof(this.config.autoshowfiltericon) !== 'undefined') {
            output.autoshowfiltericon = this.config.autoshowfiltericon;
        }

        if (typeof(this.config.virtualmode) !== 'undefined') {
            output.virtualmode = this.config.virtualmode;
            output.rendergridrows = function(obj) {
                return obj.data;
            };
        }

        if (typeof(this.config.filterable) !== "undefined") {
            output.filterable = this.config.filterable;
        }


        if (typeof(this.config.width) !== 'undefined') {
            output.width = this.config.width;
        }

        if (typeof(this.config.theme) !== 'undefined') {
            output.theme = this.config.theme;
        }

        return output;
    }

    reload() {
        console.log('Attempting to update bound data');
        $('#' + this.elmId).jqxGrid('updatebounddata');
    }

    create() {
        $('#' + this.elmId).jqxGrid(this.config.grid);
    }

    get(name) {
        return $('#' + this.elmId).jqxGrid(name);
    }

    hideColumn(columnId) {
        $('#' + this.elmId).jqxGrid('hidecolumn', columnId);
    }

    /**
     * Renders an image in a cell
     */
    imageRender(row, datafield, value) {
        console.log('Calling ' + this.whoami() + "::imageRender");
        return '<img src="' + value + '" />';
    }

    /**
     * Calls a method on the jqxGrid
     */
    call(methodName, args) {
        if (typeof(args) == 'undefined') {
            $('#' + this.elmId).jqxGrid(methodName);
        } else {
            // Spreading the function arguemnts to pass to grid
            if (!Array.isArray(args)) {
                args = [args];
            }
            // Note the spread operator here. (...)
            // @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_operator
            $('#' + this.elmId).jqxGrid(methodName, ...args);
        }
    }

    addFilter(type, fieldId, condition, value) {
        console.log('adding Filter');
        var filterGroup = new $.jqx.filter();
        console.log("type: " + type + " condition: " + condition);
        var filter      = filterGroup.createfilter(type, "", condition);

        filterGroup.addfilter(0, filter);

        $('#' + this.elmId).jqxGrid('addfilter', fieldId, filterGroup);
    }

    /**
     * Binds an event to the grid from the adapter
     */
    bind(eventname, callable) {
        $('#' + this.elmId).on(eventname, callable);
    }

    bindDataSource(url, dataType, columns, idField, dataFields, type) {
        var that = this;
        var source = {
            datatype: dataType,
            datafields: dataFields,
            id: idField,
            url: url,
            async: false,
            sort: function() {
                // Bind sorting function
                $('#' + that.elmId).jqxGrid('updatebounddata', 'sort');
            },
            filter: function() {
                $('#' + that.elmId).jqxGrid('updatebounddata', 'filter');
            }
        };

        var dataAdapter = new $.jqx.dataAdapter(source,
            {
                downloadComplete: function(data, status, xhr) {},
                loadComplete: function(data) {},
                loadError: function(xhr, status, error) {}
            });
        $('#' + this.elmId).jqxGrid(
            {
                source: dataAdapter,
                columns: columns
            }
        );


    }

    /**
     * Handle property changes, passing them through to the grid
     */
    propertyChanged(name) {
        super.propertyChanged(name);
        // Set the property on the jqxGrid object
        var propObj = {};
        propObj[name] = this[name];
        $('#' + this.elmId).jqxGrid(propObj);
    }

    whoami() {
        return "jqxAdapter";
    }

}
