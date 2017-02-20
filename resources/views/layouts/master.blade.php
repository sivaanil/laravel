<!DOCTYPE html>
<html ng-app="mainApp" main-app>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript">
        var homeNode = '5000'; // hotfix for SiteGate - needs further fixes
        var serverType = '{!! getenv('C2_SERVER_TYPE') !!}';
    </script>
    <meta name="viewport"
          content="width=device-width, user-scalable=no"> {{--This make it look decent on mobile platforms--}}
    <link rel="stylesheet" type="text/css" href="{!! asset('css/vendor/normalize.css') !!}">
    <meta name="_token" content="{!!  csrf_token() !!}"/>
    {{--<link rel="stylesheet" type="text/css" href="{!! asset('css/vendor/foundation.min.css') !!}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{!! asset('css/vendor/docs.css') !!}">--}}
    <link rel="stylesheet" type="text/css" href="{!! asset('bootstrap/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/page_standard.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('bootstrap/css/select2/select2.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/styles/jqx.base.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/styles/csq.web.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('modules/snmpforward/css/snmpgrid.css') !!}" />
    <link rel="stylesheet" href="{!! asset('modules/wizard/css/smart_wizard.css') !!}" />
    <script type="text/javascript" src="{!! asset('js/vendor/jquery.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jquery.bind-first.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jquery.cookie.js') !!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/scrollDiv/jquery-ui-1.10.3.custom.min.js')!!}"></script>

    <script type="text/javascript" src="{!!asset('js/vendor/scrollDiv/jquery.kinetic.min.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/scrollDiv/jquery.smoothTouchScroll.min.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jquery.scrollTo.min.js')!!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/modernizr.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/placeholder.js') !!}"></script>
    {{--<script type="text/javascript" src="{!! asset('js/vendor/foundation.min.js') !!}"></script>--}}
    {{--<script type="text/javascript" src="{!! asset('js/select2.js') !!}"></script>--}}
    <script type="text/javascript" src="{!! asset('js/select2/select2.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('bootstrap/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('js/lib/c2adapter.js') !!}"></script>

    @if(Session::get('my.locale', Config::get('app.locale')) != 'en')
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/alarm.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/select2/select2_locale_'.Session::get('my.locale', Config::get('app.locale')).'.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/device.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/grid.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/network.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/system.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/tree.js') !!}"></script>
        <script type="text/javascript"
                src="{!! asset('js/lang/'.Session::get('my.locale', Config::get('app.locale')).'/virtual.js') !!}"></script>
    @else
        <script type="text/javascript" src="{!! asset('js/lang/en/alarm.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/device.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/grid.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/network.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/system.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/tree.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/virtual.js') !!}"></script>
    @endif

    <script type="text/javascript" src="{!!asset('js/moment.js')!!}"></script>

    <script type="text/javascript" src="{!!asset('js/angular.min.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/angular-aria.js')!!}"></script>
    <script type="text/javascript" src="{{asset('js/angular-ui-router.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/angular-ui-bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/angular-resource.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/angular-moment.min.js')}}"></script> <script type="text/javascript" src="{{asset('js/ng-load.js')}}"></script>

    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxcore.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxbuttons.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxscrollbar.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxcheckbox.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxdocking.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxwindow.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxsplitter.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxprogressbar.js')!!}"></script>


    <script type="text/javascript" src="{!! asset('modules/sensors/js/SensorUI.js') !!}"></script>
    <script src="{!! asset('js/vendor/socket.io.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('modules/wizard/js/jquery.smartWizard.js') !!}"></script>
    <script src="{!! asset('/js/lib/c2tree.js') !!}"></script>

    @yield("uniqueHeaders")

    <link rel="stylesheet" type="text/css" href="{!! asset('css/vendor/scrollDiv/smoothTouchScroll.css') !!}"/>

    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxinput.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxmenu.js')!!}"></script>
    {{--<script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxmenu-min.js')!!}"></script>--}}
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxknockout.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxangular.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/ngJqxsettings.js')!!}"></script>

    <script type="text/javascript" src="{!!asset('js/mainApp.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('js/mainAppService.js')!!}"></script>


    {!! HTML::script( asset('components/preferencesComponent/js/app.js') ) !!}
    {!! HTML::script( asset('components/preferencesComponent/js/services.js') ) !!}

    {!! HTML::style( asset('components/gridComponent/css/gridComponent.css') ) !!}
    {!! HTML::script( asset('components/gridComponent/js/app.js') ) !!}
    {!! HTML::script( asset('components/gridComponent/js/config.js') ) !!}
    {!! HTML::script( asset('components/gridComponent/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/gridComponent/js/directives.js') ) !!}
    {!! HTML::script( asset('components/gridComponent/js/filters.js') ) !!}
    {!! HTML::script( asset('components/gridComponent/js/services.js') ) !!}

    {!! HTML::style( asset('components/filters/css/filter.css') ) !!}
    {!! HTML::script( asset('components/filters/js/app.js') ) !!}
    {!! HTML::script( asset('components/filters/js/config.js') ) !!}
    {!! HTML::script( asset('components/filters/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/filters/js/directives.js') ) !!}
    {!! HTML::script( asset('components/filters/js/filters.js') ) !!}
    {!! HTML::script( asset('components/filters/js/services.js') ) !!}

    {!! HTML::script( asset('components/gridWithFiltersComponent/js/app.js') ) !!} {{--App needs to be first--}}
    {!! HTML::script( asset('components/gridWithFiltersComponent/js/config.js') ) !!}
    {!! HTML::script( asset('components/gridWithFiltersComponent/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/gridWithFiltersComponent/js/filters.js') ) !!}
    {!! HTML::script( asset('components/gridWithFiltersComponent/js/services.js') ) !!}
    {!! HTML::script( asset('components/gridWithFiltersComponent/js/directives.js') ) !!}

    {!! HTML::style( asset('components/menuComponent/css/menuComponent.css') ) !!}
    {!! HTML::script( asset('components/menuComponent/js/app.js') ) !!}
    {!! HTML::script( asset('components/menuComponent/js/config.js') ) !!}
    {!! HTML::script( asset('components/menuComponent/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/menuComponent/js/directives.js') ) !!}
    {!! HTML::script( asset('components/menuComponent/js/filters.js') ) !!}
    {!! HTML::script( asset('components/menuComponent/js/services.js') ) !!}

    {!! HTML::style( asset('components/panelsComponent/css/panelMenuButton.css') ) !!}
    {!! HTML::style( asset('components/panelsComponent/css/panel.css') ) !!}
    {!! HTML::script( asset('components/panelsComponent/js/app.js') ) !!}
    {!! HTML::script( asset('components/panelsComponent/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/panelsComponent/js/directives.js') ) !!}

    {!! HTML::style( asset('components/formComponent/css/formComponent.css') ) !!}
    {!! HTML::script( asset('components/formComponent/js/app.js') ) !!} {{--App needs to be first--}}
    {!! HTML::script( asset('components/formComponent/js/config.js') ) !!}
    {!! HTML::script( asset('components/formComponent/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/formComponent/js/filters.js') ) !!}
    {!! HTML::script( asset('components/formComponent/js/services.js') ) !!}
    {!! HTML::script( asset('components/formComponent/js/directives.js') ) !!}

    {!! HTML::style( asset('components/networkTreeComponent/css/networkTreeComponent.css') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxtree.js') ) !!}
    {!! HTML::script( asset('components/networkTreeComponent/js/app.js') ) !!}
    {!! HTML::script( asset('components/networkTreeComponent/js/config.js') ) !!}
    {!! HTML::script( asset('components/networkTreeComponent/js/controllers.js') ) !!}
    {!! HTML::script( asset('components/networkTreeComponent/js/directives.js') ) !!}
    {!! HTML::script( asset('components/networkTreeComponent/js/services.js') ) !!}

    {!! HTML::script( asset('modules/alarms/js/app.js') ) !!}
    {!! HTML::script( asset('modules/alarms/js/controllers.js') ) !!}

    {!! HTML::script( asset('modules/device/js/app.js') ) !!}
    {!! HTML::script( asset('modules/device/js/controllers.js') ) !!}
    {!! HTML::script( asset('modules/device/js/directives.js') ) !!}

    {!! HTML::script( asset('modules/network/js/app.js') ) !!}
    {!! HTML::script( asset('modules/network/js/controllers.js') ) !!}
    {!! HTML::script( asset('modules/network/js/directives.js') ) !!}

    {!! HTML::script( asset('modules/system/js/app.js') ) !!}
    {!! HTML::script( asset('modules/system/js/controllers.js') ) !!}
    {!! HTML::script( asset('modules/system/js/directives.js') ) !!}
    
    {!! HTML::script( asset('modules/virtualGenerator/js/app.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/controller.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/contactClosureController.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/fuelSensorController.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/relayControlController.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/generatorSummaryController.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/sensorTreeController.js') ) !!}
    {!! HTML::script( asset('modules/virtualGenerator/js/directives.js') ) !!}

    {{-- Alarm module related headers - could potentially be lazy loaded --}}

    {!! HTML::style( asset('modules/alarms/css/alarms.css') ) !!}
    {!! HTML::script( asset('js/alarms.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxdata.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxlistbox.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxdropdownlist.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxcombobox.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.sort.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.pager.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.selection.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.edit.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.filter.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.columnsreorder.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxgrid.columnsresize.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxdatetimeinput.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxcalendar.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxwindow.js') ) !!}
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxtabs.js') ) !!}

    {!! HTML::style( asset('css/nodeSelection.css') ) !!}
    {!! HTML::script( asset('js/nodeSelection.js') ) !!}

    <script type="text/javascript">
        var baseUrl = '{!!url();!!}';
        $(document).ready(function () {
            if (document.getElementById("jqxSearchMenu") != null) {
                $("#jqxSearchMenu").jqxMenu({autoSizeMainItems: true});
                $('#jqxSearchMenu').jqxMenu({height: '32px', minimizeWidth: 'auto', theme: 'custom'});
                //$("#jqxSearchMenu").css('visibility', 'visible');

                $("#jqxSearchMenu").jqxMenu('setItemOpenDirection', 'theSearchMenuli', 'left', 'down');
                $('#jqxSearchMenu').jqxMenu({clickToOpen: true});
                $('#jqxSearchMenu').jqxMenu({enableHover: false});
            }
            if (document.getElementById("makeMeScrollable") != null) {
                $("#makeMeScrollable").smoothTouchScroll();
            }
            if ($("#splitter").length !== 0) {
                $("#splitter").jqxSplitter({width: '99.5%', height: '99.9%', panels: [{size: '30%'}, {size: '70%'}]});
            }
            $('#layer1').css('visibility', 'hidden');
            $('#layer1').css('display', 'none');
            $('#layer2').css('visibility', 'visible');
            $('#jqxMainMenu').css('visibility', 'visible');
            $(".crumbText").each(function () {
                var currentContent = $(this).html();
                var newContent = currentContent.replace(/_/g, '_&#8203;')
                $(this).html(newContent);
            });
        });
    </script>
    <style media="screen" type="text/css">
        .layer1_class {
            z-index: 5;
            width: 100%;
            height: 50%;
            margin: auto;
            position: relative;
            visibility: visible;
        }

        .layer2_class {
            z-index: 2;
            visibility: hidden;
            position: relative;
            width: 100%;
            height: 100%
        }
    </style>
</head>

<body style="height:100vh;" class="c2-{{$_ENV['C2_SERVER_TYPE']}}">
<div id="layer1" class="layer1_class">
    <div style="position: absolute; bottom:0; left:50%; margin-left:-47.5px">
        <img style="float:left;" id=loadinggif src="{!!url();!!}/img/gifs/loading.gif">

        <p style="float:left;"><strong><em>Loading...</em></strong></p>
    </div>
</div>

<div id="layer2" class="layer2_class" ng-app="panel">
    @if(!isset($breadcrumb))
        <?php $breadcrumb = ''; ?>
    @endif
    {{--@include('navbar', array('nodeId'=>0))--}}
    @include('menuArea', array('nodeId'=>isset($nodeId)?$nodeId:0, 'activePage'=> isset($activePage)?$activePage:'selection'))


    {{-- check for flash notification message --}}
    @if(Session::has('flash_notice'))
        <div id="flash_notice">{!! Session::get('flash_notice') !!}</div>
    @endif

        @if($breadcrumb !='')
            <div id="splitter">
                <div>
                    <div id="sidebar">
                        <div id="sidebarView">
                        </div>
                    </div>
                </div>
                <div id="content">
                    <div id="mainPanelView" style="height:100%;">
                    </div>
                </div>
            </div>
        @else
            @yield("body")
        @endif
</div>

</body>
</html>
