angular.module('alarms').controller("alarmCtrl", ['$scope', '$rootScope', '$window', '$http', '$timeout', 'mainService', function ($scope, $rootScope, $window, $http, $timeout, mainService) {

    $scope.buttonList = [];
    $scope.gridRowActions = [];
    $scope.rootScope = $rootScope;
    $scope.alarmLocalization = $window.alarmLocalization;
    $scope.jqxWindowSettings = {
        width: '100%', resizable: true, isModal: true, autoOpen: false, modalOpacity: 0.3
    };
    $scope.updateHeaderBar = function () {
        //console.log("attempting update");
        //console.log($scope.buttonList);
        for (var i = 0; i < $scope.buttonList.length; i++) {
            //console.log("doing update "+i);
            $('#alarmMenu').append($scope.buttonList[i]);
        }
    };
    $scope.moveElementToBar = function (elementId) {
        //var curElement = '<li> ' + document.getElementById(elementId).outerHTML + '</li>';
        var curElement = document.getElementById(elementId).outerHTML;
        $("#" + elementId).css('visibility', 'visible');
        $("#" + elementId).remove();
        // need to make generic
        $('#alarmMenu ul').first().append(curElement);
        //this.updateHeaderBar();
    };

    $scope.gridActionHandler = function (itemName, alarmId) {
        //console.log("I can do ALL The Actions "+ itemName);
        $scope.sendActionToServer(itemName, alarmId);
    };

    $scope.sendActionToServer = function (action, id) {
        var actionUrl = baseUrl + "/alarms/alarmAction";
        var data = {action: action, alarmId: id};

        var getFilterData = $.ajax({
            url: actionUrl,
            method: 'POST',
            data: data,
            async: false,
            success: function(resp) {
                $scope.handleMenuResult($.parseJSON(resp));
            }
        });
        //var getFilterData = $http.post(actionUrl, data);
    };

    $scope.handleMenuResult = function (data) {
        //var obj = jQuery.parseJSON(data);
        var itemName = data.action;
        if (itemName === "wedInterface") {
            mainService.launchWebInterface(data.link);
        }
        if (itemName === "scanDevice" || itemName === "alarmScan" || itemName === "propScan") {
            //TODO show something about the scan having been started
        }
        if (itemName === "stopScan" || itemName === "stopAlarmScan" || itemName === "stopPropScan") {
            //console.log(data);
            $('#stopScanWindow').jqxWindow('open');
            document.getElementById("nodeId").value = data.nodeId;
            //document.getElementById("maxDurationStopScan").value = data.maxTime.max;
            $("#maxDurationStopScan").html($("#maxDurationStopScan").html() + " " + data.maxTime.maxDisp);

            document.getElementById("monthNS").value = 0;
            document.getElementById("weekNS").value = 0;
            document.getElementById("dayNS").value = 0;
            document.getElementById("hourNS").value = 0;
            document.getElementById("minutesNS").value = 0;
            if (itemName == "stopScan") {
                document.getElementById("type").value = "scan";
            } else if (itemName == "stopAlarmScan") {
                document.getElementById("type").value = "alarm";
            } else {
                document.getElementById("type").value = "prop";
            }

            $('#stopScanWindow').on('open', $scope.setWindowSize());
        }
        if (itemName === "startScan" || itemName === "startAlarmScan" || itemName === "startPropScan") {
            if (data.enableScanRes === "true") {
                alert("Scanning has successfully been re-enabled");
            } else {
                //TODO how to handle error case
            }
        }
        if (itemName === "ignore") {

        }
        if (itemName === "unignore") {

        }
        if (itemName === "ack") {

        }
    };
    $scope.setWindowSize = function () {
        /*var titleBarHeight = 35;
         var firstFilterOption = $('#'+$scope.filterDivId+" div:first-child");
         var filterCount = $('#'+$scope.filterDivId).children().length-1;

         if(firstFilterOption !== undefined && $scope.isInt(firstFilterOption.outerHeight())) {
         $('#'+$scope.divId).jqxWindow('height', firstFilterOption.outerHeight() + titleBarHeight);
         var availablePopUpSpace = $scope.calculatePopUpWidth(firstFilterOption, filterCount);
         $('#'+$scope.divId).jqxWindow('width', availablePopUpSpace);
         }*/
    };
}]);
 