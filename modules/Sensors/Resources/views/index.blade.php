<script src="{!! asset('js/lib/c2grid.js') !!}"></script>
<script src="{!! asset('modules/sensors/js/sensor_ui.js') !!}"></script>
<link rel="stylesheet" href="{!! asset('modules/sensors/css/sensor_ui.css') !!}" />
<div id="jqxWidget" style="height:100%;">
    <div id="sensorTabs">
        <ul>
            <li>
                <div>
                    Contact Closures
                </div>
            </li>
            <li>
                <div>
                    Analog Sensors
                </div>
            </li>
            <li>
                <div>
                    Relays
                </div>
            </li>
        </ul>
        <div style="overflow:hidden">
            <div id="contact-closure-grid" style="width:100%;"></div>
            <div id="contact-closure-dialog" class="ccdialog c2-dialog">
                <div id="contact-closure-dialog-header">
                    <span id="contact-closure-dialog-title"></span>
                </div>
                <div style="overflow:hidden;" id="contact-closure-dialog-content">
                    <div style="margin:10px;">
                        <input type="hidden" id="contact-closure-id" />
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <label for="contact-closure-name">Name</label>
                            <input type="text" id="contact-closure-name" placeholder="Sensor name" />
                        </div>
                        <div class="row">
                            <label for="snmpFormat">Normal State</label>
                            <select id="contact-closure-normal-state">
                                <option value="Normally Open">Open</option>
                                <option value="Normally Closed">Closed</option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="contact-closure-alarm-severity">Severity</label>
                            <select id="contact-closure-alarm-severity">
                                <option value="Information">Information</option>
                                <option value="Ignored">Ignored</option>
                                <option value="Warning">Warning</option>
                                <option value="Minor">Minor</option>
                                <option value="Major">Major</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="contact-closure-normal-alias">Normal Alias</label>
                            <input type="text" id="contact-closure-normal-alias" placeholder="Door Closed" />
                        </div>
                        <div class="row">
                            <label for="contact-closure-alarm-alias">Alarm Alias</label>
                            <input type="text" id="contact-closure-alarm-alias" placeholder="Door Open" />
                        </div>
                    </div>
                        <div id="contact-closure-dialog-footer">
                            <div style="float:right;" class="buttons">
                                <span class="button" data-action="saveCC" style="cursor:pointer;"><img src="{!! asset('img/icons/disk.png') !!}" /><span id="contact-closure-save-action">Update</span></span>
                                <span class="button" data-action="cancelCC" style="cursor:pointer;"><img src="{!! asset('img/icons/cancel.png') !!}" />Cancel</span>
                            </div>
                        </div>
                </div>
            </div>

        </div>
        <div style="overflow:hidden">
            Analog Sensors are not yet implemented
            <div id="analogSensorGrid">

            </div>
        </div>
        <div style="overflow:hidden">
            Relays are not yet implemented
            <div id="relayGrid">

            </div>
        </div>
    </div>
</div>
