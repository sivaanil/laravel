import sys
sys.path.append("..")
import unittest

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.action_chains import ActionChains

import time

class LogoutGeneral(c2_test_case.C2TestCase):
    def test_hover_over_logout_button_C11547(self):
        # Get the web driver and login
        driver = self.config.driver
        LogoutGeneral.log_in(self, driver)

        # Wait for the Alarms button to load and then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'Logout'))
            )
        except:
            self.fail("Logout menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        element_to_hover_over = driver.find_element_by_link_text("Logout")

        # Emulate the mouse hovering over the Alarms button
        hover = ActionChains(driver).move_to_element(element_to_hover_over)
        hover.perform()

        # Check every second for the Alarms button to be in its hover state, if max time met then fail the case
        for sec in range(0, self.config.mid_timeout):
            if (element_to_hover_over.value_of_css_property("border-bottom-color") != "rgba(255, 255, 255, 1)"):
                break
            elif (sec >= self.config.mid_timeout - 1):
                self.fail("Logout menu button not highlighted on hover after " + str(self.config.mid_timeout) + " seconds")
            time.sleep(1)

    def test_click_logout_button_C11548(self):
        # Get the web driver and login
        driver = self.config.driver
        LogoutGeneral.log_in(self, driver)

        # Wait for the Alarms button to load and then store it
        try:
            WebDriverWait(driver, self.config.mid_timeout).until(
                expected_conditions.presence_of_element_located((By.LINK_TEXT, 'Logout'))
            )
        except:
            self.fail("Logout menu button failed to load within " + str(self.config.mid_timeout) + " seconds")
        logout_btn = driver.find_element_by_link_text("Logout")

        # Click the logout button
        logout_btn.click()

        self.assertNotEqual(driver.current_url.find("login"), -1, "Did not properly logout.")






    ## Helper Methods ##
    def log_in(self, web_driver):
        if (web_driver.current_url.find("home") == -1):
            web_driver.get(self.config.base_url + "")

            # # adding this line which automatically maximizes the window. (doesn't work in chrome though)
            # web_driver.maximize_window()

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

            # Wait for the unified content to load; if it doesn't after 60 seconds fail the test case with timeout error
            try:
                WebDriverWait(web_driver, 60).until(
                    expected_conditions.presence_of_element_located((By.ID, 'content'))
                )
            except TimeoutException:
                self.fail("Timeout could not log in after 60 seconds")

            # Wait for the network tree to load so that returning to the login would not break anything
            try:
                WebDriverWait(web_driver, self.config.mid_timeout).until(
                    expected_conditions.presence_of_element_located((By.ID, "netTree"))
                )
            except TimeoutException:
                self.fail("network tree must load first and it didn't within the allotted " + str(self.config.mid_timeout) + " seconds.")
            network_tree = web_driver.find_element_by_id("netTree")

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





if __name__ == "__main__":
    unittest.main()