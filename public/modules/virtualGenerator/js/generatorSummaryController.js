angular.module('virtualGenerator').controller("generatorSummaryCtrl", ['$scope', function ($scope) {
 
    $scope.$on('showSummaryStep',  function(event, step, context) {    
        // Main Device
        $('#summary_deviceName').html($('#deviceName').val()).show();
        selectedDeviceType = "External RTU Generator";
        $('#summary_deviceType').html(selectedDeviceType).show();

        for (i = 0; i < $scope.$parent.sensorTypeList.length; i++) {
            if ($scope.$parent.sensorTypeList[i].template_id === $scope.$parent.templateId) {
                selectedDeviceOption = $scope.$parent.sensorTypeList[i].name;
            }
        }
        $('#summary_deviceOption').html(selectedDeviceOption).show();

        // Set up the contact closure summary
        var selectedContactClosure = "";
        var ccDevice = $('#contactClosure').val();
        for (i = 0; i < $scope.$parent.contactClosureList.length; i++) {
            if ($scope.$parent.contactClosureList[i].id === ccDevice) {
                selectedContactClosure = $scope.$parent.contactClosureList[i].mainDevice + " - " + $scope.$parent.contactClosureList[i].subDevice;
            }
        }
        contactClosureSummary(selectedContactClosure);

        // Set up the fuel sensor Summary
        var selectedFuelSensor = "";
        var fsDevice = $('#fuelSensor').val();
        for (i = 0; i < $scope.$parent.fuelSensorList.length; i++) {
            if ($scope.$parent.fuelSensorList[i].id === fsDevice) {
                selectedFuelSensor = $scope.$parent.fuelSensorList[i].mainDevice + " - " + $scope.$parent.fuelSensorList[i].subDevice;
            }
        }
        var selectedSensorRange = "";
        var fsRange = $('#defaultRange').val();
        for (i = 0; i < $scope.$parent.defaultRangeList.length; i++) {
            if ($scope.$parent.defaultRangeList[i].id === fsRange) {
                selectedSensorRange = $scope.$parent.defaultRangeList[i].range + $scope.$parent.defaultRangeList[i].measurement;
            }
        }
        fuelSensorSummary(selectedFuelSensor, selectedSensorRange);

        // Set up the relay control Summary
        var selectedRelayControl = "";
        var rcDevice = $('#relayControl').val();
        for (i = 0; i < $scope.$parent.relayControlList.length; i++) {
            if ($scope.$parent.relayControlList[i].id === rcDevice) {
                selectedRelayControl = $scope.$parent.relayControlList[i].mainDevice + " - " + $scope.$parent.relayControlList[i].subDevice;
            }
        }
        relayControlSummary(selectedRelayControl);
    });

}]);

