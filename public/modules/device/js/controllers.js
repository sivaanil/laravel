angular.module('device').controller("deviceCtrl", ['$scope', '$interval', '$rootScope', '$compile', '$http', '$timeout', 'mainService', '$resource', function ($scope, $interval, $rootScope, $compile, $http, $timeout, mainService, $resource) {
    $scope.deviceTypes = [];
    $scope.deviceInfo = [];
    $scope.formData.devicePorts = [];
    $scope.snmpVers = ['1', '2c', '3'];
    $scope.snmpAuthTypeOptions = ['noAuthNoPriv','authNoPriv','authPriv'];
    $scope.snmpAuthEncryptionOptions = ['MD5', 'SHA'];
    $scope.snmpPrivacyEncryptionOptions = ['DES', 'AES'];
    $scope.deviceLocalization = deviceLocalization;
    $scope.rootScope = $rootScope;
    $scope.compile = $compile;
    $scope.serverType = window.serverType;
    $scope.deviceClassList = [];
    $scope.deviceTypeList = [];
    $scope.deviceOptionsList = [];
    $scope.templateId = "";

    $scope.loadDevice = function (id) {
        var deviceInfo = $resource(
            baseUrl + '/device/' + id,
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
        deviceInfo.get({}, function (result) {
            if (result.data == null) {
                window.location = '#/stateChange/alarms';
            } else {
                // bind to scope
                $scope.deviceInfo = result.data;
                $scope.devicePorts = result.data.ports;
                $scope.$parent.formData = result.data;
            }
        });
    };

    $scope.loadDeviceList = function () {
        var deviceList = $resource(
            baseUrl + '/deviceTypes',
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
        deviceList.query({}, function (result) {
            // bind to scope
            $scope.deviceTypes = result;
            var setDeviceType = function (result) {
                $scope.formData.deviceType = result;
                $scope.loadDeviceDefaults(result);            };
            mainService.getPreference('lastSelectedDeviceType', setDeviceType);

        });
    };

    $scope.loadDeviceDefaults = function (id) {
        $scope.loadPortInfo(id);
        var deviceInfo = $resource(
            baseUrl + '/deviceType/' + id,
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
        deviceInfo.get({}, function (result) {
            // bind to scope
            $scope.deviceInfo = result;
            $scope.formData.webUsername = result.defaultWebUiUser;
            $scope.formData.webPassword = result.defaultWebUiPw;
            $scope.formData.uses_snmp = result.uses_snmp;
            $scope.formData.snmpVer = result.defaultSNMPVer;
            $scope.formData.snmpRead = result.defaultSNMPRead;
            $scope.formData.snmpWrite = result.defaultSNMPWrite;
            $scope.formData.snmpAuthType = result.SNMPauthType;
            $scope.formData.snmpUserName = result.SNMPuserName;
            $scope.formData.snmpAuthEncryption = result.SNMPauthEncryption;
            $scope.formData.snmpAuthPassword = result.SNMPauthPassword;
            $scope.formData.snmpPrivacyEncryption = result.SNMPprivEncryption;
            $scope.formData.snmpPrivacyPassword = result.SNMPprivPassword;
        });
        if(id) {
            mainService.setPreference('lastSelectedDeviceType', id);
        }
        $scope.setDeviceAutoName(id);
    };

    $scope.setDeviceAutoName = function (id) {
        if (id == "53" ||
            id == "23" ||
            id == "1147"||
            id == "218"||
            id == "1082" ||
            id == "5000" ||
            id == "1210"){ // hide name field for ADC.. its auto filled in
            $scope.formData.deviceName = 'AUTO';
            $('#deviceName').prop( "disabled", true );
        } else {
            $scope.formData.deviceName = '';
            $('#deviceName').prop( "disabled", false );
        }
    };

    $scope.loadPortInfo = function (id) {
        var portInfo = $resource(
            baseUrl + '/deviceType/getPorts/' + id,
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
        portInfo.query({}, function (result) {
            // bind to scope
            $scope.formData.devicePorts = result;
            angular.forEach($scope.formData.devicePorts, function(devicePort) {
                devicePort.port = devicePort.default_port;
            });
        });
    };

    $scope.buildDevice = function (event, $rootScope, $compile) {

        var url = baseUrl + "/modules/device/buildprogress.html";
        onPanelLoad = function () {
            $('#buildProgressBar').jqxProgressBar({ theme: 'custom', width: 280, height: 30, value: 0, showText:true});
            $('.dialog-footer-buttons').show();
            $('#buildProgressWindow').jqxWindow({
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
            $('#buildProgressWindow').jqxWindow('open');
        };
        loadPanel('buildProgressWindowContent', url, $scope.rootScope, $scope.compile, onPanelLoad);
        $scope.refreshBuildProgressInterval=$interval(function(){
            $scope.refreshBuildProgress();
        },2000);

    };

    $scope.submitForm = function () {
        $scope.formData.deviceName =  $('#deviceName').val();
        $scope.formData.deviceType =  $('#deviceType').val();
        $scope.formData.parentNodeId = $rootScope.$nodeId;
        if ($scope.formData.parentNodeId == null) {
            $scope.formData.parentNodeId = window.homeNode;
        }
        var formData = $resource(
            baseUrl + this.dataUrl,
            null,
            {
                'post': {
                    'method': "POST",
                    'params': {

                    }
                }
            }
        );
        // submit the form data
        formData.post($scope.formData, function (result, formSubmitCallback) {
            if (typeof result.errors !== 'undefined') {
                // bind errors to scope
                $scope.formSuccess = false;
                $scope.formErrors = result.errors;
            } else {
                $scope.formSuccess = result.success;
                $scope.formErrors = [];
                // success - build the device
                if (result.success === true) {
                    $rootScope.deviceToken = result.token;
                    $scope.buildDevice();
                }
                $scope.closeBuildDeviceWindow();
            }
        });
    };

    $scope.refreshBuildProgress = function () {
        var progressInfo = $resource(
            baseUrl + '/device/buildProgress/' + $rootScope.deviceToken,
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
            $('.build-device-message').text(result.message);
            // build completed successfully
            if (result.percentage == 100) {
                // refresh the tree
                $rootScope.$broadcast('refreshTreeEvent');
                // redirect to device info page
                $rootScope.$broadcast('nodeChangeEvent', { nodeId: result.node_id });
                $rootScope.$state.go('deviceInfo', {id: result.node_id});
                $('.cancel-build-device-button').hide();
                $('.close-build-device-button').show();
                $interval.cancel($scope.refreshBuildProgressInterval);
                $scope.closeBuildDeviceWindow();
                // make sure current node is set correctly
                $rootScope.$nodeId =  result.node_id;
                // launch alarm scan
                $scope.launchScanDevice('A');
            }
            if (result.status == 3 || result.status == 4) {
                $('.cancel-build-device-button').hide();
                $('.close-build-device-button').show();
                $interval.cancel($scope.refreshBuildProgressInterval);
            }

            $('#buildProgressBar').jqxProgressBar({ value: result.percentage });
        });
    };

    $scope.cancelBuild = function () {
        var progressInfo = $resource(
            baseUrl + '/device/cancelBuild/' + $rootScope.deviceToken,
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
            // do nothing
        });
        $scope.closeBuildProgressWindow();
    };

    $scope.cancelScan = function () {
        $interval.cancel($scope.refreshScanProgressInterval);
        $scope.closeScanProgressWindow();
    };

    $scope.closeBuildDeviceWindow = function () {
        $('#buildDeviceWindow').jqxWindow('close');
    };

    $scope.closeBuildProgressWindow = function () {
        $('#buildProgressWindow').jqxWindow('close');
    };

    $scope.closeRebuildProgressWindow = function () {
        $('#rebuildProgressWindow').jqxWindow('close');
    };

    $scope.closeScanProgressWindow = function () {
        $('#scanProgressWindow').jqxWindow('close');
    };

    $scope.closeRemoveDeviceWindow = function () {
        $('#removeDeviceWindow').jqxWindow('close');
    };

    $scope.loadDeviceClasses = function () {
        $.ajax({
            url: '/deviceClass',
            method: 'GET',
            success: function (result) {
                $scope.deviceClassList = result;
                $scope.$apply();
            }
        });
    };
    
    $scope.loadDeviceTypes = function () {
        var deviceClass = $('#deviceClass').val();
        if (deviceClass !== "" && deviceClass !== "? undefined:undefined ?") {
            $.ajax({
                url: '/deviceType/getDeviceTypes/' + deviceClass,
                method: 'GET',
                success: function (result) {
                    $scope.deviceTypeList = result;
                    $scope.$apply();
                }
            });
        }
    };

    $scope.loadDeviceOptions = function () {
        var deviceType = $('#deviceType').val();
        if (deviceType !== "" && deviceType !== "? undefined:undefined ?") {
            $.ajax({
                url: '/virtualDevice/getVirtualDevices/' + deviceType,
                method: 'GET',
                success: function (result) {
                    $scope.deviceOptionsList = result;
                    $scope.formData.chosenDeviceType = deviceType;
                    $scope.$apply();
                }
            });
        }
    };

    $scope.submitPreForm = function () {
        var device = {
            deviceName: $('#deviceName').val(),
            deviceClass: $('#deviceClass').val(),
            deviceType: $('#deviceType').val(),
            deviceOptions: $('#deviceOptions').val()
        };

        $.ajax({
            url: '/device/validatePreForm',
            method: 'POST',
            dataType: 'json',
            data: device,
            success: function (result) {
                if (typeof result.errors !== 'undefined') {
                    // bind errors to scope
                    $scope.formSuccess = false;
                    $scope.formErrors = result.errors;
                } else {
                    $scope.formSuccess = result.success;
                    $scope.formErrors = [];
                    // success - proceed with configuration
                    if (result.success === true) {
                        if ($scope.formData.chosenDeviceType === '5063') {
                            // Launch the virtual device wizard
                            $scope.templateId = $('#deviceOptions').val();
                            $scope.launchVirtualDeviceWizard();
                        } else {
                           // Launch the add device dialog
                            $scope.launchBuildDevice();
                        }
                        $scope.closePreDialogWindow();
                    }
                }
            }
        });
    };
    
    $scope.launchBuildDevice = function () {
        var url = baseUrl + "/modules/device/adddevice.html";
        onPanelLoad = function() {
            $('#buildDeviceWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 900,
                maxWidth: 1200,
                minHeight: 200,
                minWidth: 200,
                height: 700,
                width: 1000,
                closeButtonAction: 'hide',
                isModal: true,
                initContent: function () {
                    $('.dialog-footer-buttons').show();
                }
            });
            $('#buildDeviceWindow').jqxWindow('open');
        };
        mainService.loadPanel('buildDeviceWindowContent', url, onPanelLoad);
    };
    
    $scope.launchVirtualDeviceWizard = function () {
        var url = "";
        if ($scope.templateId === "1") {
            url = baseUrl + "/modules/virtualGenerator/readOnlyGeneratorWizard.html";
        } else if ($scope.templateId === "2") {
            url = baseUrl + "/modules/virtualGenerator/readWriteGeneratorWizard.html";
        } else if ($scope.templateId === "3") {
            url = baseUrl + "/modules/virtualGenerator/readFuelGeneratorWizard.html";
        } else if ($scope.templateId === "4") {
            url = baseUrl + "/modules/virtualGenerator/readWriteFuelGeneratorWizard.html";
        }
        onPanelLoad = function() {
            $('#launchVirtualBuildWizard').jqxWindow({
                showCollapseButton: false,
                maxHeight: 675,
                maxWidth: 1050,
                minHeight: 275,
                minWidth: 650,
                height: 510,
                width: 850,
                isModal: true,
                initContent: function () {

                }
            });
            $('#launchVirtualBuildWizard').jqxWindow('open');
            $('#wizard').smartWizard({
                transitionEffect: 'slideleft',
                onShowStep: $scope.loadStepInfo,
                onFinish: $scope.finishCallback,
                enableFinishButton: true
            });
        };
        mainService.loadPanel('buildVirtualBuildWizardContent', url, onPanelLoad);
    };
    
    $scope.closePreDialogWindow = function () {
        $('#preDeviceWindow').jqxWindow('close');
    };
    
    $scope.loadStepInfo = function (step, context) {
        $rootScope.$broadcast('stepEvent', step, context);
    };
    
    $scope.finishCallback = function () {
        $rootScope.$broadcast('finishEvent');
    };

}]);
 