angular.module('virtualGenerator').controller("contactClosureCtrl", ['$scope', function ($scope) {

    $scope.$on('showContactClosureStep',  function(event, step, context) {
        var ccName = $('#deviceNameCC').val();
        if (ccName.length <= 0) {
            document.getElementById('deviceNameCC').value = 'gen_running';
        }
        if ($scope.$parent.normalState === "") {
            $scope.$parent.normalState = "OPEN";
        }
    });
    
    $scope.$on('validateContactClosure', function (event) { 
        $scope.$parent.isValid = true;  
        
        // Validate Selected Contact Closure 
        var cc = $('#contactClosure').val(); 
        if (cc === "? undefined:undefined ?") {
            $scope.$parent.isValid = false; 
            $('#msg_contactClosure').html('Please select a contact closure').show(); 
        } else {
            $('#msg_contactClosure').html('&nbsp').show(); 
        }
        
        // Validate the Name
        var sn = $('#deviceNameCC').val(); 
        if (!sn && sn.length <= 0) { 
            $scope.$parent.isValid = false; 
            $('#msg_deviceNameCC').html('Please enter a name for the contact closure').show(); 
        } else {
            $('#msg_deviceNameCC').html('&nbsp').show(); 
        }
        
        // Validate the Normal State
        var ns = $('#normalStateList').val(); 
        if (ns === "? undefined:undefined ?") { 
            $scope.$parent.isValid = false; 
            $('#msg_normalState').html('Please select a running state').show(); 
        } else {
            $('#msg_normalState').html('&nbsp').show(); 
        }
    });

}]);