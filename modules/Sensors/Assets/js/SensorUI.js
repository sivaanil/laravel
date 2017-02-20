"use strict";
        class SensorUI {

        set ccGrid(grid) {
        this._ccGrid = grid;
        }

        get ccGrid() {
        return this._ccGrid;
        }

        set relayGrid(grid) {
        this._relayGrid = grid;
        }

        get relayGrid() {
        return this._relayGrid;
        }

        /**
         * Initialize the Sensor UI panel
         */
        init() {
        // Instantiate the grid
        var config = {
        elmId: 'contact-closure-grid',
                virtualmode: false,
                type:      'jqx',
                filterable: true,
                sortable: true,
                width: '100%',
                height: '100%'
        };
                var datafields = [
                {name: 'id'},
                {name: 'name'},
                {name: 'path'},
                {name: 'current_state'},
                {name: 'normal_state'},
                {name: 'severity'}
                ];
                var g = new c2.C2Grid(config);
                var columns = [
                {text: 'Name', datafield: 'name', width: '200px'},
                {text: 'Path', datafield: 'path', width: '200px'},
                {text: 'Current State', datafield: 'current_state', width: '125px'},
                {text: 'Normal State', datafield: 'normal_state', width: '155px'},
                {text: 'Severity', datafield: 'severity', width: '100px'},
                {text: '', datafield: 'id', cellsrenderer: window.sensorUI.renderCCImageCell, width: '70px', filterable: false, sortable: false}
                ];
                // Bind to the data source for contact closures
                var id = window.nodeId;
                g.bindDataSource("/api/Sensors/contactclosure?parent_node=" + id, "json", columns, "id",
                        datafields);
                // Set the grid in the SNMP Forward UI controlelr
                this.ccGrid = g;
                // Contact closure jqxWindow setup
                var offset = $('#contact-closure-grid').offset();
                $('#contact-closure-dialog').jqxWindow({
        position: { x: offset.left + 50, y: offset.top + 50},
                showCollapseButton: true,
                width: 500,
                height: 250,
                initContent: function () {

                $('.ccdialog').jqxWindow('hide');
                        // button events
                        $('#contact-closure-dialog-footer .button').click(function() {
                var action = $(this).attr('data-action');
                        if (action) {
                window.sensorUI[action]();
                }
                });
                }
        });
                //subscribing to the sensorEvent
                // this part of the code will be moved to the state handler
                var url = window.location.origin; 
                console.log(url);
                var socket = io(url);
                socket.on('sensor-channel:Modules\\Sensors\\Events\\SensorEvent', function(message){
                // log this
                console.log("update from the beoadcaster!");
                        window.sensorUI.ccGrid.reload();
                });
        }

        hideAllWindows() {
        $('#contact-closure-dialog').jqxWindow('hide');
        }

        /**
         * edit a sensor from the grid
         * @param id int ID of the sensor to edit
         */
        editCC(id) {
        // Pop up window to handle editin the sensor by type
        // TODO - implement detail call
        var url = "sensors/CC/detail/" + id;
                $.ajax({
                url: url,
                        dataType: 'json',
                        success: function(data) {
                        // Populate the dialog window with SNMP Destination data
                        $('#contact-closure-dialog-title').html('<h4>Editing ' + data.name + '</h4>');
                                window.sensorUI.dialog(data, 'CC');
                        }
                });
        }


        /**
         * Save updated contact closure
         */
        saveCC() {
        var data = {
        _token              : $('#contact-closure-dialog-content #_token').val(),
                id                  : $('#contact-closure-dialog-content #contact-closure-id').val(),
                name                : $('#contact-closure-dialog-content #contact-closure-name').val(),
                normalState         : $('#contact-closure-dialog-content #contact-closure-normal-state').val(),
                alarmSeverity       : $('#contact-closure-dialog-content #contact-closure-alarm-severity').val(),
                normalStateAlias    : $('#contact-closure-dialog-content #contact-closure-normal-alias').val(),
                alarmStateAlias     : $('#contact-closure-dialog-content #contact-closure-alarm-alias').val()
        };
                $.ajax({
                url: '/api/Sensors/contactclosure/' + data.id,
                        method: 'PUT',
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                        window.sensorUI.ccGrid.reload();
                                $('#contact-closure-dialog').jqxWindow('hide');
                        }
                });
        }

        dialog(data, type) {

        var selector = '';
                switch (type) {
        case 'CC':
                selector = '#contact-closure-dialog';
                window.sensorUI.initCCFields(data);
                break;
        }

        $(selector).jqxWindow('show');
        }

        initCCFields(data) {
        if (typeof data !== "undefined") {
        // Fill out the fields from the dataset
        $('#contact-closure-id').val(data.id);
                $('#contact-closure-name').val(data.name);
                $('#contact-closure-normal-state').val(data.properties['DI Register Normal State']);
                $('#contact-closure-normal-alias').val(data.properties['DI Register Normal Alias']);
                $('#contact-closure-alarm-alias').val(data.properties['DI Register Alarm Alias']);
                $('#contact-closure-alarm-severity').val(data.properties['DI Register Severity']);
        }

        }

        cancelCC() {
        $('#contact-closure-dialog').jqxWindow('hide');
        }

        renderCCImageCell(row, datafield, value) {
        console.log(value);
                if (!value) {
        return "";
        }
        var output = '<div style="text-align:center;width:100%;">';
                // edit image
                window.dialogType = 'CC';
                output += '<img class="cellimage" src="img/icons/pencil.png" onclick="window.sensorUI.editCC(' + value + ');" />';
                output += '</div>';
                return output;
        }


        }
