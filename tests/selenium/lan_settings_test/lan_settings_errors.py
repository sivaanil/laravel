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
from selenium.webdriver.common.keys import Keys
import unittest
import time

class LanSettingsErrors(c2_test_case.C2TestCase):
    def test_device_ports_cannot_conflict_with_WAN_C141556(self):
        # Get the web driver
        driver = self.config.driver

        LanSettingsErrors.load_the_WAN_settings_canvas(self, driver)
        gateway_address = LanSettingsErrors.get_the_gateway_address(self, driver)
        subnet_mask = LanSettingsErrors.get_the_subnet_mask(self, driver)
        conflicting_device_port_address = LanSettingsErrors.generate_ip_from_ip(self, gateway_address)
        LanSettingsErrors.load_the_LAN_settings_canvas(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("ip address field didn't load within " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")
        for sec in range(0, self.config.mid_timeout):
            if (ip_address_field.get_attribute("value") != ""):
                break
            time.sleep(1)
        ip_address_field.send_keys(Keys.CONTROL + "a")
        ip_address_field.send_keys(conflicting_device_port_address)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "netmask"))
            )
        except TimeoutException:
            self.fail("ip address field didn't load within " + str(self.config.mid_timeout) + " seconds.")
        netmask_field = driver.find_element_by_id("netmask")
        netmask_field.send_keys(Keys.CONTROL + "a")
        netmask_field.send_keys(subnet_mask)

        netmask_field.send_keys(Keys.TAB)
        time.sleep(self.config.short_timeout)
        driver.save_screenshot("C141556_Device_ports_conflict_with_wan.png")
        LanSettingsErrors.click_the_save_button(self, driver)

        for sec in range(0, self.config.long_timeout):
            save_message_element = driver.find_element_by_xpath("//div[@id='mainPanelView']/div[2]/div/div/div")
            if (save_message_element.text == "Device ports subnet conflicts with WAN"):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("The error message did not display within the allotted " + str(self.config.long_timeout) + " seconds or " +
                    "the error message was not correct. Error Message: " + save_message_element.text)
            time.sleep(1)

        LanSettingsErrors.click_the_cancel_button(self, driver)

    def test_management_port_cannot_conflict_with_WAN_C141557(self):
        # Get the web driver
        driver = self.config.driver

        LanSettingsErrors.load_the_WAN_settings_canvas(self, driver)
        gateway_address = LanSettingsErrors.get_the_gateway_address(self, driver)
        subnet_mask = LanSettingsErrors.get_the_subnet_mask(self, driver)
        conflicting_device_port_address = LanSettingsErrors.generate_ip_from_ip(self, gateway_address)
        LanSettingsErrors.load_the_LAN_settings_canvas(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "consoleIpAddress"))
            )
        except TimeoutException:
            self.fail("ip address field didn't load within " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("consoleIpAddress")
        for sec in range(0, self.config.mid_timeout):
            if (ip_address_field.get_attribute("value") != ""):
                break
            time.sleep(1)
        ip_address_field.send_keys(Keys.CONTROL + "a")
        ip_address_field.send_keys(conflicting_device_port_address)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "consoleNetmask"))
            )
        except TimeoutException:
            self.fail("ip address field didn't load within " + str(self.config.mid_timeout) + " seconds.")
        netmask_field = driver.find_element_by_id("consoleNetmask")
        netmask_field.send_keys(Keys.CONTROL + "a")
        netmask_field.send_keys(subnet_mask)

        netmask_field.send_keys(Keys.TAB)
        time.sleep(self.config.short_timeout)
        driver.save_screenshot("C141557_Management_port_conflict_with_wan.png")
        LanSettingsErrors.click_the_save_button(self, driver)

        for sec in range(0, self.config.long_timeout):
            save_message_element = driver.find_element_by_xpath("//div[@id='mainPanelView']/div[2]/div/div/div")
            if (save_message_element.text == "Maintenance port subnet conflicts with WAN"):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("The error message did not display within the allotted " + str(self.config.long_timeout) + " seconds or " +
                    "the error message was not correct. Error Message: " + save_message_element.text)
            time.sleep(1)

        LanSettingsErrors.click_the_cancel_button(self, driver)

    def test_device_ports_not_conflict_with_mgmt_port_C144814(self):
        driver = self.config.driver
        LanSettingsErrors.load_the_LAN_settings_canvas(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("Ip Address text field did not load in within the allotted " + str(self.config.mid_timeout) + " seconds.")

        ip_address_text = driver.find_element_by_id("ipAddress").get_attribute("value")
        for sec in range(0, self.config.mid_timeout):
            ip_address_text = driver.find_element_by_id("ipAddress").get_attribute("value")
            if (ip_address_text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Ip Address text field did not populate within the allotted " + str(self.config.mid_timeout) + " seconds.")
            time.sleep(1)
        subnet_mask_text = driver.find_element_by_id("netmask").get_attribute("value")

        mgmt_ip_address_field = driver.find_element_by_id("consoleIpAddress")
        mgmt_ip_address_field.send_keys(Keys.CONTROL + "a")
        mgmt_ip_address_field.send_keys(ip_address_text)

        mgmt_subnet_mask_field = driver.find_element_by_id("consoleNetmask")
        mgmt_subnet_mask_field.send_keys(Keys.CONTROL + "a")
        mgmt_subnet_mask_field.send_keys(subnet_mask_text)

        mgmt_subnet_mask_field.send_keys(Keys.TAB)
        time.sleep(self.config.short_timeout)
        driver.save_screenshot("C144814_Device_ports_no_conflict_with_Management_port.png")
        LanSettingsErrors.click_the_save_button(self, driver)

        for sec in range(0, self.config.long_timeout):
            save_message_element = driver.find_element_by_xpath("//div[@id='mainPanelView']/div[2]/div/div/div")
            if (save_message_element.text == "Device and management port subnets conflict."):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("The error message did not display within the allotted " + str(self.config.long_timeout) + " seconds or " +
                    "the error message was not correct. Error Message: " + save_message_element.text)
            time.sleep(1)

        LanSettingsErrors.click_the_cancel_button(self, driver)











    ## helper methods ##
    def load_the_WAN_settings_canvas(self, web_driver):
        # Wait for the Alarms button to load and then click it
        try:
            WebDriverWait (web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'WAN Settings'))
            )
        except:
            self.fail("WAN menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        web_driver.find_element_by_link_text("WAN Settings").click()

        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "staticConfiguration"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "staticConfiguration"))
            )
        except TimeoutException:
            self.fail("WAN settings failed to load within " + str(self.config.mid_timeout) + " seconds.")

    def get_the_gateway_address(self, web_driver):
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "gateway"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "gateway"))
            )
        except TimeoutException:
            self.fail("Gateway address field failed to load within " + str(self.config.mid_timeout) + " seconds.")

        return(web_driver.find_element_by_id("gateway").get_attribute("value"))

    def get_the_subnet_mask(self, web_driver):
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "netmask"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "netmask"))
            )
        except TimeoutException:
            self.fail("Netmask field failed to load within " + str(self.config.mid_timeout) + " seconds.")

        return(web_driver.find_element_by_id("netmask").get_attribute("value"))

    def generate_ip_from_ip(self, ip):
        ip_address_array = []
        ip_parts_string = ""
        for character in ip:
            if (character != "."):
                ip_parts_string += character
            elif (character == "."):
                ip_address_array.append(ip_parts_string)
                ip_parts_string = ""

        last_ip_part = int(ip_parts_string)
        last_ip_part += 1

        return(ip_address_array[0] + "." + ip_address_array[1] + "." + ip_address_array[2] + "." + str(last_ip_part))

    def load_the_LAN_settings_canvas(self, web_driver):
        # Wait for the Alarms button to load and then click it
        try:
            WebDriverWait (web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'LAN Settings'))
            )
        except:
            self.fail("LAN menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        web_driver.find_element_by_link_text("LAN Settings").click()

        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "lanSettingsForm"))
            )

            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "lanSettingsForm"))
            )
        except TimeoutException:
            self.fail("LAN settings failed to load within " + str(self.config.mid_timeout) + " seconds.")

    def click_the_cancel_button(self, web_driver):
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "lanSettingsForm"))
            )
        except TimeoutException:
            self.fail("LAN Settings form didn't load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        lanSettingsForm = web_driver.find_element_by_id("lanSettingsForm")

        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Cancel"):
                btn.click()
                break

    def click_the_save_button(self, web_driver):
        try:
            WebDriverWait(web_driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "lanSettingsForm"))
            )
        except TimeoutException:
            self.fail("LAN Settings form didn't load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        lanSettingsForm = web_driver.find_element_by_id("lanSettingsForm")

        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Save"):
                btn.click()
                break

if __name__ == "__main__":
    unittest.main()