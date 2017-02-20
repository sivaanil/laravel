angular.module('menu').controller("menuCtrl", ['$scope', '$http', '$timeout', 'mainService', function ($scope, $http, $timeout, mainService) {
    $scope.activeTab = null;
    $scope.createMenu = function (pData) {
        var source = [];
        //this.menu.removeAll();
        for (i = 0; i < pData.length; i++) {
            var childrenMenu = undefined;
            if (typeof pData[i]['children'] != 'undefined') {

                childrenMenu = [];
                for (var j = 0; j < pData[i]['children'].length; j++) {
                    if (pData[i]['children'][j].enabled) {
                        childrenMenu[j] = [];
                        childrenMenu[j].html = $scope.createMenuItem(pData[i]['children'][j]['dispText'],
                            pData[i]['children'][j]['command'],
                            false,
                            "",
                            undefined,
                            true,
                            pData[i]['children'][j]['target'],
                            pData[i]['children'][j].icon);
                    }
                }
            }
            if (pData[i].enabled) {
                var currentMenuItem = $scope.createMenuItem(pData[i].dispName, pData[i].navigateTo, pData[i].active, "", childrenMenu, false, pData[i].icon);
                source[i] = [];
                source[i].html = currentMenuItem;
                source[i].id = "menuItem" + i;
                if (typeof childrenMenu != 'undefined') {
                    source[i].items = childrenMenu;
                }
            }
        }
        $('#' + $scope.divId).jqxMenu({source: source});

    };

    $scope.createMenuItem = function (name, link, isActive, html, items, isChild, target, icon, enabled) {
        var clickableItemMenuWidth = largestMenuWidth + 75;
        // custom html
        if (html) {
            return html;
        }
        // parent
        if (isChild != true) {
            html = "<div class=\"-custom-parent\">" +
                "<a href=" + link + ">" + name + "</a>" + "</div>";
        }
        // child
        else if (typeof target != 'undefined' && target.length > 0) {
            html = "<div style=' width:" + clickableItemMenuWidth + "px; display:table-cell; position:relative;  vertical-align : middle; cursor: pointer;' class='custom-menu-entry' onclick='callLink(\"" + link.replace(/\"/g, '\\\"') + "\",\"" + target + "\")'>" +
                "<img class=\"custom-menu-image\" src=\"../img/icons/" + icon + "\">" +
                "<label>" + name + "</label>" + "</div>";
        }
        // ??
        else {
            html = "<div style=' width:" + clickableItemMenuWidth + "px; display:table-cell;  position:relative; vertical-align : middle; cursor: pointer;' class='custom-menu-entry' onclick='callLink(\"" + link.replace(/\"/g, '\\\"') + "\")'>" +
                "<img class=\"custom-menu-image\" src=\"../img/icons/" + icon + "\">" +
                "<label>" + name + "</label>" + "</div>";
        }
        return html;
    };

    $scope.initMenuComponent = function () {
        this.activePage = 'selction';
        if (typeof $scope.dataUrl != 'undefined') {
            $.ajax({
                type: 'GET',
                url: baseUrl + $scope.dataUrl,
                data: {nodeId: this.nodeId, activePage: this.activePage},

                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    $scope.createMenu(obj);
                    $('#' + $scope.divId).jqxMenu({height: '32px', theme: 'custom', autoSizeMainItems: true});
                    $scope.adjustMenuSize();//the manu items have changed recalculate its size
                    $scope.adjustMenuWidth();//change the size and update classes
                }
            });
        } else {
            $('#' + $scope.divId).on('initialized', function () {
                $scope.adjustMenuSize();//the manu items have changed recalculate its size
                $scope.adjustMenuWidth();//change the size and update classes
            });
        }
    };

    $scope.adjustMenuWidth = function () {
        $scope.adjustMenuSize();
        if (($(window).width() - $('#jqxSearchMenu').outerWidth()) < menuWidth + 5
            || ($scope.divId == 'alarmMenu' && $('#alarmTitleBar').width() < 1150 )
            || ($scope.divId == 'alarmTabMenu' && $('#alarmTitleBar').width() < 740 )) {
            $('#' + $scope.divId).jqxMenu('minimize');
            $('#' + $scope.divId).jqxMenu('width', '44px');
            $('#' + $scope.divId).jqxMenu('autoCloseOnClick', true);
            document.getElementById("jqxMainMenu").style.position = "relative";
            if ($scope.divId == 'alarmTabMenu') {
                $('#alarmTabMenuContainer').addClass('container-minimized');
            }
            restoreActiveTab();
        } else {
            $('#' + $scope.divId).jqxMenu('restore');
            if ($scope.divId == 'alarmTabMenu') {
                $('#alarmTabMenuContainer').removeClass('container-minimized');
            }
            document.getElementById($scope.divId).style.position = "absolute";
            document.getElementById($scope.divId).style.width = "";
            document.getElementById($scope.divId).style.height = "30px";
            document.getElementById($scope.divId).style.left = "0px";
            document.getElementById($scope.divId).style.right = $('#jqxSearchMenu').outerWidth() + 'px';

            restoreActiveTab();
        }
        colorActiveMenuItem();
    };

    $scope.adjustMenuSize = function () {
        prevMenuWidth = menuWidth;
        menuWidth = 0;
        largestMenuWidth = 0;
        var menuUl = $('#' + $scope.divId).children(0);
        if (typeof menuUl != "undefined") {
            menuUl.children(true).each(function () {
                menuWidth += $(this).outerWidth(true);
                if (largestMenuWidth < $(this).outerWidth(true)) {
                    largestMenuWidth = $(this).outerWidth(true);
                }
            });
        } else {
            $('#menuItem0').parent(true).children(true).each(function () {
                menuWidth += $(this).outerWidth(true);
                if ($(this).outerHeight(true) > 35) {
                    /* If the texts goes to 2 rows more needs to be added .7 is a random number that seems to work
                     without this clicking the same node at a medium window width prevent resize from triggerign
                     If the menu was previously minimized */
                    menuWidth += $(this).outerWidth(true) * .7;
                }
                if (largestMenuWidth < $(this).outerWidth(true)) {
                    largestMenuWidth = $(this).outerWidth(true);
                }
            });
        }
        // if the width is narrower than previous calculated width, discard it
        if (menuWidth < prevMenuWidth) {
            menuWidth = prevMenuWidth;
        }
    };

    $scope.refreshBreadcrumb = function () {
        var menuRefreshUrl = baseUrl + "/refreshBreadcrumb";
        var nodeData = JSON.stringify({nodeId: $scope.nodeId});
        var getMenuData = $http.post(menuRefreshUrl, nodeData);
        getMenuData.success(function (data, status, headers, config) {
            $("#breadcrumbContainer").replaceWith(data);
            initScrollingBreadcrumb();
            widthCheckForBreadcrumbButtons();
        });
    };

    function colorActiveMenuItem() {
        var pressedTheme = "jqx-fill-state-pressed-custom";
        if (document.getElementById("menuitemActive") != null) {
            document.getElementById("menuitemActive").className += " " + pressedTheme;
        }
    }

    $scope.waitForFinalEvent = (function (callback, ms, uniqueId) { //This is also in alarm.js move it to a common js lib
        var timers = {};
        return function (callback, ms, uniqueId) {
            if (!uniqueId) {
                uniqueId = "Don't call this twice without a uniqueId";
            }
            if (timers[uniqueId]) {
                clearTimeout(timers[uniqueId]);
            }
            timers[uniqueId] = setTimeout(callback, ms);
        };
    })();

    $(window).resize(function () {
        if (typeof $scope.divId != 'undefined') {
            $scope.waitForFinalEvent(function () {
                $scope.adjustMenuWidth();
            }, 100, "menu");
        }
    });

    /*******
     * Tab menus
     ********/
    /*
     * once the functionality is nailed down this should be all be modularized
     * add db table:
     * def_filter_presets
     * fields: 
     *   filter_module
     *   filter_id
     *   filter_state
     */


    function checkCB(id) {
        if ($("#" + id).prop('checked') !== true) {
            $("#" + id).click();
        }
    }

    function uncheckCB(id) {
        if ($("#" + id).prop('checked') === true) {
            $("#" + id).click();
        }
    }

    function resetTabColors() {
        $('#tab-all-alarms').removeClass('active-tab');
        $('#tab-active-alarms').removeClass('active-tab');
        $('#tab-cleared-alarms').removeClass('active-tab');
        $('#tab-ignored-alarms').removeClass('active-tab');
        $('#tab-custom-filters').removeClass('active-tab');
    }

    function setActiveTab(id) {
        resetTabColors();
        $('#' + id).addClass('active-tab');
        $scope.activeTab = id;
    }

    function restoreActiveTab() {
        $('#' + $scope.activeTab).addClass('active-tab');
    }

    $('#tab-all-alarms').liveFirst('click', function () {
        setActiveTab('tab-all-alarms');
        checkCB('alarmsFilter_delayed_CB');
        checkCB('alarmsFilter_ignored_CB');
        checkCB('alarmsFilter_allmethods_CB');
        checkCB('alarmsFilter_allpriorities_CB');
        checkCB('alarmsFilter_allstates_CB');
    });

    $('#tab-active-alarms').liveFirst('click', function () {
        setActiveTab('tab-active-alarms');
        checkCB('alarmsFilter_delayed_CB');
        checkCB('alarmsFilter_ignored_CB');
        checkCB('alarmsFilter_allmethods_CB');
        checkCB('alarmsFilter_allpriorities_CB');
        checkCB('alarmsFilter_active_CB');
        uncheckCB('alarmsFilter_cleared_CB');
    });

    $('#tab-cleared-alarms').liveFirst('click', function () {
        setActiveTab('tab-cleared-alarms');
        checkCB('alarmsFilter_delayed_CB');
        checkCB('alarmsFilter_ignored_CB');
        checkCB('alarmsFilter_allmethods_CB');
        checkCB('alarmsFilter_allpriorities_CB');
        checkCB('alarmsFilter_cleared_CB');
        uncheckCB('alarmsFilter_active_CB');
    });

    $('#tab-ignored-alarms').liveFirst('click', function () {
        setActiveTab('tab-ignored-alarms');
        checkCB('alarmsFilter_delayed_CB');
        checkCB('alarmsFilter_includeIgnored_CB');
        uncheckCB('alarmsFilter_excludeIgnored_CB');
        checkCB('alarmsFilter_allmethods_CB');
        checkCB('alarmsFilter_allpriorities_CB');
        uncheckCB('alarmsFilter_cleared_CB');
        checkCB('alarmsFilter_active_CB');
    });

    $('#tab-custom-filters').liveFirst('click', function () {
        //setActiveTab('tab-custom-filters');
        $('#alarmsFilterShowWindowButton').click();
    });

    // workaround to auto close dropdowns on minimized menus
    $('body').liveFirst('click', function (event) {
        if (!$(event.target).hasClass('jqx-menu-minimized-button')) {
            if ($('#alarmMenu .jqx-menu-minimized-button').length > 0) {
                $('#alarmMenu').jqxMenu('restore');
                $('#alarmMenu').jqxMenu('minimize');
            }
            if ($('#alarmTabMenu .jqx-menu-minimized-button').length > 0) {
                $('#alarmTabMenu').jqxMenu('restore');
                $('#alarmTabMenu').jqxMenu('minimize');
            }
        }
    });

    function updateTabsFromFilters() {
        if ($('#alarmsFilter_delayed_CB').prop('checked') &&
            $('#alarmsFilter_ignored_CB').prop('checked') &&
            $('#alarmsFilter_allmethods_CB').prop('checked') &&
            $('#alarmsFilter_allpriorities_CB').prop('checked') &&
            $('#alarmsFilter_allstates_CB').prop('checked')) {
            // all alarms
            setActiveTab('tab-all-alarms');
        } else if ($('#alarmsFilter_delayed_CB').prop('checked') &&
            $('#alarmsFilter_ignored_CB').prop('checked') &&
            $('#alarmsFilter_allmethods_CB').prop('checked') &&
            $('#alarmsFilter_allpriorities_CB').prop('checked') &&
            $('#alarmsFilter_active_CB').prop('checked') && !$('#alarmsFilter_cleared_CB').prop('checked')) {
            // active alarms
            setActiveTab('tab-active-alarms');
        } else if ($('#alarmsFilter_delayed_CB').prop('checked') &&
            $('#alarmsFilter_ignored_CB').prop('checked') &&
            $('#alarmsFilter_allmethods_CB').prop('checked') &&
            $('#alarmsFilter_allpriorities_CB').prop('checked') && !$('#alarmsFilter_active_CB').prop('checked') &&
            $('#alarmsFilter_cleared_CB').prop('checked')) {
            // cleared alarms
            setActiveTab('tab-cleared-alarms');
        } else if ($('#alarmsFilter_delayed_CB').prop('checked') &&
            $('#alarmsFilter_includeIgnored_CB').prop('checked') && !$('#alarmsFilter_excludeIgnored_CB').prop('checked') &&
            $('#alarmsFilter_allmethods_CB').prop('checked') &&
            $('#alarmsFilter_allpriorities_CB').prop('checked') && !$('#alarmsFilter_cleared_CB').prop('checked') &&
            $('#alarmsFilter_active_CB').prop('checked')) {
            // ignored alarms
            setActiveTab('tab-ignored-alarms');
        } else {
            setActiveTab('tab-custom-filters');
        }

    }

    $('#alarmsFilter_filters input').liveFirst('change', function () {
        setTimeout(function () {
            updateTabsFromFilters();
        }, 50);
    });

}]);