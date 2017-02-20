__author__ = 'emily.ford'

import scan_devices_test
import Delete_Devices_Test
import build_devices_test
import login_test_emf
import unittest
import sys
sys.path.append('..')
import c2_test_suite


class add_specific_device_test(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_test_emf.Login)
        #self.add_test(Delete_Devices_Test.DeleteDeviceTestDevice)
        #self.add_test(build_devices_test.AddDeviceTestDevice)
        self.add_test(scan_devices_test.ScanDevice)
        self.finalize_and_run_tests()

if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    add_specific_device_test.driver_name = driver_name
    unittest.main()


