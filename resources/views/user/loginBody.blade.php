@extends('Login')

@section('js')
    <script>
        $(document).ready(function () {
            $("#username").jqxInput({});
            $("#password").jqxInput({});

            // open help page when login help button is clicked
            $("#login-help-button").click(function () {
                var link = $('<a href="' + baseUrl + '/pdf/sitegate-help.pdf' + '" />');
                link.attr('target', '_blank');
                window.open(link.attr('href'));
                //$("#login-box form").submit();
            });

            // submit form when login button is pressed
            $("#login-button").click(function () {
                $("#login-box form").submit();
            });
            // submit form when enter is pressed
            $('input[name=password]').keypress(function (e) {
                if (e.which == 13) {
                    $('#login-box form').submit();
                    return false;
                }
            });
        });
    </script>
@stop

@section('content')
    @include('partials.flashError')
    {!! Form::open(array('url'=>'login', 'method'=>'POST')) !!}
    <div id="login-box-inner">
        <div id="login-credentials-label">
            {!!trans('login.credentials')!!}
        </div>
        <table>
            <!-- username field -->
            <tr>
                <td>{!! Form::label('username', trans('login.username')) !!}</td>
                <td>{!! Form::text('username', Input::old('username'), array('autofocus'=>'autofocus',
                    'autocomplete'=>'off', 'style'=>'width:50%; min-width:300px;')) !!}
                </td>
            </tr>
            <!-- password field -->
            <tr>
                <td>{!! Form::label('password', trans('login.password'))!!}</td>
                <td>{!! Form::password('password', array('autocomplete'=>'off', 'style'=>'width:50%;
                    min-width:300px;')) !!}
                </td>
            </tr>
        </table>
        <!-- Hidden Inputs -->
        {!! Form::hidden('attempt',Session::get('attempt')) !!}

        @if (getenv('C2_SERVER_TYPE') == 'sitegate')
            <div id="login-help-button">
                <img src="./img/icons/help.png"> Help
            </div>
        @endif
        <!-- submit button -->
        <div id="login-button">
            <img src="./img/icons/key.png"> Log In
        </div>
    </div>
    {!! Form::close() !!}
@stop