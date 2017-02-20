@extends('layouts.master')
@section('uniqueHeaders')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles/jqx.base.css') }}"/>
    {{--<script type="text/javascript" src="{{asset('js/vendor/jquery.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxcore.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxdata.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxbuttons.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxscrollbar.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxmenu.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxcheckbox.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxlistbox.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxdropdownlist.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxgrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxgrid.sort.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxgrid.pager.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets//jqxgrid.selection.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/vendor/jqwidgets/jqxgrid.edit.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $.ajax({
                type: 'GET',
                url: '/device/getPorts',
                data: {
                    nodeId: {{$nodeId}}
                },

                success: function (result) {
                    var obj = result;
                    var source =
                    {
                        localdata: obj,

                        datafields: [
                            {name: 'Device Name', type: 'string'},
                            {name: 'Port Type', type: 'string'},
                            {name: 'To', type: 'number'},
                            {name: 'From', type: 'number'}
                        ],
                        datatype: "json",
                    };
                    var dataAdapter = new $.jqx.dataAdapter(source);

                    // initialize jqxGrid
                    $("#jqxgrid").jqxGrid(
                            {
                                width: '98%',
                                source: dataAdapter,
                                //pageable: true,
                                autoheight: true,
                                sortable: true,
                                altrows: true,
                                selectionmode: 'multiplecellsadvanced',
                                columns: [
                                    {text: 'Device Name', datafield: 'Device Name', width: '40%'},
                                    {
                                        text: 'Port Type',
                                        datafield: 'Port Type',
                                        cellsalign: 'right',
                                        align: 'right',
                                        width: '20%'
                                    },
                                    {text: 'From', datafield: 'From', cellsalign: 'right', width: '20%'},
                                    {text: 'To', datafield: 'To', cellsalign: 'right', width: '20%'}
                                ],

                            });

                }
            });


        });
    </script>
@stop
@section('content')
    {{ HTML::style( asset('css/deviceInfo.css') ) }}
    <h4>Device Info</h4>
    <div id="deviceInfoContainer">
        <!--<div><span class="infoCategory">Device:</span><span class="deviceValue">{{$name}}</span></div>
<br>
<div><span class="infoCategory">Class:</span><span class="deviceValue">{{$className}}</span></div>
<br>
<div><span class="infoCategory">Type:</span><span class="deviceValue">{{$typeName}}</span></div>
<br>
<div><span class="infoCategory">IP Address:</span><span class="deviceValue">{{$ip_address}}</span></div>
<br>
<div><span class="infoCategory">Secondary IP:</span><span class="deviceValue">{{$ip_address_2}}</span></div>
<br>
<div><span class="infoCategory">Web User:</span><span class="deviceValue">{{$username}}</span></div>
<br>
<div><span class="infoCategory">Web Password:</span><span class="deviceValue">{{$password}}</span></div>
<br>-->

        <dl>
            <dt>Device:</dt>
            <dd>{{$name}}</dd>
            <dt>Class:</dt>
            <dd>{{$className}}</dd>
            <dt>Type:</dt>
            <dd>{{$typeName}}</dd>
            <dt>IP Address:</dt>
            <dd>{{$ip_address}}</dd>
            <dt>Secondary IP:</dt>
            <dd>{{$ip_address_2}}</dd>
            <dt>Web User:</dt>
            <dd>{{$username}}</dd>
            <dt>Web Password</dt>
            <dd>{{$password}}</dd>
        </dl>
        {{--<table class="table">
                <thead>
                  <tr>
                    <th><?php echo implode('</th><th>', array_keys(current($ports))); ?></th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach ($ports as $row): array_map('htmlentities', $row); ?>
                        <tr>
                          <td><?php echo implode('</td><td>', $row); ?></td>
                          <!-- This can be the get details button
                        <td><button icon="">details</button></td>-->
                        </tr>
                    <?php endforeach; ?>
                <tbody>
                    </table>--}}
    </div>
    <div style="max-width: 250">
        <div id='jqxWidget'>
            <div id="jqxgrid" style="max-width: 250">
            </div>
        </div>
    </div>
@stop