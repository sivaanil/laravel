_author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

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

class AddDeviceDialogGeneral(c2_test_case.C2TestCase):
    def test_add_device_if_info_correct_C11503(self):
        # Get the web driver
        driver = self.config.driver

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_name_array = []
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
            device_name_array.append(row["DEVICE NAME"])
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

        # Open the add device dialog and populate with build info
        AddDeviceDialogGeneral.open_add_device_dialog(self, driver)
        AddDeviceDialogGeneral.populate_build_info(self, driver, device_type_array[0], device_name_array[0], ip_address_array[0],
                                                   username_array[0], password_array[0], snmp_version_array[0], snmp_read_array[0],
                                                   snmp_write_array[0], http_array[0], https_array[0], snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the Build Progress dialog to display and then store an instance of it; if the dialog doesn't display in the mid
        # timeout fail the test with a timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'buildProgressWindowContent'))
            )
        except TimeoutException:
            self.fail("Build Progress Dialog didn't load after " + str(self.config.mid_timeout) + " seconds")
        build_progress_dialog = driver.find_element_by_id("buildProgressWindowContent")

        # Wait for the build to finish by waiting for the Close button to become visible; if the button doesn't become visible after the
        # long timeout times 3 (default: 30 * 3 = 60) seconds fail the test with a timeout error and also click the cancel button so the
        # next test case can continue
        try:
            WebDriverWait(driver, self.config.long_timeout * 3).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]"))
            )
        except TimeoutException:
            build_progress_dialog.find_element_by_class_name("cancel-build-device-button").click()
            footer_buttons = driver.find_element_by_id("buildDeviceWindowContent").find_element_by_class_name(
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
            footer_buttons = driver.find_element_by_id("buildDeviceWindowContent").find_element_by_class_name(
                    "dialog-footer-buttons").find_elements_by_class_name("ng-binding")
            for button in footer_buttons:
                if (button.text == "Cancel"):
                    button.click()
                    break
        self.assertEqual(scan_message, "Build Completed Successfully!", "Build was not successful; build failed message: " + scan_message)

    def test_if_sitegate_node_selected_add_button_display_and_open_dialog_C11541(self):
        # Get the web driver and then select the SiteGate (root) node
        driver = self.config.driver
        AddDeviceDialogGeneral.select_root_node(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='networkExplorer']/div/button[1]"))
            )
        except TimeoutException:
            self.fail("Add Device button did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")

        AddDeviceDialogGeneral.open_add_device_dialog(self, driver)

        driver.find_element_by_xpath("//div[@id='buildDeviceWindowContent']/div/form/div[3]/div[3]").click()

    def test_should_not_build_with_invalid_info_C11544(self):
        # Get the web driver
        driver = self.config.driver

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_name_array = []
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
            device_name_array.append(row["DEVICE NAME"])
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

        # Open the add device dialog and populate with build info
        AddDeviceDialogGeneral.open_add_device_dialog(self, driver)
        AddDeviceDialogGeneral.populate_build_info(self, driver, device_type_array[1], device_name_array[1], ip_address_array[1],
                                                   username_array[1], password_array[1], snmp_version_array[1], snmp_read_array[1],
                                                   snmp_write_array[1], http_array[1], https_array[1], snmp_array[1], scan_interval_array[1])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the Build Progress dialog to display and then store an instance of it; if the dialog doesn't display in the mid
        # timeout fail the test with a timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'buildProgressWindowContent'))
            )
        except TimeoutException:
            self.fail("Build Progress Dialog didn't load after " + str(self.config.mid_timeout) + " seconds")
        build_progress_dialog = driver.find_element_by_id("buildProgressWindowContent")

        # Wait for the build to finish by waiting for the Close button to become visible; if the button doesn't become visible after the
        # long timeout times 3 (default: 30 * 3 = 60) seconds fail the test with a timeout error and also click the cancel button so the
        # next test case can continue
        try:
            WebDriverWait(driver, self.config.long_timeout * 3).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]"))
            )
        except TimeoutException:
            build_progress_dialog.find_element_by_class_name("cancel-build-device-button").click()
            footer_buttons = driver.find_element_by_id("buildDeviceWindowContent").find_element_by_class_name(
                    "dialog-footer-buttons").find_elements_by_class_name("ng-binding")
            for button in footer_buttons:
                if (button.text == "Cancel"):
                    button.click()
                    break
            self.skipTest("Build took longer then the allotted " + str(self.config.long_timeout * 3) +
                          " seconds most likely due to build failing, skipping the rest of test.")

        # Once the device is finished building get the scan message, check if it is the build complete message and if not fail the test with
        # the scan message, and click the close button
        scan_message = build_progress_dialog.find_element_by_class_name("build-device-message").text
        build_progress_dialog.find_element_by_class_name("close-build-device-button").click()
        if (scan_message != "Build Completed Successfully!"):
            footer_buttons = driver.find_element_by_id("buildDeviceWindowContent").find_element_by_class_name(
                    "dialog-footer-buttons").find_elements_by_class_name("ng-binding")
            for button in footer_buttons:
                if (button.text == "Cancel"):
                    button.click()
                    break
        self.assertNotEqual(scan_message.find("failed"), -1, "Build should not have been successful!")

    def test_cancel_button_closes_dialog_C11542(self):
        # Get the web driver and open the add device dialog
        driver = self.config.driver
        add_device_dialog = AddDeviceDialogGeneral.open_add_device_dialog(self, driver)

        # Find the cancel button and click it
        add_device_dialog_footer = add_device_dialog.find_element_by_class_name("dialog-footer-buttons")
        add_device_dialog_footer.find_element_by_xpath(".//div[3]").click()

        # Wait for the add device dialog to go invisible and if it doesn't fail the test
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.ID, "buildDeviceWindow"))
            )
        except TimeoutException:
            self.assertEqual(add_device_dialog.is_displayed(), False, "The cancel button did not close the dialog.")

    def test_x_button_closes_dialog_C11542(self):
        # Get the web driver and open the add device dialog
        driver = self.config.driver
        add_device_dialog = AddDeviceDialogGeneral.open_add_device_dialog(self, driver)

        # Find the X button and click it
        add_device_dialog.find_element_by_xpath(".//div/div[1]/div[2]/div").click()

        # Wait for the add device dialog to go invisible and if it doesn't fail the test
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.ID, "buildDeviceWindow"))
            )
        except TimeoutException:
            self.assertEqual(add_device_dialog.is_displayed(), False, "The cancel button did not close the dialog.")






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

            if (btn.text.find("Add") != -1):
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

    def select_root_node(self, web_driver):
        # Check to make sure the root node isn't already selected then wait for the network tree to load and click the root node
        if (web_driver.current_url.find(self.config.root_node) == -1):
            try:
                WebDriverWait(web_driver, self.config.mid_timeout).until(
                    expected_conditions.presence_of_element_located((By.ID, "netTree"))
                )
            except TimeoutException:
                self.fail("Network tree did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
            web_driver.find_element_by_xpath("//div[@id='netTree']/ul/li/div").click()

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
        unified_tables = web_driver.find_elements_by_class_name("col-xs-12.form-section")
        for table in unified_tables:
            if (table.text.find("Port Forward Settings") != -1):
                unified_table_rows = table.find_elements_by_class_name("ng-scope")

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


if __name__ == "__main__":
    unittest.main()
