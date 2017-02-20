_author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys
import copy

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.support.ui import Select
import unittest
import time
import csv

class AddDeviceDialogDeviceInfo(c2_test_case.C2TestCase):
    def test_names_with_apostrophes_added_without_inserting_additional_characters_C10920(self):
        # Get the web driver
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        # populate with build info, preset device name with an apostrophe
        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[2], "test's", ip_address_array[2],
                                                   username_array[2], password_array[2], snmp_version_array[2], snmp_read_array[2],
                                                   snmp_write_array[2], http_array[2], https_array[2], snmp_array[2], scan_interval_array[2])

        # Start the build and when finished ensure that the name remains the same as the entered one
        AddDeviceDialogDeviceInfo.start_and_wait_for_build_finish(self, driver, "test's")

        for sec in range(0, self.config.mid_timeout):
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.visibility_of_element_located((By.XPATH, "//a[@id='lastCrumb']/span"))
                )
            except TimeoutException:
                self.fail("breadcrumb did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")

            if (driver.find_element_by_xpath("//a[@id='lastCrumb']/span").text == "test's"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Unexpected characters in the device name")
            time.sleep(1)

    def test_cannot_create_device_with_blank_name_C10921(self):
        # Get the web driver
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        # populate with build info, preset device name with an apostrophe
        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[2], "", ip_address_array[2],
                                                   username_array[2], password_array[2], snmp_version_array[2], snmp_read_array[2],
                                                   snmp_write_array[2], http_array[2], https_array[2], snmp_array[2], scan_interval_array[2])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to try creating the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the error message to display and if it does verify that it is the correct one
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Error message did not display in the allotted " + str(self.config.mid_timeout) + " seconds.")
        self.assertEqual(driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[1]/div[1]/div/div").text,
                         "The device name field is required.", "Unexpected error displayed!")


        # Close the add device dialog
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)

    def test_should_not_be_able_create_name_longer_then_max_limit_C135372(self):
        # Get the web driver
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        # Open the add device dialog and populate with build info, preset device name with an apostrophe
        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[2],
                                                      "12345678901234567892123456789312345678941234567895123456789612345678971234567898",
                                                      ip_address_array[2], username_array[2], password_array[2], snmp_version_array[2],
                                                      snmp_read_array[2], snmp_write_array[2], http_array[2], https_array[2], snmp_array[2],
                                                      scan_interval_array[2])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to attempt creating the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the error message to display and if it does verify that it is the correct one
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Error message did not display in the allotted " + str(self.config.mid_timeout) + " seconds.")
        self.assertEqual(driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[1]/div[1]/div/div").text,
                         "The device name may not be greater than 64 characters.", "Unexpected error displayed!")


        # Close the add device dialog
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)

    def test_automatic_device_name_textfield_should_be_disabled_C142072(self):
        # Get the web driver, close the add device dialog (if open), and open the add device dialog again
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(device_type_dropdown)
        selector.select_by_value("1082")

        for sec in range(0, self.config.mid_timeout):
            # Find the device name field and check that it is disabled
            device_name_field = driver.find_element_by_id("deviceName")
            device_name_field_disabled_value = device_name_field.get_attribute("disabled")

            if (device_name_field_disabled_value != None):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Device Name field is enabled, it should be disabled.")
            time.sleep(1)

        # Close the add device dialog
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)

    def test_device_type_dropdown_should_populate_C142068(self):
        # Get the webdriver and open the add device dialog
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # Close the add device dialog
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)

    def test_error_messages_clear_when_new_device_type_C11563(self):
        # Get the web driver
        driver = self.config.driver

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        # Open the add device dialog and populate with build info, preset device name with an apostrophe
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)
        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[2],
                                                      "12345678901234567892123456789312345678941234567895123456789612345678971234567898",
                                                      ip_address_array[2], username_array[2], password_array[2], snmp_version_array[2],
                                                      snmp_read_array[2], snmp_write_array[2], http_array[2], https_array[2], snmp_array[2],
                                                      scan_interval_array[2])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to attempt creating the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the error message to display and if it does verify that it is the correct one
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Error message did not display in the allotted " + str(self.config.mid_timeout) + " seconds.")

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(driver.find_element_by_id("deviceType"))
        selector.select_by_value("1271")

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH,
                                                                     "//div[@id='buildDeviceWindowContent']/div/form/div/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Error message did not hide within the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_last_built_device_type_should_be_remembered_C10924(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        # Populate with build info
        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "Selenium Built This", ip_address_array[0],
                                                      username_array[0], password_array[0], snmp_version_array[0], snmp_read_array[0],
                                                      snmp_write_array[0], http_array[0], https_array[0], snmp_array[0],
                                                      scan_interval_array[0])

        # Find the code for the currently selected device
        selector = Select(driver.find_element_by_id("deviceType"))
        last_selected_option = selector.first_selected_option.get_attribute("value")

        # Build the device
        AddDeviceDialogDeviceInfo.start_and_wait_for_build_finish(self, driver, "Selenium Built This")

        # Open the add device dialog then wait for the device type dropdown to display then store it to a variable; if not displayed after the
        # long timeout fail the test with a timeout error
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Get the dropdown again and compare its currently selected option to the previous one
        selector2 = Select(driver.find_element_by_id("deviceType"))
        self.assertEqual(selector2.first_selected_option.get_attribute("value"), last_selected_option,
                         "The selected device does not match the previously selected device.")

    def test_cannot_create_device_with_blank_ip_C10926(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "", "", username_array[0], password_array[0],
                                                      snmp_version_array[0], snmp_read_array[0], snmp_write_array[0], http_array[0],
                                                      https_array[0], snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the error message to display and compare it to the expected one
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div/div[3]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div/div[3]/div/div"))
            )
        except TimeoutException:
            self.fail("Error message did not display in the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_cannot_create_device_with_invalid_ip_C10926(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "", "1.1", username_array[0], password_array[0],
                                                      snmp_version_array[0], snmp_read_array[0], snmp_write_array[0], http_array[0],
                                                      https_array[0], snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the error message to display and compare it to the expected one
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div/div[3]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div/div[3]/div/div"))
            )
        except TimeoutException:
            self.fail("Error message did not display in the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_snmp_versions_should_have_appropriate_options_C10931(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Select snmp version 1 in the snmp version dropdown
        selector = Select(driver.find_element_by_id("snmpVer"))
        selector.select_by_value("1")

        # Get the snmp group of controls, then get a list of the labels in that group, lastly store a list of the expected labels in that group
        snmpSettings = driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[2]")
        labels = snmpSettings.find_elements_by_tag_name("label")
        expected_labels = ["Read String", "Write String"]

        # Loop through the list of expected labels and loop through the snmp labels. If the expected label is found move on to the next label
        # if not fail the test.
        for expected_label in expected_labels:
            for index in range(0, len(labels)):
                if (labels[index].text.find(expected_label) != -1):
                    break
                elif (index >= len(labels) - 1):
                    self.fail("The expected label " + expected_label + " Could not be found.")

        # Select snmp version 2c in the snmp version dropdown
        selector = Select(driver.find_element_by_id("snmpVer"))
        selector.select_by_value("2c")

        # Get the snmp group of controls, then get a list of the labels in that group, lastly store a list of the expected labels in that group
        snmpSettings = driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[2]")
        labels = snmpSettings.find_elements_by_tag_name("label")
        expected_labels = ["Read String", "Write String"]

        # Loop through the list of expected labels and loop through the snmp labels. If the expected label is found move on to the next label
        # if not fail the test.
        for expected_label in expected_labels:
            for index in range(0, len(labels)):
                if (labels[index].text.find(expected_label) != -1):
                    break
                elif (index >= len(labels) - 1):
                    self.fail("The expected label " + expected_label + " Could not be found.")

        # Select snmp version 3 in the snmp version dropdown
        selector = Select(driver.find_element_by_id("snmpVer"))
        selector.select_by_value("3")

        # Get the snmp group of controls, then get a list of the labels in that group, lastly store a list of the expected labels in that group
        snmpSettings = driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[2]")
        labels = snmpSettings.find_elements_by_tag_name("label")
        expected_labels = ["Security Type", "SNMP Username", "Authentication Encryption", "Authentication Password", "Privacy Encryption",
                           "Privacy Password"]

        # Loop through the list of expected labels and loop through the snmp labels. If the expected label is found move on to the next label
        # if not fail the test.
        for expected_label in expected_labels:
            for index in range(0, len(labels)):
                if (labels[index].text.find(expected_label) != -1):
                    break
                elif (index >= len(labels) - 1):
                    self.fail("The expected label " + expected_label + " Could not be found.")

    def test_should_not_build_with_incorrect_snmp_ver1and2_settings_C11538(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "test snmp settings", ip_address_array[0],
                                                      username_array[0], password_array[0], "2c", " ", " ", http_array[0], https_array[0],
                                                      snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[2]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[2]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected error did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")
        self.assertEqual(driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[2]/div/div").text,
                         "The snmp read field is required.", "The error message did not match the expected error message.")

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[3]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[3]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected error did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_should_not_build_with_incorrect_snmp_ver3_settings_C11538(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "test snmp settings", ip_address_array[0],
                                                      username_array[0], password_array[0], "3", "", "", http_array[0], https_array[0],
                                                      snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[5]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[5]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected error did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")
        self.assertEqual(driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[5]/div/div").text,
                         "The snmp username field is required.", "The error message did not match the expected error message.")

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[7]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[7]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected error did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")
        self.assertEqual(driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[7]/div/div").text,
                         "The snmp auth password field is required.", "The error message did not match the expected error message.")

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[9]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildDeviceWindowContent']/div/form/div/div[2]/div[9]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected error did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_pure_http_device_not_have_snmp_settings_C10932(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(device_type_dropdown)
        selector.select_by_value("132")

        # Wait for the SNMP version dropdown to go invisible once it is assume all other snmp settings should be too
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.ID, "snmpVer"))
            )
        except TimeoutException:
            self.fail("SNMP Version dropdown still visible within the allotted " + str(self.config.mid_timeout) + " seconds.")

        # Check all the snmp setting fields for visibility (none of them should be visible)
        self.assertEqual(driver.find_element_by_id("snmpRead").is_displayed(), False, "SNMP Read String field should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpWrite").is_displayed(), False, "SNMP Write String field should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpAuthType").is_displayed(), False,
                         "SNMP Security Type dropdown should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpUserName").is_displayed(), False, "SNMP Username field should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpAuthEncryption").is_displayed(), False,
                         "SNMP Authentication Encryption dropdown should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpAuthPassword").is_displayed(), False,
                         "SNMP Authentication Password field should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpPrivacyEncryption").is_displayed(), False,
                         "SNMP Privacy Encryption dropdown should not be still visible.")
        self.assertEqual(driver.find_element_by_id("snmpPrivacyPassword").is_displayed(), False,
                         "SNMP Privacy Password field should not be still visible.")

        # Get all the tables in unified and loop through them, look for the Port Forward Settings one and get the rows in the table
        unified_tables = driver.find_elements_by_class_name("col-xs-12.form-section")
        for table in unified_tables:
            if (table.text.find("Port Forward Settings") != -1):
                unified_table_rows = table.find_elements_by_class_name("ng-scope")

                # Loop through all the rows in the table looking for the snmp one and check that its text field is not visible
                for table_row in unified_table_rows:
                    if (table_row.find_element_by_class_name("ng-binding").text == "SNMP"):
                        port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                        self.assertEqual(port_field.is_displayed(), False, "SNMP port field should not be still visible")
                        break

    def test_pure_snmp_device_have_no_http_info_C10933(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(device_type_dropdown)
        selector.select_by_value("218")

        unified_tables = driver.find_elements_by_class_name("col-xs-12.form-section")
        for table in unified_tables:
            if (table.text.find("Port Forward Settings") != -1):
                unified_table_rows = table.find_elements_by_class_name("ng-scope")

                for table_row in unified_table_rows:
                    if (table_row.find_element_by_class_name("ng-binding").text == "HTTP"):
                        port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                        self.assertEqual(port_field.is_displayed(), False, "HTTP port field should not still display")
                    elif(table_row.find_element_by_class_name("ng-binding").text == "HTTPS"):
                        port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                        self.assertEqual(port_field.is_displayed(), False, "HTTPS port field should not still display")

    def test_scan_interval_cannot_be_blank_C10934(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "test snmp settings", ip_address_array[0],
                                                      username_array[0], password_array[0], snmp_version_array[0], snmp_read_array[0],
                                                      snmp_write_array[0], http_array[0], https_array[0], snmp_array[0], " ")

        driver.find_element_by_id("scanInterval").clear()
        driver.find_element_by_id("scanInterval").send_keys(" ")

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div[2]/div[2]/div[3]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div[2]/div[2]/div[3]/div/div"))
            )
        except TimeoutException:
            self.fail("Error didn't display within the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_scan_interval_cannot_be_less_then_5min_C10934(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        ip_address_array = []
        username_array = []
        password_array = []
        snmp_version_array = []
        snmp_read_array = []
        snmp_write_array = []
        http_array = []
        https_array = []
        snmp_array = []
        scan_interval_array = []

        # Loop through each row in the file and append it to the arrays created above
        for row in device_info_file:
            device_type_array.append(row["DEVICE TYPE ID"])
            ip_address_array.append(row["IP ADDRESS"])
            username_array.append(row["USERNAME"])
            password_array.append(row["PASSWORD"])
            snmp_version_array.append(row["SNMP VERSION"])
            snmp_read_array.append(row["SNMP READ"])
            snmp_write_array.append(row["SNMP WRITE"])
            http_array.append(row["HTTP"])
            https_array.append(row["HTTPS"])
            snmp_array.append(row["SNMP"])
            scan_interval_array.append(row["SCAN INTERVAL"])

        AddDeviceDialogDeviceInfo.populate_build_info(self, driver, device_type_array[0], "test snmp settings", ip_address_array[0],
                                                      username_array[0], password_array[0], snmp_version_array[0], snmp_read_array[0],
                                                      snmp_write_array[0], http_array[0], https_array[0], snmp_array[0], "4")

        driver.find_element_by_id("scanInterval").clear()
        driver.find_element_by_id("scanInterval").send_keys("4")

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div[2]/div[2]/div[3]/div/div"))
            )

            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                 "//div[@id='buildDeviceWindowContent']/div/form/div[2]/div[2]/div[3]/div/div"))
            )
        except TimeoutException:
            self.fail("Error didn't display within the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_switch_to_device_with_different_port_defaults_C10940(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (needed because of Bug 5937)
        time.sleep(self.config.short_timeout)

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(device_type_dropdown)
        selector.select_by_value("1228")

        # This was the only way to ensure the fields didn't overwrite themselves. (needed because of Bug 5937)
        time.sleep(self.config.short_timeout)

        # Get the table and the rows for the table
        unified_table = driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div[2]")
        unified_table_rows = unified_table.find_elements_by_class_name("ng-scope")

        # Find the http port field and store its value
        cradlepoint_http_port = ""
        for table_row in unified_table_rows:
            if (table_row.find_element_by_class_name("ng-binding").text == "HTTP"):
                port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                cradlepoint_http_port = copy.copy(port_field.get_attribute("value"))
                break

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(device_type_dropdown)
        selector.select_by_value("736")

        # This was the only way to ensure the fields didn't overwrite themselves. (needed because of Bug 5937)
        time.sleep(self.config.short_timeout)

        # Get the table and the rows for the table
        unified_table = driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div[2]")
        unified_table_rows = unified_table.find_elements_by_class_name("ng-scope")

        # Find the http port field and store its value
        for table_row in unified_table_rows:
            if (table_row.find_element_by_class_name("ng-binding").text == "HTTP"):
                port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                self.assertNotEqual(cradlepoint_http_port, port_field.get_attribute("value"), "The Ports should not match! first port: " +
                                    cradlepoint_http_port + ", second port: " + port_field.get_attribute("value"))
                break

    def test_labels_should_be_in_each_text_field_C125598(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the dialog content to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Add Device Dialog content did not load within the allotted " + str(self.config.mid_timeout + " seconds."))

        # Select SNMP version 2c
        snmp_selector = Select(driver.find_element_by_id("snmpVer"))
        snmp_selector.select_by_value("2c")

        # Wait for the dialog content to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Add Device Dialog content did not load within the allotted " + str(self.config.mid_timeout + " seconds."))
        add_device_dialog_content = driver.find_element_by_id("buildDeviceWindowContent")

        # Get an array of control groups and then for any that have text fields check that those textfields either have placeholder text or
        # preentered text.
        add_device_dialog_form_group_array = add_device_dialog_content.find_elements_by_class_name("form-group")
        for form_group in add_device_dialog_form_group_array:
            if (form_group.find_element_by_tag_name("label").text != "Device Type:" and
                    form_group.find_element_by_tag_name("label").text != "SNMP Version:" and
                    form_group.find_element_by_tag_name("label").text != "" and
                    form_group.find_element_by_tag_name("label").text != "Security Type:" and
                    form_group.find_element_by_tag_name("label").text != "Authentication Encryption:" and
                    form_group.find_element_by_tag_name("label").text != "Privacy Encryption:"):

                if (form_group.find_element_by_tag_name("input").get_attribute("placeholder") != ""):
                    self.assertNotEqual(form_group.find_element_by_tag_name("input").get_attribute("placeholder"), "",
                                    form_group.find_element_by_tag_name("label").text + " is missing an instruction label in the text field.")
                else:
                    self.assertNotEqual(form_group.find_element_by_tag_name("input").get_attribute("value"), "",
                                    form_group.find_element_by_tag_name("label").text + " is missing an instruction label in the text field.")

        # Select SNMP version 3
        snmp_selector = Select(driver.find_element_by_id("snmpVer"))
        snmp_selector.select_by_value("3")

        # Wait for stuff to load (there's a bug for this)
        time.sleep(self.config.short_timeout)

        # Wait for the dialog content to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Add Device Dialog content did not load within the allotted " + str(self.config.mid_timeout + " seconds."))
        add_device_dialog_content = driver.find_element_by_id("buildDeviceWindowContent")

        # Get an array of control groups and then for any that have text fields check that those textfields either have placeholder text or
        # preentered text.
        add_device_dialog_form_group_array = add_device_dialog_content.find_elements_by_class_name("form-group")
        for form_group in add_device_dialog_form_group_array:
            if (form_group.find_element_by_tag_name("label").text != "Device Type:" and
                    form_group.find_element_by_tag_name("label").text != "SNMP Version:" and
                    form_group.find_element_by_tag_name("label").text != "" and
                    form_group.find_element_by_tag_name("label").text != "Security Type:" and
                    form_group.find_element_by_tag_name("label").text != "Authentication Encryption:" and
                    form_group.find_element_by_tag_name("label").text != "Privacy Encryption:"):

                if (form_group.find_element_by_tag_name("input").get_attribute("placeholder") != ""):
                    self.assertNotEqual(form_group.find_element_by_tag_name("input").get_attribute("placeholder"), "",
                                    form_group.find_element_by_tag_name("label").text + " is missing an instruction label in the text field.")
                else:
                    self.assertNotEqual(form_group.find_element_by_tag_name("input").get_attribute("value"), "",
                                    form_group.find_element_by_tag_name("label").text + " is missing an instruction label in the text field.")

    def test_fields_have_labels_C144040(self):
        # Get the web driver, close the add device dropdown (just in case), and open the add device dropdown
        driver = self.config.driver
        AddDeviceDialogDeviceInfo.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfo.open_add_device_dialog(self, driver)

        # Wait for the dialog content to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Add Device Dialog content did not load within the allotted " + str(self.config.mid_timeout + " seconds."))

        # Select SNMP version 2c
        snmp_selector = Select(driver.find_element_by_id("snmpVer"))
        snmp_selector.select_by_value("2c")

        # Short timeout to allow for add device dialog loading
        time.sleep(self.config.short_timeout)

        # Wait for the dialog content to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Add Device Dialog content did not load within the allotted " + str(self.config.mid_timeout + " seconds."))
        add_device_dialog_content = driver.find_element_by_id("buildDeviceWindowContent")

        # Create a list of labels that need to display then get all the control groups and loop through both finding all the expected labels
        expected_label_array = ["Device Name:", "Device Type:", "Primary IP Address:", "Web UI Username:", "Web UI Password:", "SNMP Version:",
                                "Read String:", "Write String:", "Scan Interval:"]
        add_device_dialog_form_group_array = add_device_dialog_content.find_elements_by_class_name("form-group")
        for expected_label in expected_label_array:
            for index in range(0, len(add_device_dialog_form_group_array)):
                form_group = add_device_dialog_form_group_array[index]
                if (form_group.find_element_by_tag_name("label").text == expected_label):
                   break
                elif (index >= len(add_device_dialog_form_group_array) - 1):
                    self.fail("The expected label: " + expected_label + " was not found")

        # Select SNMP version 3
        snmp_selector = Select(driver.find_element_by_id("snmpVer"))
        snmp_selector.select_by_value("3")

        # Short timeout to allow for add device dialog loading
        time.sleep(self.config.short_timeout)

        # Wait for the dialog content to load
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Add Device Dialog content did not load within the allotted " + str(self.config.mid_timeout + " seconds."))
        add_device_dialog_content = driver.find_element_by_id("buildDeviceWindowContent")

        # Create a list of labels that need to display then get all the control groups and loop through both finding all the expected labels
        expected_label_array = ["Device Name:", "Device Type:", "Primary IP Address:", "Web UI Username:", "Web UI Password:", "SNMP Version:",
                                "Security Type:", "SNMP Username:", "Authentication Encryption:", "Authentication Password:",
                                "Privacy Encryption:", "Privacy Password:", "Scan Interval:"]
        add_device_dialog_form_group_array = add_device_dialog_content.find_elements_by_class_name("form-group")
        for expected_label in expected_label_array:
            for index in range(0, len(add_device_dialog_form_group_array)):
                form_group = add_device_dialog_form_group_array[index]
                if (form_group.find_element_by_tag_name("label").text == expected_label):
                   break
                elif (index >= len(add_device_dialog_form_group_array) - 1):
                    self.fail("The expected label: " + expected_label + " was not found")















    ## Helper Methods ##
    def open_add_device_dialog(self, web_driver):
        # Wait for the network tree menu buttons to load and then store them in an array.
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "networkExplorer"))
            )
        except TimeoutException:
            self.fail("Network explorer did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        menu_buttons = web_driver.find_element_by_id("networkExplorer").find_elements_by_tag_name("button")

        # Wait for the SiteGate node to display and then click it
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='netTree']/ul/li/div"))
            )
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='netTree']/ul/li/div"))
            )
        except TimeoutException:
            self.fail("Root node did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        if (web_driver.find_element_by_xpath("//div[@id='netTree']/ul/li/div").value_of_css_property("background-color") == "transparent"):
            web_driver.find_element_by_xpath("//div[@id='netTree']/ul/li/div").click()

        # Loop through the array of buttons looking for the one with the label "Add", click it, and break out of the loop
        for btn in menu_buttons:
            for sec in range(0, self.config.short_timeout):
                if (btn.is_displayed() == True):
                    break
                time.sleep(1)

            if (btn.text == "Add"):
                btn.click()
                break

        # Wait for the build dialog to load before returning it
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildDeviceWindowContent"))
            )
        except TimeoutException:
            self.fail("Build dialog failed to open within the allotted " + str(self.config.mid_timeout) + " seconds.")
        return(web_driver.find_element_by_id("buildDeviceWindow"))

    def populate_build_info(self, web_driver, device_type_id, device_name, ip_address, username, password, snmp_version, snmp_read,
                            snmp_write, http_port, https_port, snmp_port, scan_interval):
        # Wait for the device type dropdown to display then store it to a variable; if not displayed after the long timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(web_driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'deviceType'))
            )
        except TimeoutException:
            self.fail("Add Device dialog didn't load after " + str(self.config.long_timeout) + " seconds")
        device_type_dropdown = web_driver.find_element_by_id("deviceType")

        # In order to make sure all the device type options have loaded check every second for the list of dropdown options to be
        # greater then 100; after the long timeout fail the test with a timeout error
        for index in range(0, self.config.long_timeout):
            temp_list = device_type_dropdown.find_elements_by_tag_name('option')
            if (len(temp_list) > 100):
                break
            else:
                time.sleep(1)
            if (index >= self.config.long_timeout - 1):
                self.fail("Device type dropdown didn't populate in " + str(self.config.long_timeout) + " seconds")

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Using the Select class get the device type dropdown and select the desired device type (TSUN4)
        selector = Select(device_type_dropdown)
        selector.select_by_value(device_type_id)

        # This was the only way to ensure the fields didn't overwrite themselves. (I will work to see if there's a way to fix this)
        time.sleep(self.config.short_timeout)

        # Enter the device info into the appropriate fields (checking that the stored values aren't blank)
        if (device_name != ""):
            device_name_field = web_driver.find_element_by_id("deviceName")
            device_name_field_disabled_value = device_name_field.get_attribute("disabled")
            if (device_name_field_disabled_value == None):
                device_name_field.clear()
                device_name_field.send_keys(device_name)
        if (ip_address != ""):
            web_driver.find_element_by_id("primaryIpAddress").clear()
            web_driver.find_element_by_id("primaryIpAddress").send_keys(ip_address)
        if (username != ""):
            web_driver.find_element_by_id("webUsername").clear()
            web_driver.find_element_by_id("webUsername").send_keys(username)
        if (password != ""):
            web_driver.find_element_by_id("webPassword").clear()
            web_driver.find_element_by_id("webPassword").send_keys(password)

        # Enter the device info into the snmp fields (checking that the stored values aren't blank)
        if (snmp_version != ""):
            snmp_selector = Select(web_driver.find_element_by_id("snmpVer"))
            snmp_selector.select_by_value(snmp_version)
        if (snmp_read != ""):
            web_driver.find_element_by_id("snmpRead").clear()
            web_driver.find_element_by_id("snmpRead").send_keys(snmp_read)
        if (snmp_write != ""):
            web_driver.find_element_by_id("snmpWrite").clear()
            web_driver.find_element_by_id("snmpWrite").send_keys(snmp_write)

        # First grab every table in Unified, then look for the table labeled for Port Forward Settings and get the rows for that table. Loop through the rows
        # determine which row is HTTP and which is SNMP and then fill in the data accordingly
        # Get the table and the rows for the table
        unified_table = web_driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div[2]")
        unified_table_rows = unified_table.find_elements_by_class_name("ng-scope")

        for table_row in unified_table_rows:
            if (table_row.find_element_by_class_name("ng-binding").text == "HTTP" and http_port != ""):
                port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                port_field.clear()
                port_field.send_keys(http_port)
            elif(table_row.find_element_by_class_name("ng-binding").text == "HTTPS" and https_port != ""):
                port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                port_field.clear()
                port_field.send_keys(https_port)
            elif (table_row.find_element_by_class_name("ng-binding").text == "SNMP" and snmp_port != ""):
                port_field = table_row.find_element_by_xpath(".//td[2]/div/input")
                port_field.clear()
                port_field.send_keys(snmp_port)

    def start_and_wait_for_build_finish(self, web_driver, device_name):
        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        web_driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the Build Progress dialog to display and then store an instance of it; if the dialog doesn't display in the mid
        # timeout fail the test with a timeout error
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'buildProgressWindowContent'))
            )
        except TimeoutException:
            self.fail("Build Progress Dialog didn't load after " + str(self.config.mid_timeout) + " seconds")
        build_progress_dialog = web_driver.find_element_by_id("buildProgressWindowContent")

        # Wait for the build to finish by waiting for the Close button to become visible; if the button doesn't become visible after the
        # long timeout times 3 (default: 30 * 3 = 60) seconds fail the test with a timeout error and also click the cancel button so the
        # next test case can continue
        try:
            WebDriverWait(web_driver, self.config.long_timeout * 3).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]"))
            )
        except TimeoutException:
            build_progress_dialog.find_element_by_class_name("cancel-build-device-button").click()
            footer_buttons = web_driver.find_element_by_id("buildDeviceWindowContent").find_element_by_class_name(
                    "dialog-footer-buttons").find_elements_by_class_name("ng-binding")
            for button in footer_buttons:
                if (button.text == "Cancel"):
                    button.click()
                    break
            self.fail("Build did not complete after " + str(self.config.long_timeout * 3) + " seconds.")

        # Once the device is finished building get the scan message, check if it is the build complete message and if not fail the test with
        # the scan message, and click the close button
        scan_message = build_progress_dialog.find_element_by_class_name("build-device-message").text
        build_progress_dialog.find_element_by_class_name("close-build-device-button").click()
        if (scan_message != "Build Completed Successfully!"):
            footer_buttons = web_driver.find_element_by_id("buildDeviceWindowContent").find_element_by_class_name(
                    "dialog-footer-buttons").find_elements_by_class_name("ng-binding")
            for button in footer_buttons:
                if (button.text == "Cancel"):
                    button.click()
                    break
        self.assertEqual(scan_message, "Build Completed Successfully!", "Build was not successful; build failed message: " + scan_message)

        for sec in range(0, self.config.mid_timeout):
            try:
                WebDriverWait(web_driver, self.config.mid_timeout).until(
                    expected_conditions.visibility_of_element_located((By.XPATH, "//a[@id='lastCrumb']/span"))
                )
            except TimeoutException:
                self.fail("Device did not get added to the breadcrumb within the allotted " + str(self.config.mid_timeout) + "seconds.")
            try:
                if (web_driver.find_element_by_xpath("//a[@id='lastCrumb']/span").text == device_name):
                    break
                elif(sec >= self.config.mid_timeout - 1):
                    self.fail("Device did not get added to the breadcrumb within the allotted " + str(self.config.mid_timeout) + "seconds.")
                time.sleep(1)
            except:
                sec -= 1

    def close_the_add_device_dialog_if_open(self, web_driver):
        try:
            if (web_driver.find_element_by_id("buildDeviceWindow").is_displayed() == True):
                footer_buttons = web_driver.find_element_by_class_name("dialog-footer-buttons").find_elements_by_tag_name("div")
                for index in range(0, len(footer_buttons)):
                    footer_buttons = web_driver.find_element_by_class_name("dialog-footer-buttons").find_elements_by_tag_name("div")

                    if (index > len(footer_buttons)):
                        index = 0
                    btn = footer_buttons[index]
                    if (btn.text == "Cancel" and btn.is_displayed() == True):
                        try:
                            btn.click()
                            break
                        except:
                            index -= 1
        except:
            pass


if __name__ == "__main__":
    unittest.main()