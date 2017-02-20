@extends('layouts.master')
@section('content')
    {{ HTML::style( asset('css/stopScan.css') ) }}


    {{ Form::open(array('method'=>'POST', 'style'=>"padding:10px;", 'action' => array('StopScanController@stopScanPost', Auth::user()->id ))) }}
    <label>Max Duration: {{$time['maxDisp']}}</label>
    <div class="row">
        <div class="large-6 columns">
            <p>
                {{ Form::label('indefinatecb', 'Indefinitely:') }}
                {{ Form::checkBox('indefinatecb', 'yes', false, $time['extra']) }} {{--extra contains the javascrip function and the disabled flag--}}
            </p>

        </div>
    </div>
    <div class="row">
        <div class="small-4 columns" style="visibility: hidden; display: none">
            <!-- username field -->
            <p>
                {{ Form::label('nodeId', 'Node Id:') }}<br/>
                {{ Form::text('nodeId', $nodeId) }}
            </p>

            <p>
                {{ Form::label('type', 'Type:') }}<br/>
                {{ Form::text('type', $type) }}
            </p>

            <p>
                {{ Form::label('max', 'Max:') }}<br/>
                {{ Form::text('max', $time['max']) }}
            </p>
        </div>
        <div class="small-4 columns">
            <p>
                {{ Form::label('monthNS', 'Months:') }}<br/>
                {{ Form::input('number','monthNS', $type) }}
            </p>

        </div>
        <div class="small-4 columns">
            <p>
                {{ Form::label('weekNS', 'Weeks:') }}<br/>
                {{ Form::input('number','weekNS', $type) }}
            </p>

        </div>
        <div class="small-4 columns">
            <p>
                {{ Form::label('dayNS', 'Days:') }}<br/>
                {{ Form::input('number','dayNS', $type) }}
            </p>

        </div>
        <div class="small-6 columns">
            <p>
                {{ Form::label('hourNS', 'Hours:') }}<br/>
                {{ Form::input('number','hourNS', $type) }}
            </p>

        </div>
        <div class="small-6 columns">
            <p>
                {{ Form::label('minutesNS', 'Minutes:') }}<br/>
                {{ Form::input('number','minutesNS', $type) }}
            </p>

        </div>
    </div>
    <div class="row">
        <div class="small-8 columns">
            <p>
                {{ Form::label('clearAlarms', 'Clear Current Alarms:') }}
                {{ Form::checkBox('clearAlarms', 'yes') }}
            </p>
        </div>
    </div>

    <div class="row" style="width: 100%">
        <div class="large-12 columns" style="width: 100%">
            {{Form::textarea('notes', null, array( 'placeholder'=>"Reason for stopping this scan", "rows" =>"4")) }}
        </div>
    </div>

    <div class="row">
        <p>{{ Form::submit('Submit') }}</p>
    </div>
    {{ Form::close() }}

    <script>
        function calc() {
            document.getElementById("monthNS").disabled = (document.getElementById("indefinatecb").checked);
            document.getElementById("weekNS").disabled = (document.getElementById("indefinatecb").checked);
            document.getElementById("dayNS").disabled = (document.getElementById("indefinatecb").checked);
            document.getElementById("hourNS").disabled = (document.getElementById("indefinatecb").checked);
            document.getElementById("minutesNS").disabled = (document.getElementById("indefinatecb").checked);
        }

    </script>

@stop
