import sys
sys.path.append('../')

import login_test.login_success as login_success

import unittest
import c2_test_suite
import lan_settings_general
import lan_settings_canvas
import lan_settings_errors


class add_device_test(c2_test_suite.C2TestSuite):

    def test_all(self):
        # Login
        self.add_test(login_success.LoginSuccess)

        # General
        self.add_test(lan_settings_general.LanSettingsGeneral)

        # Canvas
        self.add_test(lan_settings_canvas.LanSettingsCanvas)

        # Errors
        self.add_test(lan_settings_errors.LanSettingsErrors)

        self.finalize_and_run_tests()


if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    add_device_test.driver_name = driver_name
    unittest.main()