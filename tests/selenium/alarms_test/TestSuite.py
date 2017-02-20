_author_ = 'Andrew Bascom'

import sys
sys.path.append('../')

import unittest
import login_test.login_success
import Alarms_General_test
import Alarms_Filters_test
import Alarm_Grid_test
import Alarm_Grid_Page_Controls_test
import Alarm_Grid_Actions_Menu_test
import Alarms_Menu_test
import Alarms_Menu_Display_Filters_test
import Alarms_Menu_Select_Columns_test

import c2_test_suite


class AlarmsTestSuite(c2_test_suite.C2TestSuite):
    def test_all(self):
        # Open Unified
        self.add_test(login_test.login_success.LoginSuccess)

        # General
        self.add_test(Alarms_General_test.AlarmsGeneralTest)

        # Filters
        self.add_test(Alarms_Filters_test.AlarmsFiltersTest)

        # Grid
        self.add_test(Alarm_Grid_test.AlarmGridTest)
        self.add_test(Alarm_Grid_Page_Controls_test.AlarmGridPageControlsTest)
        self.add_test(Alarm_Grid_Actions_Menu_test.AlarmGridActionsMenuTest)
        self.add_test(Alarm_Grid_Actions_Menu_test.AlarmGridActionsMenuTestCleanup)

        # Menu
        self.add_test(Alarms_Menu_test.AlarmsMenuTest)
        self.add_test(Alarms_Menu_Display_Filters_test.AlarmsMenuDisplayFiltersTest)
        self.add_test(Alarms_Menu_Select_Columns_test.AlarmsMenuSelectColumnsTest)

        self.finalize_and_run_tests()


if __name__ == '__main__':
    driver_name = 'firefox'
    if len(sys.argv) > 1:
        driver_name = sys.argv[1]
        sys.argv.pop(1)
    AlarmsTestSuite.driver_name = driver_name
    unittest.main()
