import sys
sys.path.append("..")
import unittest

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException

import time

class LoginGeneral(c2_test_case.C2TestCase):
    def test_incorrect_credentails_should_fail_login_C10264(self):
        # Get the web driver and navigate to the Unified interface
        driver = self.config.driver
        LoginGeneral.load_page(self, driver)

        # Wait for the username field to load and then clear it and fill it with the failed username
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "username"))
            )
        except TimeoutException:
            self.fail("Username field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("failing_username")

        # Wait for the password field to load and then clear it and fill it with the failed password
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "password"))
            )
        except TimeoutException:
            self.fail("Password field did not load within the alloted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("password").click()
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("failing_password")

        # Click the login button (should not need to wait for it to load after waiting for the fields.
        driver.find_element_by_id("login-button").click()

        # Wait for the error message to display
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "flash_error"))
            )
        except TimeoutException:
            self.fail("No error message displayed before the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_incorrect_credentails_error_should_not_specify_credential_error_C144123(self):
        # Get the web driver and navigate to the Unified interface
        driver = self.config.driver
        LoginGeneral.load_page(self, driver)

        # Wait for the username field to load and then clear it and fill it with the failed username
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "username"))
            )
        except TimeoutException:
            self.fail("Username field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("failing_username")

        # Wait for the password field to load and then clear it and fill it with the failed password
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "password"))
            )
        except TimeoutException:
            self.fail("Password field did not load within the alloted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("password").click()
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("failing_password")

        # Click the login button (should not need to wait for it to load after waiting for the fields.
        driver.find_element_by_id("login-button").click()

        # Wait for the error message to display and
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "flash_error"))
            )
        except TimeoutException:
            self.fail("No error message displayed before the allotted " + str(self.config.mid_timeout) + " seconds.")
        error_message_element_text = driver.find_element_by_id("flash_error").text

        # Check the error message that it includes both password and username
        self.assertNotEqual(error_message_element_text.find("Username"), -1,
                            "Error message should be ambiguous as to whether username or password is wrong.")
        self.assertNotEqual(error_message_element_text.find("Password"), -1,
                            "Error message should be ambiguous as to whether username or password is wrong.")

    def test_fields_should_be_case_sensitive_C142056(self):
        # Get the web driver and navigate to the Unified interface
        driver = self.config.driver
        LoginGeneral.load_page(self, driver)

        # Wait for the username field to load and then clear it and fill it with the failed username
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "username"))
            )
        except TimeoutException:
            self.fail("Username field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("g8keeper")

        # Wait for the password field to load and then clear it and fill it with the failed password
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "password"))
            )
        except TimeoutException:
            self.fail("Password field did not load within the alloted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("password").click()
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("123456")

        # Click the login button (should not need to wait for it to load after waiting for the fields.
        driver.find_element_by_id("login-button").click()

        # Wait for the error message to display and
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "flash_error"))
            )
        except TimeoutException:
            self.fail("No error message displayed before the allotted " + str(self.config.mid_timeout) + " seconds.")

    def test_after_5_failed_attempts_next_attempt_timeout_C11549(self):
        # Get the web driver and navigate to the Unified interface
        driver = self.config.driver
        LoginGeneral.load_page(self, driver)

        # Wait for the username field to load and then clear it and fill it with the failed username
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "username"))
            )
        except TimeoutException:
            self.fail("Username field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("G8Keeper")

        # Wait for the password field to load and then clear it and fill it with the failed password
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "password"))
            )
        except TimeoutException:
            self.fail("Password field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("password").click()
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("123456")

        # Click the login button (should not need to wait for it to load after waiting for the fields.
        driver.find_element_by_id("login-button").click()

        # Wait for the network tree to load so that returning to the login would not break anything
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "netTree"))
            )
        except TimeoutException:
            self.fail("network tree must load first and it didn't within the allotted " + str(self.config.mid_timeout) + " seconds.")
        network_tree = driver.find_element_by_id("netTree")

        # Get the list of the network tree nodes then loop through the list of them by using an index
        network_tree_nodes = network_tree.find_elements_by_tag_name("li")
        for index in range(0, self.config.long_timeout):
            if (len(network_tree_nodes) <= 1):
                network_tree_nodes = network_tree.find_elements_by_tag_name("li")
            elif (index >= self.config.long_timeout - 1):
                self.fail("Network Tree nodes did not load after " + str(self.config.long_timeout) + " seconds")
            else:
                break
            time.sleep(1)
        LoginGeneral.load_page(self, driver)

        # loop six times to activate the timeout error
        for index in range(0, 6):
            # Wait for the username field to load and then clear it and fill it with the failed username
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.presence_of_element_located((By.ID, "username"))
                )
            except TimeoutException:
                self.fail("Username field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
            driver.find_element_by_id("username").clear()
            driver.find_element_by_id("username").send_keys("failed_username")

            # Wait for the password field to load and then clear it and fill it with the failed password
            try:
                WebDriverWait(driver, self.config.mid_timeout).until(
                    expected_conditions.presence_of_element_located((By.ID, "password"))
                )
            except TimeoutException:
                self.fail("Password field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
            driver.find_element_by_id("password").click()
            driver.find_element_by_id("password").clear()
            driver.find_element_by_id("password").send_keys("failed_password")

            # Click the login button (should not need to wait for it to load after waiting for the fields.
            driver.find_element_by_id("login-button").click()

        # Wait for the error message to display and
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.visibility_of_element_located((By.ID, "flash_error"))
            )
        except TimeoutException:
            self.fail("No error message displayed before the allotted " + str(self.config.mid_timeout) + " seconds.")
        error_message_element_text = driver.find_element_by_id("flash_error").text

        # Check that the error message includes the too many login attempts
        self.assertNotEqual(error_message_element_text.find("Too many login attempts."), -1,
                            "Did not display the timeout error as expected")

        # Not really any better way to fix this, after activating the timeout the user has to wait 60 seconds before login will work
        # so we are just going to sleep the 60 seconds
        time.sleep(60)

    def test_login_go_to_alarms_C10271(self):
        # Get the web driver and navigate to the Unified interface
        driver = self.config.driver
        LoginGeneral.load_page(self, driver)

        # Wait for the username field to load and then clear it and fill it with the failed username
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "username"))
            )
        except TimeoutException:
            self.fail("Username field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("G8Keeper")

        # Wait for the password field to load and then clear it and fill it with the failed password
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "password"))
            )
        except TimeoutException:
            self.fail("Password field did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("password").click()
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("123456")

        # Click the login button (should not need to wait for it to load after waiting for the fields.
        driver.find_element_by_id("login-button").click()

        self.assertEqual(driver.current_url.find("login"), -1, "The login page is still loaded")
        self.assertNotEqual(driver.current_url.find("alarm"), -1, "The Alarms canvas did not load")

        # Wait for the network tree to load so that returning to the login would not break anything
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "netTree"))
            )
        except TimeoutException:
            self.fail("network tree must load first and it didn't within the allotted " + str(self.config.mid_timeout) + " seconds.")
        network_tree = driver.find_element_by_id("netTree")

        # Get the list of the network tree nodes then loop through the list of them by using an index
        network_tree_nodes = network_tree.find_elements_by_tag_name("li")
        for index in range(0, self.config.long_timeout):
            if (len(network_tree_nodes) <= 1):
                network_tree_nodes = network_tree.find_elements_by_tag_name("li")
            elif (index >= self.config.long_timeout - 1):
                self.fail("Network Tree nodes did not load after " + str(self.config.long_timeout) + " seconds")
            else:
                break
            time.sleep(1)
        LoginGeneral.load_page(self, driver)

    def test_open_help_pdf_C11564(self): # The 'z' is to ensure this test goes last
        # Get the web driver and navigate to the Unified interface
        driver = self.config.driver
        LoginGeneral.load_page(self, driver)

        # Wait for the help button to load and then click it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "login-help-button"))
            )
        except TimeoutException:
            self.fail("Help button did not load within the allotted " + str(self.config.mid_timeout) + " seconds.")
        driver.find_element_by_id("login-help-button").click()

        # Store the current window then get a list of the open windows. Ensure that there are two window open if not fail the test case.
        current_window = driver.current_window_handle
        window_list = driver.window_handles
        # To do the above check I needed to check the length of the list so I've set up a loop to check every second that the list of windows
        # are indeed greater then 1 and if so I break out of the loop, if the loop hits the max ammount of seconds allowed I fail the test.
        for sec in range(0, self.config.long_timeout):
            window_list = driver.window_handles
            if (len(window_list) >= 2):
                break
            elif (sec >= self.config.long_timeout - 1):
                self.fail("Gaucamole page not opened")
            time.sleep(1)

        # Loop through the open windows list and find the window that isn't the one the driver is currently connected to, then switch the
        # driver's connection to it.
        for window_id in window_list:
            if (window_id != current_window):
                driver.switch_to_window(window_id)

        # Check to see if the url is correct
        self.assertEqual(driver.current_url, self.config.base_url + "pdf/sitegate-help.pdf", "Did not open the expected window.")








    ## Helper Methods ##
    def load_page(self, web_driver):
        # Check to make sure that sitegate is loaded if not load it
        if (web_driver.current_url.find(self.config.base_url) == -1):
            web_driver.get(self.config.base_url + "")

        # Check to make sure that the login page is loaded if not load it
        if (web_driver.current_url.find("login")):
            web_driver.get(self.config.base_url + "login")



# This class and test case is necessary to return Selenium's control back to the main window
class LoginGeneralCleanup(c2_test_case.C2TestCase):
    def test_return_control_to_correct_window(self):
        #Get the driver
        driver = self.config.driver

        # Get the current window and the list of windows
        current_window = driver.current_window_handle
        window_list = driver.window_handles

        # Make sure the list of windows is greater then one and store the partial guac url
        if (len(window_list) > 1):
            pdf_url = self.config.base_url + "pdf/sitegate-help.pdf"

            # Check to ensure the window is guacamole and then loop till we find the Unified window and switch the driver's control to it.
            if (driver.current_url.find(pdf_url) != -1):
                for window_id in window_list:
                    if (window_id != current_window):
                        driver.switch_to_window(window_id)

        # Final check just to ensure the right window was switched to check to make sure the url is for Unified
        expected_url = (self.config.base_url + "login")
        self.assertNotEqual(driver.current_url.find(expected_url), -1, "FAILURE! driver did not switch to the correct window. Current URL: " +
                         driver.current_url + " expected url: " + expected_url + ".")

if __name__ == "__main__":
    unittest.main()

