@extends('layouts.master')
@section('body')
    @yield('js')
    <div id="login-box-wrapper">
        <div id="login-box">
            <img id="banner" src="./img/img/c2_banner_logo_{{$_ENV['C2_SERVER_TYPE']}}.png">
            <!-- check for login error flash var -->

            @yield('content')
        </div>
    </div>
@stop