@extends('wizard::layouts.master')

@section('content')

    <script src="{!! asset('js/vendor/jquery.js') !!}"></script>
    <script src="{!! asset('modules/wizard/js/jquery.smartWizard.js') !!}"></script>
    <link rel="stylesheet" href="{!! asset('modules/wizard/css/smart_wizard.css') !!}" />
    <script>
        $(function() {
            $('#wizard').smartWizard({
                transitionEffect:'slideleft',
                onLeaveStep: leaveStepCallback,
                onFinish: finishCallback,
                enableFinishButton: true
            });
        });

        // Called whenever a step is exited in the wizard.
        function leaveStepCallback(obj) {
            var step = obj.attr('step');
            return validateStep(step);
        }

        function finishCallback(obj) {
            return true;
        }

        // Validates a specific step number
        function validateStep(step) {
            var isValid = true;
            switch (step) {
                case 1:

                    break;
                case 2:

                    break;
            }
            return isValid;
        }
    </script>


	<h1>Wizard Component Example Page</h1>

	<p>
        This page provides an example of a wizard with step validation and a finish callback.
	</p>
    <!-- Begin wizard HTML -->
    <div id="wizard" class="swMain">
        <ul>
            <li><a href="#step-1">
            <label class="stepNumber">1</label>
            <span class="stepDesc">
               Account Details
            </span>
            </a></li>
            <li><a href="#step-2">
            <label class="stepNumber">2</label>
            <span class="stepDesc">
               Profile Details
            </span>
            </a></li>
        </ul>
        <div id="step-1">
            <h2 class="StepTitle">Step 1: Account Details</h2>
            <table cellspacing="3" cellpadding="3" align="center">
                <tr>
                    <td align="center" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Username :</td>
                    <td align="left">
                        <input type="text" id="username" name="username" value="" class="txtBox">
                    </td>
                    <td align="left"><span id="msg_username"></span>&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Password :</td>
                    <td align="left">
                        <input type="password" id="password" name="password" value="" class="txtBox">
                    </td>
                    <td align="left"><span id="msg_password"></span>&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Confirm Password :</td>
                    <td align="left">
                        <input type="password" id="cpassword" name="cpassword" value="" class="txtBox">
                    </td>
                    <td align="left"><span id="msg_cpassword"></span>&nbsp;</td>
                </tr>
            </table>
        </div>

        <div id="step-2">
            <h2 class="StepTitle">Step 2: Profile Details</h2>
            <table cellspacing="3" cellpadding="3" align="center">
                <tr>
                    <td align="center" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">First Name :</td>
                    <td align="left">
                        <input type="text" id="firstname" name="firstname" value="" class="txtBox">
                    </td>
                    <td align="left"><span id="msg_firstname"></span>&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Last Name :</td>
                    <td align="left">
                        <input type="text" id="lastname" name="lastname" value="" class="txtBox">
                    </td>
                    <td align="left"><span id="msg_lastname"></span>&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Gender :</td>
                    <td align="left">
                        <select id="gender" name="gender" class="txtBox">
                            <option value="">-select-</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </td>
                    <td align="left"><span id="msg_gender"></span>&nbsp;</td>
                </tr>
            </table>
        </div>

    </div>
@stop
