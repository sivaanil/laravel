@extends('layouts.master')
@section('content')

    <h4>Location & Access Info</h4>
    <div id="deviceInfoContainer">

        <dl>
            <dt>Street:</dt>
            <dd>{{$street}}</dd>
            <dt>City:</dt>
            <dd>{{$city}}</dd>
            <dt>State:</dt>
            <dd>{{$state}}</dd>
            <dt>Zip:</dt>
            <dd>{{$zip}}</dd>
            <dt>Country:</dt>
            <dd>{{$country}}</dd>
            <dt>Latitude:</dt>
            <dd>{{$latitude}}</dd>
            <dt>Longitude</dt>
            <dd>{{$longitude}}</dd>
            <dt>Description</dt>
            <dd>{{$description}}</dd>
        </dl>
    </div>
@stop