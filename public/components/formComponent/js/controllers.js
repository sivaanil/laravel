angular.module('form').controller("formCtrl", ['$scope', '$rootScope', '$http', '$timeout', 'mainService', '$resource', function ($scope, $rootScope, $http, $timeout, mainService, $resource) {
    $scope.formData = [];
    $scope.formErrors = [];
    $scope.formSuccess = false;

    $scope.loadFormData = function () {
        if ($scope.dataUrl) {
            var formData = $resource(
                baseUrl + $scope.dataUrl,
                null,
                {
                    'get': {
                        'method': "GET",
                        'params': {}
                    }
                }
            );
            // get the form data
            formData.get({}, function (result) {
                // bind to scope
                $scope.formData = result.data;
                $scope.formErrors = [];
            });
        }
    };
        
    $scope.submitForm = function (formSubmitCallback) {
        var formData = $resource(
            baseUrl + this.dataUrl, 
            null,
            {
              'post': {
                'method': "POST",
                'params': {
                        
                }
              }
            }
        );
        // submit the form data
        formData.post($scope.formData, function (result, formSubmitCallback) {
            if(typeof result.errors !== 'undefined') {
                // bind errors to scope
                $scope.formSuccess = false;
                $scope.formErrors = result.errors;
            } else {
                $scope.formSuccess = result.success;
                $scope.formErrors = [];
            }
            formSubmitCallback(result);
        });       
    };

}]);

