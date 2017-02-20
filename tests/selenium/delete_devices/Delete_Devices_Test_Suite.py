__author__ = 'emily.ford'

import Delete_Devices_Test
import login_test_emf
import unittest
import sys
sys.path.append('..')
import c2_test_suite
#import login_test.EMF_Tests.login_test_emf as login_test_emf


class Delete_Device_Test_Suite(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_test_emf.Login)
        self.add_test(Delete_Devices_Test.DeleteDeviceTestDevice)
        self.finalize_and_run_tests()

if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    Delete_Device_Test_Suite.driver_name = driver_name
    unittest.main()