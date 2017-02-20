angular.module('device').directive('deviceModule', function ($window) {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.loadDeviceClasses();
        }
    };
});

angular.module('device').directive('addDeviceModule', function ($window) {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.loadDeviceDefaults(deviceType.value);
        }
    };
});

angular.module('device').directive('deviceInfo', function ($window, mainService) {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.mainService = mainService;
            scope.loadDevice(scope.rootScope.$nodeId);
        }
    };
});

angular.module('device').directive('buildProgress', ['$compile', function ($compile) {
    return {
        restrict: 'A',
        link: function(scope, element, attr) {
            var el = angular.element('<div id="buildProgressBar">' +
                '</div>'
            );
            $compile(el)(scope);
            element.append(el);
        }
    };
}]);

angular.module('device').directive('scanProgress', ['$compile', function ($compile) {
    return {
        restrict: 'A',
        link: function(scope, element, attr) {
            var el = angular.element('<div id="scanProgressBar">' +
                '</div>'
            );
            $compile(el)(scope);
            element.append(el);
        }
    };
}]);