/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

angular.module('panel').controller("canvasCtrl", ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {


    $scope.source = null;
    $scope.openPanels = [{value: "Device Information", id: "angular-window-0"}];
    $scope.sectionsCount = 0;
    $scope.windowsCount = 0;
    $scope.maxSections = 6;
    $scope.docking = null;
    $scope.dockingSection = null;

    /*
     * This custom render function takes care of adding new windows to the jqxDocking
     */
    $scope.buildWindow = function (element) {

        $scope.dockingSection = $('.angular-section');
        if ($scope.sectionsCount < $scope.maxSections) {
            $scope.handleWindow();
        }

    };

    /*
     * This method will handle the new window
     */
    $scope.handleWindow = function () {

        if ($scope.dockingSection) {
            var window = $scope.dockingSection.children();
            var _id = 'angular-window-' + $scope.windowsCount;
            var id = $scope.windowsCount;

            //this has to be fixed later
            if (window[id + 1] && window[id + 1].outerHTML === '<div class="spacer" style="clear: both;"></div>') {
                window[id + 1].remove();
            }
            window[id].id = _id;
            $scope.docking.jqxDocking('addWindow', window[id].id, 'dock', id, 1);
            $scope.docking.jqxDocking('enableWindowResize', window[id].id);
            $scope.windowsCount += 1;

        }

    };

    /*
     * This method to handle the new docking sections.
     *  this function is not going to matter
     */
    $scope.handleSection = function () {
        var id = 'angular-section-' + $scope.sectionsCount;
        $scope.sectionsCount += 1;
        el.id = id;
    };


    /**
     * insert panel to the list of visible panels
     */
    $scope.menuActionControl = function (panel) {
        var id = 'angular-window-' + $scope.windowsCount;
        var obj = {value: panel.value, id: id};
        $scope.openPanels.push(obj);

    };


    /*$(function(){
     $("#goal").droppable({
     drop:function(event,ui){
     target = $(this).addClass("stone-in");
     setTimeout(function(){
     target.removeClass("stone-in");
     },1000)
     }
     })
     });*/


    $scope.getMenuButtonControls = function () {
        $scope.getControlData('/canvasCtrl/getMenuButtonControls');
        return true;
    };

    $scope.getControlData = function (url) {

        //http request block
        $http({
            method: 'GET',
            url: baseUrl + url

        }).success(function (data) {
            $scope.controls = data;
            var panelTopMenu = $("#panelTopMenu");
            panelTopMenu.jqxMenu({width: '100%', height: '100%', autoSizeMainItems: true});

        });
    };

    $scope.getAllPanels = function () {

        //http request block
        $http({
            method: 'GET',
            url: baseUrl + '/panelsList'
        }).success(function (data) {

            $scope.source = data;
            $scope.getMenuButtonControls();

        });
    };

    /*
     *  initiate docking to build the first dock
     */
    $scope.loadDefaultPanel = function () {
        $scope.docking = $('#docking');
        if ($scope.docking) {
            $scope.docking.jqxDocking({orientation: 'horizontal', width: '100%', mode: 'docked'});
        }
    };

}]);








