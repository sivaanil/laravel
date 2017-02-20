__author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.keys import Keys


import unittest, time


class AlarmGridPageControlsTest(c2_test_case.C2TestCase):
    def test_dropdown_should_show_rows_per_page_C10237(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the Alarm Grid footer to be available and then find and click the Rows Per Page dropdown
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "pageralarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm grid footer didn't load within the allotted " + str(self.config.mid_timeout) + " seconds")
        rows_per_page_dropdown_element = driver.find_element_by_xpath("//div[@id='pageralarmsGrid']/div/div/div/div/div/div[2]/div")
        rows_per_page_dropdown_element.click()

        # Get the list of dropdown options and store it
        rows_per_page_dropdown_options_list = driver.find_elements_by_class_name("jqx-listitem-element")

        # Create an array for the expected dropdown options then loop through the dropdown options array and ensure all options are correct
        expected_options_array = ["10", "25", "50"]
        for expected_option in expected_options_array:
            # I wanted to ensure that we could take the dropdown options in any order so we loop through the collected options list here to
            # check for the values correct, and if we don't find an option by the end of the list then I fail the test case.
            for index in range(0, len(rows_per_page_dropdown_options_list)):
                if (rows_per_page_dropdown_options_list[index].text == expected_option):
                    break
                elif (index >= len(rows_per_page_dropdown_options_list) - 1):
                    self.fail("The dropdown options found do not match the expected options.")

        # Find the Rows Per Page dropdown again and click it to close it
        rows_per_page_dropdown_element = driver.find_element_by_xpath("//div[@id='pageralarmsGrid']/div/div/div/div/div/div[2]/div")
        rows_per_page_dropdown_element.click()

    def test_skip_left_arrow_should_go_to_first_page_C10238(self):
        #Get the driver
        driver = self.config.driver

        # Wait for the Alarm Grid footer to be available and then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "pageralarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm grid footer didn't load within the allotted " + str(self.config.mid_timeout) + " seconds")
        alarm_grid_footer_element = driver.find_element_by_id("pageralarmsGrid")

        # Wait for the number of pages to load (which is the last thing on the alarm grid to load)
        for sec in range(0, self.config.mid_timeout):
            if (alarm_grid_footer_element.find_element_by_class_name("end-label").text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Number of pages didn't load.")
            time.sleep(1)

        # Find the Last Page button in the alarm grid footer and click it, then wait for the last page to load.
        skip_right_button = alarm_grid_footer_element.find_element_by_class_name("right-last-button")
        skip_right_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip right was clicked")

        # Find the First Page button in the alarm grid footer and click it, then wait for the first page to load
        skip_left_button = alarm_grid_footer_element.find_element_by_class_name("left-first-button")
        skip_left_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip left was clicked")

        # Find the current page text field in the footer and then check that the current page is equal to 1 since the first page should
        # be page 1.
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        self.assertEqual(alarm_grid_footer_current_page_text_field.get_attribute("value"), "1",
                         "Skip left button did not go back to the first page as expected! (current page: " +
                         alarm_grid_footer_current_page_text_field.get_attribute("value") + ")")

    def test_left_arrow_should_go_to_previous_page_C10239(self):
        #Get the driver
        driver = self.config.driver

        # Wait for the Alarm Grid footer to be available and then store it.
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "pageralarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm grid footer didn't load within the allotted " + str(self.config.mid_timeout) + " seconds")
        alarm_grid_footer_element = driver.find_element_by_id("pageralarmsGrid")

        # Wait for the number of pages to load (which is the last thing on the alarm grid to load)
        for sec in range(0, self.config.mid_timeout):
            alarm_grid_footer_element = driver.find_element_by_id("pageralarmsGrid")
            if (alarm_grid_footer_element.find_element_by_class_name("end-label").text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Number of pages didn't load.")
            time.sleep(1)

        # Find the Last Page button in the alarm grid footer and click it, then wait for the last page to load.
        skip_right_button = alarm_grid_footer_element.find_element_by_class_name("right-last-button")
        skip_right_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip right was clicked")

        # Find the current page text field and store the number as the previous page number
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        previous_page_number = int(alarm_grid_footer_current_page_text_field.get_attribute("value"))

        # Find and click the previous page button, then wait for the previous page to load.
        previous_button = alarm_grid_footer_element.find_element_by_class_name("left-button")
        previous_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after left was clicked")

        # Find the current page text field and store the current page number
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        current_page_number = int(alarm_grid_footer_current_page_text_field.get_attribute("value"))

        # If click to the last page did not work for whatever reason (still investigating), use the number of pages label to get the last
        # page number and then check if the current page is equal to that. Otherwise check that the current page is less then the previous
        # page number.
        if (previous_page_number == 1):
            current_page_should_be = int(AlarmGridPageControlsTest.cut_off_string_at(self,
                alarm_grid_footer_element.find_element_by_class_name("end-label").text, " "))
            self.assertEqual(current_page_number, current_page_should_be, "Did not go to the previous page as expected." +
                             " previous page is 1; current page expected to be: " + str(current_page_should_be) + "; but is: " +
                             str(current_page_number))
        else:
            self.assertLess(int(current_page_number), int(previous_page_number),
                        "Did not go to the previous page as expected. (current page: " + str(current_page_number) + "; previous page: " +
                        str(previous_page_number) + ")")

        # Find the first page button and then click it and wait for the first page to load (this resets the grid for the next test)
        skip_left_button = alarm_grid_footer_element.find_element_by_class_name("left-first-button")
        skip_left_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip left was clicked")

    def test_right_arrow_should_go_to_next_page_C10240(self):
        #Get the driver
        driver = self.config.driver

        # Wait for the Alarm Grid footer to be available and then store it.
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "pageralarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm grid footer didn't load within the allotted " + str(self.config.mid_timeout) + " seconds")
        alarm_grid_footer_element = driver.find_element_by_id("pageralarmsGrid")

        # Wait for the number of pages to load (which is the last thing on the alarm grid to load)
        for sec in range(0, self.config.mid_timeout):
            if (alarm_grid_footer_element.find_element_by_class_name("end-label").text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Number of pages didn't load.")
            time.sleep(1)

        # Find the first page button and click it, then wait for the first page to load.
        skip_left_button = alarm_grid_footer_element.find_element_by_class_name("left-first-button")
        skip_left_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip left was clicked")

        # I'm actually not sure why I'm putting out the effort to do this since the first page should be 1, but anyway find the current page
        # text field and store it as the previous page number.
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        previous_page_number = int(alarm_grid_footer_current_page_text_field.get_attribute("value"))

        # Find the next button and click it, then wait for the next page to load.
        next_button = alarm_grid_footer_element.find_element_by_class_name("right-button")
        next_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after right button was clicked")

        # Find the current page text field and store the value as the current page number. Check that the current page number is greater then
        # the previous page number
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        current_page_number = int(alarm_grid_footer_current_page_text_field.get_attribute("value"))
        self.assertGreater(int(current_page_number), int(previous_page_number), "Did not go to the next page as expected. (current page: " +
                           str(current_page_number) + "; previous page: " + str(previous_page_number) + ")")

        # To reset for the next test find and click the first page button, then wait for the first page to load
        skip_left_button = alarm_grid_footer_element.find_element_by_class_name("left-first-button")
        skip_left_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip left was clicked")

    def test_skip_right_arrow_should_go_to_last_page_C10242(self):
        #Get the driver
        driver = self.config.driver

        # Wait for the Alarm Grid footer to be available and then store it.
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "pageralarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm grid footer didn't load within the allotted 10 seconds")
        alarm_grid_footer_element = driver.find_element_by_id("pageralarmsGrid")

        # Wait for the number of pages to load (which is the last thing on the alarm grid to load)
        for sec in range(0, self.config.mid_timeout):
            if (alarm_grid_footer_element.find_element_by_class_name("end-label").text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Number of pages didn't load.")
            time.sleep(1)

        # Find the Last Page button in the alarm grid footer and click it, then wait for the last page to load.
        skip_right_button = alarm_grid_footer_element.find_element_by_class_name("right-last-button")
        skip_right_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip right was clicked")

        # Find and store the current page text field and the number of pages label
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        alarm_grid_footer_label = alarm_grid_footer_element.find_element_by_class_name("end-label").text
        last_page = ""
        for index in range(0, len(alarm_grid_footer_label)):
            if (alarm_grid_footer_label[index] != 'o' and alarm_grid_footer_label[index] != 'f' and alarm_grid_footer_label[index] != ' '):
                last_page += alarm_grid_footer_label[index]

        # Check that the current page is equal to the last page listed in the number of pages label
        self.assertEqual(alarm_grid_footer_current_page_text_field.get_attribute("value"), last_page,
                         "Did not go to the last page! (current page: " + alarm_grid_footer_current_page_text_field.get_attribute("value") +
                         ")")

        # To reset for the next test find and click the first page button, then wait for the first page to load
        skip_left_button = alarm_grid_footer_element.find_element_by_class_name("left-first-button")
        skip_left_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip left was clicked")


    def test_page_number_field_allow_custom_number_C10243(self):
        #Get the driver
        driver = self.config.driver

        # Wait for the Alarm Grid footer to be available and then store it.
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "pageralarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm grid footer didn't load within the allotted " + str(self.config.mid_timeout) + " seconds")
        alarm_grid_footer_element = driver.find_element_by_id("pageralarmsGrid")

        # Wait for the number of pages to load (which is the last thing on the alarm grid to load)
        for sec in range(0, self.config.mid_timeout):
            if (alarm_grid_footer_element.find_element_by_class_name("end-label").text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Number of pages didn't load.")
            time.sleep(1)

        # Find the Last Page button in the alarm grid footer and click it, then wait for the last page to load.
        skip_right_button = alarm_grid_footer_element.find_element_by_class_name("right-last-button")
        skip_right_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip right was clicked")

        # Find and store the current page text field and the number of pages label
        alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
        alarm_grid_footer_label = alarm_grid_footer_element.find_element_by_class_name("end-label").text
        last_page = ""
        for index in range(0, len(alarm_grid_footer_label)):
            if (alarm_grid_footer_label[index] != 'o' and alarm_grid_footer_label[index] != 'f' and alarm_grid_footer_label[index] != ' '):
                last_page += alarm_grid_footer_label[index]

        # If the last page isn't 2 or more fail the test and let the user know they need at least 2 pages of alarms
        if (last_page <= 1):
            self.fail("add custom page number unable to be tested, need at least 2 pages of alarms")

        # Choose a custom page number by dividing the last page in half and rounding. Enter the custom page number by first pressing backspace
        # to clear the field, second entering the custom page number, and third hitting enter.
        page_number_to_enter = int(last_page) / 2
        alarm_grid_footer_current_page_text_field.send_keys(Keys.BACKSPACE)
        alarm_grid_footer_current_page_text_field.send_keys(str(page_number_to_enter))
        alarm_grid_footer_current_page_text_field.send_keys(Keys.ENTER)

        # Every second find the current page number text field and see if the page number matches the custom page number chosen above. If
        # after the max number of seconds have passed the number still doesn't match, fail the test case.
        for sec in range(0, self.config.mid_timeout):
            alarm_grid_footer_current_page_text_field = alarm_grid_footer_element.find_element_by_id("input")
            next_page = int(alarm_grid_footer_current_page_text_field.get_attribute("value"))
            if (next_page == page_number_to_enter):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Page did not change")
            time.sleep(1)

        # To reset for the next test find and click the first page button, then wait for the first page to load
        skip_left_button = alarm_grid_footer_element.find_element_by_class_name("left-first-button")
        skip_left_button.click()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
        except TimeoutException:
            self.fail("Alarm grid failed to load within alotted " + str(self.config.mid_timeout) + " seconds after skip left was clicked")





    ## Helper Methods ##
    def cut_off_string_at(self, input_string, char_cut_off):
        # loop through every character in the string, adding it to a new string, till the cut off character is found then return the new string
        return_string = ""
        for string_char in input_string:
            if (string_char == char_cut_off):
                break
            else:
                return_string += string_char
        return (return_string)

if __name__ == "__main__":
    unittest.main()

