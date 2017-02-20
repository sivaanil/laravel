@extends('Login')

@section('js')
    <script>
        $(document).ready(function () {
            $("#old_password").jqxInput({});
            $("#new_password").jqxInput({});
            $("#new_password_confirm").jqxInput({});

            $("#login-button").click(function () {
                if ($("#new_password").val() != $("#new_password_confirm").val()) {
                    $('#flash_error').html('New passwords do not match');
                } else {
                    $("#login-box form").submit();
                }
            })
            // submit form when enter is pressed
            $('input[name=new_password_confirm]').keypress(function (e) {
                if (e.which == 13) {
                    if ($("#new_password").val() != $("#new_password_confirm").val()) {
                        $('#flash_error').html('New passwords do not match');
                    } else {
                        $("#login-box form").submit();
                    }
                    return false;
                }
            })
        });
    </script>
@stop

@section('content')
    {!! Form::open(array('url'=>'reset', 'method'=>'POST')) !!}
    <div id="login-box-inner">
        <!-- check for login error flash var -->
        @include('partials.flashMessage')
        @include('partials.flashError')
        <table>
            <tr>
                <td>{!! Form::label('old_password', trans('login.old_password')) !!}</td>
                <td>{!! Form::password('old_password',['autofocus'=>'autofocus','autocomplete'=>'off',
                    'style'=>'width:50%; min-width:300px;']) !!}
                </td>
            </tr>
            <tr>
                <td>{!! Form::label('new_password', trans('login.new_password')) !!}</td>
                <td>{!! Form::password('new_password',['autocomplete'=>'off',
                    'style'=>'width:50%; min-width:300px;']) !!}
                </td>
            </tr>
            <tr>
                <td>{!! Form::label('new_password_confirm', trans('login.new_password_confirm')) !!}</td>
                <td>{!! Form::password('new_password_confirm',['autocomplete'=>'off', 'style'=>'width:50%;
                    min-width:300px;']) !!}
                </td>
            </tr>
        </table>
        <!-- submit button -->
        <div id="login-button">
            {!! Form::hidden('username', Session::get('username'))!!}
            <img src="./img/icons/key.png"> {!! Form::label('reset_password', trans('login.resetPassword')) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop