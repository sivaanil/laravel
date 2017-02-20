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
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.keys import Keys
import unittest
import time
import csv

class LanSettingsCanvas(c2_test_case.C2TestCase):
    def test_lan_ports_label_correct_C134141(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "lanSettingsForm"))
            )
        except TimeoutException:
            self.fail("LAN Settings form did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")

        self.assertEqual(lanSettingsForm.find_element_by_tag_name("h5").text, "LAN Port Settings (Device Ports)",
                         "The LAN Port Settings label is incorrect.")

    def test_management_port_label_correct_C134141(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "lanSettingsForm"))
            )
        except TimeoutException:
            self.fail("LAN Settings form did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")

        self.assertEqual(lanSettingsForm.find_element_by_xpath(".//div[2]/h5").text, "MGMT Port Settings (Management Port)",
                         "The Management Port Settings label is incorrect.")

    def test_save_button_not_work_with_blank_field_C10807(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")
        ip_address_field.clear()

        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")
        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Save"):
                btn.click()
                break

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//form[@id='lanSettingsForm']/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected Error didn't display within the allotted: " + str(self.config.mid_timeout) + " seconds.")

        LanSettingsCanvas.click_the_cancel_button(self, driver)

    def test_invalid_entry_raises_correct_errors_C10808(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")
        ip_address_field.clear()

        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")
        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Save"):
                btn.click()
                break

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//form[@id='lanSettingsForm']/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected Error didn't display within the allotted: " + str(self.config.mid_timeout) + " seconds.")

        for sec in range(0, self.config.mid_timeout):
            if (driver.find_element_by_xpath("//form[@id='lanSettingsForm']/div[1]/div[1]/div/div").text == "The IP Address field is required."):
                break
            elif (sec >= self.config.mid_timeout):
                self.fail("The error message for a blank form was not correct.")
            time.sleep(1)

        LanSettingsCanvas.click_the_cancel_button(self, driver)
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//form[@id='lanSettingsForm']/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected Error didn't hide within the allotted: " + str(self.config.mid_timeout) + " seconds.")

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")
        ip_address_field.clear()
        ip_address_field.send_keys("1.1")

        driver.save_screenshot("C10808_invalid_LAN_IP.png")

        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")
        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Save"):
                btn.click()
                break

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//form[@id='lanSettingsForm']/div[1]/div[1]/div/div"))
            )
        except TimeoutException:
            self.fail("Expected Error didn't display within the allotted: " + str(self.config.mid_timeout) + " seconds.")

        error_message_element = driver.find_element_by_xpath("//form[@id='lanSettingsForm']/div[1]/div[1]/div/div")
        if (error_message_element.text == "Enter the Local IP Address"):
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.staleness_of(error_message_element)
                )
            except TimeoutException:
                self.fail("Expected Error didn't display within the allotted: " + str(self.config.mid_timeout) + " seconds.")

            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.visibility_of_element_located((By.XPATH, "//form[@id='lanSettingsForm']/div[1]/div[1]/div/div"))
                )
            except TimeoutException:
                self.fail("Expected Error didn't display within the allotted: " + str(self.config.mid_timeout) + " seconds.")
            error_message_element = driver.find_element_by_xpath("//form[@id='lanSettingsForm']/div[1]/div[1]/div/div")

        self.assertEqual(error_message_element.text, "The IP Address must be a valid IP address.",
                         "The error message for an invalid IP was not correct.")

        LanSettingsCanvas.click_the_cancel_button(self, driver)

    def test_save_button_C10809(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")
        ip_address_field.clear()
        ip_address_field.send_keys("192.168.63.13")

        driver.save_screenshot("C10809_save_button_test.png")

        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")
        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Save"):
                btn.click()
                break

        for sec in range(0, self.config.long_timeout):
            save_message_element = driver.find_element_by_xpath("//div[@id='mainPanelView']/div[2]/div/div[2]")
            if (save_message_element.text == "Your changes have been saved"):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("The save message did not display within the allotted " + str(self.config.long_timeout) + " seconds.")
            time.sleep(1)

        driver.refresh()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")

        for sec in range(0, self.config.mid_timeout):
            if(ip_address_field.get_attribute("value") != ""):
                break
            time.sleep(1)

        self.assertEqual(ip_address_field.get_attribute("value"), "192.168.63.13", "Expected IP Adress is incorrect.")
        ip_address_field.clear()
        ip_address_field.send_keys("192.168.63.9")

    def test_cancel_button_C11597(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")

        for sec in range(0, self.config.mid_timeout):
            ip_address_text = driver.find_element_by_id("ipAddress").get_attribute("value")
            if (ip_address_text != ""):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Ip Address text field did not populate within the allotted " + str(self.config.mid_timeout) + " seconds.")
            time.sleep(1)

        ip_address_field = driver.find_element_by_id("ipAddress")
        ip_address_field.send_keys(Keys.CONTROL + "a")
        ip_address_field.send_keys("192.168.63.13")

        driver.save_screenshot("C11597_cancel_button_test.png")

        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")
        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Cancel"):
                btn.click()
                break

        for sec in range(0, self.config.mid_timeout):
            if (lanSettingsForm.get_attribute("value") != "192.168.63.13" and lanSettingsForm.get_attribute("value") != ""):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("The Cancel button failed to revert changes")
            time.sleep(1)

    def test_changed_status_should_report_C11592(self):
        # Get the web driver
        driver = self.config.driver

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")
        ip_address_field.send_keys(Keys.CONTROL + "a")
        ip_address_field.send_keys("192.168.63.13")

        driver.save_screenshot("C10809_save_button_test.png")

        lanSettingsForm = driver.find_element_by_id("lanSettingsForm")
        lan_settings_buttons = lanSettingsForm.find_elements_by_tag_name("button")
        for btn in lan_settings_buttons:
            if (btn.text == "Save"):
                btn.click()
                break

        for sec in range(0, self.config.long_timeout):
            save_message_element = driver.find_element_by_xpath("//div[@id='mainPanelView']/div[2]/div/div[2]")
            if (save_message_element.text == "Your changes have been saved"):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("The save message did not display within the allotted " + str(self.config.long_timeout) + " seconds.")
            time.sleep(1)

        driver.refresh()
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "ipAddress"))
            )
        except TimeoutException:
            self.fail("lan fields did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        ip_address_field = driver.find_element_by_id("ipAddress")

        for sec in range(0, self.config.mid_timeout):
            if(ip_address_field.get_attribute("value") != ""):
                break
            time.sleep(1)

        ip_address_field.send_keys(Keys.CONTROL + "a")
        ip_address_field.send_keys("192.168.63.9")





    ## Helper Methods ##
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

if __name__ == "__main__":
    unittest.main()