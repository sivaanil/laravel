@extends('layouts.master')
@section('content')
    {{ HTML::style( asset('css/deviceInfo.css') ) }}
    <h4>Scan Times</h4>
    <div id="deviceInfoContainer">


        <dl>
            {{--Replace this with a loop so that it can handle prop/alarm Scan times without a problem--}}
            <dt>Last Successful Scan:</dt>
            <dd>{{$last_scan}}</dd>
            <dt>Last Failed Scan:</dt>
            <dd>{{$last_failed_scan}}</dd>
            <dt>Scan Interval:</dt>
            <dd>{{$scan_interval}}</dd>
            <dt>Retry Interval:</dt>
            <dd>{{$retry_interval}}</dd>
            <dt>Fail Count:</dt>
            <dd>{{$fail_count}}</dd>
            <dt>Fail Threshold:</dt>
            <dd>{{$fail_threshold}}</dd>

        </dl>
    </div>
@stop