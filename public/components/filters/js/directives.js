angular.module('filter').directive('filterComponent', ["$window", "mainService", function ($window, mainService) {
    return {
        restrict: 'EA',
        transclude: true,
        scope: {
            id: "@",
            divid: "@",
            hideallcb: "@",
            module: "@",
            gridWithFilters: '@',
            filterGroups: "=",
            jqxWindowSettings: "=",
            filterGroupAction: '&',
        },
        link: function (scope, element, attrs) {
            //directive parameters
            scope.alarmLocalization = $window.alarmLocalization;
            scope.$parent.id = scope.id;
            scope.$parent.divId = scope.divid;
            scope.$parent.filterDivId = scope.divid + "_filters";
            //scope.$parent.hideAllCB = ;
            scope.hideAllCB = scope.hideallcb === "true";

            //replace this with a user pref
            scope.filterToggleValue = !scope.hideAllCB;
            scope.$parent.showWindowButton = scope.$parent.divId + "ShowWindowButton";
            //console.log(scope.$parent.showWindowButton);
            scope.$parent.html = "";
            scope.$parent.filterObj = "";
            //go find a parent that has a buttonList
            scope.$parent.menuVarName = "buttonList";
            scope.$parent.theMenuParent = mainService.findParentWithTarget(scope, scope.$parent.menuVarName);
            //scope.$parent.initIndeterminateState();

            scope.$watch(function () {
                return scope.$parent.filterList;
            }, function (filterList) {
                setTimeout(function () {
                    scope.$parent.showHideTimeClearedColumn();
                }, 50);
                scope.$parent.$parent.filterGroups = scope.$parent.filterList;
            }, true);

        },

        //$parent.$parent isn't ideal here but the alternative would be to ticket it on every element in the arrayobjects being looped over
        template: "<li id=\"{!!divid!!}ShowWindowButton\" /></li>" +
        "<div id=\"{!!divid!!}\" ng-jqwidgets=\"jqxWindow\" ng-jqxsettings=\"{!!jqxWindowSettings!!}\">" +
        "<div id=\"windowHeader\">" +
        "<span>" +
        "<img style=\"margin-right: 15px\" />Display Filters" +
        "</span>" +
        "</div>" +
        "<div id=\"{!!divid!!}_filters\"   class='btn-group' ng-show=\"filterToggleValue\"  >" +
        "<div class='cbdiv' style='float: left; overflow: hidden' ng-repeat='group in filterGroups' >" +
        "<ul class=\"alarmFilterUl\" id=\"{!!$parent.$parent.divId!!}_{!!group.filter_id!!}_CB_list\">" +
        "<li>" +
        "<label class=\"cb-group\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"{!!$parent.alarmLocalization[group.filter_id+'Tooltip'] !!}\">" +
        "<input type=\"checkbox\" " +
        "id=\"{!!$parent.$parent.divId!!}_{!!group.filter_id!!}_CB\" " +
        "ng-model=\"group.value\"" +
        "ng-change=\"filterGroupAction({group:group} )\">" +
        "{!! group.disp!!}" +
        "</input>" +
        "</label>" +
        "</li>" +
        "<li ng-repeat='curFilter in group.list' ng-repeat-done-notification>" +
        "<label  data-toggle=\"tooltip\" data-placement=\"top\" title=\"{!!$parent.$parent.alarmLocalization[curFilter.filter_id+'Tooltip'] !!}\">" +
        "<input type=\"checkbox\"" +
        "id=\"{!!$parent.$parent.$parent.divId!!}_{!!curFilter.filter_id!!}_CB\"" +
        "ng-model=\"curFilter.value\"" +
        "ng-change=\"filterGroupAction({group:$parent.group, filter:curFilter} )\">" +
        "{!!curFilter.disp!!}" +
        "<span class=\"filter-count\" >" +
        "</span>" +
        "</label>" +
        "</li>" +
        "</ul></div>" +
        "<div>" +
        "</div>"
    };
}]);

angular.module('filter').directive('ngRepeatDoneNotification', function ($rootScope) {
    return function (scope, element, attrs) {
        if (scope.$last) {
            //This is getting called too many times but at the end all of the CBs are in the right state
            scope.$parent.$parent.$parent.initIndeterminateState(scope.$parent.filterGroups);
        }
    };
});