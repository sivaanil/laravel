"use strict";

// Script for handling UI manipulation on SNMP Northbound trap destinations

function snmpforward_init() {


    window.snmp = new SNMP();
    window.snmp.init();

    // Instantiate the grid
    var config = {
        elmId: 'snmp-forward-grid',
        addButton: {
            icon: '/img/icons/add.png',
            text: 'Add SNMP Destination',
            action: window.snmp.add
        },
        virtualmode: true,
        type:      'jqx',
        filterable: true,
        sortable: true,
        width: '100%',
        height: '100%',
        ready: function() {
            $('#snmp-forward-grid').jqxGrid('autoresizecolumns');
        }
    };

    var datafields = [
        {name: 'id'},
        {name: 'name'},
        {name: 'ip_address'},
        {name: 'snmp_version_id'},
        {name: 'format'},
    ];
    var g = new c2.C2Grid(config);

    var columns = [
        {text: 'Name', datafield: 'name', width: '100px'},
        {text: 'IP Address', datafield: 'ip_address', width: '100px'},
        {text: 'Version', datafield: 'snmp_version_id', width: '75px'},
        {text: 'Format', datafield: 'format', width: '200px'},
        {text: '', datafield: 'id', cellsrenderer: window.snmp.renderImageCell, width: '70px', filterable: false, sortable: false}
    ];

    g.bindDataSource("/api/Snmpforward/", "json", columns, "id",
        datafields);

    // Set the grid in the SNMP Forward UI controlelr
    window.snmp.grid = g;
    // Event listeners
    window.snmp.initEvents(g);

};


