angular.module('tree').directive('networkTree', ["$window", "mainService", function ($window, mainService) {
    return {
        restrict: 'EA',
        transclude: true,
        scope: {
            dataType: "@",
            allowDrag: '@',
            allowDrop: '@',
            hasThreeStates: "@",
            hasCheckBoxes: "@",
            hasMenuIcon: "@",
            orderBy: "@",
            reverseOrder: "@",
            treeType: '@'
        },
        link: function (scope, element, attrs) {
            scope.$parent.treeLocalization = $window.treeLocalization;
            scope.$parent.dataType = attrs.dataType;
            scope.$parent.allowDrag = attrs.allowDrag === 'true' ? true : false;
            scope.$parent.allowDrop = attrs.allowDrop === 'true' ? true : false;

            if (attrs.treeModel != "") {
                scope.$parent.treeModel = JSON.parse(attrs.treeModel);
            }

            if (attrs.treeFilter != "") {
                scope.$parent.treeFilter = JSON.parse(attrs.treeFilter);
            }

            scope.$parent.hasThreeStates = attrs.hasThreeStates === 'true' ? true : false;
            scope.$parent.hasCheckBoxes = attrs.hasCheckBoxes === 'true' ? true : false;
            //scope.$parent.hasMenuIcon = attrs.hasMenuIcon ==='true'?true:false;
            scope.$parent.orderBy = attrs.orderBy;
            scope.$parent.reverseOrder = attrs.reverseOrder;
            scope.$parent.treeType = attrs.treeType;

            if (scope.$parent.dataType === "array") {
                scope.$parent.source = scope.$parent.treeModel;
            } else { //json
                if (scope.$parent.treeType === 'lazy') {
                    scope.$parent.lazyLoadTree();
                } else if (scope.$parent.treeType === 'full') {
                    scope.$parent.fullLoadTree();
                }
            }
            scope.$on('refreshTreeEvent', function (event, args) {
                scope.$parent.refreshNetworkTree();
            });
        },
        template: "<div id='netTree' style='visibility: hidden; border: none; overflow: auto'>" +
        "</div>"
    };
}]);

angular.module('tree').directive('treeContextMenu', function () {
    return {
        restrict: 'EA',
        scope: {
            nodeControlModel: "=",
            selectedItemType: "=",
            controlAction: '&'
        },
        template: '<div id="controlContainer" ng-modal="selectedItemType">' +
        '<div id="contextTreeMenu">' +
        '<ul>' +
        '<li ng-repeat="control in nodeControlModel" ng-show="{!! control.show !!}" ng-click="controlAction({message:control})">' +
        '<img class="{!! control.class !!}"' +
        'ng-class="{!! control.class !!}"' +
        'title="{!! control.value !!} "' +
        '>' +
        '</img>' +
        '&nbsp;&nbsp;' +
        '<label for="{!! control.id !!}" >{!! control.value !!}</label>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>'

    };
});