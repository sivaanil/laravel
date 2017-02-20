angular.module('filter').controller("filterCtrl", ['$scope', '$rootScope', '$http', 'mainService', function ($scope, $rootScope, $http, mainService) {

    $scope.initFilter = function () {
        var filterUrl = baseUrl + "/filter";

        var data = JSON.stringify({module: $scope.module, nodeId: $scope.nodeid, includeCounts: false});

        $scope.jqxWindowSettings = "{" +
            "width: '100%', resizable: true, isModal: false, autoOpen: false, modalOpacity: 0.3" +
            "}";

        var getFilterData = $http.post(filterUrl, data);
        getFilterData.success(function (data, status, headers, config) {
            $scope.filterObj = data;
            $scope.filterList = $scope.formatData(data);
            // reset the popup window size once data loads, in case it was opened while data was loading
            setTimeout(function () {
                $scope.setWindowSize();
            }, 100);

            // once filters have loaded, another fetch for counts (because slow)
            $scope.getFilterCounts();
        });
        // initialize the menu button for the popup filter window
        setTimeout(function () {
            $scope.filterPopUpOpenButtonInit();
        }, 100);
        $scope.$on('nodeChangeEvent', function (event, args) {
            $scope.getFilterCounts();
        });
    };

    $scope.getFilterCounts = function() {
        var filterCountUrl = baseUrl + "/filter";
        var countData = JSON.stringify({module: $scope.module, nodeId: $rootScope.$nodeId, includeCounts: true});
        var getCountData = $http.post(filterCountUrl, countData);
        getCountData.success(function (data, status, headers, config) {
            $scope.applyCountData(data);
            $scope.setWindowSize();
        });
    }

    $scope.filterPopUpOpenButtonInit = function () {
        // move it to the bar
        if ($scope.theMenuParent !== false) {
            $scope.theMenuParent.moveElementToBar($scope.showWindowButton);
        }

        $('#alarmMenu').on('itemclick', function (event) {
            if (event.target.id == $scope.showWindowButton) {
                $('#' + $scope.divId).jqxWindow('open');
                $('#' + $scope.divId).on('open', $scope.setWindowSize());
            }
        });
    };

    /*
     * Size the popup filter window based on contents
     */
    $scope.setWindowSize = function () {
        var titleBarHeight = 35;
        var firstFilterOption = $('#' + $scope.filterDivId + " div:first-child");
        var filterCount = $('#' + $scope.filterDivId).children().length - 1;

        if (firstFilterOption !== undefined && $scope.isInt(firstFilterOption.outerHeight())) {
            var heights = $('#' + $scope.filterDivId + " div").map(function () {
                    return $(this).height();
                }).get(),
                maxHeight = Math.max.apply(null, heights);
            $('#' + $scope.divId).jqxWindow('height', maxHeight + titleBarHeight + 10);
            var availablePopUpSpace = $scope.calculatePopUpWidth(firstFilterOption, filterCount);
            if (availablePopUpSpace < 200) {
                availablePopUpSpace = 300;
            }
            $('#' + $scope.divId).jqxWindow('minWidth', '200');
            $('#' + $scope.divId).jqxWindow('maxWidth', availablePopUpSpace);
            $('#' + $scope.divId).jqxWindow('width', availablePopUpSpace);
            $('#' + $scope.divId).jqxWindow('position', 'middle, center');
        }
    };

    $scope.isInt = function (n) {
        return Number(n) === n && n % 1 === 0;
    };

    $scope.calculatePopUpWidth = function (options, count) {
        var childWidth = options.outerWidth();
        var totalWidth = childWidth * count + 15;
        if (totalWidth < $(window).width()) {
            return totalWidth;
        } else {
            //Some math
            //no partial children
            var numChildrenThatCanBeDisplayed = Math.floor(($(window).width()) / (childWidth + 15));
            //Make sure it isn't 0
            numChildrenThatCanBeDisplayed = Math.max(1, numChildrenThatCanBeDisplayed);//numChildrenThatCanBeDisplayed===0?1:numChildrenThatCanBeDisplayed;

            return numChildrenThatCanBeDisplayed * (childWidth + 15);
        }
    };

    $scope.initIndeterminateState = function (filterList) {
        var i = 0;
        while (filterList[i] !== undefined) {
            $scope.setGroupState(filterList[i]);
            i++;
        }
    };

    $scope.formatData = function (rawData) {
        var cur;
        var res = {};
        var groupNum = -1;
        var childNum;
        for (var i = 0; i < rawData.length; i++) {
            cur = rawData[i];
            if (cur.isParent) {
                groupNum++;
                childNum = 0;
                res[groupNum] = {};
                res[groupNum].filter_id = cur.id;
                res[groupNum].id = cur.numId;
                res[groupNum].count = cur.count;
                res[groupNum].disp = cur.disp;
                res[groupNum].value = true;//cur.state==="true";
                res[groupNum].list = [];
            } else {
                //console.log("CHILD "+childNum);
                res[groupNum].value = res[groupNum].value && cur.state === "true";
                res[groupNum].list[childNum] = {};
                res[groupNum].list[childNum].filter_id = cur.id;
                res[groupNum].list[childNum].id = cur.numId;
                res[groupNum].list[childNum].count = cur.count;
                res[groupNum].list[childNum].disp = cur.disp;
                res[groupNum].list[childNum].value = cur.state === "true";
                childNum++;
            }
        }
        return res;
    };

    // append counts to checkbox labels
    $scope.applyCountData = function (rawData) {
        var cur;
        for (var i = 0; i < rawData.length; i++) {
            cur = rawData[i];
            if (!cur.isParent) { // parents don't get counts
                $('#' + $scope.module + 'sFilter_' + cur.id + '_CB').parent().find('.filter-count').text(' (' + cur.count + ')');
            }
        }
    };

    $scope.checkBoxChangeAction = function (group, changeCB) {
        //TODO modify parent if child has changed, modify children if parent has changed
        var i;
        var cur;
        var checkbox = document.getElementById($scope.divId + "_" + group.filter_id + "_CB");

        if (changeCB !== undefined) {
            //child CB was clicked
            //Loop over children if 1 is false and 1 is true parent is indeterminate and value = false
            $scope.setGroupState(group);
        } else {
            for (i = 0; i < group.list.length; i++) {
                cur = group.list[i];
                cur.value = group.value;
            }
            checkbox.indeterminate = false;
        }

    };

    //Indeterminate is when 
    $scope.setGroupState = function (group) {
        //if all are true parent is true
        //if all are false parent indeterminate is false
        var atLeastOneChecked = false;
        var selectedValue = true;
        var cur;
        var checkbox = document.getElementById($scope.divId + "_" + group.filter_id + "_CB");
        if (checkbox === null) {
            return;
        }

        for (i = 0; i < group.list.length; i++) {
            cur = group.list[i];
            selectedValue = selectedValue && cur.value;
            atLeastOneChecked = atLeastOneChecked || cur.value;
        }
        group.value = selectedValue;
        checkbox.indeterminate = !(selectedValue === atLeastOneChecked);
    };

    /*
     * Show/hide cleared time column based on whether cleared alarms are being shown
     * This can be reworked smarter once we have user preferences etc
     */
    $scope.showHideTimeClearedColumn = function () {
        // remember the Auto Resize state so we can set it back to how it was
        var wasAutoResizeChecked = $('#alarmsGrid_resizeCB').prop('checked');
        if ($('#alarmsFilter_cleared_CB').prop('checked')) {
            if (!$("#alarmsGrid").jqxGrid('iscolumnvisible', 'clear')) {
                $("#alarmsGrid").jqxGrid('showcolumn', 'clear');
            }
            if (wasAutoResizeChecked) {
                //$('#alarmsGrid_resizeCB').attr('checked', 'checked');
            }
        } else {
            if ($("#alarmsGrid").jqxGrid('iscolumnvisible', 'clear')) {
                $("#alarmsGrid").jqxGrid('hidecolumn', 'clear');
            }
            if (wasAutoResizeChecked) {
                $('#alarmsGrid_resizeCB').attr('checked', 'checked');
            }
        }
    };

}]);