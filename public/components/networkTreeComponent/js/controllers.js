angular.module('tree').controller("treeCtrl", ['$scope', '$interval', '$rootScope', '$compile', '$http', '$timeout', '$resource', 'mainService', '$q', function ($scope, $interval, $rootScope, $compile, $http, $timeout, $resource, mainService, $q) {
    $scope.$compile = $compile;
    $scope.tree = null;
    $scope.treeInitialized = false;
    $scope.generalTreeMenu = null;
    $scope.nodeTreeMenu = null;
    $scope.contextTreeMenu = null;
    $scope._contextTreeMenu = null;
    $scope.source = [];
    $scope.generalControls = [];
    $scope.nodeControls = [];
    $scope.selectedItem = null;
    $scope.selectedItemType = "group";
    $scope.lastsynctime = "";
    $scope.timeout = "";
    $scope.dragging = false;

    $scope.dataType = "json";
    $scope.toggleMode = 'none';
    $scope.allowDrag = false;
    $scope.allowDrop = false;
    $scope.treeModel = [];
    $scope.treeFilter = [];
    $scope.hasThreeStates = false;
    $scope.hasCheckBoxes = false;
    $scope.hasMenuIcon = false;
    $scope.orderBy = "";
    $scope.reverseOrder = "";
    $scope.treeType = "lazy";

    $scope.canceller = $q.defer();

    $scope.getTreeControls = function () {
        $scope.getControlData('/networkTree/getTreeControls');
        return true;
    };

    $scope.lazyLoadTree = function () {
        $scope.loadTree('/networkTree/loadFirstLevel');
        return true;
    };

    $scope.fullLoadTree = function () {
        $scope.getTreeData('/networkTree/loadAllLevel');
        return true;
    };

    $scope.nodeType = function () {
        if ($scope.selectedItem && $scope.selectedItem.label) {
            var type = $scope.selectedItem.icon;
            if (type.search("device") === -1) {
                $scope.selectedItemType = "group";
            } else {
                $scope.selectedItemType = "device";
            }
        }

        if (!$scope.$$phase) {
            $scope.$apply();
        }
    };

    $scope.actionControl = function (message) {
        switch (message.event) {
            case 'expandItem':
                $scope.expandItem();
                break;
            case 'collapseItem':
                $scope.collapseItem();
                break;
            case 'addDevice':
                $scope.addItem('device');
                break;
            case 'scanDevice':
                break;
            case 'addGroup':
                $scope.addItem('group');
                break;
            case 'editItem':

                break;
            case 'removeItem':
                $scope.removeItem();
                break;
            default:
                break;
        }
    };

    $scope.expandItem = function () {
        if ($scope.selectedItem) {
            $scope.tree.jqxTree('expandItem', $scope.selectedItem);

        }
    };

    $scope.collapseItem = function () {
        if ($scope.selectedItem) {
            $scope.tree.jqxTree('collapseItem', $scope.selectedItem);
        }
    };

    $scope.dragStart = function (item) {

        var success = true;
        if (item === null) {
            alert("Cannot move item.");
            success = false;
        } else if (item.type === "device" && item.mainDevice === "0") {
            alert("Cannot move a Sub-Device.");
            success = false;
        }

        return success;
    };

    $scope.dragEnd = function (item, dropItem, args, dropPosition, tree) {

        var success = true;
        if (item === null) {
            alert("Cannot move item.");
            success = false;
        } else if (dropItem == null) {
            alert("Cannot drop item here.");
            success = false;
        } else if (item.nodeId === dropItem.nodeId) {
            alert("Cannot move item to itself.");
            success = false;
        } else if (!$scope.checkDestinationTree(dropItem, item)) {
            alert("Cannot move Parent node into a Child node.");
            success = false;
        } else if ($scope.checkHierarchy(dropItem, item) && dropItem.type === "group") {
            alert("Item already resides in this group.");
            success = false;
        } else if (item.inheritsIpAddress === "true" && (item.parentClassId !== 'null' && item.parentClassId !== "5" && item.parentClassId !== "25")) {
            alert("Cannot move a Sub-Device.");
            success = false;
        } else if ((dropItem.type !== "group") && (dropItem.deviceClass !== "Modem") && (dropItem.classId !== "25")) {
            alert("Destination is not a Group or Compatible Device.");
            success = false;
        } else if ((dropItem.type !== "group") && (dropItem.deviceClass === "Modem") && ( item.deviceClass === "Modem")) {
            alert("Cannot move Modem into a Modem.");
            success = false;
        } else if ((dropItem.type !== "group") && (dropItem.deviceClass === "Power Outlet") && ( item.deviceClass === "Power Outlet")) {
            alert("Cannot move a Power Outlet into a Power Outlet.");
            success = false;
        } else if ((dropItem.typeId === "1292") && ( item.type === "group" )) {
            alert("Cannot move a folder into a Power Outlet.");
            success = false;
        } else if ((dropItem.typeId === "1292") && ( dropItem.items.length > 0 )) {
            alert("Cannot move more than one device into one Power Outlet.");
            success = false;
        } else if ((dropItem.ipAddress !== 'null' && dropItem.ipAddress !== item.ipAddress) && dropItem.classId !== "25") {
            alert("In order to move this device to this location, the IP addresses of the device and modem must be the same.");
            success = false;
        }

        $scope.tree.jqxTree("refresh");

        return success;

    };

    $scope.checkDestinationTree = function (dropItem, item) {
        if (dropItem.parentNodeId == null) {
            return true;
        }
        if (dropItem.parentNodeId == item.nodeId) {
            return false;
        }

        return true;
        //return $scope.checkDestinationTree(dropItem.parent, item); // deal with this
    };

    $scope.checkHierarchy = function (dropItem, item) {

        if (dropItem.nodeId == item.parentNodeId) {
            return true;
        }
        return false;
    };

    $scope.dataSync = function () {
        $http({
            method: 'GET',
            url: baseUrl + '/networkTree/syncServer?lastsynctime=' + $scope.lastsynctime
        }).success(function (data) {

            var existingLiElements = $scope.tree.jqxTree('getItems');

            angular.forEach(data.updated, function (updatedNode) {

                for (var i = 0; (i < existingLiElements.length); i++) {
                    var existingLiElement = existingLiElements[i];
                    if (existingLiElement.value == updatedNode.value) {
                        existingLiElement.label = updatedNode.label;
                        existingLiElement.icon = updatedNode.icon;
                    }
                }
            });

            angular.forEach(data.deleted, function (deletedNode) {

                for (var i = 0; (i < existingLiElements.length); i++) {
                    var existingLiElement = existingLiElements[i];
                    if (existingLiElement.value == deletedNode.value) {
                        var stop = null;
                    }
                }
            });


            $scope.lastsynctime = data.lastsynctime;
        });
        $scope.timeout = $timeout($scope.dataSync, 90000);
    };

    //$scope.timeout = $timeout($scope.dataSync,90000);

    $scope.cancelDataSync = function () {
        $timeout.cancel($scope.timeout);
    };

    $scope.loadTree = function (url) {
        $scope.getTreeData(url, $scope.initializeTree);
    }

    $scope.initializeTree = function (data) {
        $scope.source = data.nodes;
        $scope.lastsynctime = data.lastsynctime;

        $scope.tree = $('#netTree');

        if ($scope.tree) {
            $scope.tree.css('visibility', 'visible');
            $scope.tree.jqxTree({
                source: $scope.source,
                theme: 'bootstrap',
                allowDrag: $scope.allowDrag,
                allowDrop: $scope.allowDrop,
                width: '100%',
                height: '98%',
                toggleMode: $scope.toggleMode,
                hasThreeStates: $scope.hasThreeStates,
                checkboxes: $scope.hasCheckBoxes,
                //hasMenuIcon: $scope.hasMenuIcon,
                dragStart: function (item) {
                    $scope.dragging = true;
                    return $scope.dragStart(item);
                },
                dragEnd: function (item, dropItem, args, dropPosition, tree) {
                    if (dropPosition !== 'inside') {
                        return false;
                    }
                    $scope.tree.jqxTree('expandItem', dropItem);

                    $scope.dragging = false;

                    return $scope.dragEnd(item, dropItem, args, dropPosition, tree);
                }
            });
            setHeight();

            var selectedLabel = $.jqx.cookie.cookie("jqxTree");

            if (selectedLabel) {
                var items = $scope.tree.jqxTree('getItems');
                $.each(items, function () {
                    if (this.label == selectedLabel) {
                        $scope.selectedItem = this.element;
                    }
                });
            } else {
                $scope.selectedItem = $scope.tree.find('li:first')[0];
            }
            if(!$scope.treeInitialized) {
                $scope.initializeTreeEventListeners();
                $scope.treeInitialized = true;
                $scope.selectNode();
            }
        }

    };

    $scope.initializeTreeEventListeners = function() {
        $scope.tree.jqxTree('selectItem', $scope.selectedItem);
        $scope.tree.jqxTree('expandItem', $scope.selectedItem);

        $scope.tree.on('expand', function (event) {
            $scope.expandNode(event);
        });

        $scope.tree.on('select', function (event) {
            $scope.selectNode(event);
        });

        $scope.$on('nodeChangeEvent', function (event, args) {
            $scope.changeNode(args.nodeId);
        });
    }

    $scope.getTreeData = function (url, callBack) {
        //http request block
        $http({
            method: 'GET',
            url: baseUrl + url
        }).success(function (data) {
            callBack(data);
        }).error(function (data, status, headers, config) {
            alert("Error loading Tree");
        });
    };

    $scope.getControlData = function (url) {
        //http request block
        $http({
            method: 'GET',
            url: baseUrl + url

        }).success(function (data) {
            $scope.generalControls = data[0];
            $scope.nodeControls = data[1];

            $scope.generalTreeMenu = $("#generalTreeMenu");
            $scope.generalTreeMenu.jqxMenu({width: '100%', height: '100%'});
            $scope.generalTreeMenu.css('background', '#ffffff');

            $scope.nodeTreeMenu = $("#nodeTreeMenu");
            $scope.nodeTreeMenu.jqxMenu({width: '100%', height: '100%'});
            $scope.nodeTreeMenu.css('background', '#ffffff');

            $scope.contextTreeMenu = $("#contextTreeMenu");
            $scope._contextTreeMenu = $scope.contextTreeMenu.jqxMenu({
                width: '120',
                height: '150',
                autoOpenPopup: false,
                mode: 'popup'
            });
            $scope.contextTreeMenu.css('background', '#ffffff');


        });
    };

    $scope.refreshNetworkTree = function () {
        $scope.loadTree('/networkTree/loadFirstLevel');
    };

    $scope.expandNode = function (event) {
        if($scope.treeType === "full"){
            return;
        }

        if($scope.tree.jqxTree('getItem', event.args.element) === null){
            return;
        }
        var label = $scope.tree.jqxTree('getItem', event.args.element).label;
        var nodeId = $scope.tree.jqxTree('getItem', event.args.element).value;
        var $element = $(event.args.element);
        var loader = false;
        var loaderItem = null;
        var children = $element.find('ul:first').children();
        $.each(children, function () {
            var item = $scope.tree.jqxTree('getItem', this);
            if (item && item.label == 'Loading...') {
                loaderItem = item;
                loader = true;
                return false
            }
        });
        if (loader) {
            $http({
                method: 'GET',
                url: baseUrl + '/networkTree/loadNextLevel?nodeId='+nodeId
            }).success(function(data) {
                var items = data.nodes;
                $scope.lastsynctime = data.lastsynctime;
                $scope.tree.jqxTree('addTo', items, $element[0]);
                // remove "Loading..."
                $scope.tree.jqxTree('removeItem', loaderItem.element);

                if ($scope.newSelectedItem) {
                    $scope.changeNode($scope.newSelectedItem);
                }

            });
        }
    };

    $scope.selectNode = function (event) {
        $scope.selectedItem = $scope.tree.jqxTree('selectedItem');
        if ($scope.selectedItem != null) {
            var nodeId = $scope.tree.jqxTree('selectedItem').value;
            $rootScope.$state.go($rootScope.$state.current.name, {id: nodeId});
            $scope.nodeType();
            /*
             if ($scope.selectedItem.label.search("device") !== -1) {
             var item = $scope.selectedItem;
             var foundClosestGroup = false;
             while (item != null && foundClosestGroup === false) {
             item = $scope.tree.jqxTree('getPrevItem', item);

             if (item != null && item.label.search("device") === -1 && item.isExpanded === true) {
             foundClosestGroup = true;
             }
             }
             $scope.selectedItem = item;
             }
             */

            $.jqx.cookie.cookie("jqxTree", $scope.selectedItem.label, null);

            $http({
                method: 'GET',
                url: baseUrl + '/networkTree/getNodeInformation?nodeId=' + nodeId

            }).success(function (data) {
                $scope.selectedItem = data[0];
                $scope.updateButtons();

            });
        }
    };

    $scope.changeNode = function (nodeId) {
        if (nodeId != null) {
            // cancel any previous requests to avoid timing issues / endless loops
            $scope.canceller.resolve();
            $scope.canceller = $q.defer();

            $scope.newSelectedItem = nodeId;

            // retrieve the node's breadcrumb
            $http({
                method: 'GET',
                url: baseUrl + '/breadcrumbData/' + nodeId,
                timeout: $scope.canceller.promise

            }).success(function (data) {
                angular.forEach(data, function(value, key) {
                    currentItem = $scope.findNode(value.hyperlink);
                    if (currentItem) {
                        if (key < data.length - 1) { // intermediate node - expand it
                            $('#netTree').jqxTree('expandItem', currentItem);
                            return;
                        } else { // leaf node - select it (don't expand it)
                            $scope.newSelectedItem = null;
                            $('#netTree').jqxTree('selectItem', currentItem);
                            // scroll the selected item into view
                            if (!$scope.checkIfInView($('.jqx-tree-item-selected')[0])) {
                                $('#netTree').scrollTo($('.jqx-tree-item-selected'));
                            }
                        }
                    } else {
                        // can't find it - expand the previous item
                        $('#netTree').jqxTree('expandItem', prevItem);
                    }
                    var prevItem = currentItem;
                });
            });
        }
    };

    $scope.checkIfInView = function(element) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();
        var elemTop = $(element).offset().top;
        return ((elemTop <= docViewBottom) && (elemTop >= docViewTop));
    };

    $scope.findNode = function (nodeId) {
        var items = $('#netTree').jqxTree('getItems');
        for (var i = 0; i < items.length; i++) {
            var currentItem = items[i];
            if (currentItem.value == nodeId) {
                // item found
                return currentItem;
            }
        }
        // item not found
        return false;
    };

    // hide and show the action buttons based on node types
    $scope.updateButtons = function() {
        // show buttons
        $('.network-tree-action-buttons').show();

        // if it's a device and has a web interface, show the web interface button
        if ($scope.selectedItem.type == 'device' && $scope.selectedItem.hasWeb == 1) {
            $('.btn-launch-web-interface').show();
        } else {
            $('.btn-launch-web-interface').hide();
        }
        // if it's a SiteGate, and we're on the SiteGate environment, disable the launch web button
        if(window.serverType == 'sitegate' && $scope.selectedItem.label == 'SiteGate') {
            $('.btn-launch-web-interface').hide();
            $('.btn-launch-web-interface-sitegate-disabled').show();
        } else {
            $('.btn-launch-web-interface').show();
            $('.btn-launch-web-interface-sitegate-disabled').hide();
        }
        // if it's a group, disable the launch web button
        if($scope.selectedItem.type == 'group') {
            $('.btn-launch-web-interface').hide();
            $('.btn-launch-web-interface-group-disabled').show();
        } else {
            $('.btn-launch-web-interface-group-disabled').hide();
        }
        // if it's a group or a SiteGate, show add device button
        if($scope.selectedItem.type == 'group' || $scope.selectedItem.label == 'SiteGate') {
            $('.btn-add-device').show();
        } else {
            $('.btn-add-device').hide();
        }
        // if it's a main device, and not a SiteGate in a SiteGate environment, show scanning and remove buttons
        if ($scope.selectedItem.mainDevice == 1 && $scope.selectedItem.type == 'device' &&  !($scope.selectedItem.label == 'SiteGate' && window.serverType == 'sitegate')) {
            $('.btn-remove-device').show();
            $('.btn-scan-device').show();
            if ($scope.selectedItem.hasPropScan == true) {
                $('.btn-scan-properties').show();
            } else {
                $('.btn-scan-properties').hide();
            }
        } else {
            $('.btn-remove-device').hide();
            $('.btn-scan-device').hide();
        }
        // if it's a device and has a rebuilder, show the rebuilder button
        if ($scope.selectedItem.type == 'device' && $scope.selectedItem.hasRebuilder == true) {
            $('.btn-rebuild-device').show();
        } else {
            $('.btn-rebuild-device').hide();
        }

        setHeight();
    };


    $scope.launchBuildDevice = function (event, $rootScope, $compile) {
        var url = baseUrl + "/modules/device/addDeviceDialog.html";
        onPanelLoad = function() {
            $('#preDeviceWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 900,
                maxWidth: 1200,
                minHeight: 200,
                minWidth: 200,
                height: 350,
                width: 500,
                closeButtonAction: 'hide',
                isModal: true,
                initContent: function () {
                    $('.dialog-footer-buttons').show();
                }
            });
            $('#preDeviceWindow').jqxWindow('open');
        };
        mainService.loadPanel('preDeviceWindowContent', url, onPanelLoad);
    };

    $scope.launchScanDevice = function (scanType) {
        var url = baseUrl + "/modules/device/scanprogress.html";
        onPanelLoad = function () {
            $('#scanProgressWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 220,
                maxWidth: 420,
                minHeight: 220,
                minWidth: 320,
                height: 220,
                width: 320,
                showCloseButton: false,
                isModal: true,
                initContent: function () {
                }
            });
            $('#scanProgressWindow').jqxWindow('open');
            $('#scanProgressBar').jqxProgressBar({ theme: 'custom', width: 280, height: 30, value: 0, showText:true});
        };
        mainService.loadPanel('scanProgressWindowContent', url, onPanelLoad);

        $.ajax({
            type: 'GET',
            url:  baseUrl + "/device/startScan/" + $rootScope.$nodeId + '/' + scanType,
            success: function(result) {
                $scope.scanId = result.scanId;

            }
        });

        $scope.refreshScanProgressInterval=$interval(function(){
            $scope.refreshScanProgress();
        },2000);
        $('#scanProgressWindow').jqxWindow('open');

    };

    $scope.refreshScanProgress = function () {
        var progressInfo = $resource(
            baseUrl + '/device/scanProgress/' + $scope.scanId,
            null,
            {
                'get': {
                    'method': "GET",
                    'params': {

                    }
                }
            }
        );
        // get the form data
        progressInfo.get({}, function (result) {
            // bind to scope
            $('.scan-device-message').text(result.message);
            // scan is done or error - stop polling scanning, hide the cancel button, show the close button
            if (result.status == 3 || result.status == 4) {
                $interval.cancel($scope.refreshScanProgressInterval);
                $('.cancel-scan-device-button').hide();
                $('.close-scan-device-button').show();
            }

            $('#scanProgressBar').jqxProgressBar({ value: result.percentage });
        });
    };

    $scope.launchRebuildDevice = function () {
        var url = baseUrl + "/modules/device/rebuildprogress.html";
        onPanelLoad = function () {
            $('#rebuildProgressWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 220,
                maxWidth: 420,
                minHeight: 220,
                minWidth: 320,
                height: 220,
                width: 320,
                showCloseButton: false,
                isModal: true,
                initContent: function () {
                }
            });
            $('#rebuildProgressWindow').jqxWindow('open');
        };
        mainService.loadPanel('rebuildProgressWindowContent', url, onPanelLoad);

        $.ajax({
            type: 'GET',
            url:  baseUrl + "/device/startRebuild/" + $rootScope.$nodeId + '/',
            success: function(result) {
                $scope.rebuildId = result.rebuildId;

            }
        });

        $scope.refreshRebuildProgressInterval=$interval(function(){
            $scope.refreshRebuildProgress();
        },2000);
        $('#rebuildProgressWindow').jqxWindow('open');

    };

    $scope.refreshRebuildProgress = function () {
        var progressInfo = $resource(
            baseUrl + '/device/rebuildProgress/' + $scope.rebuildId,
            null,
            {
                'get': {
                    'method': "GET",
                    'params': {

                    }
                }
            }
        );
        // get the form data
        progressInfo.get({}, function (result) {
            // rebuild is done - stop polling rebuild, hide the cancel button, show the close button
            if (result.status == 2) {
                $('.rebuild-device-message').text('Device rebuild completed.');
                $interval.cancel($scope.refreshRebuildProgressInterval);
                $('.close-rebuild-device-button').show();
                // hide in-progress spinner
                $('.rebuild-progress-spinner').hide();
                // refresh network tree
                $scope.refreshNetworkTree();
                // launch alarm scan
                $scope.launchScanDevice('A');
            }
        });
    };


    $scope.launchWebInterface = function () {
        var url = baseUrl + "/device/webInterface/" + $rootScope.$nodeId;
        $.ajax({
            url: url,
            async: false,
            success: function(resp) {
                mainService.launchWebInterface(resp);
            }
        });
    };

    $scope.launchRemoveDevice = function() {
        var url = baseUrl + "/modules/device/removedevice.html";
        onPanelLoad = function () {
            $('#removeDeviceWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 220,
                maxWidth: 420,
                minHeight: 220,
                minWidth: 320,
                height: 160,
                width: 320,
                showCloseButton: false,
                isModal: true,
                initContent: function () {
                }
            });
            $('#removeDeviceWindow').jqxWindow('open');
        };
        mainService.loadPanel('removeDeviceWindowContent', url, onPanelLoad);

    };

    $scope.removeDevice = function() {
        $.ajax({
            type: 'GET',
            url:  baseUrl + "/device/remove/" + $rootScope.$nodeId,
            success: function(result) {
                $('#removeDeviceWindow').jqxWindow('close');
                $scope.refreshNetworkTree();
            }
        });
    }


}]);
