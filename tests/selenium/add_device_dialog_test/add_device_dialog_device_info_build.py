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

class AddDeviceDialogDeviceInfoBuild(c2_test_case.C2TestCase):
    def test_click_create_opens_build_dialog_C141962(self):
        # Get the web driver, close the add device dialog, open the add device dialog
        driver = self.config.driver
        AddDeviceDialogDeviceInfoBuild.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfoBuild.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        name_array = []
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
            name_array.append(row["DEVICE NAME"])
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

        # populate build info
        AddDeviceDialogDeviceInfoBuild.populate_build_info(self, driver, device_type_array[0], name_array[0], ip_address_array[0],
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

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]"))
            )
            driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").click()
        except TimeoutException:
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.visibility_of_element_located((By.XPATH,
                                                                       "//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]"))
                )
            except:
                self.fail("Close button didn't load within the allotted " + str(self.config.mid_timeout) + " seconds.")
            driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").click()

    def test_build_progress_bar_never_goes_back_C10911(self):
        # Get the web driver, close the add device dialog, open the add device dialog
        driver = self.config.driver
        AddDeviceDialogDeviceInfoBuild.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfoBuild.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        name_array = []
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
            name_array.append(row["DEVICE NAME"])
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

        # populate build info
        AddDeviceDialogDeviceInfoBuild.populate_build_info(self, driver, device_type_array[0], name_array[0], ip_address_array[0],
                                                   username_array[0], password_array[0], snmp_version_array[0], snmp_read_array[0],
                                                   snmp_write_array[0], http_array[0], https_array[0], snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'buildProgressWindowContent'))
            )
        except TimeoutException:
            self.fail("Build Progress Dialog didn't load after " + str(self.config.mid_timeout) + " seconds.")
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "buildProgressBar"))
            )
        except TimeoutException:
            self.fail("Build Progress bar did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")

        last_width = 0
        for sec in range(0, self.config.long_timeout):
            if (driver.find_element_by_class_name("build-device-message").text == "Build Completed Successfully!"):
                break

            current_width = float(
                    driver.find_element_by_xpath("//div[@id='buildProgressBar']/div[1]").value_of_css_property("width").replace('px', ''))

            if (current_width > last_width):
                # Close the build dialog
                if (driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").is_displayed() == True):
                    driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").click()
                elif (driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").is_displayed() == True):
                    driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").click()
            self.assertGreaterEqual(current_width, last_width, "The current width of the progress bar: " + str(current_width) +
                                    " is less then the previous width of the progress bar: " + str(last_width) +
                                    " (Progress bar should not go back)!")

            last_width = current_width
            time.sleep(1)

        # Close the build dialog
        if (driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").is_displayed() == True):
            driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").click()
        elif (driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").is_displayed() == True):
            driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").click()

    def test_cancel_build_C10916_and_canceled_build_no_show_in_network_tree_C10917(self):
        # Get the web driver, close the add device dialog, open the add device dialog
        driver = self.config.driver
        AddDeviceDialogDeviceInfoBuild.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfoBuild.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        name_array = []
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
            name_array.append(row["DEVICE NAME"])
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

        # populate build info
        AddDeviceDialogDeviceInfoBuild.populate_build_info(self, driver, device_type_array[3], name_array[3], ip_address_array[3],
                                                   username_array[3], password_array[3], snmp_version_array[3], snmp_read_array[3],
                                                   snmp_write_array[3], http_array[3], https_array[3], snmp_array[3], scan_interval_array[3])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'buildProgressWindowContent'))
            )
        except TimeoutException:
            self.fail("Build Progress Dialog didn't load after " + str(self.config.mid_timeout) + " seconds.")

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH,
                                                                   "//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]"))
            )
        except TimeoutException:
            self.fail("Cancel button failed to load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").click()

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.ID, "buildProgressWindowContent"))
            )
        except TimeoutException:
            self.fail("Build Progress dialog did not close within the allotted " + str(self.config.mid_timeout) + " seconds.")

        ## Checking network tree for node ##
        # Wait for the network tree to display and get it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "netTree"))
            )
        except TimeoutException:
            self.fail("Network tree did not display within the allotted " + str(self.config.mid_timeout) + " seconds.")
        network_tree_element = driver.find_element_by_id("netTree")

        # This was the only way to ensure the fields didn't overwrite themselves. (needed because of Bug 5937)
        time.sleep(self.config.short_timeout)

        # Get the list of nodes in the tree
        network_tree_node_array = network_tree_element.find_elements_by_tag_name("li")
        for index in range(0, len(network_tree_node_array)):
            network_tree_node_array = network_tree_element.find_elements_by_tag_name("li")
            node = network_tree_node_array[index]
            self.assertNotEqual(node.find_element_by_tag_name("div").text, name_array[3],
                                "The canceled device was found in the network tree.")

    def test_build_status_update_C142073(self):
        # Get the web driver, close the add device dialog, open the add device dialog
        driver = self.config.driver
        AddDeviceDialogDeviceInfoBuild.close_the_add_device_dialog_if_open(self, driver)
        AddDeviceDialogDeviceInfoBuild.open_add_device_dialog(self, driver)

        # Load the file containing info on the devices to be built and prep arrays to store the info
        device_info_file = csv.DictReader(open("deviceToBeBuilt.csv"))
        device_type_array = []
        name_array = []
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
            name_array.append(row["DEVICE NAME"])
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

        # populate build info
        AddDeviceDialogDeviceInfoBuild.populate_build_info(self, driver, device_type_array[0], name_array[0], ip_address_array[0],
                                                   username_array[0], password_array[0], snmp_version_array[0], snmp_read_array[0],
                                                   snmp_write_array[0], http_array[0], https_array[0], snmp_array[0], scan_interval_array[0])

        # Skip the test if building devices is disabled
        if (self.config.skip_builds == True):
            self.skipTest("User chose to skip any tests with building devices.")

        # Click the Create button to create the device
        driver.find_element_by_class_name("dialog-footer-buttons").find_element_by_xpath(".//div[1]").click()

        # Wait for the Build Progress dialog to display and then store an instance of it; if the dialog doesn't display in the mid
        # timeout fail the test with a timeout error; and get the current scan message
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'buildProgressWindowContent'))
            )
        except TimeoutException:
            self.fail("Build Progress Dialog didn't load after " + str(self.config.mid_timeout) + " seconds")
        build_progress_dialog = driver.find_element_by_id("buildProgressWindowContent")

        # Wait for the scan message to display and then grab it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.CLASS_NAME, "build-device-message"))
            )
        except TimeoutException:
            self.fail("First scan message did not display within the allotted, " + str(self.config.mid_timeout) + " seconds.")
        first_scan_message = build_progress_dialog.find_element_by_class_name("build-device-message").text

        # Loop for a few seconds, looking for the build complete message if not found compare the first and new message to make sure they're
        # different then cancel the build, otherwise make sure the first scan message isn't the same as the current one either.
        for sec in range(0, self.config.mid_timeout):
            if (build_progress_dialog.find_element_by_class_name("build-device-message").text == "Build Completed Successfully!"):
                self.assertNotEqual(first_scan_message, "Build Completed Successfully!",
                                    "Scan message should not be the same as the first scan message.")
                break
            elif (sec >= self.config.mid_timeout - 1):
                second_scan_message = build_progress_dialog.find_element_by_class_name("build-device-message").text
                self.assertNotEqual(first_scan_message, second_scan_message, "Scan message should not be the same as the first scan message.")
            time.sleep(1)

        # Close the build dialog
        if (driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").is_displayed() == True):
            driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[1]").click()
        elif (driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").is_displayed() == True):
            driver.find_element_by_xpath("//div[@id='buildProgressWindowContent']/div/form/div/div[2]/div[2]").click()









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
        device_type_dropdown = web_driver.find_element_by_id("deviceType")
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

if __name__ == "__main__":
    unittest.main()