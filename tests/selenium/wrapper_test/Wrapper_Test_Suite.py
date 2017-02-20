__author__ = 'emily.ford'

import Build
import Guacamole
import Scan
import Remove
import breadcrumb_test
import login_test
import unittest
import sys
import switching_between_canvases_test
sys.path.append('..')
import c2_test_suite


class device_info_test(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_test.Login)
        self.add_test(switching_between_canvases_test.SwitchCanvases)
        self.add_test(breadcrumb_test.BreadCrumb)
        self.add_test(Build.BuildWrapper)
        self.add_test(Scan.ScanWrapper) #fix the scan tsun4 issue then this is done
        self.add_test(Remove.RemoveWrapper)
        #self.add_test(Guacamole.GuacWrapper) #make this
        self.finalize_and_run_tests()

if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    device_info_test.driver_name = driver_name
    unittest.main()