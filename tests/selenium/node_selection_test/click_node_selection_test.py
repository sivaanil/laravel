__author__ = 'daniel.madden'

# -*- coding: utf-8 -*-
from selenium.webdriver.common.action_chains import ActionChains
import sys
sys.path.append("..")
import c2_test_case
import unittest, time, re


class ClickNodeSelection(c2_test_case.C2TestCase):

    def test_node_selection_button_hover_color(self):
        driver = self.config.driver
        element_to_hover_over = driver.find_element_by_id("menuItem0")
        hover = ActionChains(driver).move_to_element(element_to_hover_over)
        hover.perform()
        self.assertEqual(element_to_hover_over.value_of_css_property("background-color"), "rgba(250, 250, 250, 1)")

    def test_node_selection_button_url_change(self):
        driver = self.config.driver
        timeout = 2
        time.sleep(timeout)
        expected_url = self.config.base_url + "home"
        self.assertEqual(driver.current_url,
                         expected_url,
                         "{} FAILURE! URL Redirect to '{}' did not work within {} seconds.".format(__file__, expected_url, timeout))
        driver.find_element_by_link_text("Node Selection").click()
        expected_url = self.config.base_url + "NodeChange/321"
        self.assertEqual(driver.current_url,
                         expected_url,
                         "{} FAILURE! URL Redirect to '{}' did not work.".format(__file__, expected_url))

if __name__ == "__main__":
    unittest.main()
