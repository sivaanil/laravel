(function(){
    var app = angular.module('mainApp', ['alarms', 'device', 'network', 'system', 'virtualGenerator', 'grid', 'filter', 'menu', 'tree', 'panel', "ngJqxsettings", "gridwithfilters", 'preferences', 'form', 'ui.router', 'ngResource', 'ngLoad', 'ngAria']);

    // parent scope
    app.directive('mainApp', function($compile) {
        return {
            controller: function($scope, $rootScope, $compile) {
                this.scope = $scope;
                // load tree
                var url = baseUrl + "/components/networkTreeComponent/tree.html";
                loadPanel('sidebarView', url, $rootScope, $compile);
            }
        };
    });
    app.config(function($stateProvider, $urlRouterProvider){

      // For any unmatched url, send to default route
      $urlRouterProvider.otherwise("/alarms/" + window.homeNode);

      $stateProvider
        // change node (immediately redirects without leaving extra entry in history)
        .state('nodeChange', {
          url: "/nodeChange/:id",
            onEnter: function($stateParams, $rootScope, $compile, $timeout) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                var prevName = $rootScope.$state.current.name;
                $timeout(function(){
                    if (!prevName) {
                        prevName = 'nodes';
                    }
                    $rootScope.$state.go(prevName, { id:  $stateParams.id }, { location: 'replace' } );
                });
            }
        })
        .state('stateChange', {
          url: "/stateChange/:newState",
            onEnter: function($stateParams, $rootScope, $compile, $timeout) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                var nodeId = $rootScope.$nodeId;
                $timeout(function(){
                    $rootScope.$state.go($stateParams.newState, { id:  nodeId }, { location: 'replace' } );
                });
            }
        })
        .state('nodes', {
          url: "/nodes/:id",
            onEnter: function($stateParams, $rootScope, $compile) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                var url = baseUrl + "/nodes/" + $stateParams.id;
                onPanelLoad = function () {
                    initNodeActions('true');
                    fetchSeverities($stateParams.id);
                };
                loadPanel('mainPanelView', url, $rootScope, $compile, onPanelLoad);
                $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
            }
        })
        .state('alarms', {
            url: "/alarms/:id",
            onEnter: function($stateParams, $rootScope, $compile) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                if ($('#mainAlarmDiv').length > 0) {
                    // don't reload
                } else {
                    var url = baseUrl + "/modules/alarms/alarms.html";
                    loadPanel('mainPanelView', url, $rootScope, $compile);
                }
                $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
            },
            onExit: function($stateParams, $rootScope) {
            }
        })
        .state('lanSettings', {
            url: "/lanSettings/:id",
            controller: "formCtrl",
            onEnter: function($stateParams, $rootScope, $compile) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                var url = baseUrl + "/modules/network/lansettings.html";
                loadPanel('mainPanelView', url, $rootScope, $compile);
                $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
            },
            onExit: function($stateParams, $rootScope) {
            }
        })
        .state('wanSettings', {
            url: "/wanSettings/:id",
            controller: "formCtrl",
            onEnter: function($stateParams, $rootScope, $compile) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                var url = baseUrl + "/modules/network/wansettings.html";
                // Example: How to verify permissions slug before loading a panel
                //loadPanel('mainPanelView', url, $rootScope, $compile, undefined, 'wan-settings');
                loadPanel('mainPanelView', url, $rootScope, $compile);
                $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
            },
            onExit: function($stateParams, $rootScope) {
            }
        })
          .state('systemSettings', {
              url: "/systemSettings/:id",
              controller: "formCtrl",
              onEnter: function($stateParams, $rootScope, $compile) {
                  if($stateParams.id) {
                      $rootScope.$nodeId = $stateParams.id;
                  }
                  var url = baseUrl + "/modules/system/systemsettings.html";
                  loadPanel('mainPanelView', url, $rootScope, $compile);
                  $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
              },
              onExit: function($stateParams, $rootScope) {
              }
          })
        .state('addDevice', {
            url: "/addDevice/:id",
            controller: "formCtrl",
            onEnter: function($stateParams, $rootScope, $compile) {
                if($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                }
                var url = baseUrl + "/modules/device/addDeviceDialog.html";
                loadPanel('mainPanelView', url, $rootScope, $compile);
                $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
            },
            onExit: function($stateParams, $rootScope) {
            }
        })
        .state('sensors', {
            url: "/sensors/:id",
            onEnter: function($stateParams, $rootScope, $compile) {
                if ($stateParams.id) {
                    $rootScope.$nodeId = $stateParams.id;
                    window.nodeId = $stateParams.id;
                }
                // Load the sensor panel
                loadModulePanel('mainPanelView', 'sensors', $rootScope, $compile, function() { sensor_init(); });
                $rootScope.$broadcast('nodeChangeEvent', {nodeId: $stateParams.id});
            },
            onExit: function($stateParams, $rootScope) {}
        })
