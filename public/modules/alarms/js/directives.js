angular.module('alarm').directive('alarmModule', function ($window) {
    return {
        restrict: 'EA',
        transclude: true,
        scope: {
            id: "@",
            divid: "@",
        },
        link: function (scope, element, attrs) {
            var w = angular.element($window);
            w.bind('resize', function () {
                waitForFinalEvent(function () { //This function is defined in the menuArea.blade

                }, 100, "alarm" + scope.divid);
            });
            //scope.$parent.initAlarmComponent();

        },
        template: "<div style=\"height:100%\"><div style='' id=\"{!!divid!!}_cDropdown\"></div>" +
        "<div id=\"{!!divid!!}_resizeCB\" title=\"" + $window.gridLocalization.autoResizeCBToolTip + "\"style='margin-left: 10px; float: right;'>" + $window.gridLocalization.autoResizeCB + "</div>" +
        "<div style=\"clear:both;\"></div>" +
        "<div id=\"{!!divid!!}\" style=\" height:100%\">" +
        "</div></div>"
    };
});