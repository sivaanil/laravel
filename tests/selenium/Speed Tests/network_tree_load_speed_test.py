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

class NetworkTreeLoadSpeedTest(c2_test_case.C2TestCase):

    def test_network_tree_load_speed(self):
        driver = self.config.driver

        # Wait for the Alarm button to be available and then click it; if it doesn't become available within 60 seconds fail the test with a timeout error
        try:
            WebDriverWait(driver, 60).until(
                expected_conditions.presence_of_element_located((By.ID, 'netTree'))
            )
        except TimeoutException:
            self.fail("Network Tree container did not load within 60 seconds")
        network_tree_container = driver.find_element_by_id("netTree")

        try:
            WebDriverWait(driver, 60).until(
                expected_conditions.presence_of_element_located((By.CLASS_NAME, "jqx-tree-dropdown.jqx-tree-dropdown-bootstrap"))
            )
        except TimeoutException:
            self.fail("Network Tree did not load within 60 seconds")

        # driver.save_screenshot('seleniumNetTreeLoadTest.png')

if __name__ == "__main__":
    unittest.main()