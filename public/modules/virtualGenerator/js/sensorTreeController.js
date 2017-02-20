angular.module('virtualGenerator').controller("sensorTreeCtrl", ['$scope', function ($scope) {
    $scope.virtualLocalization = virtualLocalization;
    $scope.tree = null;
    
    $scope.onTreeLoad = function () {
        $.ajax({
            url: '/networkTree/loadFirstLevel',
            method: "GET",
            dataType: "json",
            success: function(data) {
                // Initialize the tree options here
                var treeOptions = {
                    source: data.nodes,
                    checkboxes: true,
                    height: '325px',
                    width: '285px'
                };
                $('#sensorTree').c2tree(treeOptions);
                $('$contactClosure').enabled = false;
            }
        });
        $scope.tree = $('#sensorTree');
    };
    
    $scope.onSensorSubmit = function() {
        var items = $scope.tree.c2tree('getCheckedItems');
        // Something here to assign the name of that item to the text field
    };
    
    $scope.onSensorCancel = function() {
        $('#sensorTreeWindow').jqxWindow('close');
    };
    
}]);
