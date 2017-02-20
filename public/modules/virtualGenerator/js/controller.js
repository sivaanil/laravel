angular.module('virtualGenerator').controller("virtualGeneratorCtrl", ['$scope', '$rootScope', 'mainService', function ($scope, $rootScope, mainService) {
    $scope.virtualLocalization = virtualLocalization;
    $scope.rootNode = "";
    $scope.deviceType = "";
    $scope.templateId = "";
    $scope.sensorTypeList = [];
    $scope.stepInclusion = [];
    $scope.children = [];
    $scope.isValid = true;
    
    // Contact Closure Variables
    $scope.contactClosureList = [];
    $scope.normalStateList = ['OPEN', 'CLOSED'];
    $scope.normalState = "";
    
    // Fuel Sensor Variables
    $scope.fuelSensorList = [];
    $scope.defaultRangeList = [
        {id: '1', range: '0-5', measurement: 'V'},
        {id: '2', range: '0-10', measurement: 'V'},
        {id: '3', range: '4-20', measurement: 'mA'}
    ];
    $scope.defaultRange = "";
    $scope.gaugeRangeList = ["mV", "V", "mA", "A"];
    $scope.gaugeRange = "";
    
    // Relay Control Variables
    $scope.relayControlList = [];
    $scope.onStateList = ['1', '2'];
    $scope.onState = "";
    
    $scope.loadSensors = function (nodeId, type, template) {
        $scope.rootNode = nodeId;
        $scope.deviceType = type;
        $scope.templateId = template;
        $.ajax({
            url: '/virtualDevice/getSensorTypes/' + $scope.templateId,
            method: 'GET',
            success: function (result) {
                $scope.sensorTypeList = result;
                $scope.stepInclusion = [];
                for (i = 0; i < $scope.sensorTypeList.length; i++) {
                    var sensorType = $scope.sensorTypeList[i].included_sensors;
                    $scope.getStepsInvolved(sensorType);
                    $scope.loadLists(sensorType);
                }
            }
        });
    };
    
    // Populate all the sensor dropdowns available on the node
    $scope.loadLists = function (sensorType) {
        $.ajax({
            url: '/virtualDevice/getSensors/' + sensorType,
            method: 'GET',
            success: function (result) {
                if (sensorType === "1083") {
                    $scope.contactClosureList = result;
                } else if (sensorType === "1082") {
                    $scope.relayControlList = result;
                } else if (sensorType === "1084") {
                    $scope.fuelSensorList = result;
                }
                $scope.$apply();
            }
        });
    };
    
    // Unfortunately, this had to be broken out into its own function, it was over-populating when included in loadLists
    $scope.getStepsInvolved = function (sensorType) {
        if (sensorType === "1083") {
             $scope.stepInclusion.push('Contact Closure');
         } else if (sensorType === "1082") {
             $scope.stepInclusion.push('Relay Control');
         } else if (sensorType === "1084") {
             $scope.stepInclusion.push('Fuel Sensor');
         }
    };
    
    $scope.launchTree = function () {
        var url = baseUrl + "/modules/virtualGenerator/sensorTree.html";
        onPanelLoad = function() {
            $('#sensorTreeWindow').jqxWindow({
                showCollapseButton: false,
                height: 400,
                width: 300,
                closeButtonAction: 'hide',
                isModal: true,
                initContent: function () {
                    $('.dialog-footer-buttons').show();
                }
            });
            $('#sensorTreeWindow').jqxWindow('open');
        };
        mainService.loadPanel('sensorTreeWindowContent', url, onPanelLoad);
    };

    // On show step event for displaying the information, determined by template
    $scope.$on('stepEvent',  function(event, step, context) {
        if (context.toStep === 1) {
            $rootScope.$broadcast('showContactClosureStep', step, context);
        } else if (context.toStep === 2) {
            if ($scope.templateId === "1") {
                $rootScope.$broadcast('showSummaryStep', step, context);
            } else if ($scope.templateId === "2") {
                $rootScope.$broadcast('showRelayControlStep', step, context);
            } else {
                $rootScope.$broadcast('showFuelSensorStep', step, context);
            }
        } else if (context.toStep === 3) {
            if ($scope.templateId === "4") {
                $rootScope.$broadcast('showRelayControlStep', step, context);
            } else {
                $rootScope.$broadcast('showSummaryStep', step, context);
            }
        } else {
            $rootScope.$broadcast('showSummaryStep', step, context);
        }
    });
    
    // On finishing the wizard, call events for validation and building the device to send to the server
    $scope.$on('finishEvent',  function(event) {
        if ($scope.validateSteps()) {
            $scope.buildChildren();
            $scope.createDevice();
        }
    });
    
    // Validation!
    $scope.validateSteps = function () { 
 	var isStepValid = true;
        var stepNum = [];
        var errorNum = 0;
        var singleErrorMessage = 'Please fix the errors in the highlighted step.';
        var multipleErrorMessage = 'Please fix the errors in the highlighted steps.';
        
        for (i = 0; i < $scope.stepInclusion.length; i++) {
            if ($scope.stepInclusion[i] === 'Contact Closure') {
                $rootScope.$broadcast('validateContactClosure');
                if ($scope.isValid === false) { 
                    isStepValid = false;
                    errorNum++;
                    stepNum.push($scope.getStepNum($scope.stepInclusion[i]));
                }
            } else if ($scope.stepInclusion[i] === 'Fuel Sensor') {
                $rootScope.$broadcast('validateFuelSensor');
                if ($scope.isValid === false) { 
                    isStepValid = false;
                    errorNum++;
                    stepNum.push($scope.getStepNum($scope.stepInclusion[i]));
                }
            } else if ($scope.stepInclusion[i] === 'Relay Control') {
                $rootScope.$broadcast('validateRelayControl');
                if ($scope.isValid === false) { 
                    isStepValid = false;
                    errorNum++;
                    stepNum.push($scope.getStepNum($scope.stepInclusion[i]));
                }
            }
        }
        if (!isStepValid) {
            if (errorNum > 1) {
                $('#wizard').smartWizard('showMessage', multipleErrorMessage);
            } else {
                $('#wizard').smartWizard('showMessage', singleErrorMessage); 
            }
            for (var i = 0; i < stepNum.length; i++) {
                $('#wizard').smartWizard('setError', {stepnum:stepNum[i], iserror:true});
            }
        } else { 
            $('#wizard').smartWizard('hideMessage');
            for (var i = 0; i < stepNum.length; i++) {
                $('#wizard').smartWizard('setError', {stepnum:stepNum[i], iserror:false});
            }
        }
       return isStepValid;
    };
    
    $scope.getStepNum = function (step) {
        if (step === "Contact Closure") {
            return 1;
        } else if (step === "Fuel Sensor") {
            return 2;
        } else if (step === "Relay Control") {
            if ($scope.templateId === "2") {
                return 2;
            } else if ($scope.templateId === "4") {
                return 3;
            }
        }
    };

    $scope.buildChildren = function() {
        for (i = 0; i < $scope.stepInclusion.length; i++) {
            if ($scope.stepInclusion[i] === 'Contact Closure') {
                var alarmMatch = '0';
                if ($('#normalState').val() === 'OPEN') {
                    alarmMatch = '0';
                }
                var normalState = {
                    property_definition: "generator_running",
                    case_sensitive: 0,
                    alarm_on_match: alarmMatch,
                    text: "Idle"
                };

                var contactClosure = {
                    real_device_node: $('#contactClosure').val(),
                    name: $('#deviceNameCC').val(),
                    property_list: true,
                    children: [''],
                    text_thresholds: [normalState]
                };
                $scope.children.push(contactClosure);
            } else if ($scope.stepInclusion[i] === 'Fuel Sensor') {
//                var fuelCapacity = {
//                    property_definition: "volume_tankvolume",
//                    lower_bound: 0,
//                    upper_bound: $('#tankFC').val(),
//                    alarm_inclusive: 0
//                };
                var fuelProp = {
                    property_definition: "volume_percentlevel",
                    original_minimum: $('#minGR').val(),
                    original_maximum: $('#maxGR').val(),
                    new_minimum: $('#minFL').val(),
                    new_maximum: $('#maxFL').val()
                };

                var fuelSensor = {
                    real_device_node: $('#fuelSensor').val(),
                    name: $('#deviceNameFS').val(),
                    property_list: true,
                    children: [''],
                    //property_thresholds: fuelCapacity,
                    property_value_translation: [fuelProp]
                };
                $scope.children.push(fuelSensor);
            } else if ($scope.stepInclusion[i] === 'Relay Control') {
                var alarmMatch = '0';
                if ($('#onState').val() === '1') {
                    alarmMatch = '1';
                }
                var onState = {
                    property_definition: "setRelayState",
                    case_sensitive: 0,
                    alarm_on_match: alarmMatch,
                    text: "1"
                };

                var relayControl = {
                    real_device_node: $('#relayControl').val(),
                    name: $('#deviceNameRC').val(),
                    property_list: true,
                    children: [''],
                    text_thresholds: [onState],
                };
                $scope.children.push(relayControl);
            }
        }
    };
    
    $scope.createDevice = function() {
        var mainDevice = {
            real_device_node: -1,
            name: $('#deviceName').val(),
            property_list: true,
            children: $scope.children
        };

        var device = {
            rootNode: $scope.rootNode,
            deviceStructure: mainDevice
        };

        $.ajax({
            url: '/virtualDevice/buildVirtualDevice/' + $scope.deviceType,
            method: 'POST',
            dataType: 'json',
            data: device,
            success: function (data) {
                alert(data);
            }
        });
        $('#launchVirtualBuildWizard').jqxWindow('close');
        return true;
    };
    
}]);