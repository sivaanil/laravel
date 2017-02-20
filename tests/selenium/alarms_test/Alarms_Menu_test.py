__author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException

import unittest, time


class AlarmsMenuTest(c2_test_case.C2TestCase):
    def test_uncheck_auto_resize_ensure_it_stays_checked_C10826(self):
        #Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs
        AlarmsMenuTest.change_panel_widths(self, driver)

        # Wait for the auto resize checkbox to be displayed then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGrid_resizeCB"))
            )
        except TimeoutException:
            self.fail("Alarm grid Auto Resize checkbox could not be found within the allotted " + str(self.config.mid_timeout) + " seconds")
        auto_resize_button = driver.find_element_by_id("alarmsGrid_resizeCB")

        # If the auto resize chackbox is selected click it to deselect it
        if (auto_resize_button.is_selected() == True):
            auto_resize_button.click()

        # Wait till the divider is available and store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='splitter']/div[2]"))
            )
        except TimeoutException:
            self.fail("The canvas divider did not load within " + str(self.config.mid_timeout) + " seconds")
        divider_element = driver.find_element_by_xpath("//div[@id='splitter']/div[2]")

        # Emulate moving the mouse to the divider and move it off to the dragable part
        actions = ActionChains(driver)
        actions.move_to_element(divider_element)
        actions.move_by_offset(0, 120)
        actions.perform()

        # Emulate clicking down on the divider
        actions = ActionChains(driver)
        actions.click_and_hold()
        actions.perform()

        # Emulate moving the mouse to the right 150 pixels
        for index in range(0, 150):
            actions = ActionChains(driver)
            actions.move_by_offset(1, 0)
            actions.perform()

        # Emulate the mouse releasing the divider
        actions = ActionChains(driver)
        actions.release()
        actions.perform()

        # Wait for the collapsed options menu button to display and click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmMenu"))
            )
        except TimeoutException:
            self.fail("alarm menu button not displaying in the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("alarmMenu").click()

        # Wait for the auto resize button to be displayed and store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGrid_resizeCB"))
            )
        except TimeoutException:
            self.fail("Alarm grid auto resize checkbox did not display within the allotted " + str(self.config.mid_timeout) + " seconds")
        auto_resize_button = driver.find_element_by_id("alarmsGrid_resizeCB")

        # Check that the auto resize button is not selected if it is selected fail the test
        self.assertEqual(auto_resize_button.is_selected(), False, "Auto Resize button state did not remain set as expected")

    def test_select_columns_displays_dialog_C10218(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs, and click the select column button
        AlarmsMenuTest.change_panel_widths(self, driver)
        driver.find_element_by_id("alarmsGridColumnButton").click()

        # Wait for the select column dialog to display and then click its close button
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )
        except TimeoutException:
            self.fail("The Select Columns dialog did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_xpath("//div[@id='alarmsGridColumnPopup']/div/div/div[2]/div").click()

    def test_clicking_excel_export_generates_excel(self):
        # Get the driver
        driver = self.config.driver

        # Get the number of windows currently open
        num_windows_open = len(driver.window_handles)

        # Move the divider to ensure best access to all buttons, tabs, and columns; Wait for the export excel button to display then click it
        AlarmsMenuTest.change_panel_widths(self, driver)
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGridExcelExportButton"))
            )
        except TimeoutException:
            self.fail("Export button didn't unhide")
        driver.find_element_by_id("alarmsGridExcelExportButton").click()

        # Check every second for the number of windows open to become greater then the original number of windows open. After the max amount
        # of time if the number of windows open didn't increase fail the test. (A window opens briefly when a download link is clicked)
        for sec in range(0, self.config.long_timeout):
            window_list = driver.window_handles
            if (len(window_list) >= num_windows_open + 1):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("Excel download page did not open within the allotted " + str(self.config.long_timeout) + " seconds")
            time.sleep(1)

    def test_select_active_alarms_tab(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs
        AlarmsMenuTest.change_panel_widths(self, driver)

        # Wait for the active tab to display and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-active-alarms"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "tab-active-alarms"))
            )
        except TimeoutException:
            self.fail("Active Alarms tab did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("tab-active-alarms").click()

        # Wait for the alarm grid to update
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Find the select columns button and click it
        driver.find_element_by_id("alarmsGridColumnButton").click()

        # Wait for the select columns dialog to display
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )
        except TimeoutException:
            self.fail("The Select Columns dialog did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")

        # Get the list of available columns and loop through it to find the Cleared Time column then click it
        available_columns = driver.find_element_by_id("alarmsGrid_availableColumnsList").find_elements_by_tag_name("li")
        for column in available_columns:
            if (column.text == "Cleared Time"):
                column.click()
                break

        # Find the add column button and click it then close the select column dialog. Wait for the alarm grid to update.
        driver.find_element_by_id("alarmsGrid_selectButtonAdd").click()
        driver.find_element_by_xpath("//div[@id='alarmsGridColumnPopup']/div/div/div[2]/div").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Get the alarm grid columns then loop through them
        alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
        for index in range(0, len(alarm_columns)):
            alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
            column = alarm_columns[index]

            # find the Cleared Time column then loop 3 times, clicking the column header to sort
            if (column.text == "Cleared Time"):
                for num in range(0, 3):
                    alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
                    column = alarm_columns[index]
                    column.click()

                    # Wait for the alarm grid to update then ensure the alarm has no clear date, if it does fail the test
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                        )
                    except TimeoutException:
                        self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")
                    self.assertEqual(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[6]/div").text, "",
                                    "Found alarm that is not Active, test failed!")
                break

    def test_select_alarm_history_tab_C10231(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs
        AlarmsMenuTest.change_panel_widths(self, driver)

        # Wait for the cleared tab to display and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-cleared-alarms"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "tab-cleared-alarms"))
            )
        except TimeoutException:
            self.fail("Alarm History tab did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("tab-cleared-alarms").click()

        # Wait for the alarm grid to update
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Get the alarm columns and loop through them
        alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
        for index in range(0, len(alarm_columns)):
            alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
            column = alarm_columns[index]

            # Find the Cleared Time column then loop 3 times clicking the header to sort
            if (column.text == "Cleared Time"):
                for num in range(0, 3):
                    alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
                    column = alarm_columns[index]
                    column.click()

                    # Wait for the alarm grid to update then check that there is a date if not then fail the test.
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                        )
                    except TimeoutException:
                        self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")
                    self.assertNotEqual(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[6]/div").text, "",
                                    "Found alarm that is not cleared, test failed!")
                break

    def test_select_all_alarms_tab_C10229(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs
        AlarmsMenuTest.change_panel_widths(self, driver)

        # Wait for the all alarms tab to display and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-all-alarms"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "tab-all-alarms"))
            )
        except TimeoutException:
            self.fail("All Alarms tab did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("tab-all-alarms").click()

        # Wait for the alarm grid to update
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Get the alarm columns and loop through them
        alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
        for index in range(0, len(alarm_columns)):
            alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
            column = alarm_columns[index]

            # Find the Cleared Time column then loop 3 times clicking the header to sort
            if (column.text == "Cleared Time"):
                for num in range(0, 3):
                    alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
                    column = alarm_columns[index]
                    column.click()

                    # Wait for the alarm grid to update
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                        )
                    except TimeoutException:
                        self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

                    # If the sort is ascending then the first alarm should be without a cleared date, check this if failed fail the test. If
                    # the sort is descending then the first alarm should have a cleared date, check this if failed then fail the test.
                    if (index == 0):
                        self.assertEqual(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[6]/div").text, "",
                                    "Found alarm that is not cleared, test failed!")
                    elif (index == 1):
                        self.assertNotEqual(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[6]/div").text, "",
                                    "Found alarm that is not cleared, test failed!")
                break

    # This test case is unfinished since there is no way to distingish an ignored alarm from a regular one
    def test_select_ignored_alarms_tab_C10232(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs
        AlarmsMenuTest.change_panel_widths(self, driver)

        # Wait for the ignored tab to display and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-ignored-alarms"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "tab-ignored-alarms"))
            )
        except TimeoutException:
            self.fail("Ignored Alarms tab did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("tab-ignored-alarms").click()

        # Wait for the alarm grid to update
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Get the columns from the alarm grid and loop through them
        alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
        for index in range(0, len(alarm_columns)):
            alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
            column = alarm_columns[index]

            # Find the severity column then break
            if (column.text == "Severity"):
                break

    def test_select_custom_filters_tab_C10233(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the custom tab to load and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-custom-filters"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "tab-custom-filters"))
            )
        except TimeoutException:
            self.fail("Custom filters tab did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("tab-custom-filters").click()

        # Wait for the custom filters dialog to display and then click its close button
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsFilter"))
            )
        except TimeoutException:
            self.fail("Custom filters dialog didn't load within the allotted " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_xpath("//div[@id='alarmsFilter']/div/div/div[2]/div").click()

    def test_reset_columns_button_C135244(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to allow room for best viewing of the buttons, columns, and tabs
        AlarmsMenuTest.change_panel_widths(self, driver)

        # Find the select column button and click it then wait for the select column dialog to display
        driver.find_element_by_id("alarmsGridColumnButton").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )
        except TimeoutException:
            self.fail("The Select Columns dialog did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")

        # Get the available columns and find the Cleared Time column click it and then click the add button. Close the select columns dialog
        # and then wait for the alarm grid to update
        available_columns = driver.find_element_by_id("alarmsGrid_availableColumnsList").find_elements_by_tag_name("li")
        for column in available_columns:
            if (column.text == "Cleared Time"):
                column.click()
                break
        driver.find_element_by_id("alarmsGrid_selectButtonAdd").click()
        driver.find_element_by_xpath("//div[@id='alarmsGridColumnPopup']/div/div/div[2]/div").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Find and click the reset button then wait for the alarm grid to update
        driver.find_element_by_id("alarmsGridResetColumnsButton").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")

        # Get the alarm columns and loop through them, check that there is no cleared time column, if there is fail the test.
        alarm_columns = AlarmsMenuTest.get_alarm_columns(self, driver)
        for column in alarm_columns:
            self.assertNotEqual(column.text, "Cleared Time", "The Alarm grid columns did not reset.")





    ## helper methods ##
    def change_panel_widths(self, web_driver):
        # Wait for the splitter to be available and then store it.
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='splitter']/div[2]"))
            )
        except TimeoutException:
            self.fail("The canvas divider did not load within " + str(self.config.mid_timeout) + " seconds")
        divider_element = web_driver.find_element_by_xpath("//div[@id='splitter']/div[2]")

        # Find the location of the divider horizontally, check that it isn't more then the max chosen to allow best viewing of the grid (309).
        left_pos = int(divider_element.value_of_css_property("left").replace("px", ""))
        if (left_pos > 309):
            # Set up an action chain to emulate moving the mouse to the divider and offsetting it a bit.
            actions = ActionChains(web_driver)
            actions.move_to_element(divider_element)
            actions.move_by_offset(0, 120)
            actions.perform()

            # Set up an action chain to emulate holding down on the mouse's location
            actions = ActionChains(web_driver)
            actions.click_and_hold()
            actions.perform()

            # loop through the necessary amount of pixels to get the divider to the intended location. On each iteration set up an action
            # chain to emulate moving the mouse by -1 pixel. (I'm not sure why you can't just emulate the whole movement at once, but I
            # tried and it wouldn't work, for some reason this does so I go with what works)
            for index in range(0, left_pos - 309):
                actions = ActionChains(web_driver)
                actions.move_by_offset(-1, 0)
                actions.perform()

            # Set up an action chain to emulate releasing the mouse.
            actions = ActionChains(web_driver)
            actions.release()
            actions.perform()

            # Lastly check the position of the divider every second just to make sure it is in the right location before leaving the function.
            for sec in range(0, self.config.mid_timeout):
                left_pos = int(divider_element.value_of_css_property("left").replace("px", ""))
                if (left_pos <= 309):
                    break
                time.sleep(1)

    def get_alarm_columns(self, web_driver):
        # Wait for the column headers to load then store em.
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "columntablealarmsGrid"))
            )
        except TimeoutException:
            self.fail("column headers did not load within " + str(self.config.mid_timeout) + " seconds")
        column_header_container_element = web_driver.find_element_by_id("columntablealarmsGrid")

        # Return a list of each column header
        return column_header_container_element.find_elements_by_css_selector('[role="columnheader"]')

if __name__ == "__main__":
    unittest.main()