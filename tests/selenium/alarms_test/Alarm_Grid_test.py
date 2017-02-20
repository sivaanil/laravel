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


class AlarmGridTest(c2_test_case.C2TestCase):
    def test_click_row_highlights_row_C10197(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the 1st alarm to load and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm row did not load with the allotted " + str(self.config.mid_timeout) + " seconds.")
        alarm_row = driver.find_element_by_id("row0alarmsGrid")
        alarm_row.click()

        # Every second check that the background color of the alarm row has been set to the highlighted color and if so pass the test. If
        # it hasn't changed by the max second then fail the case.
        for sec in range(0, self.config.mid_timeout):
            alarm_row_background = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[2]")
            if (alarm_row_background.value_of_css_property("background-color") == 'rgba(127, 206, 255, 1)'):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Alarm row did not highlight when clicked!")
            time.sleep(1)

    def test_grid_auto_resizes_when_set_C10203(self):
        # Get the driver
        driver = self.config.driver

        # Move the divider to display all the columns, buttons, and tabs.
        AlarmGridTest.change_panel_widths(self, driver)

        # Wait for the auto resize checkbox to be visible and then store it.
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGrid_resizeCB"))
            )
        except TimeoutException:
            self.fail("Alarm Grid resize checkbox failed to load within allotted " + str(self.config.mid_timeout) + " seconds")
        auto_resize_button = driver.find_element_by_id("alarmsGrid_resizeCB")

        # If the auto resize checkbox is not checked, click it
        if (auto_resize_button.is_selected() == False):
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.visibility_of_element_located((By.ID, "alarmsGrid_resizeCB"))
                )
            except TimeoutException:
                self.fail("Cannot set the Alarm Grid resize checkbox!")
            auto_resize_button = driver.find_element_by_id("alarmsGrid_resizeCB")
            auto_resize_button.click()

        # Find the splitter button and click it
        splitter_button = driver.find_element_by_xpath("//div[@id='splitter']/div[2]/div")
        splitter_button.click()

        # Find the content side and get its width
        canvas_element = driver.find_element_by_id("content")
        canvas_element_width = float(AlarmGridTest.cut_off_string_at(self, canvas_element.value_of_css_property("width"), 'p'))

        # Find the alarm grid and get its width
        alarms_grid_element = driver.find_element_by_id("alarmsGrid")
        alarms_grid_element_width = float(AlarmGridTest.cut_off_string_at(self, alarms_grid_element.value_of_css_property("width"), "p"))

        # Check that the alarms grid fills the canvas
        self.assertLess(canvas_element_width - 20, alarms_grid_element_width, "Columns not resized properly! (canvas width: " +
                        str(canvas_element_width) + "; alarm grid width: " + str(alarms_grid_element_width) + ")")

        # Click the splitter button again to return the alarm grid to regular size.
        splitter_button.click()

    def test_clicking_device_path_goes_to_correct_link_C10209(self):
        # Get the driver
        driver = self.config.driver

        # Get the current url and store it (for switching back to the home node later)
        original_address = driver.current_url

        # Wait for the 1st alarm row to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm row did not load")

        # Find the device path link from the 1st alarm row, store the url, and click the link
        alarm_link = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[3]/div/a")
        alarm_link_text = alarm_link.get_attribute("href")
        alarm_link.click()

        # Every second see if the current url is equal to the url from the alarm. If after the max time given the url isn't correct fail the
        # case
        for sec in range(0, self.config.mid_timeout):
            if (driver.current_url == alarm_link_text):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("The propper node did not select within " + str(self.config.mid_timeout) + " seconds")
            time.sleep(1)

        # Tell the driver to go to the first address (which should be the home node)
        driver.get(original_address)
        AlarmGridTest.refresh_and_wait(self, driver)

    def test_mouse_over_row_should_highlight_it_C10211(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the 1st alarm row to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm row did not load")

        # Find the 1st alarm row and emulate the mouse moving over it. store the alarm row once again.
        alarm_row = driver.find_element_by_id("row0alarmsGrid")
        ActionChains(driver).move_to_element(alarm_row).perform()
        alarm_row = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[2]")

        # Every second check the background of the alarm row to see if it's taken the hover state, if it does not within the allotted
        # timeframe fail the test.
        for sec in range(0, self.config.mid_timeout):
            if (alarm_row.value_of_css_property("background_image") != "none"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Hover failed!")
            time.sleep(1)


    def test_click_i_button_should_open_alarm_note_C10212(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmGridTest.change_panel_widths(self, driver)

        # loop 24 times (a number that is more rows then actually available) and each iteration wait for the row to load.
        for index in range(0, 24):
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.presence_of_element_located((By.ID, "row" + str(index) + "alarmsGrid"))
                )
            except TimeoutException:
                self.fail("Alarm row did not load")

            # Make sure the row is visible then find the notes button and if it has text click it. If the row is not visible break out of the
            # loop since there are around 5 rows at the bottom of the list that remain hidden.
            if (driver.find_element_by_id("row" + str(index) + "alarmsGrid").is_displayed() == True):
                notes_button = driver.find_element_by_xpath("//div[@id='row" + str(index) + "alarmsGrid']/div[8]/div")
                if (notes_button.text != ""):
                    notes_button.click()

                    # Wait for the notes dialog to display, find it, and check if it is visible
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.presence_of_element_located((By.ID, "notesWindow"))
                        )
                    except TimeoutException:
                        self.fail("Notes window did not load.")
                    notes_window_element = driver.find_element_by_id("notesWindow")
                    self.assertEqual(notes_window_element.is_displayed(), True, "Test failed Notes Window did not display.")

                    # Find and click the close button on the notes dialog to close it, then break out of the loop
                    notes_window_element.find_element_by_xpath(".//div/div/div[2]/div").click()
                    break
            else:
                break

    def test_click_actions_menu_should_open_the_menu_C10213(self):
        # Get the driver and move the divider so that all buttons, tabs, and columns are visible
        driver = self.config.driver
        AlarmGridTest.change_panel_widths(self, driver)

        # Wait for the 1st alarm row to load, find its action button, and click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='row0alarmsGrid']/div[9]"))
            )
        except TimeoutException:
            self.fail("Alarm row did not load")
        alarm_row_action_button = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[9]")
        alarm_row_action_button.click()

        # Wait for Action menu to display if it doesn't display in the allotted time fail the test
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGrid_grid_menu_container"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGrid_grid_menu_container"))
            )
        except TimeoutException:
            self.fail("Alarm action menu not opened!")

    def test_message_displays_for_no_alarms_C10213(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the network tree to load and then store it
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "netTree"))
            )
        except TimeoutException:
            self.fail("Network tree failed to load within the alotted " + str(self.config.long_timeout) + " minutes")
        network_tree_element = driver.find_element_by_id("netTree")

        # Get the list of the network tree nodes and check to ensure the entire tree loaded, if it doesn't load within the allotted time
        # fail the test case.
        network_tree_nodes_list = network_tree_element.find_elements_by_tag_name("li")
        for sec in range(0, self.config.long_timeout):
            if (len(network_tree_nodes_list) <= 1):
                network_tree_nodes_list = network_tree_element.find_elements_by_tag_name("li")
            elif (sec >= self.config.long_timeout - 1):
                self.fail("Network Tree nodes did not load after " + str(self.config.long_timeout) + " seconds")
            else:
                break
            time.sleep(1)

        # Loop through the nodes in the tree, check to see if the node is visible and if so click it.
        for node_element in network_tree_nodes_list:
            if (node_element.is_displayed() == True):
                node_element.find_element_by_xpath(".//div").click()

                # Wait for the alarm grid to load and then find the 1st alarm row and store it.
                try:
                    WebDriverWait(driver, self.config.mid_timeout).until(
                        expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                    )
                except TimeoutException:
                    self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")
                first_alarm_row = driver.find_element_by_id("row0alarmsGrid")

                # get the alarm row's height, see if the height is greater then 63 (standard alarm row size) and if so check if the text
                # is the expected text.
                amount_in_pixels = AlarmGridTest.cut_off_string_at(self, first_alarm_row.value_of_css_property("height"), 'p')
                if (float(amount_in_pixels) > 63):
                    self.assertEqual(driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[1]").text, "No alarms to display",
                                     "Expected text: 'No alarms to display' was not found for node without alarms. Got text: " +
                                     driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[1]").text)

                    # Return to the home node and wait for the alarm grid to load, then break out of the loop
                    network_tree_element.find_element_by_xpath(".//ul/li/div").click()
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
                        )
                    except TimeoutException:
                        self.fail("Alarm row failed to load within alotted " + str(self.config.mid_timeout) + " seconds")
                    break





    ## Helper functions ##
    def cut_off_string_at(self, input_string, char_cut_off):
        # loop through every character in the string, adding it to a new string, till the cut off character is found then return the new string
        return_string = ""
        for string_char in input_string:
            if (string_char == char_cut_off):
                break
            else:
                return_string += string_char
        return (return_string)

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

if __name__ == "__main__":
    unittest.main()

