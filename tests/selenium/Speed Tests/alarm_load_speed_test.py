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

class AlarmLoadSpeedTest(c2_test_case.C2TestCase):

    def test_alarm_load_speed(self):
        driver = self.config.driver

        # Wait for the alarm grid to load and then wait for the first row to load (just in case); if they don't load within 60 - 180 seconds fail the test with a timeout error
        try:
            WebDriverWait(driver, 60).until(
                expected_conditions.presence_of_element_located((By.ID, "alarmsGrid"))
            )
            WebDriverWait(driver, 60).until(
                expected_conditions.invisibility_of_element_located((By.XPATH, "//div[@id='alarmsGrid']/div/div"))
            )
            WebDriverWait(driver, 60).until(
                expected_conditions.presence_of_element_located((By.ID, "row0alarmsGrid"))
            )
        except TimeoutException:
            self.fail("Alarm Grid did not load within 60 seconds")

        # driver.save_screenshot('seleniumAlarmsLoadTest.png')

if __name__ == "__main__":
    unittest.main()