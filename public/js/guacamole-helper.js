$(document).ready(function () {

    var getCookie = function (a, b) {
        b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : '';
    };

    var deleteCookie = function (name) {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    };

    var checkExist = setInterval(function () {
        var userNameInput = $(".login-form input[type='text']");

        if (userNameInput.length) {
            clearInterval(checkExist);

            var username = getCookie('guacuser');
            var pwd = getCookie('guacpwd');

            if (username && pwd) {

                userNameInput.val(username).change();

                passwordInput = $(".login-form input[type='password']");

                passwordInput.change(function () {
                    deleteCookie('guacuser');
                    deleteCookie('guacpwd');
                    $('form.login-form .login').click();
                });

                passwordInput.val(pwd).change();
            }
        }

    }, 100);

    var guaccid = getCookie('guaccid');

    function sendPing() {
        $.post('/browserping', {id: guaccid});
    }

    // send ping every x seconds (currently 60)
    var pingInterval = window.setInterval(sendPing, 60000);
});
