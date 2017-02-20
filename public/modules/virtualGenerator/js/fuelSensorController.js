angular.module('virtualGenerator').controller("fuelSensorCtrl", ['$scope', function ($scope) {

    $scope.$on('showFuelSensorStep',  function(event, step, context) {
        var fsName = $('#deviceNameFS').val();
        var flMin = $('#minFL').val();
        var flMax = $('#maxFL').val();
        if (fsName.length <= 0) {
            document.getElementById('deviceNameFS').value = 'fuel_sensor';
        }
        if (flMin.length <= 0 && flMax <= 0) {
            document.getElementById('minFL').value = "0";
            document.getElementById('maxFL').value = "100";
        }
    });
    
    $scope.$on('validateFuelSensor', function (event) { 
        $scope.$parent.isValid = true; 
        
        // Validate Selected Fuel Sensor 
        var as = $('#fuelSensor').val(); 
        if (as === "? undefined:undefined ?") { 
            $scope.$parent.isValid = false; 
            $('#msg_fuelSensor').html('Please select a fuel sensor').show(); 
        } else {
            $('#msg_fuelSensor').html('&nbsp').show(); 
        }

        // Validate the Name
        var sn = $('#deviceNameFS').val(); 
        if (!sn && sn.length <= 0) { 
            $scope.$parent.isValid = false; 
            $('#msg_deviceNameFS').html('Please enter a name').show(); 
        } else {
            $('#msg_deviceNameFS').html('&nbsp').show(); 
        }
        
        // Validate the Default Range
        var fg = $('#gaugeRange').val(); 
        var fgMin = $('#minGR').val(); 
        var fgMax = $('#maxGR').val();
        if (fgMin || fgMax) {
            if (((fgMin && fgMax) && (+fgMin < +fgMax)) && (fg !== "? string: ?")) {
                $('#msg_gaugeRange').html('&nbsp').show(); 
            } else {
                $scope.$parent.isValid = false; 
                $('#msg_gaugeRange').html('Please enter a valid range').show(); 
            }
        }
        
        // Validate the Fuel Percentage
        var fpMin = $('#minFL').val(); 
        var fpMax = $('#maxFL').val();
        if (fpMin || fpMax) {
            if ((fpMin && fpMax) && (+fpMin < +fpMax)) {
                $('#msg_fuelLevel').html('&nbsp').show(); 
            } else {
                $scope.$parent.isValid = false; 
                $('#msg_fuelLevel').html('Please enter a valid range').show();
            }
        }

        // Validate the Fuel Tank capacity
        var tank = $('#tankFC').val();
        if (tank && +tank <= 0) {
            $scope.$parent.isValid = false; 
            $('#msg_fuelCapacity').html('Please enter a valid fuel tank capacity').show();
        } else {
            $('#msg_fuelCapacity').html('&nbsp').show();
        }
    });

}]);