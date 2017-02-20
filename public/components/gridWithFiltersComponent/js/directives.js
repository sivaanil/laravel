angular.module('gridwithfilters').directive('gridWithFiltersComponent', function ($window) {
    return {
        restrict: 'EA',
        transclude: true,
        scope: {
            //General Params
            id: "@",
            divid: "@",
            module: "@",
            nodeid: "@",
            disabledraganddrop: "@",
            rowdetailsformat: "@",
            altrow: "@",
            pageable: "@",
            gridtitle: "@",
            localizedemptrydatastring: "@",
            //Filter params
            hideallcb: "@",
            filterGroups: "="
        },
        link: function (scope, element, attrs) {
            //console.log("LINKING FILTER GRID");
            //directive parameters
            scope.$parent.divId = scope.divid;
            scope.$parent.module = scope.module;
            //scope.$parent.dataUrl = scope.dataurl;
            //grid Params
            //scope.$parent.exportUrl = scope.exporturl;
            scope.$parent.nodeId = scope.nodeid;
            scope.$parent.rowDetailsFormat = scope.rowdetailsformat;
            scope.$parent.disableDragAndDrop = scope.disabledraganddrop === "false" ? true : false;
            scope.$parent.altrow = scope.altrow === "true";
            scope.$parent.pageable = scope.pageable === "true";
            scope.$parent.gridTitle = scope.gridtitle;
            scope.$parent.localizedEmptryDataString = scope.localizedemptrydatastring;

            scope.$parent.hideAllCB = scope.hideallcb === "true";

            scope.$parent.initFilterWithGrid();
            scope.$watch(function () {
                return scope.$parent.filterList
            }, function (filterList) {
                if ($('#' + scope.divid + "AlarmContainer") !== undefined && scope.$parent.filterList !== undefined) {
                    //scope.saveCustomFilterPreferences();
                    $('#' + scope.divid + "AlarmContainer").scope().filterGroups = scope.$parent.filterList;
                }
            }, true);
        },
        template: "<div id=\"{!!divid!!}FilterTop\">" +
        '<div ng-app="filter" ng-controller="filterCtrl">' +
        "<div id='{!!divid!!}FilterContainer' ng-init='initFilter()'>" + //{!!module!!}
            //{!!--This name needs to be lower case--!!}

        '<filter-component ' +
        'module="{!!module!!}"' +
        'id="{!!divid!!}FilterComponent" ' +
        'divid="{!!divid!!}Filter" ' +
        "hideallcb=\"{!!hideallcb!!}\" " +//
        'filter-groups="filterList" ' +
        'filter-group-action="checkBoxChangeAction(group, filter)"' +
        'jqx-window-settings="jqxWindowSettings"' +
            //'grid-with-filters=true'+
        '>' +
        '</filter-component>' +
        '</div>' +
        '</div>' +
        '</div>'
        +
        '<div id="{!!divid!!}AlarmTop" style="height:100%">' +
        '<div ng-app="grid" style="height:100%">' +
        '<div id="{!!divid!!}AlarmContainer" ng-controller="gridCtrl"  style="height:100%">' +
            //{!!--This name needs to be lower case--!!}
        '<grid-component ' +
        'id="{!!divid!!}GridComponent" ' +
        'divid="{!!divid!!}Grid" ' +
        'dataurl="/gridData/{!!module!!}" ' +
        'nodeid=\"{!!nodeid!!}\" ' +
        'exportUrl="" ' +
        'disabledraganddrop=\"{!!disabledraganddrop!!}\" ' +
        'rowdetailsformat=\"{!!rowdetailsformat!!}\" ' +
        'altrow=\"{!!altrow!!}\" ' +
        'pageable=\"{!!pageable!!}\" ' +
        'gridtitle=\"{!!gridtitle!!}\" ' +
        'localizedemptrydatastring="{!!localizedemptrydatastring!!}"' +
        'filter-groups="filterList" ' +
        'available-columns="availableColumns" ' +
        'selected-columns="selectedColumns" ' +
        'jqx-window-settings="jqxWindowSettings"' +
        'wait-for-filters=true' +
            // 'gridWithFilters=true'+
        '>' +
        '</grid-component>' +
        '</div>' +
        '</div>' +
        '</div>'
    };
});