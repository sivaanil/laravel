angular.module('grid').directive('gridComponent', ['$window', 'mainService', '$timeout', function ($window, mainService, $timeout) {
    return {
        restrict: 'EA',
        transclude: true,
        scope: {

            id: "@",
            divid: "@",
            dataurl: "@",
            gridtitle: "@",
            nodeid: "@",
            exporturl: "@",
            disabledraganddrop: "@",
            rowdetailsformat: "@",
            altrow: "@",
            pageable: "@",
            localizedemptrydatastring: "@",
            filterGroups: "=",
            availableColumns: "=",
            selectedColumns: "=",
            jqxWindowSettings: "=",
            waitforFilter: "="
        },
        link: function (scope, element, attrs) {
            //directive parameters
            scope.$parent.divId = scope.divid;
            scope.$parent.dataUrl = scope.dataurl;
            scope.$parent.gridTitle = scope.gridtitle;
            scope.$parent.gridId = scope.gridid;
            scope.$parent.exportUrl = scope.exporturl;
            scope.$parent.nodeId = scope.nodeid;
            scope.$parent.rowDetailsFormat = scope.rowdetailsformat;
            scope.$parent.disableDragAndDrop = scope.disabledraganddrop === "false" ? true : false;
            scope.$parent.altrow = scope.altrow === "true";
            scope.$parent.pageable = scope.pageable === "true";
            scope.$parent.localizedEmptryDataString = scope.localizedemptrydatastring;
            scope.$parent.filterGroups = scope.filterGroups;

            //internal parameters
            scope.$parent.cDropdownId = scope.divid + "_cDropdown";
            scope.$parent.resizeCB = scope.divid + "_resizeCB";
            scope.$parent.gridmenu = scope.divid + "_gridmenu";
            scope.$parent.gridMenuContainer = scope.divid + "_grid_menu_container";
            scope.$parent.urlData = {isFirst: true, nodeId: scope.nodeid};
            scope.$parent.contentData = Array();
            scope.$parent.gridSource = {};
            scope.$parent.gridSortList = {};
            scope.$parent.gridFilterList = Array();
            scope.$parent.resettingData = false;
            scope.$parent.dynamicWidthColumns = [];
            scope.$parent.expandedRows = [];
            scope.$parent.hiddenColumns = [];
            scope.$parent.availableColumns = [];
            scope.$parent.selectedColumns = [];
            scope.$parent.resizeCBBeingChecked = false;
            scope.$parent.gridLocalization = $window.gridLocalization;
            scope.$parent.gridLocalization.emptydatastring = scope.$parent.localizedEmptryDataString;
            scope.$parent.waitForFilter = true;
            var w = angular.element($window);

            setHeight();
            w.bind('resize', function () {
                waitForFinalEvent(function () { //This function is defined in the menuArea.blade
                    scope.$parent.windowResize();
                }, 100, "grid" + scope.divid);
            });
            scope.$parent.menuVarName = "buttonList";
            scope.$parent.theMenuParent = mainService.findParentWithTarget(scope, scope.$parent.menuVarName);

            scope.$parent.gridRowActionsVarName = "gridRowActions";
            scope.$parent.gridRowActionHandler = mainService.findParentWithTarget(scope, scope.$parent.gridRowActionsVarName);

            scope.$parent.initGridComponent();
            var timer = false;
            scope.$watch(function () {
                return scope.$parent.filterGroups
            }, function (filterGroups) {
                if (timer) {
                    $timeout.cancel(timer)
                }
                timer = $timeout(function () {
                    if ($('.alarmFilterUl').length > 0) {
                        if (scope.$parent.waitforFilter == false) {
                            if (scope.$parent.gridInit) {
                                scope.$parent.filterChange();
                            }
                        }
                        scope.$parent.waitforFilter = false;
                    }
                }, 50)
            }, true);
            scope.$on('nodeChangeEvent', function (event, args) {
                scope.$parent.nodeId = args.nodeId;
                scope.$parent.filterChange();
            });

        },
        template: "<div style=\"height:100%\"><li data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + $window.gridLocalization.selectColumnsButtonTooltip + "\" id=\"{!!divid!!}ColumnButton\">" + $window.gridLocalization.selectColumnsButton + "</li>" +
        "<li data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + $window.gridLocalization.resetColumnsButtonTooltip + "\"  id=\"{!!divid!!}ResetColumnsButton\">" + $window.gridLocalization.resetColumnsButton + "</li>" +
        "<li data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + $window.gridLocalization.excelExportButtonTooltip + "\" id=\"{!!divid!!}ExcelExportButton\">" + $window.gridLocalization.excelExportButton + "</li>" +
        "<div id=\"{!!divid!!}ColumnPopup\" ng-jqwidgets=\"jqxWindow\" ng-jqxsettings=\"{!!jqxWindowSettings!!}\" style=\"display: none\" ng-controller=\"gridCtrl\">" +
        "<div id=\"{!!divid!!}ColumnPopupHeader\">" +
        "<span>" +
        $window.gridLocalization.selectColumnsWindowHeader +
        "</span>" +
        "</div>" +
        "<div id=\"{!!divid!!}ColumnPopupContent\">" +
        "<div id=\"{!!divid!!}_availableColumns\" class=\"available-columns\" >" +
        "<h3>" + $window.gridLocalization.availableColumns + "</h3>" +
        "<ul id=\"{!!divid!!}_availableColumnsList\" class=\"available-columns-list\">" +
        "<li ng-repeat=\"curColumn in $parent.availableColumns\" ng-click=\"updatecolumnselection($index, availableColumns, curColumn, $event)\" class=\"{!!curColumn.selectedClass!!}\">" +
        "{!!curColumn.text!!}" +
        "</li>" +
        "</ul>" +
        "</div>" +
        "<div id=\"{!!divid!!}_selectButtons\" class=\"select-buttons\" >" +
        "<div id=\"{!!divid!!}_selectButtonAdd\" class=\"select-button-add\" ng-click=\"addColumn()\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + $window.gridLocalization.displayColumnToolTip + "\">" +
        "</div>" +
        "<div id=\"{!!divid!!}_selectButtonRemove\" class=\"select-button-remove\" ng-click=\"removeColumn()\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + $window.gridLocalization.hideColumnToolTip + "\">" +
        "</div>" +
        "</div>" +
        "<div id=\"{!!divid!!}_selectedColumns\" class=\"selected-columns\">" +
        "<h3>" + $window.gridLocalization.selectedColumns + "</h3>" +
        "<ul id=\"{!!divid!!}_selectedColumnsList\" class=\"selected-columns-list\">" +
        "<li ng-repeat=\"curColumn in $parent.selectedColumns | filter:{text: '!null'}\" ng-click=\"updatecolumnselection($index, selectedColumns, curColumn, $event)\" class=\"{!!curColumn.selectedClass!!}\" ng-show=\"curColumn.text\">" +
        "<div class=\"column-size-increase\" ng-click=\"increaseColumnSize($index, availableColumns, curColumn, $event)\" data-toggle=\"tooltip\" data-placement=\"top\"  title=\"" + $window.gridLocalization.increaseColumnSizeToolTip + "\">+</div>" +
        "<div class=\"column-size-decrease\" ng-click=\"decreaseColumnSize($index, availableColumns, curColumn, $event)\" data-toggle=\"tooltip\" data-placement=\"top\"  title=\"" + $window.gridLocalization.decreaseColumnSizeToolTip + "\">-</div>" +
        "{!!curColumn.text!!}" +
        "</li>" +
        "</ul>" +
        "</div>" +
        "<div id=\"{!!divid!!}_reorderButtons\" class=\"reorder-buttons\">" +
        "<div id=\"{!!divid!!}_reorderButtonUp\" class=\"reorder-button-up\" ng-click=\"reorderColumnUp($index, availableColumns, curColumn, $event)\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" + $window.gridLocalization.reorderColumnUpToolTip + "\">" +
        "</div>" +
        "<div id=\"{!!divid!!}_reorderButtonDown\" class=\"reorder-button-down\" ng-click=\"reorderColumnDown($index, availableColumns, curColumn, $event)\"  data-toggle=\"tooltip\" data-placement=\"left\" title=\"" + $window.gridLocalization.reorderColumnDownToolTip + "\">" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<li id=\"{!!divid!!}_resizeCB_container\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + $window.gridLocalization.autoResizeCBToolTip + "\"><input type=\"checkbox\"  id=\"{!!divid!!}_resizeCB\" value=\"1\">" + $window.gridLocalization.autoResizeCB + "</li>" +
        "<div style=\"clear:both;\"></div>" +
        "<div id=\"{!!divid!!}\" style=\" height:100%\">" +
        "</div>" +
        "<div id=\"{!!divid!!}_gridmenu\">" +

        "</div></div>"
    };
}]);