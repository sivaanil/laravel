<script src="{!! asset('js/lib/c2grid.js') !!}"></script>
<script src="{!! asset('modules/snmpforward/js/SNMP.js') !!}"></script>
<script src="{!! asset('modules/snmpforward/js/snmpgrid.js') !!}"></script>
<div class="title-bar">
    <h4>SNMP Northbound Trap Destinations</h4>
</div>
<div id="grid-container" style="width:100%;text-align:left;" />
    <div id="snmp-forward-grid"></div>
</div>
<div id="snmpDialog" class="snmpDialog c2-dialog">
    <div id="snmpDialogHeader">
        <span id="snmpDialogTitle"></span>
    </div>
    <div style="overflow:hidden;" id="snmpDialogContent">
        <div style="margin:10px;">
            <input type="hidden" id="snmpId" />
            <input type="hidden" id="_token" value="{{ csrf_token() }}">
            <div class="row">
                <label for="snmpName">Name</label>
                <input type="text" id="snmpName" placeholder="Destination name" />
            </div>
            <div class="row">
                <label for="snmpFormat">Format</label>
                <input type="text" id="snmpFormat" placeholder="Enter format here" />
            </div>
            <div class="row">
                <label for="snmpIPAddress">IP Address</label>
                <input type="text" id="snmpIPAddress" placeholder="127.0.0.1" />
            </div>
            <div class="row">
                <label for="snmpReadCommunity">Read Community</label>
                <input type="text" id="snmpReadCommunity" placeholder="Read Community String" />
            </div>
            <div class="row">
                <label for="snmpWriteCommunity">Write Community</label>
                <input type="text" id="snmpWriteCommunity" placeholder="Write Community String" />
            </div>
            <div class="row">
                <label for="snmpVersion">SNMP Version</label>
                <select id="snmpVersion">
                    <option value="1">SNMP Trap (v1)</option>
                    <option value="2c">SNMP Inform (v2c)</option>
                </select>
            </div>
        </div>
            <div id="snmpDialogFooter">
                <div style="float:right;" class="buttons">
                    <span class="button" data-action="save"><img src="{!! asset('img/icons/disk.png') !!}" /><span id="snmpSaveAction">Update</span></span>
                    <span class="button" data-action="cancel"><img src="{!! asset('img/icons/cancel.png') !!}" />Cancel</span>
                </div>
            </div>
    </div>
</div>

