angular.module('virtualGenerator').controller("relayControlCtrl", ['$scope', function ($scope) {

    $scope.$on('showRelayControlStep',  function(event, step, context) {
        var rcName = $('#deviceNameRC').val();
        if (rcName.length <= 0) {
            document.getElementById('deviceNameRC').value = 'gen_fail';
        }
        if ($scope.$parent.onState === "") {
            $scope.$parent.onState = "1";
        }
    });
    
    $scope.$on('validateRelayControl', function (event) { 
        $scope.$parent.isValid = true;  
        
        // Validate Selected Relay Control
        var rc = $('#relayControl').val();
        if (rc === "? undefined:undefined ?") { 
            $scope.$parent.isValid = false; 
            $('#msg_relayControl').html('Please select a relay control').show(); 
        } else {
            $('#msg_relayControl').html('&nbsp').show(); 
        }
        
        // Validate the Name
        var sn = $('#deviceNameRC').val(); 
        if (!sn && sn.length <= 0) { 
            $scope.$parent.isValid = false; 
            $('#msg_deviceNameRC').html('Please enter a name').show(); 
        } else {
            $('#msg_deviceNameRC').html('&nbsp').show(); 
        }
        
        // Validate the On State
        var os = $('#onStateList').val(); 
        if (os === "? undefined:undefined ?") { 
            $scope.$parent.isValid = false; 
            $('#msg_onState').html('Please select an on state').show(); 
        } else {
            $('#msg_onState').html('&nbsp').show(); 
        }
    });

}]);
