angular.module('network').directive('wanSettings', function ($window) {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.networkLocalization = $window.networkLocalization;
            scope.dhcpOptions = [
                {
                    value: 0,
                    label: 'Static'
                },
                {
                    value: 4,
                    label: 'DHCP - IPv4'
                },
                {
                    value: 6,
                    label: 'DHCP - IPv6'
                }
            ];
        }
    };
});

angular.module('network').directive('lanSettings', function ($window) {
    return {
        transclude: false,
        scope: true,
        require: "^mainApp",
        link: function (scope, element, attrs) {
            scope.networkLocalization = $window.networkLocalization;
        }
    };
});
