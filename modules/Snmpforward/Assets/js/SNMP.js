"use strict";

class SNMP {

    set grid(grid) {
        this._grid = grid;
    }

    get grid() {
        return this._grid;
    }

    set elms(elms) {
        this._elms = elms;
    }

    get elms() {
        return (this._elms);
    }

    init() {

        $('.snmpDialog').jqxWindow('destroy');
        var offset = $('#snmp-forward-grid').offset();
        $('#snmpDialog').jqxWindow({
            position: { x: offset.left + 50, y: offset.top + 50},
            showCollapseButton: true,
            width: 500,
            height: 350,
            initContent: function () {
                $('#snmpDialog').jqxWindow('hide');
            }
        });

        this.elms = [
            $('#snmpDialogContent #snmpId'),
            $('#snmpDialogContent #snmpName'),
            $('#snmpDialogContent #snmpIPAddress'),
            $('#snmpDialogContent #snmpFormat'),
            $('#snmpDialogContent #snmpVersion'),
            $('#snmpDialogContent #snmpReadCommunity'),
            $('#snmpDialogContent #snmpWriteCommunity')
        ];

    }

    initEvents() {
        $('#snmpDialogFooter .button').click(function() {
            var action = $(this).attr('data-action');
            if (action) {
                window.snmp[action]();
            }
        });
    }

    /**
     * Add or update SNMPDestination record
     */
    save() {
        // get values from form
        var data = {
            _token: $('#snmpDialogContent #_token').val(),
            id: $('#snmpDialogContent #snmpId').val(),
            name: $('#snmpDialogContent #snmpName').val(),
            ip_address: $('#snmpDialogContent #snmpIPAddress').val(),
            format: $('#snmpDialogContent #snmpFormat').val(),
            snmp_version_id: $('#snmpDialogContent #snmpVersion').val(),
            read_community: $('#snmpDialogContent #snmpReadCommunity').val(),
            write_community: $('#snmpDialogContent #snmpWriteCommunity').val()
        };


        // If there is an id, update. Otherwise, add new
        var method = data.id.length > 0 ? 'PUT' : 'POST';
        var url    = data.id.length > 0 ? '/api/Snmpforward/' + data.id : "/api/Snmpforward";


        $.ajax({
            url: url,
            method: method,
            dataType: 'json',
            data: data,
            success: function (data) {
                window.snmp.grid.reload();
                window.snmp.cancel();
            }
        });
    }

    /**
     * Clear form inputs
     */
    clear() {
        for (var i = 0; i < this.elms.length; ++i) {
            this.elms[i].val('');
        }
    }


    /**
     * Discard form changes, close the dialog
     */
    cancel() {
        $('#snmpDialog').jqxWindow('hide');
    }

    renderImageCell(row, datafield, value) {
        if (!value) {
            return "";
        }
        var output = '<div style="text-align:center;width:100%;">';
        // edit image
        output +=  '<img class="cellimage" src="img/icons/pencil.png" onclick="window.snmp.edit(' + value  + ');" />';
        // delete image
        output += '<img class="cellimage" src="img/icons/delete.png" onclick="window.snmp.delete(' + value + ');" />';
        output += '</div>';
        return output;
    }

    /**
     * Add a new SNMP destination
     *
     * @param c2.C2Grid grid to use for reload
     */
    add() {
        $('#snmpDialogTitle').html('<h4>Adding a new SNMP Destination</h4>');
        window.snmp.dialog();
    }

    /**
     * Delete SNMP destination
     */
    delete(id) {
        // confirm deletion
        var ok = confirm('Are you sure you want to delete this SNMP destination?');
        if (ok) {
            $.ajax({
                url: '/api/Snmpforward/' + id,
                method: 'DELETE',
                dataType: 'json',
                success: function(data) {
                    // reload the grid
                    window.snmp.grid.reload();
                }
            });
        }
    }

    /**
     * Edit an existing SNMP Destination
     *
     * @param int id ID of an SNMP Destination record
     */
    edit(id) {
        // Get information about the SNMP Destination
        var url = "/api/Snmpforward/" + id;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Populate the dialog window with SNMP Destination data
                $('#snmpDialogTitle').html('<h4>Editing ' + data.name + '</h4>');
                window.snmp.dialog(data);
            }
        });
    }

    /**
     * Show SNMP Destination dialog for add/edit
     *
     * @param object data - Data about an SNMP destination for edit
     */
    dialog(data) {

        // Initialize form fields if there is data
        if (typeof(data) !== 'undefined') {
            console.log(data);
            $('#snmpId').val(data.id);
            $('#snmpName').val(data.name);
            $('#snmpFormat').val(data.format);
            $('#snmpIPAddress').val(data.ip_address);
            $('#snmpReadCommunity').val(data.read_community);
            $('#snmpWriteCommunity').val(data.write_community);
            $('#snmpVersion').val(data.snmp_version_id);

        }
        $('#snmpDialog').jqxWindow('show');
    }

}
