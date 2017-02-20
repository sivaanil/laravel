<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript">
//        var homeNode = '5000'; // hotfix for SiteGate - needs further fixes
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
    <link rel="stylesheet" type="text/css" href="{!! asset('modules/snmpforward/css/snmpgrid.css') !!}"/>
    <script type="text/javascript" src="{!! asset('js/vendor/jquery.js') !!}"></script>
    <script type="text/javascript" src="{!!asset('js/vendor/scrollDiv/jquery-ui-1.10.3.custom.min.js')!!}"></script>


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
    @else
        <script type="text/javascript" src="{!! asset('js/lang/en/alarm.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/device.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/grid.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/network.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/system.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lang/en/tree.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('js/lib/c2grid.js') !!}"></script>
    @endif



    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxcore.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxdata.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxbuttons.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxscrollbar.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxmenu.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxlistbox.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxdropdownlist.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.selection.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.columnsresize.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.filter.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.sort.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.pager.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/vendor/jqwidgets/jqxgrid.grouping.js') !!}"></script>
    {!! HTML::script( asset('js/vendor/jqwidgets/jqxwindow.js') ) !!}r
    <link rel="stylesheet" type="text/css" href="{!! asset('css/vendor/scrollDiv/smoothTouchScroll.css') !!}"/>
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
    @yield("body")
</div>

</body>
</html>
