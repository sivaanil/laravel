__author__ = 'forde1'

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


class Click_Device_Info(c2_test_case.C2TestCase):
    def device_info_button(self):
        driver = self.config.driver

        # adding this line which automatically maximizes the window. (doesn't work in chrome though)
        # Wait for the username field to be found; if not found after 60 seconds fail the test case with timeout error

        time.sleep(7)
        try:
            driver.find_element_by_link_text("Device Info").click()
        except:
            self.fail("Did not work.")

if __name__ == "__main__":
    unittest.main()