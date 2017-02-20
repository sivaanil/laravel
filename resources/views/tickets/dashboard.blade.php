@extends('layouts.master')
@section('uniqueHeaders')

    {{ HTML::style( asset('js/vendor/jqwidgets/styles/jqx.base.css') ) }}
    {{ HTML::style( asset('css/styles/csq.darkblue.css') ) }}
    {{ HTML::script( asset('js/vendor/jquery.js') ) }}

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div id="window1">
                    <div>Unacknowledged Tickets
                        <input type="button" value="Select Interval" id='unackIntervalButton' style="margin-left: 5px">
                    </div>
                    <div id="unackContainer" style="width: 100%; height: 100%"></div>
                </div>
                <div id="window2">
                    <div>Unresolved Tickets
                        <input type="button" value="Select Interval" id='unresIntervalButton' style="margin-left: 5px">
                    </div>
                    <div id="unresContainer" style="width: 100%; height: 100%"></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div id="window3">
                    <div>Overdue Activity Tickets
                        <input type="button" value="Select Interval" id='overIntervalButton' style="margin-left: 5px">
                    </div>
                    <div id="overContainer" style="width: 100%; height: 100%"></div>
                </div>
                <div id="window4">
                    <div>Waiting for Customer Tickets
                        <input type="button" value="Select Interval" id='waitIntervalButton' style="margin-left: 5px">
                    </div>
                    <div id="waitContainer" style="width: 100%; height: 100%"></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="window5">
                    <div>Ticketing Metrics
                        <input type="button" value="Select Interval" id='metricsIntervalButton'
                               style="margin-left: 5px">
                    </div>
                    <div id='metricsTabs'>
                        <ul>
                            <li style="margin-left: 5px;">Statuses</li>
                            <li>Priorities</li>
                            <li>Users</li>
                            <li>Escalating Tickets</li>
                            <li>Averages</li>
                        </ul>
                        <div id="statusContainer" style="width: 100%; height: 100%"></div>
                        <div id="priorityContainer" style="width: 100%; height: 100%"></div>
                        <div id="userContainer" style="width: 100%; height: 100%"></div>
                        <div id="policyContainer" style="width: 100%; height: 100%"></div>
                        <div id="averageContainer" style="width: 100%; height: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--   Unacknowledged Tickets Interval Window -->
    <div id="unackIntervalWindow">
        <div>Change Time</div>
        <div style="margin-left: 5px">
            <div>Select the Time Interval:</div>
            <div id="unackNum" style="margin-top: 10px; margin-bottom: 10px; margin-left: 15px"></div>
        </div>
    </div>

    <!--   Unresolved Tickets Interval Window -->
    <div id="unresIntervalWindow">
        <div>Change Time</div>
        <div style="margin-left: 5px">
            <div>Select the Time Interval:</div>
            <div id="unresNum" style="margin-top: 10px; margin-bottom: 10px; margin-left: 15px"></div>
        </div>
    </div>

    <!--   Overdue Tickets Interval Window -->
    <div id="overIntervalWindow">
        <div>Change Time</div>
        <div style="margin-left: 5px">
            <div>Select the Time Interval:</div>
            <div id="overNum" style="margin-top: 10px; margin-bottom: 10px; margin-left: 15px"></div>
        </div>
    </div>

    <!--   Waiting Tickets Interval Window -->
    <div id="waitIntervalWindow">
        <div>Change Time</div>
        <div style="margin-left: 20px">
            <div>Select the Time Interval:</div>
            <div id="waitNum" style="margin-top: 10px; margin-bottom: 10px; margin-left: 15px"></div>
        </div>
    </div>

    <!--   Tickets Metrics Interval Window -->
    <div id="metricsIntervalWindow">
        <div>Change Time</div>
        <div style="margin-left: 5px">
            <div>Select the Time Interval (Only applies when calculating averages):</div>
            <div id="metricsNum" style="margin-top: 10px; margin-bottom: 10px; margin-left: 15px"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            _createElements({{$nodeId}});
            _addEventListeners();
            _createWindow({{$nodeId}});
        });
    </script>

    {{ HTML::script( asset('js/vendor/jqwidgets/jqxdata.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxdraw.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxchart.core.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxwindow.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxtabs.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxpanel.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxtooltip.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxgrid.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxgrid.selection.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxgrid.columnsresize.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxgrid.filter.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxgrid.sort.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxnumberinput.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxangular.js') ) }}
    {{ HTML::script( asset('js/vendor/jqwidgets/jqxexpander.js') ) }}
    {{ HTML::script( asset('js/dashboard.js') ) }}
@stop

