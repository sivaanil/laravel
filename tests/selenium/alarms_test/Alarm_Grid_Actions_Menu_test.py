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


class AlarmGridActionsMenuTest(c2_test_case.C2TestCase):
    def test_launch_web_should_open_gaucamole_session_C11550(self):
        # Get the driver
        driver = self.config.driver

        # Change the panel widths so that all columns, tabs, and buttons are visible
        AlarmGridActionsMenuTest.change_panel_widths(self, driver)

        # wait for the 1st alarm to display then locate its action button and click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm row did not load within the allotted " + str(self.config.mid_timeout) + " seconds")
        alarm_menu_button = driver.find_element_by_xpath("//div[@id='row0alarmsGrid']/div[9]")
        alarm_menu_button.click()

        # Wait for the Actions menu to display and store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "alarmsGrid_grid_menu_container"))
            )
        except TimeoutException:
            self.fail("Alarm Actions menu did not open within the allotted " + str(self.config.mid_timeout) + " seconds")
        alarm_menu_element = driver.find_element_by_id("alarmsGrid_grid_menu_container")

        # Retrieve the web interface button from the Actions menu, check to make sure it is visible, and click it
        web_interface_button = alarm_menu_element.find_element_by_id("wedInterface")
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of(web_interface_button)
            )
        except TimeoutException:
            self.fail("Alarm Actions menu did not open within the allotted " + str(self.config.mid_timeout) + " seconds")
        web_interface_button.click()

        # Store the current window then get a list of the open windows. Ensure that there are two window open if not fail the test case.
        current_window = driver.current_window_handle
        window_list = driver.window_handles
        # To do the above check I needed to check the length of the list so I've set up a loop to check every second that the list of windows
        # are indeed greater then 1 and if so I break out of the loop, if the loop hits the max ammount of seconds allowed I fail the test.
        for sec in range(0, self.config.long_timeout):
            window_list = driver.window_handles
            if (len(window_list) >= 2):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("Gaucamole page not opened")
            time.sleep(1)

        # Loop through the open windows list and find the window that isn't the one the driver is currently connected to, then switch the
        # driver's connection to it.
        for window_id in window_list:
            if (window_id != current_window):
                driver.switch_to_window(window_id)

        # Search the url of the new window for the piece of the guac url we know for sure if it is not found fail the case.
        gauc_url = self.config.base_url + "guacamole"
        if (driver.current_url.find(gauc_url) == -1):
            self.fail("Gaucamole did not load (URL: " + driver.current_url + ")")






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

# This test just ensures that the window handle is switched back to Unified after Guacamole has been opened once.
class AlarmGridActionsMenuTestCleanup(c2_test_case.C2TestCase):
    def test_cleanup_Gaucamole(self):
        #Get the driver
        driver = self.config.driver

        # Get the current window and the list of windows
        current_window = driver.current_window_handle
        window_list = driver.window_handles

        # Make sure the list of windows is greater then one and store the partial guac url
        if (len(window_list) > 1):
            gauc_url = self.config.base_url + "guacamole"

            # Check to ensure the window is guacamole and then loop till we find the Unified window and switch the driver's control to it.
            if (driver.current_url.find(gauc_url) != -1):
                for window_id in window_list:
                    if (window_id != current_window):
                        driver.switch_to_window(window_id)

        # Final check just to ensure the right window was switched to check to make sure the url is for Unified
        expected_url = (self.config.base_url + "home#/alarms/" + self.config.root_node)
        self.assertEqual(driver.current_url, expected_url, "FAILURE! driver did not switch to the correct window. Current URL: " +
                         driver.current_url + " expected url: " + expected_url + ".")




if __name__ == "__main__":
    unittest.main()

