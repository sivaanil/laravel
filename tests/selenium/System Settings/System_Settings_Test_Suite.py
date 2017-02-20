__author__ = 'emily.ford'

import Reboot2
import General
import Reset
import login_test
import unittest
import sys
sys.path.append('..')
import c2_test_suite


class system_settings(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_test.Login)
        self.add_test(General.GeneralSystem)
        self.add_test(Reset.ResetSystem)
        self.add_test(Reboot2.RebootSystem)
        self.finalize_and_run_tests()

if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    system_settings.driver_name = driver_name
    unittest.main()
