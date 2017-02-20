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

class AlarmPopulationTest(c2_test_case.C2TestCase):

    def test_alarm_population(self):
        driver = self.config.driver

        # Wait for the Alarm button to be available and then click it; if it doesn't become available within the mid timeout fail the test
        # with a timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'Alarms'))
            )
        except TimeoutException:
            self.fail("SiteGate didn't load the Alarms button within " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_link_text("Alarms").click()

        # Wait for the alarm grid to load and then wait for the first row to load (just in case); if they don't load within the mid timeout fail
        # the test with a timeout error
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

        # Ensure the alarm has populated like it should
        for sec in range(0, self.config.mid_timeout):
            if (driver.find_element_by_id("row0alarmsGrid").text != "No alarms to display"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Alarm Grid not loading within " + str(self.config.mid_timeout) + " seconds.")
            time.sleep(1)

        # Get the first alarm row and make sure it doesn't equal the no alarms text, since the device built/scanned should raise alarms
        first_alarm_row_text = driver.find_element_by_id("row0alarmsGrid").text
        # print (first_alarm_row_text)
        self.assertNotEqual(first_alarm_row_text, "No alarms to display", "Alarm grid not populated with expected alarms")
        self.assertNotEqual(first_alarm_row_text, "", "Alarm grid populated blank")

        for secs in range(0, self.config.mid_timeout):
            first_alarm_row_text = driver.find_element_by_id("row0alarmsGrid").text
            if (first_alarm_row_text != "undefined"):
                break
            elif (secs >= self.config.mid_timeout - 1):
                self.fail("Alarm grid populated with undefined alarms")
            time.sleep(1)

if __name__ == "__main__":
    unittest.main()