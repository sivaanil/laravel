__author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

sys.path.append("..")
from datetime import datetime

import c2_test_case
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import NoSuchElementException
from selenium.common.exceptions import TimeoutException
from natsort import humansorted
from natsort import natsorted


import unittest, time


class AlarmsFiltersTest(c2_test_case.C2TestCase):
    def test_filters_open_C10204(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Run a loop through all the column headers using and index and storing a copy of the current column
        for index in range(0, len(column_elements)):
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]

            # Ensure the column is visible, that it isn't the Actions column or the 1st one, and then click the filter button to open
            # the filter menu
            if (element.is_displayed() == True and element.text != "Actions" and element.text != ""):
                column_element_btn = AlarmsFiltersTest.open_column_filter_menu(self, element)

                # Find the filter options panel and wait for it to become visible
                alarm_filter_menu_element = driver.find_element_by_id("gridmenualarmsGrid")
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + element.text + " column.")

                # Click the filter button again to hide the filter dialog and wait for it to hide
                column_element_btn.click()
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.invisibility_of_element_located((By.ID, "gridmenualarmsGrid"))
                )

    def test_sort_ascending_C10193(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then
            # for ease of access setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # If the column is one of the following: Id, Device Path, Description, Raised Time, Cleared Time, or Notes
            if (column_label == "Id" or column_label == "Device Path" or column_label == "Description" or column_label == "Raised Time" or
                        column_label == "Cleared Time" or column_label == "Notes"):
                # Open the Filter menu and then get the sorting buttons
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                sorting_buttons = alarm_filter_menu_element.find_elements_by_tag_name("li")

                # Cycle through the sort buttons
                for sort_btn in sorting_buttons:
                    # Find the Sort Ascending button and click it
                    if (sort_btn.text == "Sort Ascending"):
                        sort_btn.click()

                        # Wait for the alarm grid to finish loading
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("" + column_label + " column did not sort within the given " + str(self.config.mid_timeout) + " seconds")

                        # Get the list of alarms so far
                        alarm_row_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)

                        # Create a new array with the values humansorted (a natsort method that sorts as a human woul expect it). If the
                        # column is either the Raised Time or Cleared Time column loop through the column values and change to dates then
                        # natsort them (human sort doesn't work for date values).
                        alarm_row_column_values_2nd = humansorted(alarm_row_column_values)
                        if (column_label == "Raised Time" or column_label == "Cleared Time"):
                            for index in range(0, len(alarm_row_column_values)):
                                alarm_row_column_values[index] = datetime.strptime(alarm_row_column_values[index], '%b %d %Y (%I:%M %p)')
                            alarm_row_column_values_2nd = natsorted(alarm_row_column_values)

                        # Use the built in python method to ensure the sorted array and alarm array are equal and if not fail the test.
                        if (AlarmsFiltersTest.are_the_arrays_equal(self, alarm_row_column_values, alarm_row_column_values_2nd) == False):
                            self.fail("Sorting " + column_label + " Column Failed!")

                        break  # Since the Sort Ascending button was found break out of the loop for sort buttons in this column

    def test_severity_column_sort_ascending_C10193(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Create a list to store the expected severity label order
        severity_order_list = ["Critical", "Major", "Minor", "Warning", "Information"]

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # If the column is the Severity column continue
            if (column_label == "Severity"):
                # Open the Filter menu and then get the sorting buttons
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                sorting_buttons = alarm_filter_menu_element.find_elements_by_tag_name("li")

                # Cycle through the sort buttons
                for sort_btn in sorting_buttons:
                    # Find the Sort Ascending button and click it
                    if (sort_btn.text == "Sort Ascending"):
                        sort_btn.click()

                        # Wait for the alarm grid to load.
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("" + column_label + " column did not sort within the given " + str(self.config.mid_timeout) + " seconds")

                        # Get the list of alarms so far
                        alarm_row_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)

                        # Loop through the severity values start out by finding out if the value matches the current order value.
                        # if not then loop through the remaining order values and find out which order value the value matches. Once found
                        # break from that loop then check the value against the order value.
                        severity_index = 0
                        for value in alarm_row_column_values:
                            if (value != severity_order_list[severity_index]):
                                for severity_index in range(severity_index, len(severity_order_list)):
                                    if (value == severity_order_list[severity_index]):
                                        break

                            self.assertEqual(value, severity_order_list[severity_index], "Severity sort did not work as expected!")

                        break  # Since the Sort Ascending button was found break out of the loop for sort buttons in this column

    def test_sort_descending_C11484(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Looking for one of the following columns: Id, Device Path, Description, Raised Time, Cleared Time, or Notes.
            if (column_label == "Id" or column_label == "Device Path" or column_label == "Description" or column_label == "Raised Time" or
                        column_label == "Cleared Time" or column_label == "Notes"):
                # Open the Filter menu and then get the sorting buttons
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                sorting_buttons = alarm_filter_menu_element.find_elements_by_tag_name("li")

                # Cycle through the sort buttons
                for sort_btn in sorting_buttons:
                    # Find the Sort Descending button and click it
                    if (sort_btn.text == "Sort Descending"):
                        sort_btn.click()

                        # Wait for the alarm grid to load
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("" + column_label + " column did not sort within the given " + str(self.config.mid_timeout) + " seconds")

                        # Get the list of alarms so far
                        alarm_row_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)

                        # Create a new array with the values humansorted (a natsort method that sorts as a human woul expect it). If the
                        # column is either the Raised Time or Cleared Time column loop through the column values and change to dates then
                        # natsort them (human sort doesn't work for date values).
                        alarm_row_column_values_2nd = humansorted(alarm_row_column_values, reverse=True)
                        if (column_label == "Raised Time" or column_label == "Cleared Time"):
                            for index in range(0, len(alarm_row_column_values)):
                                alarm_row_column_values[index] = datetime.strptime(alarm_row_column_values[index], '%b %d %Y (%I:%M %p)')
                            alarm_row_column_values_2nd = natsorted(alarm_row_column_values, reverse=True)

                        # Use the built in python method to ensure the sorted array and alarm array are equal and if not fail the test.
                        if (AlarmsFiltersTest.are_the_arrays_equal(self, alarm_row_column_values, alarm_row_column_values_2nd) == False):
                            self.fail("Sorting " + column_label + " Column Failed!")

                        break  # Since the Sort Descending button was found break out of the loop for sort buttons in this column

    def test_severity_column_sort_descending_C11484(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Create a list to store the expected severity label order
        severity_order_list = ["Information", "Warning", "Minor", "Major", "Critical"]

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # If the column is the Severity column continue
            if (column_label == "Severity"):
                # Open the Filter menu and then get the sorting buttons
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                sorting_buttons = alarm_filter_menu_element.find_elements_by_tag_name("li")

                # Cycle through the sort buttons
                for sort_btn in sorting_buttons:
                    # Find the Sort Ascending button and click it
                    if (sort_btn.text == "Sort Descending"):
                        sort_btn.click()

                        # Wait for the alarm grid to load
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("" + column_label + " column did not sort within the given " + str(self.config.mid_timeout) + " seconds")

                        # Get the list of alarms so far
                        alarm_row_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)

                        # Loop through the severity values start out by finding out if the value matches the current order value.
                        # if not then loop through the remaining order values and find out which order value the value matches. Once found
                        # break from that loop then check the value against the order value.
                        severity_index = 0
                        for value in alarm_row_column_values:
                            if (value != severity_order_list):
                                for severity_index in range(severity_index, len(severity_order_list)):
                                    if (value == severity_order_list[severity_index]):
                                        break

                            self.assertEqual(value, severity_order_list[severity_index], "")

                        break  # Since the Sort Ascending button was found break out of the loop for sort buttons in this column

    def test_remove_sort_C11485(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Looking for the following columns: Id, Device Path, Description, Raised Time, Cleared Time, Severity, or Notes.
            if (column_label == "Id" or column_label == "Device Path" or column_label == "Description" or column_label == "Raised Time"
                or column_label == "Cleared Time" or column_label == "Severity" or column_label == "Notes"):
                # Open the Filter menu and then get the sorting buttons
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                sorting_buttons = alarm_filter_menu_element.find_elements_by_tag_name("li")

                # Cycle through the sort buttons
                for sort_btn in sorting_buttons:
                    # Find the Sort Ascending button and click it
                    if (sort_btn.text == "Sort Ascending"):
                        sort_btn.click()

                        # Wait for the alarm grid to load
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("" + column_label + " column did not finish sorting in the alotted " + str(self.config.mid_timeout) +
                                      " seconds")

                        # Since the alarm grid was updated regrab the column elements, reopen the filter menu, and then regrab the sorting buttons
                        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
                        element = column_elements[index]
                        alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                        sorting_buttons = alarm_filter_menu_element.find_elements_by_tag_name("li")

                        # cycle through the sort buttons again
                        for sort_btn_2 in sorting_buttons:
                            # find the Remove Sort button, get a list of values for this column sorted, and click the sort button
                            if (sort_btn_2.text == "Remove Sort"):
                                alarm_row_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                                sort_btn_2.click()

                                # Wait for the alarm grid to load
                                try:
                                    WebDriverWait(driver, self.config.mid_timeout).until(
                                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                                    )
                                except TimeoutException:
                                    self.fail("" + column_label + " column did not finish sorting within the alotted " +
                                              str(self.config.mid_timeout) + " seconds!")

                                # Create a new array with the values now in their supposedly unsorted state. Then check to see if they're
                                # different if not fail the test case
                                alarm_row_column_values_2nd = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                                if (AlarmsFiltersTest.are_the_arrays_equal(self, alarm_row_column_values, alarm_row_column_values_2nd) == True):
                                    self.fail("Remove Sort on " + column_label + " Failed!")
                                break  # Since the Remove Sort button was found break out of the loop for sort buttons in this column
                        break  # Since the Sort Ascending button was found break out of the loop for sort buttons in this column

    def test_filtering_numerical_C11486_and_C11487_and_C11488(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for
            # ease of access setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Looking for the Id column since it is the only numerical column
            if (column_label == "Id"):
                # Grab the column label, open the filter options menu, and wait for the menu to load
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + column_label + " column.")

                # Grab the container for the filter options and then grab the first text field
                alarm_filter_options_menu_elements = alarm_filter_menu_element.find_elements_by_tag_name("li")
                alarm_filter_options_menu_element = alarm_filter_options_menu_elements[len(alarm_filter_options_menu_elements) - 1]
                alarm_filter_options_text_field = alarm_filter_options_menu_element.find_element_by_xpath(".//div/div/div[3]/input")

                # Get a list of values in this column of alarms, sort the list, then grab the middle item, enter that value into the first
                # filter text field, and finally click the filter button
                alarm_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                alarm_column_values.sort()
                filtered_value = alarm_column_values[2]
                alarm_filter_options_text_field.send_keys(filtered_value)
                alarm_filter_options_menu_element.find_element_by_id("filterbuttonalarmsGrid").click()

                # Wait for the alarm grid to load
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Filtering the " + column_label + " column Timeout!")

                # Get the list of alarm values again, loop through those values making sure none are less then the filtered value; if there
                # is fail the test case
                alarm_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                for num in range(0, len(alarm_column_values)):
                    if (alarm_column_values[num] < filtered_value):
                        self.fail("Filtering " + column_label + " Failed!")

                # Push the clear button to clear the filter and wait for the alarm grid to load
                AlarmsFiltersTest.push_the_filter_clear_button(self, index, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Clearing the " + column_label + " column filter Timeout!")

                # Get the values once again and check to see if the unfiltered list is equal to the filtered list if so then fail the test
                alarm_column_values_unfiltered = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                self.assertEqual(AlarmsFiltersTest.are_the_arrays_equal(self, alarm_column_values, alarm_column_values_unfiltered), False,
                                 "Alarm filter did not clear.")
                break

    def test_filtering_string_C11486_and_C11487_and_C11488(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Looking for the following columns: Device Path, Description, or Notes, once found click the button to open the filter
            # menu and wait for it to load
            if (column_label == "Device Path" or column_label == "Description" or column_label == "Notes"):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + column_label + " column.")

                # Grab the container for the filter options and then grab the first text field
                alarm_filter_options_menu_elements = alarm_filter_menu_element.find_elements_by_tag_name("li")
                alarm_filter_options_menu_element = alarm_filter_options_menu_elements[len(alarm_filter_options_menu_elements) - 1]
                alarm_filter_options_text_field = alarm_filter_options_menu_element.find_element_by_xpath(".//div/div/div[3]/input")

                # Enter a string to filter by into the filter text field, click the filter button, and then sleep for 5 seconds while the filtered grid loads
                alarm_filter_options_text_field.send_keys("f")
                alarm_filter_options_menu_element.find_element_by_id("filterbuttonalarmsGrid").click()

                # Wait for the alarm grid to load
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Filtering the " + column_label + " column Timeout!")

                # Get the list of alarm values for this column, loop through it, and check that each one includes the string filtered by;
                # if not fail the test case
                alarm_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                for num in range(0, len(alarm_column_values)):
                    if (alarm_column_values[num].lower().find("f") == -1):
                        self.fail("Filtering " + column_label + " Failed!")

                # Push the clear button to clear the filter and reset for the next test; wait for the alarm grid to load
                AlarmsFiltersTest.push_the_filter_clear_button(self, index, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Filtering the " + column_label + " column Timeout!")

                # Get the array of unfiltered values and then check to make sure the values aren't equal.
                alarm_column_values_unfiltered = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                self.assertEqual(AlarmsFiltersTest.are_the_arrays_equal(self, alarm_column_values, alarm_column_values_unfiltered), False,
                                 "" + column_label + " Alarm filter did not clear.")

    def test_filtering_raised_time_C11486_and_C11487_and_C11488(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Make sure the column is visible and it is either the Raised Time or Cleared Time column. Click the open filter dialog
            # button and wait for the filter menu to load
            if (element.is_displayed() == True and (column_label == "Raised Time" or column_label == "Cleared Time")):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + column_label + " column.")

                # Grab the container for the filter options
                alarm_filter_options_menu_elements = alarm_filter_menu_element.find_elements_by_tag_name("li")
                alarm_filter_options_menu_element = alarm_filter_options_menu_elements[len(alarm_filter_options_menu_elements) - 1]

                # Get the list of alarm values for this column, sort it and pick a value from that list and remove the time
                alarm_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                alarm_column_values.sort()
                alarm_column_value_chosen = AlarmsFiltersTest.cut_off_string_at(self, alarm_column_values[len(alarm_column_values) - 2], "(")

                # Select the date in the filter options calender
                AlarmsFiltersTest.select_date_filter_from_string(self, alarm_column_value_chosen, alarm_filter_options_menu_element, driver)

                # Store the chosen date as a datetime object, click the filter button, and wait for the grid to load. (the time.sleep is
                # unavoidable here as I can't detect when the date chosen value is set)
                date_chosen = datetime.strptime(alarm_column_value_chosen, '%b %d %Y ')
                time.sleep(.5)
                alarm_filter_options_menu_element.find_element_by_id("filterbuttonalarmsGrid").click()
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Filtering the " + column_label + " column Timeout!")

                # Get the list of values again then loop through them convert them to a datetime value and then ensure none of them are
                # less then the chosen date; If not fail the test case
                alarm_column_values = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                for num in range(0, len(alarm_column_values)):
                    temp_date = datetime.strptime(AlarmsFiltersTest.cut_off_string_at(self, alarm_column_values[num], "("), '%b %d %Y ')
                    if (temp_date < date_chosen):
                        self.fail("Filtering " + column_label + " Failed!")

                # Push the clear button to clear the filter and reset for the next test; wait for the alarm grid to load
                AlarmsFiltersTest.push_the_filter_clear_button(self, index, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Filtering the " + column_label + " column Timeout!")

                # Get the array of unfiltered values and then check to make sure the values aren't equal.
                alarm_column_values_unfiltered = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                self.assertEqual(AlarmsFiltersTest.are_the_arrays_equal(self, alarm_column_values, alarm_column_values_unfiltered), False,
                                 "" + column_label + " column: Alarm filter did not clear.")

    def test_filtering_severity_C11486_and_C11487(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Make sure this is the Severity column, open the filter menu.
            if (column_label == "Severity"):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + column_label + " column.")

                # Store a list of the severity options, loop through the list searching for the visible one and click the critical option
                severity_option_lists = driver.find_elements_by_id("alarmsFilter_allpriorities_CB_list")
                for severity_option_list in severity_option_lists:
                    if (severity_option_list.is_displayed() == True):
                        severity_option_list.find_element_by_id("alarmsFilter_critical_CB").click()

                        # Wait for the alarms grid to load
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("Filtering the " + column_label + " column Timeout!")

                        # Get the value list for the column from the alarm grid and loop through it searching for any alarms with a value
                        # of critical. If a critical alarm is found fail the test.
                        alarms_after_filtering = AlarmsFiltersTest.get_alarm_column_value_list(self, driver, index)
                        for alarm_value in alarms_after_filtering:
                            if (alarm_value == "Critical"):
                                self.fail("Filtering " + column_label + " Failed!")

                        # Click the critical filter button again and wait for the alarm grid to load
                        severity_option_list.find_element_by_id("alarmsFilter_critical_CB").click()
                        try:
                            WebDriverWait(driver, self.config.mid_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                            )
                        except TimeoutException:
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("Removing the filter for the " + column_label +
                                      " column did not complete within the alotted " + str(self.config.mid_timeout) + " seconds!")

                        # wrap everything up to prepare for the next
                        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
                        element = column_elements[index]
                        AlarmsFiltersTest.open_column_filter_menu(self, element)
                        break
                break

    # #This test case is currently not possible in Selenium
    # #def test_date_filter_text_populated_C11561(self):

    def test_uncheck_severity_should_uncheck_all_priorities_C11491(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]

            # Making sure this is the Severity column and opening the filter menu
            if (element.text == "Severity"):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + element.text + " column.")

                # Find the minor checkbox and click it
                minor_checkbox_elements = alarm_filter_menu_element.find_elements_by_id("alarmsFilter_minor_CB")
                for checkbox in minor_checkbox_elements:
                    if (checkbox.is_displayed() == True):
                        checkbox.click()

                # Find the all priorities checkbox and then check if it is selected if it is fail the test.
                all_priorities_checkbox_elements = alarm_filter_menu_element.find_elements_by_id("alarmsFilter_allpriorities_CB")
                for checkbox in all_priorities_checkbox_elements:
                    if (checkbox.is_displayed() == True):
                        if (checkbox.get_attribute("aria-checked") == 'false'):
                            AlarmsFiltersTest.refresh_and_wait(self, driver)
                            self.fail("All Priorities checkbox was not unchecked!")

                # Check the minor checkbox again and close the filter menu so that things are ready for the next test.
                for checkbox in minor_checkbox_elements:
                    if (checkbox.is_displayed() == True):
                        checkbox.click()
                AlarmsFiltersTest.open_column_filter_menu(self, element)
                break

    def test_actions_should_not_sort_C11560(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then
            # for ease of access setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]

            # Locate the Action column and then click it
            if (element.text == "Actions"):
                element.click()

                # Wait for the alarm grid to load
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Test Failed! Actions column attempted to sort")

                # Get the column and check to make sure it is not sorted, and if it is fail the test.
                column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
                element = column_elements[index]
                self.assertEqual(element.get_attribute("aria-sorted"), 'none', "Test Failed! Actions column attempted to sort")
                break

    def test_dropdowns_should_not_contain_null_C10195(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Find out how many column headers there are and run a loop through all the column headers
        numberColumns = len(column_elements)
        for index in range(0, numberColumns):
            # Since every time the alarm grid is sorted the variable loses access to the column headers, they have to be re found and then for ease of access
            # setting a variable to hold the current column header being used
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[index]
            column_label = element.text

            # Make sure the column is one of the following: Id, Device Path, Description, Raised Time, Cleared Time, Notes and ensure it is
            # visible. Open the filter menu.
            if (column_label == "Id" or column_label == "Device Path" or column_label == "Description" or column_label == "Raised Time"
                or column_label == "Cleared Time" or column_label == "Notes"):
                if (element.is_displayed() == True):
                    alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.visibility_of(alarm_filter_menu_element)
                        )
                    except TimeoutException:
                        AlarmsFiltersTest.refresh_and_wait(self, driver)
                        self.fail("Alarm filter menu did not open for " + column_label + " column.")

                    # loop through the dropdowns on a filter.
                    for index in range(1, 4):
                        alarm_filter_menu_element_id = "dropdownlistWrapperfilter" + str(index) + "alarmsGrid"
                        alarm_filter_dropdown_menu_element = alarm_filter_menu_element.find_element_by_id(alarm_filter_menu_element_id)

                        # click the dropdown wait for it to open, then wait for the dropdown to display, if it doesn't try clicking one more
                        # time just in case and wait again for the dropdown to open.
                        alarm_filter_dropdown_menu_element.click()
                        try:
                            WebDriverWait(driver, self.config.short_timeout).until(
                                expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                            )
                        except TimeoutException:
                            alarm_filter_dropdown_menu_element.click()
                            try:
                                WebDriverWait(driver, self.config.mid_timeout).until(
                                    expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                AlarmsFiltersTest.refresh_and_wait(self, driver)
                                self.fail("dropdown filter didn't load within the alotted " + str(self.config.mid_timeout) + " seconds")
                        alarm_filter_dropdown_list_menu_element = driver.find_element_by_id("listBoxfilter" + str(index) + "alarmsGrid")

                        # Get the dropdown options
                        alarm_filter_dropdown_options_list_menu_elements = \
                            alarm_filter_dropdown_list_menu_element.find_elements_by_class_name("jqx-listitem-element")

                        # Loop through the options and check to see if the option contains null if it does fail the test
                        for list_option in alarm_filter_dropdown_options_list_menu_elements:
                            self.assertNotEqual(list_option.text.lower(), "null", "List options (" + column_label +
                                                " column) should not be NULL!")

                        # Click the filter dropdown again to close it and then wait for it to hide, if it doesn't click the dropdown again
                        # and once again wait for the dropdown to open.
                        alarm_filter_dropdown_menu_element.click()
                        try:
                            WebDriverWait(driver, self.config.short_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                            )
                        except TimeoutException:
                            alarm_filter_dropdown_menu_element.click()
                            try:
                                WebDriverWait(driver, self.config.mid_timeout).until(
                                    expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                AlarmsFiltersTest.refresh_and_wait(self, driver)
                                self.fail("dropdown filter didn't hide within the alotted " + str(self.config.mid_timeout) + " seconds")

        # Close the filter menu so things are ready for the next test.
        AlarmsFiltersTest.open_column_filter_menu(self, column_elements[len(column_elements) - 2])

    def test_int_column_filter_choices_should_be_correct_C10205(self):
        # Get the driver
        driver = self.config.driver

        # Store a list of all the expected options
        expected_filter_dropdown_options = ["less than", "less than or equal to", "greater than", "greater than or equal to",
                                            "contains", "equal"]

        # Get column headers and loop through the columns
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
        for element_index in range(0, len(column_elements)):
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[element_index]

            # Look for the Id column and open the filter menu
            if (element.text == "Id"):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + element.text + " column.")

                # Loop through the dropdowns
                for index in range(1, 4):
                    if (index != 2):
                        alarm_filter_dropdown_id = "dropdownlistWrapperfilter" + str(index) + "alarmsGrid"
                        alarm_filter_dropdown_menu_element = alarm_filter_menu_element.find_element_by_id(alarm_filter_dropdown_id)

                        # Open the dropdown, wait for it to be visible and if it doesn't become visible try to open it again
                        alarm_filter_dropdown_menu_element.click()
                        try:
                            WebDriverWait(driver, self.config.short_timeout).until(
                                expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                            )
                        except TimeoutException:
                            alarm_filter_dropdown_menu_element.click()
                            try:
                                WebDriverWait(driver, self.config.mid_timeout).until(
                                    expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                AlarmsFiltersTest.refresh_and_wait(self, driver)
                                self.fail("dropdown filter didn't load within the alotted " + str(self.config.mid_timeout) + " seconds")
                        alarm_filter_dropdown_list_menu_element = driver.find_element_by_id("listBoxfilter" + str(index) + "alarmsGrid")

                        # Get the list of dropdown options
                        alarm_filter_1st_dropdown_optins_list_menu_elements = \
                            alarm_filter_dropdown_list_menu_element.find_elements_by_class_name("jqx-listitem-element")

                        # loop through the list of options and ensure the text for the list option isn't blank
                        option_index = 0
                        for list_option in alarm_filter_1st_dropdown_optins_list_menu_elements:
                            for sec in range(0, self.config.mid_timeout):
                                if (list_option.text != ""):
                                    break
                                time.sleep(1)

                            # Check that the option value matches the expected value.
                            self.assertEqual(str(list_option.text), str(expected_filter_dropdown_options[option_index]),
                                             "Dropdown for the int column contains an unexpected option: " + list_option.text +
                                             "; expected option: " + expected_filter_dropdown_options[option_index])
                            option_index += 1

                        # close the dropdown
                        alarm_filter_dropdown_menu_element.click()
                        try:
                            WebDriverWait(driver, self.config.short_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                            )
                        except TimeoutException:
                            alarm_filter_dropdown_menu_element.click()
                            try:
                                WebDriverWait(driver, self.config.mid_timeout).until(
                                    expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                AlarmsFiltersTest.refresh_and_wait(self, driver)
                                self.fail("dropdown filter didn't hide within the alotted " + str(self.config.mid_timeout) + " seconds")

                # close the menu
                AlarmsFiltersTest.open_column_filter_menu(self, AlarmsFiltersTest.get_alarm_columns(self, driver)[1])
                break

    def test_string_column_filter_choices_should_be_correct_C10206(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Store a list of all the expected options
        expected_filter_dropdown_options = ["equal", "does not contain", "contains"]

        # Get column headers and loop through all the columns
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
        for element_index in range(0, len(column_elements)):
            column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)
            element = column_elements[element_index]

            # If the column is one of the following: Device Path, Description, or Notes, open the filter menu
            if (element.text == "Device Path" or element.text == "Description" or element.text == "Notes"):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)

                # Wait for the filter menu to load
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + element.text + " column.")

                # Loop through the dropdowns
                for index in range(1, 4):
                    if (index != 2):
                        alarm_filter_dropdown_id = "dropdownlistWrapperfilter" + str(index) + "alarmsGrid"
                        alarm_filter_dropdown_menu_element_btn = driver.find_element_by_id(alarm_filter_dropdown_id)

                        # Open the dropdown, wait for it to be visible and if it doesn't become visible try to open it again
                        alarm_filter_dropdown_menu_element_btn.click()
                        try:
                            WebDriverWait(driver, self.config.short_timeout).until(
                                expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                            )
                        except TimeoutException:
                            alarm_filter_dropdown_menu_element_btn.click()
                            try:
                                WebDriverWait(driver, self.config.mid_timeout).until(
                                    expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                AlarmsFiltersTest.refresh_and_wait(self, driver)
                                self.fail("dropdown filter didn't load within the alotted " + str(self.config.mid_timeout) + " seconds")
                        alarm_filter_dropdown_list_menu_element = driver.find_element_by_id("listBoxfilter" + str(index) + "alarmsGrid")

                        # check once more to make sure the dropdown is open
                        for sec in range(0, self.config.mid_timeout):
                            if (alarm_filter_dropdown_list_menu_element.value_of_css_property("margin-top") >= 0):
                                break
                            else:
                                time.sleep(1)

                        # Get the list of dropdown options
                        alarm_filter_dropdown_optins_list_menu_elements = \
                            alarm_filter_dropdown_list_menu_element.find_elements_by_class_name("jqx-listitem-element")

                        # loop through the list of options and ensure the text for the list option isn't blank
                        option_index = 0
                        for list_option in alarm_filter_dropdown_optins_list_menu_elements:
                            for sec in range(0, self.config.mid_timeout):
                                if (list_option.text != ""):
                                    break
                                time.sleep(1)

                            # Check that the option value matches the expected value.
                            self.assertEqual(str(list_option.text), str(expected_filter_dropdown_options[option_index]),
                                             "Dropdown for the " + element.text + " column contains an unexpected option: " +
                                             list_option.text + "; value should be: " + expected_filter_dropdown_options[option_index])
                            option_index += 1

                        # close the dropdown
                        alarm_filter_dropdown_menu_element_btn.click()
                        try:
                            WebDriverWait(driver, self.config.short_timeout).until(
                                expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                            )
                        except TimeoutException:
                            alarm_filter_dropdown_menu_element_btn.click()
                            try:
                                WebDriverWait(driver, self.config.mid_timeout).until(
                                    expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                AlarmsFiltersTest.refresh_and_wait(self, driver)
                                self.fail("dropdown filter didn't hide within the alotted " + str(self.config.mid_timeout) + " seconds")

                # close the menu
                AlarmsFiltersTest.open_column_filter_menu(self, element)


    def test_date_column_filter_choices_should_be_correct_C10207(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Store a list of all the expected options
        expected_filter_dropdown_options = ["not set", "less than", "greater than"]

        # loop through all the columns and ensure the column is visible and either the Raised Time or Cleared Time and open the filter menu
        for element in column_elements:
            if (element.text == "Raised Time" or element.text == "Cleared Time"):
                if (element.is_displayed() == True):
                    alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.visibility_of(alarm_filter_menu_element)
                        )
                    except TimeoutException:
                        AlarmsFiltersTest.refresh_and_wait(self, driver)
                        self.fail("Alarm filter menu did not open for " + element.text + " column.")

                    # Loop through the dropdowns
                    for index in range(1, 4):
                        if (index != 2):
                            alarm_filter_dropdown_id = "dropdownlistWrapperfilter" + str(index) + "alarmsGrid"
                            alarm_filter_dropdown_menu_element = alarm_filter_menu_element.find_element_by_id(alarm_filter_dropdown_id)

                            # Open the dropdown, wait for it to be visible and if it doesn't become visible try to open it again
                            alarm_filter_dropdown_menu_element.click()
                            try:
                                WebDriverWait(driver, self.config.short_timeout).until(
                                    expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                alarm_filter_dropdown_menu_element.click()
                                try:
                                    WebDriverWait(driver, self.config.mid_timeout).until(
                                        expected_conditions.visibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                    )
                                except TimeoutException:
                                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                                    self.fail("dropdown filter didn't load within the alotted " + str(self.config.mid_timeout) + " seconds")

                            # Get the list of dropdown options
                            alarm_filter_1st_dropdown_list_menu_element = driver.find_element_by_id("listBoxfilter" + str(index) + "alarmsGrid")
                            alarm_filter_1st_dropdown_optins_list_menu_elements = \
                                alarm_filter_1st_dropdown_list_menu_element.find_elements_by_class_name("jqx-listitem-element")

                            # loop through the list of options and ensure the text for the list option isn't blank
                            option_index = 0
                            for list_option in alarm_filter_1st_dropdown_optins_list_menu_elements:
                                for sec in range(0, self.config.mid_timeout):
                                    if (list_option.text != ""):
                                        break
                                    time.sleep(1)

                                # Check that the option value matches the expected value.
                                self.assertEqual(list_option.text, expected_filter_dropdown_options[option_index],
                                                 "Dropdown for the " + element.text + " column contains an unexpected option: " +
                                                 list_option.text + "; value should be: " + expected_filter_dropdown_options[option_index])
                                option_index += 1

                            # close the dropdown
                            alarm_filter_dropdown_menu_element.click()
                            try:
                                WebDriverWait(driver, self.config.short_timeout).until(
                                    expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                )
                            except TimeoutException:
                                alarm_filter_dropdown_menu_element.click()
                                try:
                                    WebDriverWait(driver, self.config.mid_timeout).until(
                                        expected_conditions.invisibility_of_element_located((By.ID, "listBoxfilter" + str(index) + "alarmsGrid"))
                                    )
                                except TimeoutException:
                                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                                    self.fail("dropdown filter didn't hide within the alotted " + str(self.config.mid_timeout) + " seconds")

                # close the menu
                AlarmsFiltersTest.open_column_filter_menu(self, element)

    def test_severity_column_filter_choices_should_be_correct_C11490(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmsFiltersTest.change_panel_widths(self, driver)

        # Get column headers
        column_elements = AlarmsFiltersTest.get_alarm_columns(self, driver)

        # Store a list of all the expected options
        expected_filter_dropdown_options = ["All Priorities", "Critical", "Major", "Minor", "Warning", "Information"]

        # loop through all the columns and ensure the column is visible and either the Raised Time or Cleared Time and open the filter menu
        for element in column_elements:
            if (element.text == "Severity"):
                alarm_filter_menu_element = AlarmsFiltersTest.open_the_filter_menu(self, element, driver)
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.visibility_of(alarm_filter_menu_element)
                    )
                except TimeoutException:
                    AlarmsFiltersTest.refresh_and_wait(self, driver)
                    self.fail("Alarm filter menu did not open for " + element.text + " column.")

                # Get the list of severity options
                severity_checkbox_holder_element_list = alarm_filter_menu_element.find_elements_by_id("alarmsFilter_allpriorities_CB_list")
                for checkbox_holder in severity_checkbox_holder_element_list:
                    if (checkbox_holder.is_displayed() == True):
                        severity_checkbox_list = checkbox_holder.find_elements_by_class_name("ng-binding")

                        # Loop through the list of options and check to make sure the option matches the expected options
                        option_index = 0
                        for checkbox in severity_checkbox_list:
                            if (option_index == 0):
                                self.assertEqual(checkbox.text, expected_filter_dropdown_options[option_index])
                            else:
                                self.assertEqual(AlarmsFiltersTest.cut_off_string_at(self, checkbox.text, " "),
                                                 expected_filter_dropdown_options[option_index])
                            option_index += 1

                # close the menu
                AlarmsFiltersTest.open_column_filter_menu(self, element)
                break







## Helper Methods ##

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

    def refresh_and_wait(self, web_driver):
        # Refresh the page and then wait for the content, network tree, and alarm grid to load.
        web_driver.refresh()
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "content"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "netTree"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Refresh Timeout")

    def open_the_filter_menu(self, column_header_element, web_driver):
        # Try to find the filter button on the column and then attempt to click it
        AlarmsFiltersTest.open_column_filter_menu(self, column_header_element)

        # Return the filter menu
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "gridmenualarmsGrid"))
            )
        except TimeoutException:
            self.fail("Filter dialog failed to load within " + str(self.config.mid_timeout) + " seconds.")
        return (web_driver.find_element_by_id("gridmenualarmsGrid"))

    def get_alarm_column_value_list(self, web_driver, column_id):
        alarm_row_column_values = []

        for index in range(0, 10):
            # Get the alarm in the grid if alarm doesn't exist break out of the loop
            try:
                alarm_row = web_driver.find_element_by_id("row" + str(index) + "alarmsGrid")
            except NoSuchElementException:
                break

            # Then get the value for the column by first getting all the cells for that row
            alarm_row_columns = alarm_row.find_elements_by_css_selector("div[role='gridcell']")
            a_value = alarm_row_columns[column_id].text
            if (a_value != ''):
                alarm_row_column_values.append(a_value)

        # Return the list of values
        return (alarm_row_column_values)

    def are_the_arrays_equal(self, array1, array2):
        if (len(array1) != len(array2)):
            return (False)

        # cycle through all elements in the array and compare them; if one is found different return false
        for index in range(0, len(array1)):
            if (array1[index] != array2[index]):
                return (False)

        # If all the elements prove equal return true
        return (True)

    def select_date_filter_from_string(self, filter_date_string, alarm_filter_options_menu_element, web_driver):
        # change the string to an array of words
        filter_date_array = AlarmsFiltersTest.split_up_words_in_a_string(self, filter_date_string)

        # Click the date picker button to bring up the calender then locate and store the header and its label
        alarm_filter_options_menu_element.find_element_by_xpath(".//div/div/div[3]/div/div[1]/div").click()

        # Find all available calenders and then use their visibility to locate the correct one; set a local variable to it
        calender_elements = web_driver.find_elements_by_xpath("//div[@data-role='calendar']")
        for element in calender_elements:
            if (element.is_displayed() == True):
                calender_element = element

                # If the month isn't selected yet loop through till the correct month is found by clicking the previous month button.
                for month_index in range(0, 12):
                    alarm_filter_date_picker_header = calender_element.find_element_by_xpath(".//div/div/table/tbody/tr/td[1]/div")
                    test_the_header_array = AlarmsFiltersTest.split_up_words_in_a_string(self, alarm_filter_date_picker_header.text)
                    if (test_the_header_array != []):
                        if (test_the_header_array[0].lower().find(filter_date_array[0].lower()) == -1):
                            calender_element.find_element_by_xpath(".//div/div/table/tbody/tr/td[3]/div").click()
                        else:
                            break

                # If the year isn't selected yet loop through till the correct year is found by clicking the previous year button.
                for year_index in range(0, 100):
                    alarm_filter_date_picker_header = calender_element.find_element_by_xpath(".//div/div/table/tbody/tr/td[1]/div")
                    test_the_header_array = AlarmsFiltersTest.split_up_words_in_a_string(self, alarm_filter_date_picker_header.text)
                    if (test_the_header_array != []):
                        if (test_the_header_array[1] != filter_date_array[2]):
                            for index in range(0, 12):
                                calender_element.find_element_by_xpath(".//div/div/table/tbody/tr/td[3]/div").click()
                        else:
                            break
                # Find the day in the list of days and select it
                alarm_filter_options_menu_calender_date_elements = calender_element.find_element_by_xpath(
                        ".//div/table/tbody/tr[2]/td/table/tbody").find_elements_by_tag_name("td")
                for date_btn in alarm_filter_options_menu_calender_date_elements:
                    if (date_btn.text != "" and int(date_btn.text) == int(filter_date_array[1])):
                        date_btn.click()
                        break
                break

    def cut_off_string_at(self, input_string, char_cut_off):
        # loop through every character in the string, adding it to a new string, till the cut off character is found then return the new string
        return_string = ""
        for string_char in input_string:
            if (string_char == char_cut_off):
                break
            else:
                return_string += string_char
        return (return_string)

    def split_up_words_in_a_string(self, input_string):
        # loop through every character in the string, adding the character to the word string, till a space is found then append the word to the list
        # and then set the word back to an empty string
        return_list = []
        word = ""
        for string_char in input_string:
            if (string_char != " "):
                word += string_char
            else:
                return_list.append(word)
                word = ""

        # Final check if the word is not blank then append it to the list; finally return the list
        if (word != ""):
            return_list.append(word)
        return (return_list)

    def open_column_filter_menu(self, element):
        # Get the list of column buttons
        column_element_btn_candidates = element.find_elements_by_tag_name("div")
        column_element_btn = column_element_btn_candidates[10] # should be the button anyway

        # loop through the column buttons
        for index in range(0, len(column_element_btn_candidates)):
            column_element_btn_candidates = element.find_elements_by_tag_name("div")

            # find the open filter menu button
            if (column_element_btn_candidates[index].get_attribute("class") == "jqx-grid-column-menubutton " +
                    "jqx-grid-column-menubutton-custom jqx-icon-arrow-down jqx-icon-arrow-down-custom"):
                column_element_btn = column_element_btn_candidates[index]

        # click the button and return it
        column_element_btn.click()
        return (column_element_btn)

    def push_the_filter_clear_button(self, index, web_driver):
        # Get the list of columns and store the index one, then open the filter menu for it, and finally grab the clear button and click it
        column_element = AlarmsFiltersTest.get_alarm_columns(self, web_driver)[index]
        filter_options_menu = AlarmsFiltersTest.open_the_filter_menu(self, column_element, web_driver)
        filter_options_menu.find_element_by_id("filterclearbuttonalarmsGrid").click()


if __name__ == "__main__":
    unittest.main()
