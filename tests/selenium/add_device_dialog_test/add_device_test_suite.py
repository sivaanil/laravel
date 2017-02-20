import sys
sys.path.append('../')

import login_test.login_success as login_success
import add_device_dialog_general
import add_device_dialog_device_info
import add_device_dialog_device_info_build

import unittest
import c2_test_suite


class add_device_test(c2_test_suite.C2TestSuite):

    def test_all(self):
        # Login
        self.add_test(login_success.LoginSuccess)

        # General
        self.add_test(add_device_dialog_general.AddDeviceDialogGeneral)

        # Device Information
        self.add_test(add_device_dialog_device_info.AddDeviceDialogDeviceInfo)
        self.add_test(add_device_dialog_device_info_build.AddDeviceDialogDeviceInfoBuild)

        self.finalize_and_run_tests()


if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    add_device_test.driver_name = driver_name
    unittest.main()