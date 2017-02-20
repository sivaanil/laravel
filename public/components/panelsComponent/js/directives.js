/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

angular.module('panel').directive('panelView', function () {

    return {
        restrict: 'EA',
        templateUrl: 'panelView',
        transclude: true,
        scope: false
    };
});

angular.module('panel').directive('panelMenu', function () {
    return {
        restrict: 'EA',
        templateUrl: '/panelMenu',
        transclude: true,
        scope: false
    };
});


angular.module('panel').directive('manuButtonCrtl', function () {
    return {
        restrict: 'EA',
        scope: {
            controlModel: "=",
            selectedItemType: "=",
            controlAction: '&',
            controlMenuAction: '&'
        },
        transclude: true,
        template: '<div>' +
        '<div id="panelTopMenu">' +
        '<ul style="background-color: white;">' +
        '<li style="display:inline;" ng-repeat="control in controlModel">' +
        '<div class="panelButtonGroup" ng-class="nodeButtonGroup" >' +
        '<input type="button"' +
        'title="{!! control.value !!}"' +
        'class="panelButton {!! control.class !!}"' +
        'ng-click="controlMenuAction({panel:control})"' +
        'ng-class="{!! control.class !!}"' +
        '>' +
        '</input>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>'
    };
});

angular.module('panel').directive('panelContent', function () {

    return {
        restrict: 'EA',
        transclude: true,
        scope: {
            panelModel: "="
        },

        link: function (scope, element, attrs) {

            scope.$parent.loadDefaultPanel();

            scope.$watch("panelModel", function (newVal, element, scope) {

                // go build it
                if (newVal !== "" || newVal !== 'undefined') {
                    scope.$parent.buildWindow(element);
                }
            }, true);

        },
        template: '<div id="docking">' +
        '<div class="angular-section">' +
        '<div ng-repeat="s in panelModel" id="{!! s.id !!}">' +
        '<div>' +
        "{!! s.value !!}" +
        '</div>' +
        '<div>' +
        "{!! s.value !!}" +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'

    };
});


angular.module('panel').directive("display-panel", function () {
    return {
        restrict: 'EA',
        transclude: true,
        replace: true,
        template: '<div class="angular-window">' +
        '<div>' +
        'Name: hi' +
        '</div>' +
        '</div>'

    };
});







