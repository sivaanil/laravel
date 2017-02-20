angular.module('menu').directive('menuComponent', ['$window', 'mainService', function ($window, mainService) {
    return {
        restrict: 'EA',
        transclude: true,
        scope: {
            nodeid: "@",
            dataurl: "@",
            divid: "@",
            activepage: "@"
        },
        link: function (scope, element, attrs) {
            scope.$parent.nodeId = scope.nodeid;
            scope.$parent.dataUrl = scope.dataurl;
            scope.$parent.divId = scope.divid;
            scope.$parent.activePage = scope.activepage;
            scope.$parent.initMenuComponent();
            scope.$on('nodeChangeEvent', function (event, args) {
                scope.$parent.nodeId = args.nodeId;
                scope.$parent.refreshBreadcrumb();
            });
        },
        template: ""
    };
}]);