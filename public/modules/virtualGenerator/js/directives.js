angular.module('virtualGenerator').directive('virtualGeneratorModule', function () {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.loadSensors(window.homeNode, deviceType.value, deviceOptions.value);
        }
    };
});

angular.module('virtualGenerator').directive('sensorTree', function() {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.onTreeLoad(deviceOptions.value);
        }
    };
});

// Load up the partials for the step contents
angular.module('virtualGenerator').directive('contactClosureStep', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/contactClosureStep.html'
    };
});

angular.module('virtualGenerator').directive('fuelSensorStep', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/fuelSensorStep.html'
    };
});

angular.module('virtualGenerator').directive('relayControlStep', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/relayControlStep.html'
    };
});


// Load up the partials for the contents of the summary step
angular.module('virtualGenerator').directive('mainDeviceSummary', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/mainDeviceSummary.html'
    };
});

angular.module('virtualGenerator').directive('contactClosureSummary', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/contactClosureSummary.html'
    };
});

angular.module('virtualGenerator').directive('fuelSensorSummary', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/fuelSensorSummary.html'
    };
});

angular.module('virtualGenerator').directive('relayControlSummary', function() {
    return {
        restrict: 'AE',
        templateUrl: baseUrl + '/modules/virtualGenerator/partials/relayControlSummary.html'
    };
});