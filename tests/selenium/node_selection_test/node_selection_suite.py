__author__ = 'daniel.madden'

import unittest
import click_node_selection_test
import node_selection_grid_test
import sys
sys.path.append("..")
import c2_test_suite
import login_test.login_success as login_success


class NodeSelectionTestSuite(c2_test_suite.C2TestSuite):

    def test_all(self):
        self.add_test(login_success.LoginSuccess)
        self.add_test(click_node_selection_test.ClickNodeSelection)
        self.add_test(node_selection_grid_test.NodeSelectionGridTest)

        self.finalize_and_run_tests()

if __name__ == "__main__":
        driver_name = "firefox"
        if len(sys.argv) > 1:
            driver_name = sys.argv[1]
            sys.argv.pop(1)
        NodeSelectionTestSuite.driver_name = driver_name
        unittest.main()