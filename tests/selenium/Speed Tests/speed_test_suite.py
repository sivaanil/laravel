_author_ = 'Andrew Bascom'

import sys
sys.path.append('../')

import unittest
from login_test import login_success
import alarm_load_speed_test
import network_tree_load_speed_test
import c2_test_suite


class freshBuiltSiteGateTestSuite(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_success.LoginSuccess)
        self.add_test(network_tree_load_speed_test.NetworkTreeLoadSpeedTest)
        self.add_test(alarm_load_speed_test.AlarmLoadSpeedTest)

        self.finalize_and_run_tests()

if __name__ == "__main__":
        driver_name = "firefox"
        if len(sys.argv) > 1:
            driver_name = sys.argv[1]
            sys.argv.pop(1)
        freshBuiltSiteGateTestSuite.driver_name = driver_name
        unittest.main()
