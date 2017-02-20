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

import time
import unittest


class AlarmsMenuDisplayFiltersTest(c2_test_case.C2TestCase):
    def test_choosing_filter_reflects_on_grid_C10216(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider to the left to view all the tabs, buttons, and columns;
        # and open the Custom Filters dialog
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        AlarmsMenuDisplayFiltersTest.change_panel_widths(self, driver)
        display_filters_dialog_element = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)

        # Store an array for the severity options to be deselected; then loop through the array and click the severity checkboxes to
        # deselect them.
        deselect_severity_array = ["Critical", "Major", "Minor", "Warning"]
        for deselect_severity in deselect_severity_array:
            display_filters_dialog_element.find_element_by_id("alarmsFilter_" + deselect_severity.lower() + "_CB").click()

        # Wait for the alarm grid to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after filters were set")

        # Loop through the alarms in the grid and if an alarm doesn't have severity text then the loop will be broken.
        for index in range(0, 10):
            alarm_severity = driver.find_element_by_xpath("//div[@id='row" + str(index) + "alarmsGrid']/div[7]").text
            if (alarm_severity == ""):
                break

            # Loop through the severities we deselected and check that the alarm isn't that severity, if it is fail the test.
            for deselect_severity in deselect_severity_array:
                self.assertNotEqual(alarm_severity, deselect_severity, "Alarm grid did not update according to the selected filter.")

        # Loop through the severity checkboxes to reselect them and then reset the alarm grid for the next test
        for deselect_severity in deselect_severity_array:
            display_filters_dialog_element.find_element_by_id("alarmsFilter_" + deselect_severity.lower() + "_CB").click()
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)

    def test_filters_should_be_checked_according_to_tab_active_C10267(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid for this test (just in case the previous one did not) and open the custom filters dialog
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)

        # Click the active tab to display only active alarms and wait for the alarm grid to load
        driver.find_element_by_id("tab-active-alarms").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after tab was switched")

        # Loop through all the checkboxes in the custom dialog and ensure that only the Cleared and All States checkbox is not selected
        # otherwise fail the test.
        custom_filters_dialog_checkbox_label_array = custom_filters_dialog.find_elements_by_tag_name("label")
        for label in custom_filters_dialog_checkbox_label_array:
            label_text = AlarmsMenuDisplayFiltersTest.cut_off_string_at(self, label.text, '(')
            if (label_text == "Cleared " or label_text == "All States"):
                self.assertEqual(label.find_element_by_tag_name("input").is_selected(), False,
                                 "Selected filters for Active Alarms are incorrect: " + label_text + " checkbox is selected")
            else:
                self.assertEqual(label.find_element_by_tag_name("input").is_selected(), True,
                                 "Selected filters for Active Alarms are incorrect: " + label_text + " checkbox is not selected")

        # Reset the alarm grid for the next test.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)


    def test_filters_should_be_checked_according_to_tab_cleared_C10267(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't, then open the custom filters dialog, lastly click the cleared tab
        # and wait for the alarm grid to update.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)
        driver.find_element_by_id("tab-cleared-alarms").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after tab was switched")

        # Loop through all the checkboxes in the custom dialog and ensure that only the Active and All States checkbox are not selected
        # otherwise fail the test.
        custom_filters_dialog_checkbox_label_array = custom_filters_dialog.find_elements_by_tag_name("label")
        for label in custom_filters_dialog_checkbox_label_array:
            label_text = AlarmsMenuDisplayFiltersTest.cut_off_string_at(self, label.text, '(')
            if (label_text == "Active " or label_text == "All States"):
                self.assertEqual(label.find_element_by_tag_name("input").is_selected(), False,
                                 "Selected filters for Active Alarms are incorrect: " + label_text + " checkbox is selected")
            else:
                self.assertEqual(label.find_element_by_tag_name("input").is_selected(), True,
                                 "Selected filters for Active Alarms are incorrect: " + label_text + " checkbox is not selected")

        # Reset the alarm grid for the next test.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)


    def test_filters_should_be_checked_according_to_tab_all_C10267(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't, then open the custom filters dialog, lastly click the all alarms tab
        # and wait for the alarm grid to update.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)
        driver.find_element_by_id("tab-all-alarms").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after tab was switched")

        # Loop through all the checkboxes in the custom dialog and ensure that all checkboxes are selected otherwise fail the test.
        custom_filters_dialog_checkbox_label_array = custom_filters_dialog.find_elements_by_tag_name("label")
        for label in custom_filters_dialog_checkbox_label_array:
            label_text = AlarmsMenuDisplayFiltersTest.cut_off_string_at(self, label.text, '(')
            self.assertEqual(label.find_element_by_tag_name("input").is_selected(), True,
                             "Selected filters for Active Alarms are incorrect: " + label_text + " checkbox is not selected")

        # Close the custom filters dialog and go to the active alarms tab finish by waiting for the alarm grid to update
        custom_filters_dialog.find_element_by_xpath("//div[@id='windowHeader']/div[2]/div").click()
        driver.find_element_by_id("tab-active-alarms").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after filters were cleared")

    def test_filters_should_be_checked_according_to_tab_ignored_C10267(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't, then open the custom filters dialog, lastly click the ignored tab
        # and wait for the alarm grid to update.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)
        driver.find_element_by_id("tab-ignored-alarms").click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after tab was switched")

        # Loop through all the checkboxes in the custom dialog and ensure that only the Ignored and Show Normal checkbox are not selected
        # otherwise fail the test.
        custom_filters_dialog_checkbox_label_array = custom_filters_dialog.find_elements_by_tag_name("label")
        first_show_normal = True
        for label in custom_filters_dialog_checkbox_label_array:
            label_text = AlarmsMenuDisplayFiltersTest.cut_off_string_at(self, label.text, '(')
            if (label_text == "Ignored" or label_text == "Show Normal "):
                # This check is because there are two labels on the custom filters dialog that are "Ignored" and this check ensures that the
                # second one (the one we want) is checked.
                if (first_show_normal == True):
                    first_show_normal = False
                else:
                    self.assertEqual(label.find_element_by_tag_name("input").is_selected(), False,
                                    "Selected filters for Ignored Alarms are incorrect: " + label_text + " checkbox is selected")
            else:
                self.assertEqual(label.find_element_by_tag_name("input").is_selected(), True,
                                 "Selected filters for Ignored Alarms are incorrect: " + label_text + " checkbox is not selected")

        # Reset the alarm grid for the next test.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)

    def test_tabs_should_change_based_on_filters_selected_C11494(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't and open the custom filters dialog
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)

        # Store the cleared, active, and ignored checkboxes
        cleared_checkbox = custom_filters_dialog.find_element_by_id("alarmsFilter_cleared_CB")
        active_checkbox = custom_filters_dialog.find_element_by_id("alarmsFilter_active_CB")
        show_not_ignored_checkbox = custom_filters_dialog.find_element_by_id("alarmsFilter_excludeIgnored_CB")

        # Store the active, history, all alarms, ignored, and custom tabs
        active_tab = driver.find_element_by_id("tab-active-alarms")
        history_tab = driver.find_element_by_id("tab-cleared-alarms")
        all_tab = driver.find_element_by_id("tab-all-alarms")
        ignored_tab = driver.find_element_by_id("tab-ignored-alarms")
        custom_tab = driver.find_element_by_id("tab-custom-filters")

        # Alarm History
        # Click the checkboxes that should select the alarm history (cleared) tab
        cleared_checkbox.click()
        active_checkbox.click()

        # Wait for the alarm grid to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after filters were cleared")

        # Check that the active tab is no longer selected and the cleared tab is selected, otherwise fail the test
        self.assertEqual(active_tab.value_of_css_property("background-color"), "rgba(187, 187, 187, 1)",
                         "Active alarms tab is selected when it should not be.")
        self.assertEqual(history_tab.value_of_css_property("background-color"), "rgba(238, 238, 238, 1)",
                         "Alarm History tab is deselected when it should not be.")

        # Active Alarms
        # Click the checkboxes that should select the active tab
        cleared_checkbox.click()
        active_checkbox.click()

        # Wait for the alarm grid to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after filters were cleared")

        # Check that the cleared tab is no longer selected and the active tab is selected, otherwise fail the test
        self.assertEqual(active_tab.value_of_css_property("background-color"), "rgba(238, 238, 238, 1)",
                         "Active alarms tab is deselected when it should not be.")
        self.assertEqual(history_tab.value_of_css_property("background-color"), "rgba(187, 187, 187, 1)",
                         "Alarm History tab is selected when it should not be.")

        # All Alarms
        # Click the checkbox that would select the all alarms tab then wait for the alarm grid to update
        cleared_checkbox.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after filters were cleared")

        # Check that the active tab is no longer selected and the all alarms tab is selected, otherwise fail the test
        self.assertEqual(active_tab.value_of_css_property("background-color"), "rgba(187, 187, 187, 1)",
                         "Active alarms tab is selected when it should not be.")
        self.assertEqual(all_tab.value_of_css_property("background-color"), "rgba(238, 238, 238, 1)",
                         "All alarms tab is deselected when it should not be.")

        # Ignored Alarms
        # Click the checkbox that would select the ignored tab then wait for the alarm grid to update
        show_not_ignored_checkbox.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + self.config.mid_timeout + " seconds after filters were cleared")

        # Check that the all alarms tab is no longer selected and the ignored tab is selected, otherwise fail the test
        self.assertEqual(ignored_tab.value_of_css_property("background-color"), "rgba(238, 238, 238, 1)",
                         "Ignored alarms tab is deselected when it should not be.")
        self.assertEqual(all_tab.value_of_css_property("background-color"), "rgba(187, 187, 187, 1)",
                         "All alarms tab is selected when it should not be.")

        # Custom
        # Click the checkboxes that should select the custom tab
        show_not_ignored_checkbox.click()
        driver.find_element_by_id("alarmsFilter_critical_CB").click()

        # Wait for the alarm grid to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + self.config.mid_timeout + " seconds after filters were cleared")

        # Check that the all alarms tab is no longer selected and the ignored tab is selected, otherwise fail the test
        self.assertEqual(ignored_tab.value_of_css_property("background-color"), "rgba(187, 187, 187, 1)",
                         "Ignored alarms tab is selected when it should not be.")
        self.assertEqual(custom_tab.value_of_css_property("background-color"), "rgba(238, 238, 238, 1)",
                         "Custom tab is deselected when it should not be.")

        # Reset the alarm grid for the next test
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)

    def test_filter_alarm_counts_are_correct_C10824(self):
        # Not sure how to do this yet will return to at a later point
        pass

    def test_filter_alarm_counts_display_once_C142054(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't and open the custom filters dialog
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)

        # Loop through all the labels of the checkboxes except Delayed, Ignored, All Methods, All Priorities, and All States, then cheeck that
        # the text for the counts only displays once
        filter_label_elements = custom_filters_dialog.find_elements_by_tag_name("label")
        for label_element in filter_label_elements:
            if (label_element.text != "Delayed" and label_element.text != "Ignored" and label_element.text != "All Methods" and
                    label_element.text != "All Priorities" and label_element.text != "All States"):
                self.assertEqual(len(label_element.find_elements_by_tag_name("span")), 1,
                    "Too many counts displaying for filter label: " + label_element.text + ".")

        # Reset the alarm grid for the next test
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)

    def test_filter_alarm_counts_should_update_on_node_change_C142062(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't and open the custom filters dialog
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)

        # Loop through the alarm labels
        filter_label_elements = custom_filters_dialog.find_elements_by_tag_name("label")
        stored_alarm_counts = []
        for label_element in filter_label_elements:
            label_text = AlarmsMenuDisplayFiltersTest.cut_off_string_at(self, label_element.text, "(")

            # If the label says Active or Cleared add the alarm count to the alarm count list
            if (label_text == "Active " or label_text == "Cleared "):
                stored_alarm_counts.append(label_element.find_element_by_tag_name("span").text)

        # Click a node in the Network Tree and wait for the page to load (network tree and alarm grid)
        driver.find_element_by_xpath("//div[@id='netTree']/ul/li/ul/li[1]/div").click()
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='networkExplorer']/div/button[4]"))
            )
        except TimeoutException:
            self.fail("Network tree failed to load within alotted " + str(self.config.long_timeout) + " seconds after switching nodes")
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after the network tree loaded")

        # Loop through the custom filter labels again
        filter_label_elements = custom_filters_dialog.find_elements_by_tag_name("label")
        for label_element in filter_label_elements:
            label_text = AlarmsMenuDisplayFiltersTest.cut_off_string_at(self, label_element.text, "(")

            # If Active or Cleared check the alarm count does not match the stored counts otherwise fail the test.
            if (label_text == "Active "):
                self.assertNotEqual(label_element.find_element_by_tag_name("span").text, stored_alarm_counts[0],
                                    "Active Alarm count: " + label_element.find_element_by_tag_name("span").text +
                                    " still the same as the first count: " + stored_alarm_counts[0] + ".")
            if (label_text == "Cleared "):
                self.assertNotEqual(label_element.find_element_by_tag_name("span").text, stored_alarm_counts[1],
                                    "Cleared Alarm count: " + label_element.find_element_by_tag_name("span").text +
                                    " still the same as the first count: " + stored_alarm_counts[0] + ".")

        # Reset the alarm grid for the next test
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)

    def test_can_move_dialog_C11495(self):
        # Get the driver
        driver = self.config.driver

        # Open the custom filters dialog
        custom_filters_dialog = AlarmsMenuDisplayFiltersTest.open_display_filters_dialog(self, driver)

        # Locate the section of the dialog that activates mouse dragging, then store the dialog's horizontal and vertical position
        custom_filters_header_element = custom_filters_dialog.find_element_by_id("windowHeader")
        custom_filters_dialog_top_position = custom_filters_dialog.value_of_css_property("top")
        custom_filters_dialog_left_position = custom_filters_dialog.value_of_css_property("left")

        # Emulate the mouse moving to the dialog and then clicking down on it
        actions = ActionChains(driver)
        actions.move_to_element(custom_filters_header_element)
        actions.click_and_hold(custom_filters_header_element)
        actions.perform()

        # Emulate the mouse dragging the dialog up and to the left 50 pixels
        for index in range(0, 50):
            actions = ActionChains(driver)
            actions.move_by_offset(-1, -1)
            actions.perform()

        # Emulate the mouse releasing the dialog
        actions = ActionChains(driver)
        actions.release()
        actions.perform()

        # Check to ensure the dialog's new horizontal and vertical positions do not match the old ones
        self.assertNotEqual(custom_filters_dialog.value_of_css_property("top"), custom_filters_dialog_top_position,
                            "Custom Filter Dialog's new top position: " + custom_filters_dialog.value_of_css_property("top") +
                            " is still equal to the Custom Filter Dialog's old top position: " + custom_filters_dialog_top_position + ".")
        self.assertNotEqual(custom_filters_dialog.value_of_css_property("left"), custom_filters_dialog_left_position,
                            "Custom Filter Dialog's new left position: " + custom_filters_dialog.value_of_css_property("left") +
                            " is still equal to the Custom Filter Dialog's old left position: " + custom_filters_dialog_left_position + ".")

        # Reset the alarm grid for the next test.
        AlarmsMenuDisplayFiltersTest.reset_alarm_grid(self, driver)













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

    def open_display_filters_dialog(self, web_driver):
        # Wait for the custom tab to load and then click it
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-custom-filters"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "tab-custom-filters"))
            )
        except TimeoutException:
            self.fail("Custom filters tab did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        web_driver.find_element_by_id("tab-custom-filters").click()

        # Wait for the custom filters dialog to display and then return it
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsFilter"))
            )
        except TimeoutException:
            self.fail("Custom Alarm filter dialog did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        return(web_driver.find_element_by_id("alarmsFilter"))

    def cut_off_string_at(self, input_string, char_cut_off):
        # loop through every character in the string, adding it to a new string, till the cut off character is found then return the new string
        return_string = ""
        for string_char in input_string:
            if (string_char == char_cut_off):
                break
            else:
                return_string += string_char
        return (return_string)

    def reset_alarm_grid(self, web_driver):
        # If the custom filter dialog is open Ensure the close button is displayed and then click it
        if (web_driver.find_element_by_id("alarmsFilter").is_displayed() == True):
            if (web_driver.find_element_by_id("alarmsFilter").find_element_by_xpath("//div[@id='windowHeader']/div[2]/div").is_displayed() ==
                    True):
                web_driver.find_element_by_id("alarmsFilter").find_element_by_xpath("//div[@id='windowHeader']/div[2]/div").click()

        # Wait for the active tab to be visible
        try:
            WebDriverWait(web_driver, self.config.short_timeout).until(
                    expected_conditions.visibility_of_element_located((By.ID, "tab-active-alarms"))
            )
        except TimeoutException:
            return

        # Click the active tab and wait for the alarm grid to update
        web_driver.find_element_by_id("tab-active-alarms").click()
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after filters were cleared")


if __name__ == "__main__":
    unittest.main()