__author__ = 'daniel.madden'
import unittest
import sys
import login_test.login_test_suite as login_test_suite
import node_selection_test.node_selection_suite as node_selection_suite
import c2_test_suite


class AllTestsSuite(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_test_suite.LoginTestSuite)
        self.add_test(node_selection_suite.NodeSelectionTestSuite)

        self.finalize_and_run_tests()

if __name__ == "__main__":
    driver_name = "firefox"
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    AllTestsSuite.driver_name = driver_name
    unittest.main()

