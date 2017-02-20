var app = angular.module('virtualGenerator', []);

contactClosureSummary = function(selectedContactClosure) {
    var ccName = $('#deviceNameCC').val();
    var ccNormal = $('#normalState').val();

    // If the user hasn't selected a name, hide that object
    if (ccName.length > 0) {
        $('#ccName').html('Name of the Contact Closure :').show();
        $('#summary_ccName').html(ccName).show();
    } else {
        $('#ccName').html('').hide();
        $('#summary_ccName').html('').hide();
    }
    // If the user hasn't selected a normal state, hide that object
    if (ccNormal.length > 0) {
        $('#ccNormalState').html('Selected Running State for the Generator :').show();
        $('#summary_ccNormalState').html($('#normalState').val()).show();   
    } else {
        $('#ccNormalState').html('').hide();
        $('#summary_ccNormalState').html('').hide();  
    }
    
    // If the user hasn't chosen a contact closure, device will be built without one, hide everything
    if (selectedContactClosure.length > 0) {
        $('#summary_MonitoringSensor').html('Generator Monitoring Sensor').show();
        $('#ccType').html('Selected Contact Closure :').show();
        $('#summary_ccType').html(selectedContactClosure).show();
    } else {
        $('#summary_MonitoringSensor').html('').hide();
        $('#ccType').html('').hide();
        $('#summary_ccType').html('').hide();
        $('#ccName').html('').hide();
        $('#summary_ccName').html('').hide();
        $('#ccNormalState').html('').hide();
        $('#summary_ccNormalState').html('').hide();
    }
};

fuelSensorSummary = function(selectedFuelSensor, selectedSensorRange) {
    var fsName = $('#deviceNameFS').val();
    var minGR = $('#minGR').val();
    var maxGR = $('#maxGR').val();
    var GRMeasure = $('#gaugeRange').val();
    var minFL = $('#minFL').val();
    var maxFL = $('#maxFL').val();
    var tankCapacity = $('#tankFC').val();

    // If the user hasn't selected a name, hide that object
    if (fsName != null && fsName.length > 0) {
        $('#fsName').html('Name of the Analog Sensor :').show();
        $('#summary_fsName').html(fsName).show();
    } else {
        $('#fsName').html('').hide();
        $('#summary_fsName').html('').hide();
    }
    // If the user hasn't selected a sensor range, hide that object
    if (selectedSensorRange.length > 0) {
        $('#fsDefaultRange').html('Default Range of the selected Analog Sensor :').show();
        $('#summary_fsDefaultRange').html(selectedSensorRange).show();
    } else {
        $('#fsDefaultRange').html('').hide();
        $('#summary_fsDefaultRange').html('').hide();
    }
    // If the user hasn't selected a gauge range, hide that object
    if ((minGR != null && minGR.length > 0) && (maxGR != null && maxGR.length > 0) && (GRMeasure != null && GRMeasure.length > 0)) {
        $('#fsGaugeRange').html('Sensor Range of the Fuel Gauge :').show();
        $('#summary_fsGaugeRange').html(minGR + " - " + maxGR + GRMeasure).show();
    } else {
        $('#fsGaugeRange').html('').hide();
        $('#summary_fsGaugeRange').html('').hide();
    }
    // If the user hasn't input a capacity for the fuel tank,  hide that object
    if (tankCapacity != null && tankCapacity.length > 0) {
        $('#fsTankCapacity').html('Fuel Tank Capacity in Gallons :').show();
        $('#summary_fsTankCapacity').html(tankCapacity).show();
    } else {
        $('#fsTankCapacity').html('').hide();
        $('#summary_fsTankCapacity').html('').hide(); 
    }
    
    // User has not selected a fuel sensor, device will be built without it, hide everything
    if (selectedFuelSensor.length > 0) {
        $('#fuelSensorSpace').html('').show();
        $('#summary_FuelSensor').html('Generator Fuel Sensor').show();
        $('#fsType').html('Selected Analog Sensor :').show();
        $('#summary_fsType').html(selectedFuelSensor).show();
        $('#fsFuelLevel').html('Fuel Level Range :').show();
        $('#summary_fsFuelLevel').html(minFL + " - " + maxFL + "%").show();
    } else {
        $('#summary_FuelSensor').html('').hide();
        $('#fsType').html('').hide();
        $('#summary_fsType').html('').hide();
        $('#fsName').html('').hide();
        $('#summary_fsName').html('').hide();
        $('#fsDefaultRange').html('').hide();
        $('#summary_fsDefaultRange').html('').hide();
        $('#fsGaugeRange').html('').hide();
        $('#summary_fsGaugeRange').html('').hide();
        $('#fsFuelLevel').html('').hide();
        $('#summary_fsFuelLevel').html('').hide();
        $('#fuelSensorSpace').html('').hide();
        $('#fsTankCapacity').html('').hide();
        $('#summary_fsTankCapacity').html('').hide(); 
    }
};

relayControlSummary = function(selectedRelayControl) {
    var rcName = $('#deviceNameRC').val();
    var selectedOnState = $('#onState').val();

    // If the user hasn't selected a name, hide that object
    if (rcName != null && rcName.length > 0) {
        $('#rcName').html('Relay Control Name :').show();
        $('#summary_rcName').html(rcName).show();
    } else {
        $('#rcName').html('').hide();
        $('#summary_rcName').html('').hide();
    }
    // If the user hasn't selected the on state, hide that object
    if (selectedOnState != null && selectedOnState.length > 0) {
        $('#rcOn').html('Selected On State for the Generator :').show();
        $('#summary_rcOn').html(selectedOnState).show();
    } else {
        $('#rcOn').html('').hide();
        $('#summary_rcOn').html('').hide();
    }
    
    // User has not selected a relay control, device will be built without it, hide everything
    if (selectedRelayControl.length > 0) {
        $('#controlSensorSpace').html('').show();
        $('#summary_ControlSensor').html('Generator Control Sensor').show();
        $('#rcType').html('Selected Relay Control :').show();
        $('#summary_rcType').html(selectedRelayControl).show();
    } else {
        $('#summary_ControlSensor').html('').hide();
        $('#rcType').html('').hide();
        $('#summary_rcType').html('').hide();
        $('#rcName').html('').hide();
        $('#summary_rcName').html('').hide();
        $('#rcOn').html('').hide();
        $('#summary_rcOn').html('').hide();
    }
};