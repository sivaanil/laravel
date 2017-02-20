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

import unittest
import time


class AlarmsMenuSelectColumnsTest(c2_test_case.C2TestCase):
    def test_columns_in_available_columns_are_not_in_the_grid_C10219(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Locate and store the available columns list and then get the grid columns
        available_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_availableColumnsList").find_elements_by_tag_name("li")
        alarm_grid_columns = AlarmsMenuSelectColumnsTest.get_alarm_columns(self, driver)

        # Loop through the columns in the grid, make sure the column is visible and then check to see that the column isn't any of the
        # columns in the available list.
        for alarm_column in alarm_grid_columns:
            if (alarm_column.is_displayed() == True):
                for available_column in available_columns_list:
                    self.assertNotEqual(alarm_column.text, available_column.text,
                                        available_column.text + " column should not be found in the grid.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_columns_in_displayed_columns_are_in_the_grid_C10220(self):
        #Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Locate and store the available columns list and then get the grid columns
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        alarm_grid_columns = AlarmsMenuSelectColumnsTest.get_alarm_columns(self, driver)

        # Loop through all the columns in the displayed list and because the floater column still exists (just hidden) make sure non of the
        # columns are displayed. Then get the column label but cut out the plus, minus, and new line characters.
        for displayed_column in displayed_columns_list:
            if (displayed_column.is_displayed() == True):
                displayed_column_text = ""
                for character in displayed_column.text:
                    if (character != '+' and character != '-' and character != '\n'):
                        displayed_column_text += character

                # Loop through the columns in the alarm grid
                for index in range(0, len(alarm_grid_columns)):
                    alarm_column = alarm_grid_columns[index]

                    # Make sure the column is displayed then make sure the column matches the displayed one
                    if (alarm_column.is_displayed() == True):
                        if (alarm_column.text == displayed_column_text):
                            break

                    # If we get to the end of the list of columns on the alarm grid and still haven't found the displayed column then fail the
                    # test
                    if (index >= len(alarm_grid_columns) - 1):
                        self.fail(displayed_column.text + " column could not be found on the grid.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_moving_a_column_to_display_adds_it_to_alarm_grid_C10221(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Find a column in the available list to add, click it to select it, store the column's label, and finally click the column add button
        column_to_add = select_columns_dialog.find_element_by_id("alarmsGrid_availableColumnsList").find_element_by_tag_name("li")
        column_to_add.click()
        column_to_add_text = column_to_add.text
        select_columns_dialog.find_element_by_id("alarmsGrid_selectButtonAdd").click()

        # Get the alarm grid columns and loop through them
        alarm_grid_columns = AlarmsMenuSelectColumnsTest.get_alarm_columns(self, driver)
        for index in range(0, len(alarm_grid_columns)):
            alarm_column = alarm_grid_columns[index]

            # Ensure the column is visible and if the label of the column matches the added column's label break out of the loop (test passed)
            if (alarm_column.is_displayed() == True):
                if (alarm_column.text == column_to_add_text):
                    break

            # If we get to the end of the list of columns fail the test (assume the column was not added)
            if (index >= len(alarm_grid_columns) - 1):
                self.fail("The " + column_to_add_text + " column was not added to the alarm grid.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_moving_a_column_to_available_removes_it_from_the_grid_C10223(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Find a column in the available list, click it to select it, store the column's label, and finally click the column
        # add button to add it, and then click the column remove button to remove it again.
        column_to_add = select_columns_dialog.find_element_by_id("alarmsGrid_availableColumnsList").find_element_by_tag_name("li")
        column_to_add.click()
        column_to_add_text = column_to_add.text
        select_columns_dialog.find_element_by_id("alarmsGrid_selectButtonAdd").click()
        select_columns_dialog.find_element_by_id("alarmsGrid_selectButtonRemove").click()

        # Get the columns from the alarm grid and loop through them, make sure the column is displayed (columns are hidden when removed),
        # and check to make sure the column label does not match the label of the removed column.
        alarm_grid_columns = AlarmsMenuSelectColumnsTest.get_alarm_columns(self, driver)
        for alarm_column in alarm_grid_columns:
            if (alarm_column.is_displayed() == True):
                self.assertNotEqual(alarm_column.text, column_to_add_text, "Found removed column.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_plus_button_C10224(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Store the width of the column before we increase it
        column_default_width = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[3]").value_of_css_property("width")

        # Get the list of displayed columns and loop through them searching for the Device Path column, once found find its plus button and
        # click it 3 times.
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        for displayed_column in displayed_columns_list:
            if (displayed_column.text.find("Device Path") != -1):
                plus_button = displayed_column.find_element_by_xpath(".//div[1]")
                for index in range(0, 3):
                    plus_button.click()
                break

        # Check that the device path column's new width is greater then the previous one else fail the test; then reset the alarm grid for
        # the next test.
        self.assertGreater(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[3]").value_of_css_property("width"),
                            column_default_width, "The column did not increase in width")
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_minus_button_C10224(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Store the width of the column before we increase it
        column_default_width = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[3]").value_of_css_property("width")

        # Get the list of displayed columns and loop through them searching for the Device Path column, once found find its minus button and
        # click it 3 times.
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        for displayed_column in displayed_columns_list:
            if (displayed_column.text.find("Device Path") != -1):
                plus_button = displayed_column.find_element_by_xpath(".//div[2]")
                for index in range(0, 3):
                    plus_button.click()
                break

        # Check that the device path column's new width is less then the previous one else fail the test; then reset the alarm grid for
        # the next test.
        self.assertLess(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[3]").value_of_css_property("width"),
                            column_default_width, "The column did not decrease in width")
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_columns_modified_should_uncheck_auto_resize_C11496(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Get the list of displayed columns and loop through them searching for the Device Path column, once found find its minus button and
        # click it 3 times.
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        for displayed_column in displayed_columns_list:
            if (displayed_column.text.find("Device Path") != -1):
                plus_button = displayed_column.find_element_by_xpath(".//div[2]")
                for index in range(0, 3):
                    plus_button.click()
                break

        # Get the auto resize checkbox, check that it is not checked and if it is fail the test, if not click it.
        auto_resize_checkbox = driver.find_element_by_id("alarmsGrid_resizeCB")
        self.assertEqual(auto_resize_checkbox.is_selected(), False, "Auto Resize checkbox is still selected after plus button clicked.")
        auto_resize_checkbox.click()

        # Find a column in the available list to add, click it to select it, and click the column add button
        column_to_add = select_columns_dialog.find_element_by_id("alarmsGrid_availableColumnsList").find_element_by_tag_name("li")
        column_to_add.click()
        select_columns_dialog.find_element_by_id("alarmsGrid_selectButtonAdd").click()

        # Get the auto resize checkbox, check that it is not checked and if it is fail the test
        auto_resize_checkbox = driver.find_element_by_id("alarmsGrid_resizeCB")
        self.assertEqual(auto_resize_checkbox.is_selected(), False, "Auto Resize checkbox is still selected after column was added.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_move_column_down_C10225(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Get the list of displayed columns and loop through them searching for the Device Path column, once found find the move column down
        # button and click it
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        for displayed_column in displayed_columns_list:
            if (displayed_column.text.find("Device Path") != -1):
                displayed_column.click()
                select_columns_dialog.find_element_by_id("alarmsGrid_reorderButtonDown").click()

        # Get the displayed columns list and then loop through it to get the labels of the columns without the plus, minus, or new line characters
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        displayed_column_text_list = []
        for displayed_column in displayed_columns_list:
            displayed_column_text = ""
            for character in displayed_column.text:
                if (character != '+' and character != '-' and character != '\n'):
                    displayed_column_text += character
            displayed_column_text_list.append(displayed_column_text)

        # Get the column list from the grid and loop through it and get a list of the labels
        alarm_column_list = AlarmsMenuSelectColumnsTest.get_alarm_columns(self, driver)
        alarm_column_text_list = []
        for alarm_column in alarm_column_list:
            if (alarm_column.is_displayed() == True):
                alarm_column_text_list.append(alarm_column.text)

        # loop through the two lists and check that each set of labels are equal, otherwise fail the test
        for index in range(0, len(displayed_column_text_list)):
            self.assertEqual(displayed_column_text_list[index], alarm_column_text_list[index],
                             alarm_column_text_list[index] + " column is in the wrong spot, should be " + alarm_column_text_list[index] +
                             " column.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_move_column_up_C10225(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Get the list of displayed columns and loop through them searching for the Device Path column, once found find the move column up
        # button and click it
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        for displayed_column in displayed_columns_list:
            if (displayed_column.text.find("Device Path") != -1):
                displayed_column.click()
                select_columns_dialog.find_element_by_id("alarmsGrid_reorderButtonUp").click()

        # Get the displayed columns list and then loop through it to get the labels of the columns without the plus, minus, or new line characters
        displayed_columns_list = select_columns_dialog.find_element_by_id("alarmsGrid_selectedColumnsList").find_elements_by_tag_name("li")
        displayed_column_text_list = []
        for displayed_column in displayed_columns_list:
            displayed_column_text = ""
            for character in displayed_column.text:
                if (character != '+' and character != '-' and character != '\n'):
                    displayed_column_text += character
            displayed_column_text_list.append(displayed_column_text)

        # Get the column list from the grid and loop through it and get a list of the labels
        alarm_column_list = AlarmsMenuSelectColumnsTest.get_alarm_columns(self, driver)
        alarm_column_text_list = []
        for alarm_column in alarm_column_list:
            if (alarm_column.is_displayed() == True):
                alarm_column_text_list.append(alarm_column.text)

        # loop through the two lists and check that each set of labels are equal, otherwise fail the test
        for index in range(0, len(displayed_column_text_list)):
            self.assertEqual(displayed_column_text_list[index], alarm_column_text_list[index],
                             alarm_column_text_list[index] + " column is in the wrong spot, should be " + alarm_column_text_list[index] +
                             " column.")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)

    def test_can_move_dialog_C11495(self):
        # Get the driver
        driver = self.config.driver

        # Reset the alarm grid in case the previous test didn't; move the divider so all tabs, buttons, and columns display; and open
        # the select columns dialog
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)
        AlarmsMenuSelectColumnsTest.change_panel_widths(self, driver)
        select_columns_dialog = AlarmsMenuSelectColumnsTest.open_select_columns_dialog(self, driver)

        # Get the dialog's header which the mouse can use to drag the dialog, also get the dialog's horizontal and vertical positions
        select_columns_dialog_header_element = select_columns_dialog.find_element_by_id("alarmsGridColumnPopupHeader")
        select_columns_dialog_top_position = select_columns_dialog.value_of_css_property("top")
        select_columns_dialog_left_position = select_columns_dialog.value_of_css_property("left")

        # Emulate moving the mouse to the header and then clicking down on it
        actions = ActionChains(driver)
        actions.move_to_element(select_columns_dialog_header_element)
        actions.click_and_hold(select_columns_dialog_header_element)
        actions.perform()

        # Emulate moving the mouse to the top left 50 pixels (this does nothing to the dialog for some reason)
        for index in range(0, 50):
            actions = ActionChains(driver)
            actions.move_by_offset(-1, -1)
            actions.perform()

        # Emulate moving the mouse back across the dialog and down right by 10 pixels (and for some reason this gets the dialog to drag)
        for index in range(0, 60):
            actions = ActionChains(driver)
            actions.move_by_offset(1, 1)
            actions.perform()

        # Emulate the mouse releasing the dialog
        actions = ActionChains(driver)
        actions.release()
        actions.perform()

        # Check that the dialog's new horizontal and vertical positions are not equal to the previous ones, otherwise fail the test.
        self.assertNotEqual(select_columns_dialog.value_of_css_property("top"), select_columns_dialog_top_position,
                            "Custom Filter Dialog's new top position: " + select_columns_dialog.value_of_css_property("top") +
                            " is still equal to the Custom Filter Dialog's old top position: " + select_columns_dialog_top_position + ".")
        self.assertNotEqual(select_columns_dialog.value_of_css_property("left"), select_columns_dialog_left_position,
                            "Custom Filter Dialog's new left position: " + select_columns_dialog.value_of_css_property("left") +
                            " is still equal to the Custom Filter Dialog's old left position: " + select_columns_dialog_left_position + ".")

        # Reset the alarm grid for the next test
        AlarmsMenuSelectColumnsTest.reset_alarm_grid(self, driver)















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

    def open_select_columns_dialog(self, web_driver):
        # Wait for the Select Columns button to load/display and then click it
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGridColumnButton"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGridColumnButton"))
            )
        except TimeoutException:
            self.fail("Select Column button did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        web_driver.find_element_by_id("alarmsGridColumnButton").click()

        # Wait for the select columns dialog to load and then return it
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGridColumnPopup"))
            )
        except TimeoutException:
            self.fail("Select Column dialog did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")
        return(web_driver.find_element_by_id("alarmsGridColumnPopup"))

    def reset_alarm_grid(self, web_driver):
        # If the select column dialog is displayed click its close button
        if (web_driver.find_element_by_id("alarmsGridColumnPopup").is_displayed() == True):
            web_driver.find_element_by_xpath("//div[@id='alarmsGridColumnPopup']/div/div/div[2]/div").click()

            # Click the reset columns button to ensure the columns are in the correct positions and sizes
            web_driver.find_element_by_id("alarmsGridResetColumnsButton").click()

            # Wait for the alarm grid to update
            try:
                WebDriverWait(web_driver, self.config.mid_timeout).until(
                    expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='splitter']/div[2]"))
                )
            except TimeoutException:
                self.fail("The canvas divider did not load within " + str(self.config.mid_timeout) + " seconds")

            # Find the auto resize checkbox and make sure it is selected, if not click it to select it
            auto_resize_checkbox = web_driver.find_element_by_id("alarmsGrid_resizeCB")
            if (auto_resize_checkbox.is_selected() == False):
                auto_resize_checkbox.click()

            # Find the divider and then emulate the mouse moving to it and offsetting a bit to grab the dragable part
            divider_element = web_driver.find_element_by_xpath("//div[@id='splitter']/div[2]")
            actions = ActionChains(web_driver)
            actions.move_to_element(divider_element)
            actions.move_by_offset(0, 120)
            actions.perform()

            # Emulate the mouse holding down on the divider
            actions = ActionChains(web_driver)
            actions.click_and_hold()
            actions.perform()

            # Emulate the mouse moving back to the right by 20 pixel
            for index in range(0, 20):
                actions = ActionChains(web_driver)
                actions.move_by_offset(1, 0)
                actions.perform()

            # Emulate the mouse releasing the divider
            actions = ActionChains(web_driver)
            actions.release()
            actions.perform()

if __name__ == "__main__":
    unittest.main()