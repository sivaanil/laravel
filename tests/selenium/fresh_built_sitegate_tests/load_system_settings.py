__author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException

import unittest
import time


class LoadSystemSettingsTest(c2_test_case.C2TestCase):
    def test_load_system_settings(self):
        driver = self.config.driver

        # Wait for the System Settings button to be found and then click the button; if not found after the mid timeout fail the test case with
        # timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'System Settings'))
            )
        except TimeoutException:
            self.fail("SiteGate didn't load the System Settings button within " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_link_text("System Settings").click()

        # Wait for the System Settings canvas to load; if not loaded after the mid timeout fail the test case with timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.CLASS_NAME, 'form-group'))
            )
        except TimeoutException:
            self.fail("System Settings canvas did not load within the allotted " + str(self.config.mid_timeout) + " seconds")

        # Get the list of labels for each setting and a list of each value for the settings
        settings_label_list = driver.find_elements_by_class_name("control-label.col-xs-12.col-sm-4")
        settings_list = driver.find_elements_by_class_name("col-xs-12.col-sm-8")

        num = 0 # Tracks which setting we are currently on
        for setting in settings_list: # loop through all the settings
            if (num < 3): # Check if one of the first three settings since they include a '%' in their value no matter what
                # Every second check to see if the setting value has more then just '%' in the text; if at the end of 20 seconds it doesn't fail
                # the test case with an error containing information on which setting failed
                for secs in range (0, self.config.mid_timeout):
                    if (setting.text == "%"):
                        if (secs >= self.config.mid_timeout - 1):
                            self.fail(settings_label_list[num].text + " setting did not load a value within " + str(self.config.mid_timeout) +
                                    " seconds")
                        time.sleep(1)
                    else:
                        break
            else:
                # Every second check to see if the setting value (SiteGate ID) has a value; if at the end of 20 seconds it doesn't fail the
                # test case with an error containing information on the setting that failed
                for secs in range (0, self.config.mid_timeout):
                    if (setting.text == ""):
                        if (secs >= self.config.mid_timeout - 1):
                            self.fail("The " + settings_label_list[num].text + " setting did not load a value within the allotted " +
                                str(self.config.mid_timeout) + "seconds.")
                        time.sleep(1)
                    else:
                        break
            num += 1 # increment our tracking number


if __name__ == "__main__":
    unittest.main()
