__author__ = 'daniel.madden'

# -*- coding: utf-8 -*-
import sys
sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException
from selenium.common.exceptions import NoSuchElementException

import unittest, time

class AlarmsGeneralTest(c2_test_case.C2TestCase):

    def test_alarms_button_url_change_C10202(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the Alarms button to load and then click it
        try:
            WebDriverWait (driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'Alarms'))
            )
        except:
            self.fail("Alarms menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_link_text("Alarms").click()

        # Check to see if the url is the expected one if not fail the test
        expected_url = (self.config.base_url + "home#/alarms/" + self.config.root_node)
        self.assertEqual(driver.current_url, expected_url, "{} FAILURE! URL Redirect to '{}' did not work.".format(__file__, expected_url))

    def test_alarms_hover_color_change_C10263(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the Alarms button to load and then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'Alarms'))
            )
        except:
            self.fail("Alarms menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        element_to_hover_over = driver.find_element_by_link_text("Alarms")

        # Emulate the mouse hovering over the Alarms button
        hover = ActionChains(driver).move_to_element(element_to_hover_over)
        hover.perform()

        # Check every second for the Alarms button to be in its hover state, if max time met then fail the case
        for sec in range(0, self.config.mid_timeout):
            if (element_to_hover_over.value_of_css_property("border-bottom-color") != "rgba(255, 255, 255, 1)"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Alarms menu button not highlighted on hover after " + str(self.config.mid_timeout) + " seconds")
            time.sleep(1)

    def test_alarms_grid_loads_C11545(self):
        # get the driver
        driver = self.config.driver

        # Wait for the alarm grid to load and then wait for the first row to load (just in case);
        # if they don't load within 60 - 180 seconds fail the test with a timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGrid"))
            )
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm Grid did not load within " + str(self.config.mid_timeout) + " seconds")

    def test_should_vertical_scrollbar_display_C11562(self):
        # get the driver
        driver = self.config.driver

        # Wait for the Custom tab to load and click it.
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "tab-custom-filters"))
            )
        except TimeoutException:
            self.fail("Custom filter tab did not load within " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_id("tab-custom-filters").click()

        # Wait for the all states checkbox to load/display and then store the label for the checkbox
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsFilter_allstates_CB_list"))
            )
        except TimeoutException:
            self.fail("Custom Filter dialog did not load within " + str(self.config.mid_timeout) + " seconds")
        custom_filter_label_numbers = driver.find_element_by_id("alarmsFilter_allstates_CB_list").find_elements_by_class_name("filter-count")

        # Loop through the label keeping only the numbers and total up the max alarms
        total_alarms = 0
        for num in custom_filter_label_numbers:
            num_string = ""
            for ch in num.text:
                if (ch != '(' and ch != ')'):
                    num_string += ch
            total_alarms += int(num_string)

        # Close the Custom filter dialog and if the total number of alarms is greater then 8 check to see if the vertical scrollbar exists
        # if it doesn't fail the test.
        driver.find_element_by_xpath("//div[@id='windowHeader']/div[2]/div").click()
        if (total_alarms > 8):
            try:
                driver.find_element_by_id("verticalScrollBaralarmsGrid")
            except NoSuchElementException:
                self.fail("The vertical scroll bar cannot be found, but should exist")

    def test_order_of_alarm_tabs_C134140(self):
        # get the driver
        driver = self.config.driver

        # Wait for the alarm tabs to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmTabMenu"))
            )
        except TimeoutException:
            self.fail("Alarm tabs did not load within " + self.config.mid_timeout + " seconds.")

        # Store the alarm tabs list and then store a list of the expected names in order
        alarm_tab_container_element = driver.find_element_by_id("alarmTabMenu")
        alarm_tab_elements_list = alarm_tab_container_element.find_elements_by_tag_name("li")
        expected_tab_names = ["Active Alarms", "Alarm History", "All Alarms", "Ignored Alarms", "Custom"]

        # Loop through the alarm tabs list and check if they're correct, fail the test if not.
        index = 0
        for tab in alarm_tab_elements_list:
            self.assertEqual(tab.text, expected_tab_names[index], "Tab name: " + tab.text + " is not the expected name: " +
                             expected_tab_names[index])
            index += 1

    # def test_tooltips_are_correct_C141426(self):
    #     # get the driver and sleep for the alarm grid to load
    #     driver = self.config.driver
    #
    #     tooltip_fail_messages = ""
    #
    #     try:
    #         WebDriverWait(driver, 60).until(
    #             expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='splitter']/div[2]"))
    #         )
    #     except TimeoutException:
    #         self.fail("Canvas divider did not load within 60 seconds")
    #     divider_element = driver.find_element_by_xpath("//div[@id='splitter']/div[2]")
    #
    #     left_pos = int(divider_element.value_of_css_property("left").replace("px", ""))
    #     if (left_pos > 309):
    #         actions = ActionChains(driver)
    #         actions.move_to_element(divider_element)
    #         actions.move_by_offset(0, 120)
    #         actions.perform()
    #         time.sleep(2)
    #
    #         actions = ActionChains(driver)
    #         actions.click_and_hold()
    #         actions.perform()
    #         time.sleep(1)
    #
    #         for index in range(0, 150):
    #             actions = ActionChains(driver)
    #             actions.move_by_offset(-1, 0)
    #             actions.perform()
    #
    #         actions = ActionChains(driver)
    #         actions.release()
    #         actions.perform()
    #         time.sleep(5)
    #
    #     try:
    #         WebDriverWait(driver, 60).until(
    #             expected_conditions.presence_of_element_located((By.ID, "alarmTabMenu"))
    #         )
    #     except TimeoutException:
    #         self.fail("Alarm tabs did not load within 60 seconds.")
    #     alarm_tab_container_element = driver.find_element_by_id("alarmTabMenu")
    #     alarm_tab_elements_list = alarm_tab_container_element.find_elements_by_tag_name("li")
    #
    #     for tab in alarm_tab_elements_list:
    #         actions


if __name__ == "__main__":
    unittest.main()