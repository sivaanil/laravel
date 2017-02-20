__author__ = 'andrew.bascom'

# -*- coding: utf-8 -*-
import sys

sys.path.append("..")

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from selenium.common.exceptions import StaleElementReferenceException
import unittest
import time

class ScanTestDevice(c2_test_case.C2TestCase):

    def test_scan_device(self):
        driver = self.config.driver

        # Wait for the network tree to load and then store an instance of it; if the tree doesn't load in the long timeout fail the test with
        # a timeout error
        try:
            WebDriverWait(driver, self.config.long_timeout).until(
                expected_conditions.presence_of_element_located((By.ID, 'netTree'))
            )
        except TimeoutException:
            self.fail("Network Tree didn't load after " + str(self.config.long_timeout) + " seconds")
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

        # print ("Network tree length: " + str(len(network_tree_nodes)))
        for index in range(0, len(network_tree_nodes)):
            node = network_tree_nodes[index]

            # Try to get the label for this node; if the element reference has been lost wait for the network tree to load again, save the
            # instance of the tree, get the list of nodes, grab the specific node for this index, and lastly get the node's label
            try:
                node_text = node.find_element_by_xpath(".//div").text
            except StaleElementReferenceException:
                try:
                    WebDriverWait(driver, self.config.long_timeout).until(
                        expected_conditions.presence_of_element_located((By.ID, 'netTree'))
                    )
                except TimeoutException:
                    print ("node reference lost")
                    self.fail("Network Tree didn't load after " + str(self.config.long_timeout) + " seconds")
                network_tree = driver.find_element_by_id("netTree")

                network_tree_nodes = network_tree.find_elements_by_tag_name("li")
                node = network_tree_nodes[index]
                node_text = node.find_element_by_xpath(".//div").text

            # In case the node reference is lost once again added a try/except
            try:
                # check if the node label is the device we just built and then click the node to navigate to it
                if (node_text == "Selenium Test Device" or node_text == "ION-B System TSUN"):
                    node.find_element_by_xpath(".//div").click()

                    # Wait for the Scan button to load and display and then click it; if the button doesn't load/display within the mid
                    # timeout fail the test case with a timeout error
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.presence_of_element_located((By.XPATH, "//div[@id='networkExplorer']/div/button[2]"))
                        )
                        WebDriverWait(driver, self.config.long_timeout).until(
                            expected_conditions.visibility_of_element_located((By.XPATH, "//div[@id='networkExplorer']/div/button[2]"))
                        )
                    except TimeoutException:
                        self.fail("Scan Alarms button did not load after " + str(self.config.mid_timeout) + " seconds")
                    driver.find_element_by_xpath("//div[@id='networkExplorer']/div/button[2]").click()

                    # Wait for the scan progress dialog to display and then store an instance of it; if the dialog doesn't display within
                    # the mid timeout fail the test case with a timeout
                    try:
                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.presence_of_element_located((By.ID, 'scanProgressWindowContent'))
                        )

                        WebDriverWait(driver, self.config.mid_timeout).until(
                            expected_conditions.visibility_of_element_located((By.ID, "scanProgressWindowContent"))
                        )
                    except TimeoutException:
                        self.fail("Alarm Scan dialog didn't load after " + str(self.config.mid_timeout) + " seconds")
                    alarm_scan_dialog = driver.find_element_by_id("scanProgressWindowContent")

                    # Wait for the Cancel button to change to close which indicates scanning has completed; if the button doesn't change
                    # within the long timeout times 3 (default: 20 * 3 = 60) fail the test case with a timeout error
                    try:
                        WebDriverWait(driver, self.config.long_timeout * 3).until(
                            expected_conditions.visibility_of_element_located((By.XPATH,
                                                                               "//div[@id='scanProgressWindowContent']/div/form/div/div[2]/div[2]"))
                        )
                    except TimeoutException:
                        alarm_scan_dialog.find_element_by_class_name("cancel-scan-device-button").click()
                        self.fail("Alarm Scan did not complete within " + str(self.config.long_timeout * 3) + " seconds")

                    # Get the message from the dialog and if the message is a fail message, fail the test case and report the message;
                    # Also click the close button to close the dialog
                    scan_message = alarm_scan_dialog.find_element_by_class_name("scan-device-message").text
                    alarm_scan_dialog.find_element_by_class_name("close-scan-device-button").click()
                    self.assertEqual(scan_message, "Scan Completed Successfully!", "Scan was not successful; scan failed message: " +
                                     scan_message)

                    break
            # If the above doesn't work at any point due to a lost reference, set the loop back by one so that it can be tried again.
            except StaleElementReferenceException:
                print ("node reference lost")
                index -= 1

if __name__ == "__main__":
    unittest.main()
