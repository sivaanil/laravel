__author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.keys import Keys
import unittest

class GaucamoleXtermLoadTest(c2_test_case.C2TestCase):

    def test_load_gaucamole_xterm(self):
        driver = self.config.driver

        # Wait for the Launch Web Interface button to load and display then click it; if it doesn't load within the mid timeout fail the test
        # with timeout error
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='networkExplorer']/div/button[6]"))
            )
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='networkExplorer']/div/button[6]"))
            )
        except TimeoutException:
            self.fail("Launch Web Interface button did not load after " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_xpath("//div[@id='networkExplorer']/div/button[6]").click()

        # Get the current window that driver is connected to, then get the list of windows open under selenium's control, and lastly check to
        # make sure there are two windows open otherwise assume Gaucamole didn't open and fail the test
        current_window = driver.current_window_handle
        window_list = driver.window_handles
        if (len(window_list) < 2):
            self.fail("Gaucamole page did not open")

        # Loop through the windows and when a window (assumably Gauc) is found that's not the current one switch the driver's control to it
        for window_id in window_list:
            if (window_id != current_window):
                driver.switch_to.window(window_id)

        # Get the expected url for guacamole and check to see if it is within the current url, if not fail the test
        gauc_url = self.config.base_url + "guacamole"
        if (driver.current_url.find(gauc_url) == -1):
            self.fail("Gaucamole did not load (URL: " + driver.current_url + ")")

        # Wait for Guacamole to load; if it doesn't load within the long timeout fail the test with timeout error
        # try:
        #     WebDriverWait(driver, self.config.long_timeout).until(
        #         expected_conditions.presence_of_element_located((By.ID, "display"))
        #     )
        # except TimeoutException:
        #     self.fail("Gaucamole didn't finish loading within " + str(self.config.long_timeout) + " seconds")

        # Trick selenium to log in to the ADRF (this doesn't work)
        # ActionChains(driver).send_keys("vzdasops").send_keys(Keys.TAB).send_keys("VzWFL751").send_keys(Keys.ENTER).perform()

        # Trick selenium into opening Xterm (this doesn't always work)
        # ActionChains(driver).key_down(Keys.CONTROL).key_down(Keys.ALT).send_keys(Keys.F9).key_up(Keys.CONTROL).key_up(Keys.ALT).perform()

if __name__ == "__main__":
    unittest.main()