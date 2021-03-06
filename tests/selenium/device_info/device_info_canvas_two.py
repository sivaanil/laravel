__author__ = 'forde1'

import sys
sys.path.append("..")
import unittest
import time
import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException


class LoginSuccess(c2_test_case.C2TestCase):
    def device_info(self):
        driver = self.config.driver

        # adding this line which automatically maximizes the window. (doesn't work in chrome though)
        # Wait for the username field to be found; if not found after 60 seconds fail the test case with timeout error

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "#/stateChange/deviceInfo"))
            )
        except TimeoutException:
            self.fail("Device Info form did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")

if __name__ == "__main__":
    unittest.main()
