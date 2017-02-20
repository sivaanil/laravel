import sys
sys.path.append("..")
import unittest

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from sshtunnel import SSHTunnelForwarder
import mysql.connector

import time

class LoginResetPasswordSetup(c2_test_case.C2TestCase):
    def test_switch_force_password_change_on(self):
        with SSHTunnelForwarder(
                (self.config.ip_address, 22),
                ssh_password="qcsit3gat3", ssh_username="c2-maintenance",
                remote_bind_address=("localhost", 3306)
        ) as server:

            database = mysql.connector.connect(user="root", password="root", host="localhost", port=server.local_bind_port,
                                               database="forge")

            cursor = database.cursor()
            cursor.execute("UPDATE css_authentication_user SET force_pwd_change = '1' WHERE (id = '5')")

            database.commit()
            database.close()

class LoginResetPasswordGeneral(c2_test_case.C2TestCase):
    def test_reset_password_should_display_C141739(self):
        # Get the driver
        driver = self.config.driver

        LoginResetPasswordGeneral.load_page(self, driver)

        self.assertNotEqual(driver.current_url.find("reset"), -1, " The reset screen did not display.")

    def test_old_password_is_correct_C141740(self):
        # Get the driver
        driver = self.config.driver

        LoginResetPasswordGeneral.load_page(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "old_password"))
            )
        except TimeoutException:
            self.fail("Old Password field did not load within " + str(self.config.mid_timeout) + "seconds.")
        old_password_field = driver.find_element_by_id("old_password")
        new_password_field = driver.find_element_by_id("new_password")
        new_password_confirm_field = driver.find_element_by_id("new_password_confirm")

        old_password_field.clear()
        old_password_field.send_keys("wrong_password")
        new_password_field.clear()
        new_password_field.send_keys("seleniumTest")
        new_password_confirm_field.clear()
        new_password_confirm_field.send_keys("seleniumTest")

        driver.find_element_by_id("login-button").click()
        for sec in range(0, self.config.mid_timeout):
            if (driver.find_element_by_id("flash_error").text == "An error has occured, please try again."):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Error message was not correct or did not display. The current error message is: " +
                          driver.find_element_by_id("flash_error").text)
            time.sleep(1)

    def test_new_passwords_match_C141741(self):
        # Get the driver
        driver = self.config.driver

        LoginResetPasswordGeneral.load_page(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "old_password"))
            )
        except TimeoutException:
            self.fail("Old Password field did not load within " + str(self.config.mid_timeout) + "seconds.")
        old_password_field = driver.find_element_by_id("old_password")
        new_password_field = driver.find_element_by_id("new_password")
        new_password_confirm_field = driver.find_element_by_id("new_password_confirm")

        old_password_field.clear()
        old_password_field.send_keys("123456")
        new_password_field.clear()
        new_password_field.send_keys("seleniumTest")
        new_password_confirm_field.clear()
        new_password_confirm_field.send_keys("thisPasswordWillNotMatch")

        driver.find_element_by_id("login-button").click()
        for sec in range(0, self.config.mid_timeout):
            if (driver.find_element_by_id("flash_error").text == "New passwords do not match"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Error message was not correct or did not display. The current error message is: " +
                          driver.find_element_by_id("flash_error").text)
            time.sleep(1)

    # Added z to the method name so that this test is run last since it resets the password
    def test_z_reset_password_should_submit_changes_C141742(self):
        # Get the driver
        driver = self.config.driver

        LoginResetPasswordGeneral.load_page(self, driver)

        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, "old_password"))
            )
        except TimeoutException:
            self.fail("Old Password field did not load within " + str(self.config.mid_timeout) + "seconds.")
        old_password_field = driver.find_element_by_id("old_password")
        new_password_field = driver.find_element_by_id("new_password")
        new_password_confirm_field = driver.find_element_by_id("new_password_confirm")

        old_password_field.clear()
        old_password_field.send_keys("123456")
        new_password_field.clear()
        new_password_field.send_keys("seleniumTest")
        new_password_confirm_field.clear()
        new_password_confirm_field.send_keys("seleniumTest")

        driver.find_element_by_id("login-button").click()

        # Test that the url loaded is the same as the expected url for SiteGate "home"
        expected_url = '{}{}'.format(self.config.base_url, 'home#/alarms/' + self.config.root_node)
        self.assertEqual(driver.current_url, expected_url,
                         "{} FAILURE! URL Redirect to '{}' did not work within {} seconds.".format(__file__, expected_url, 60))









    ## Helper Methods ##
    def load_page(self, web_driver):
        if (web_driver.current_url.find("reset") == -1):
            # Navigate to SiteGate
            web_driver.get(self.config.base_url + "")

            # Wait for the username field to be found; if not found after 60 seconds fail the test case with timeout error
            try:
                WebDriverWait(web_driver, 60).until(
                    expected_conditions.presence_of_element_located((By.ID, 'username'))
                )
            except TimeoutException:
                self.fail("Timeout Login page did not load within 60 seconds")

            # clear and fill in the username and password fields then click the login button
            web_driver.find_element_by_id("username").clear()
            web_driver.find_element_by_id("username").send_keys("G8Keeper")
            web_driver.find_element_by_id("password").clear()
            web_driver.find_element_by_id("password").send_keys("123456")
            web_driver.find_element_by_id("login-button").click()



class login_reset_password_cleanup(c2_test_case.C2TestCase):
    def test_login_with_new_password_C141743(self):
        # Get the web driver
        driver = self.config.driver

        # Wait for the Alarms button to load and then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'Logout'))
            )
        except:
            self.fail("Logout menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        driver.find_element_by_link_text("Logout").click()

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
        driver.find_element_by_id("password").send_keys("seleniumTest")
        driver.find_element_by_id("login-button").click()

        # Test that the url loaded is the same as the expected url for SiteGate "home"
        expected_url = '{}{}'.format(self.config.base_url, 'home#/alarms/' + self.config.root_node)
        self.assertEqual(driver.current_url, expected_url,
                         "{} FAILURE! URL Redirect to '{}' did not work within {} seconds.".format(__file__, expected_url, 60))

    def test_reset_password_to_default(self):
        with SSHTunnelForwarder(
                (self.config.ip_address, 22),
                ssh_password="qcsit3gat3", ssh_username="c2-maintenance",
                remote_bind_address=("localhost", 3306)
        ) as server:

            database = mysql.connector.connect(user="root", password="G2MadRev", host="localhost", port=server.local_bind_port,
                                               database="cswapi_unified")

            cursor = database.cursor()
            cursor.execute(
                    "UPDATE css_authentication_user SET password = '$2y$10$Qj3g1.DLjKQQOY0cpUJApOjk5j73I7APBSaM.0fl/7.VmofdM.q56' WHERE (id = '5')")

            database.commit()
            database.close()


if __name__ == "__main__":
    unittest.main()