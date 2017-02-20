_author_ = 'Andrew Bascom'

import sys
sys.path.append('../')

import unittest
from login_test import login_success
from fresh_built_sitegate_tests import load_system_settings
from fresh_built_sitegate_tests import add_test_device
from fresh_built_sitegate_tests import scan_test_device
from fresh_built_sitegate_tests import alarm_population_test
from fresh_built_sitegate_tests import gaucamole_xterm_load_test
import c2_test_suite


class freshBuiltSiteGateTestSuite(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_success.LoginSuccess)
        self.add_test(load_system_settings.LoadSystemSettingsTest)
        self.add_test(add_test_device.AddTestDevice)
        self.add_test(scan_test_device.ScanTestDevice)
        self.add_test(alarm_population_test.AlarmPopulationTest)
        self.add_test(gaucamole_xterm_load_test.GaucamoleXtermLoadTest)

        self.finalize_and_run_tests()

if __name__ == "__main__":
        driver_name = "firefox"
        if len(sys.argv) > 1:
            driver_name = sys.argv[1]
            sys.argv.pop(1)
        freshBuiltSiteGateTestSuite.driver_name = driver_name
        unittest.main()
