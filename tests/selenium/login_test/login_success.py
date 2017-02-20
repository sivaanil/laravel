import sys
sys.path.append("..")
import unittest

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException


class LoginSuccess(c2_test_case.C2TestCase):

    def test_login_success(self):
        driver = self.config.driver
        driver.get(self.config.base_url + "")

        # adding this line which automatically maximizes the window. (doesn't work in chrome though)
        driver.maximize_window()

        # Wait for the username field to be found; if not found after 60 seconds fail the test case with timeout error
        try:
            WebDriverWait(driver, 60).until(
                expected_conditions.presence_of_element_located((By.ID, 'username'))
            )
        except TimeoutException:
            self.fail("Timeout Login page did not load within 60 seconds")

        # clear and fill in the username and password fields then click the login button
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("G8Keeper")
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("123456")
        driver.find_element_by_id("login-button").click()

        # Wait for the unified content to load; if it doesn't after 60 seconds fail the test case with timeout error
        try:
            WebDriverWait(driver, 60).until(
                expected_conditions.presence_of_element_located((By.ID, 'content'))
            )
        except TimeoutException:
            self.fail("Timeout could not log in after 60 seconds")

        # Test that the url loaded is the same as the expected url for SiteGate "home"
        expected_url = '{}{}'.format(self.config.base_url, 'home#/alarms/' + self.config.root_node)
        self.assertEqual(driver.current_url, expected_url, "{} FAILURE! URL Redirect to '{}' did not work within {} seconds.".format(__file__, expected_url, 60))

if __name__ == "__main__":
    unittest.main()
