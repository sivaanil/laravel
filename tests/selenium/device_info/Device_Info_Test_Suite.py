__author__ = 'emily.ford'
import click_device_info
import device_info_canvas
import unittest
import sys
sys.path.append("..")
import c2_test_suite
import login_test.login_success as login_success


class Device_Info_Test_Suite(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_success.LoginSuccess)
        self.add_test(click_device_info.Click_Device_Info)
        self.finalize_and_run_tests()

if __name__ == "__main__":
        driver_name = "firefox"
        if len(sys.argv) > 1:
            driver_name = sys.argv[1]
            sys.argv.pop(1)
        Device_Info_Test_Suite.driver_name = driver_name
        unittest.main()