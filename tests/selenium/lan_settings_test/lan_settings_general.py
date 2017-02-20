_author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys
import copy

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.webdriver.common.action_chains import ActionChains
from selenium.common.exceptions import TimeoutException
import unittest
import time

class LanSettingsGeneral(c2_test_case.C2TestCase):
    def test_mouse_over_lan_button_C10804(self):
        # Get the web driver
        driver = self.config.driver

        # Wait for the Alarms button to load and then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'LAN Settings'))
            )
        except:
            self.fail("LAN menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        element_to_hover_over = driver.find_element_by_link_text("LAN Settings")

        # Emulate the mouse hovering over the Alarms button
        hover = ActionChains(driver).move_to_element(element_to_hover_over)
        hover.perform()

        # Check every second for the Alarms button to be in its hover state, if max time met then fail the case
        for sec in range(0, self.config.mid_timeout):
            if (element_to_hover_over.value_of_css_property("border-bottom-color") != "rgba(255, 255, 255, 1)"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("LAN Settings menu button not highlighted on hover after " + str(self.config.mid_timeout) + " seconds")
            time.sleep(1)

    def test_button_click_loads_lan_settings_C10805(self):
        # Get the driver
        driver = self.config.driver

        # Wait for the Alarms button to load and then click it
        try:
            WebDriverWait (driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'LAN Settings'))
            )
        except:
            self.fail("LAN menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_link_text("LAN Settings").click()

        # Check to see if the url is the expected one if not fail the test
        expected_url = (self.config.base_url + "home#/lanSettings/" + self.config.root_node)
        self.assertEqual(driver.current_url, expected_url, "{} FAILURE! URL Redirect to '{}' did not work.".format(__file__, expected_url))

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

        driver.save_screenshot("original_lan_port_settings.png")

if __name__ == "__main__":
    unittest.main()