/*        .state('snmpforward', {
            url: "/snmpforward",
            onEnter: function($stateParams, $rootScope, $compile) {
                loadModulePanel('mainPanelView', 'snmpforward', $rootScope, $compile, function() { snmpforward_init(); });
            },
            onExit: function($stateParams, $rootScope) {}
        })
*/
        .state('deviceInfo', {
              url: "/device/:id",
              onEnter: function($stateParams, $rootScope, $compile) {
                  if($stateParams.id) {
                      $rootScope.$nodeId = $stateParams.id;
                  }
                  var url = baseUrl + "/modules/device/deviceinfo.html";
                  loadPanel('mainPanelView', url, $rootScope, $compile);
                  $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
              },
              onExit: function($stateParams, $rootScope) {
              }
          })
/*          .state('cameraInfo', {
              url: "/camera/:id",
              onEnter: function($stateParams, $rootScope, $compile) {
                  if($stateParams.id) {
                      $rootScope.$nodeId = $stateParams.id;
                  }
                  var url = baseUrl + "/modules/device/camerainfo.html";
                  loadPanel('mainPanelView', url, $rootScope, $compile);
                  $rootScope.$broadcast('nodeChangeEvent', { nodeId: $stateParams.id });
              },
              onExit: function($stateParams, $rootScope) {
              }
          })
*/
    })



})();

function hasPermission(slug) {
    $.get('/aclcheck/' + slug, function(data) {
        return data.status;
    });
}


function setHeight() {
    var winHeight = $(window).height();
    var winWidth = $(window).width();
    if (winWidth < 650) {
        var divHeight = winHeight - 115;
    } else {
        var divHeight = winHeight - 140;
    }
    if ($("#mainAlarmDiv").length > 0) {
        var mainHeight = divHeight - 4;
        $('#mainAlarmDiv').css('height', mainHeight + 'px');
    }
    if ($("#splitter").length > 0) {
        var splitterHeight = divHeight + 35;
        $("#splitter").jqxSplitter({ height: splitterHeight + 'px' });
    }
    if ($("#scrollableTree").length > 0) {
        var treeHeight = divHeight + 26 - $(".network-tree-action-buttons").height();
        $("#scrollableTree").css('height', treeHeight + 'px');
    }

    if (typeof window.sensorUI !== "undefined" && typeof window.sensorUI.ccGrid !== "undefined") {
        var mainHeight = divHeight - 10;
        window.sensorUI.ccGrid.setHeight(mainHeight);
    }

    if (typeof window.snmp !== "undefined" && typeof window.snmp.grid !== "undefined") {
        var mainHeight = divHeight - 10;
        window.snmp.grid.setHeight(mainHeight);
    }


}

function loadModulePanel(panelId, module, $rootScope, $compile, onPanelLoad, permission) {
    $('#' + panelId).html('');
    if (typeof permission !=='undefined') {
        if (!hasPermission(permission)) {
            url = baseUrl + '/modules/system/unauthorized.html';
        }
    }

    var getPanelData = $.get('/' + module, function(data) {
            var $scope = $('#' + panelId).html(data).scope();
            $compile($('#' + panelId))($scope || $rootScope);
            //$rootScope.digest();
            if (typeof onPanelLoad !== 'undefined') {
                onPanelLoad();
            }
        });
}

function loadPanel(panelId, url, $rootScope, $compile, onPanelLoad, permission) {
    $('#'+panelId).html('');

    if (typeof(permission) != "undefined") {
        if (!hasPermission(permission)) {
            url = baseUrl + '/modules/system/unauthorized.html';
        }
    }

    var getPanelData = $.get(url, function(data) {
        var $scope = $('#'+panelId).html(data).scope();
        $compile($('#'+panelId))($scope || $rootScope);
        $rootScope.$digest();
        if (typeof onPanelLoad !== 'undefined') {
            onPanelLoad();
        }
    });
}

$(window).resize(function () {
     setHeight();
 });

 $('#netTree').on('initialized', function (event) {
    setHeight();
 });

angular.module('mainApp').run(['$rootScope', '$state', '$window',
    function ($rootScope, $state, $window) {
        $rootScope.$state = $state;
        $rootScope.serverType = $window.serverType;
    }
]);

// fix for automatically passing the CSRF token with all AJAX requests
$.ajaxSetup(
{
    beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="_token"]').attr('content'));
    }
});

// activate tooltips (bootstrap tooltip)
$(function () {
    $("body").tooltip({ selector: '[data-toggle="tooltip"]' });
})

// automatically launch fullscreen on mobile devices
// http://stackoverflow.com/questions/7836204/chrome-fullscreen-api
/*
addEventListener("click", function() {
    var
      el = document.documentElement
    , rfs =
           el.requestFullScreen
        || el.webkitRequestFullScreen
        || el.mozRequestFullScreen
    ;
    rfs.call(el);
});
*/

