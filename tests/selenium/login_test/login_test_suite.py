__author__ = 'daniel.madden'
import unittest
import login_general
import logout_general
import login_reset_password

import sys
sys.path.append('../')
import c2_test_suite


class LoginTestSuite(c2_test_suite.C2TestSuite):

    def test_all(self):
        # Login
        self.add_test(login_general.LoginGeneral)
        self.add_test(login_general.LoginGeneralCleanup)

        # Logout
        self.add_test(logout_general.LogoutGeneral)

        # Reset Password
        self.add_test(login_reset_password.LoginResetPasswordSetup)
        self.add_test(login_reset_password.LoginResetPasswordGeneral)
        self.add_test(login_reset_password.login_reset_password_cleanup)


        self.finalize_and_run_tests()

if __name__ == "__main__":
        driver_name = "firefox"
        if len(sys.argv) > 1:
            driver_name = sys.argv[1]
            sys.argv.pop(1)
        LoginTestSuite.driver_name = driver_name
        unittest.main()
