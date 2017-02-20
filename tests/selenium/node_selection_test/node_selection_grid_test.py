__author__ = 'daniel.madden'

# -*- coding: utf-8 -*-
from selenium.webdriver.common.action_chains import ActionChains
import sys
sys.path.append("..")
import c2_test_case
import unittest
import time


class NodeSelectionGridTest(c2_test_case.C2TestCase):
    def test_hover_over_node_underlined(self):
        driver = self.config.driver

        # To make sure the page loaded fully before proceeding.
        time.sleep(2)

        element_to_hover_over = driver.find_element_by_link_text("[Current] All Clients")

        hover = ActionChains(driver).move_to_element(element_to_hover_over)
        hover.perform()

        self.assertEqual(element_to_hover_over.value_of_css_property("text-decoration"),
                         "underline",
                         "Node link was not underlined when hovered over")

    def test_current_node_click(self):
        driver = self.config.driver

        # To make sure the page loaded fully before proceeding.
        time.sleep(2)

        element_to_test = driver.find_element_by_partial_link_text("[Current]")
        url = element_to_test.get_attribute("href")

        self.assertEqual(url,
                         driver.current_url,
                         "URL mismatch between Current Node and Current URL!")

        element_to_test.click()

        self.assertEqual(url,
                         driver.current_url,
                         "URL mismatch between Current Node and Current URL!")

    def test_not_current_node_click(self):
        driver = self.config.driver

        # To make sure the page loaded fully before proceeding.
        time.sleep(2)

        elements = driver.find_elements_by_class_name("notCurrentNode")

        # Grab the first element that's not the current node and click it
        if len(elements) > 0:
            div_to_test = elements[0]
            anchor = div_to_test.find_element_by_xpath(".//div/div/div/a")
            url = anchor.get_attribute("href")
            text = anchor.get_attribute("innerHTML").replace("\t", "")
            anchor.click()
            time.sleep(2)
            self.assertEqual(driver.current_url, url, "URL Mismatch Between Clicked Element and Current URL!")
            self.assertIsNotNone(driver.find_element_by_link_text("[Current] {}".format(text)),
                                 "Node did not switch to showing 'Current' after it was clicked!")

if __name__ == "__main__":
    unittest.main()
