<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

//Event::listen('illuminate.query', function($query) {
//    Log::info($query);
//});

Route::any('/', array(
    'as' => 'root',
    function () {
        return loginMake();
    }
));
//Login routes
Route::post('/login', 'Auth\AuthController@authenticate');
Route::get('/login', array(
    'as' => 'root',
    function () {
        return loginMake();
    }
));

// Password Reset Routes
Route::get('reset', [
    'as' => 'reset',
    'uses' => function () {
        return pwResetMake();
    }
]);
Route::post('reset', 'Auth\AuthController@postReset');

Route::any('/logout', 'Auth\AuthController@logout');

// API Call for new, versioned api
Route::any('/api/{module}/{any?}', 'RestAPIController@process')->where('any', '([A-z\d-\/_.]+)?');
// temporary - fix for bug UN-664 until new API is enabled - dummy route to return 200 instead of 404
//Route::any('/api/{module}/{any?}', array('as' => 'root', function () { return loginMake(); } ));

//the routes declared inside the Route::group function require authenication. Any routes declared outside this are public
Route::group(array('middleware' => 'auth'), function () {

    Route::any('/menutest', 'HomeController@menuTest');
    Route::any('/componenttest', 'HomeController@buttonTest');
    Route::any('/home', array('as' => 'home', 'uses' => "HomeController@show"));
    Route::get('/aclcheck/{slug}', array('as' => 'aclcheck', 'uses' => "HomeController@aclcheck"));
    Route::get('/nodes/{id}', array('as' => 'nodeSelection', 'uses' => "nodes\NodeSelectionController@show"));
    Route::get('/nodesWithSeverities/{id}', array('as' => 'nodeSelection', 'uses' => "nodes\NodeSelectionController@showWithSeverities"));
    Route::any("/autocomplete", 'nodes\NodeSelectionController@autoComplete');

    //Route::get("/device/{id}/location", 'LocationController@show');
    //Route::get("/device/{id}/scanTimes", 'ScanTimesController@show');

    Route::get("/device/getPorts/{id}", array('as' => 'getPorts', 'uses' => 'devices\DeviceController@getPorts'));
    Route::get("/device/{id}", 'devices\DeviceController@show');
    Route::get("/device/startScan/{id}/{type}", 'devices\DeviceController@startScan');
    Route::get("/device/startRebuild/{id}", 'devices\DeviceController@startRebuild');
    Route::resource('/device', 'devices\DeviceController', ['only' => ['store', 'index']]);
    Route::get("/device/buildProgress/{id}", array('as' => 'buildProgress', 'uses' => 'devices\DeviceController@getBuildProgress'));
    Route::get("/device/cancelBuild/{id}", array('as' => 'cancelBuild', 'uses' => 'devices\DeviceController@cancelBuild'));
    Route::get("/device/rebuildProgress/{id}", array('as' => 'rebuildProgress', 'uses' => 'devices\DeviceController@getRebuildProgress'));
    Route::get("/device/scanProgress/{id}", array('as' => 'scanProgress', 'uses' => 'devices\DeviceController@getScanProgress'));
    Route::get("/device/webInterface/{id}", array('as' => 'webInterface', 'uses' => 'devices\DeviceController@getWebLink'));
    Route::get("/device/remove/{id}", array('as' => 'remove', 'uses' => 'devices\DeviceController@removeDevice'));

    Route::get("/deviceType/{id}", array('as' => 'deviceType', 'uses' => 'devices\DeviceTypeController@show'));
    Route::get("/deviceType/getPorts/{id}", array('as' => 'getPorts', 'uses' => 'devices\DeviceTypeController@getPorts'));
    Route::get("/deviceTypes", array('as' => 'deviceTypes', 'uses' => 'devices\DeviceTypeController@index'));

    Route::get("/deviceClass", array('as' => 'deviceClass', 'uses' => 'devices\DeviceClassController@index'));
    Route::get("/deviceType/getDeviceTypes/{name}", array('as' => 'deviceType', 'uses' => 'devices\DeviceTypeController@getDeviceTypes'));
    Route::post("/device/validatePreForm", array('as' => 'device', 'uses' => 'devices\DeviceController@validatePreForm'));

    Route::post("/virtualDevice/buildVirtualDevice/{class}", array('as' => 'virtualDevice', 'uses' => 'VirtualDeviceController@buildVirtualDeviceWizard'));
    Route::get("/virtualDevice/getSensors/{type}", array('as' => 'getSensors', 'uses' => 'VirtualDeviceController@getSensors'));
    Route::get("/virtualDevice/getSensorTypes/{type}", array('as' => 'getSensorTypes', 'uses' => 'VirtualDeviceController@getSensorTypes'));
    Route::get("/virtualDevice/getVirtualDevices/{type}", array('as' => 'getVirtualDevices', 'uses' => 'VirtualDeviceController@getVirtualDevices'));
    Route::resource("/virtualDevice", 'VirtualDeviceController', ['only' => ['store', 'index']]);

    //Route::get("/device/{id}/state/{type}", 'PropController@show');
    //SitePortal Tree
    Route::get("/showTree", array('as' => 'showTree', 'uses' => 'NetworkTreeController@index'));
    Route::get("/networkTree/loadFirstLevel", array('as' => 'loadFirstLevel', 'uses' => 'NetworkTreeController@loadFirstLevel'));
    Route::get("/networkTree/loadNextLevel", array('as' => 'loadNextLevel', 'uses' => 'NetworkTreeController@loadNextLevel'));
    Route::get("/networkTree/syncServer", array('as' => 'syncServer', 'uses' => 'NetworkTreeController@syncServer'));
    Route::get("/networkTree/loadAllLevel", array('as' => 'loadAllLevel', 'uses' => 'NetworkTreeController@loadAllLevel'));
    Route::get("/networkTree/getTreeControls", array('as' => 'getTreeControls', 'uses' => 'NetworkTreeController@getTreeControls'));
    Route::get("/networkTree/getNodeInformation", array('as' => 'getNodeInformation', 'uses' => 'NetworkTreeController@getNodeInformation'));

    //panels
    Route::get("/panel", array('as' => 'panel', 'uses' => 'PanelsController@panel'));
    Route::get("/panelView", array('as' => 'panelView', 'uses' => 'PanelsController@panelView'));
    Route::get("/panelsList", array('as' => 'panelsList', 'uses' => 'PanelsController@panelsList'));
    Route::get("/panelMenu", array('as' => 'panelMenu', 'uses' => 'PanelsController@panelMenu'));
    Route::get("/canvasCtrl", array('as' => 'canvasCtrl', 'uses' => 'PanelsController@canvasCtrl'));
    Route::get("/canvasCtrl/getMenuButtonControls", array('as' => 'getMenuButtonControls', 'uses' => 'PanelsController@getMenuButtonControls'));

    // Routes for the Ticket Dashboard
//        Route::get('/tickets/{id}/dashboard', 'TicketController@show');
//        Route::get('/tickets/unack', 'TicketController@getUnackData');
//        Route::get('/tickets/unresolved', 'TicketController@getUnresolvedData');
//        Route::get('/tickets/overdue', 'TicketController@getOverdueData');
//        Route::get('/tickets/wait', 'TicketController@getWaitData');
//        Route::get('/tickets/average', 'TicketController@getAverageData');
//        Route::get('/tickets/priority', 'TicketController@getPriorityData');
//        Route::get('/tickets/status', 'TicketController@getStatusData');
//        Route::get('/tickets/user', 'TicketController@getUserData');
//        Route::get('/tickets/policy', 'TicketController@getPolicyData');

    Route::get('/content', function () {
        return View::make('content');
    });

    Route::get("/alarms/{id}", 'nodes\AlarmController@show');
    Route::get("/updateAlarms", array('as' => 'updateAlarms', 'uses' => 'nodes\AlarmController@updateAlarms'));

    Route::get('/DeviceActions/{id}', array('as' => 'actions', 'uses' => 'ActionController@show'));
    Route::get("/DeviceActions/{id}/stopscan/{type}", 'StopScanController@show');

    Route::get("/updateScanInfo", array('as' => 'startScan', 'uses' => 'ActionController@updateScanInfo'));
    Route::get("/refreshMenu", array('as' => 'refreshMenu', 'uses' => 'MenuController@refreshMenu'));
    Route::post("/refreshBreadcrumb", array('as' => 'refreshBreadcrumbMenu', 'uses' => 'MenuController@refreshBreadcrumbMenu'));
    Route::get("/breadcrumbData/{nodeId}", array('as' => 'breadcrumbData', 'uses' => 'MenuController@breadcrumbData'));
    Route::get("/launchScan/{id}/{type}", array('as' => 'startScan', 'uses' => 'MenuController@launchScan'));
    Route::get("/gridData/alarm", array('as' => 'AlarmGridHeaders', 'uses' => 'nodes\AlarmController@tmpGetAlarmHeaders'));
    Route::post("/gridData/alarm", array('as' => 'AlarmGridHeaders', 'uses' => 'nodes\AlarmController@tmpGetAlarmHeaders'));
    Route::post("/alarms/alarmAction", array('as' => 'AlarmGridHeaders', 'uses' => 'nodes\AlarmController@handleGridAction'));

    Route::get("/dataExport/alarm", array('as' => 'dataExport', 'uses' => 'nodes\AlarmController@dataExport'));
    Route::get("/dataExport/deviceInventory/{nodeId}", array('as' => 'dataExportDevice', 'uses' => 'devices\DeviceController@dataExport'));

    Route::any("/filter", array('as' => 'FilterFetch', 'uses' => 'general\FilterController@getFilters'));

    Route::post("/getPreferences", array('as' => 'getPreferences', 'uses' => 'PreferencesController@getPreferences'));
    Route::post("/getPreference", array('as' => 'getPreference', 'uses' => 'PreferencesController@getPreference'));
    Route::post("/setPreference", array('as' => 'setPreference', 'uses' => 'PreferencesController@setPreference'));


    /* these routes are only accessible when the server is of type SiteGate */
    Route::group(array('middleware' => 'servertype:sitegate'), function () {
        Route::any('/LANSetting/validate', array('as' => 'LANSetting', 'uses' => "LANSettingController@validateForm"));
        Route::resource('/LANSetting', 'LANSettingController', ['only' => ['store', 'index']]);

        Route::any('/WANSetting/validate', array(
            'as' => 'WANSetting', 'uses' => "WANSettingController@validateForm")
        );
        Route::resource('/WANSetting', 'WANSettingController', ['only' => ['store', 'index']]);

        Route::resource('/system/settings', 'system\SettingsController', ['only' => ['index']]);
        Route::post('/system/settimezone', 'system\SettingsController@setTimeZone');
        Route::any('/system/reboot', array('as' => 'Reboot', 'uses' => 'system\SettingsController@reboot'));
        Route::any('/system/resetGuacamole', array('as' => 'ResetGaucamole', 'uses' => 'system\SettingsController@resetGuacamole'));
    });

    // only load these functions if they haven't been previously loaded.
    if (!(function_exists('setLanguage') || function_exists('loginMake') || function_exists('pwResetMake'))) {
        
        function setLanguage()
        {
            $lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
            Session::put('my.locale', $lang);
            App::setLocale($lang);
            return;
        }

        function loginMake()
        {
            setLanguage();

            if (Session::has('attempt')) {
                $attempt = Input::get('attempt') + 3000;
            } else {
                $attempt = Session::put('attempt', 0);
            }

            return View::make('user.loginBody')->with('attempt', $attempt);
        }

        function pwResetMake()
        {
            return View::make('user.passwordReset');
        }
    }

    /* function ($id) {
      $arr = array();
      return GeneralHelper::makeWithExtras('devices/ActionsMenu', $arr, $id);
      }); */
    /* Route::get('/alarms', function() {
      return View::make('Alarms');
      }); */
//Device routes
});

// API route to look like the old direct file access
// It does its own authentication
Route::get('/cswapi_lite/siteportal_device_api/sitePortalLinkNode.php', ['as' => 'oldAPICall', 'uses' => 'APIController@OldCall']);

Route::get('/browse/{guacUserName}/{guacPassword}/{connectionId}', ['as' => 'LaunchBrowser', 'uses' => 'BrowseController@LaunchBrowser']);
Route::post('/browserping', ['as' => 'browserping', 'uses' => 'BrowseController@PingConnection']);
