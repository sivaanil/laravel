__author__ = 'emily.ford'

# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException
from selenium.common.exceptions import NoAlertPresentException
import sys
sys.path.append('..')
import c2_test_case
import selenium_config
import unittest, time, re, os, subprocess

class BuildWrapper(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        ResetBrowserPath = "//div[@id='mainPanelView']/div[2]/form/div/div/div[2]/div/button[2]"
        ResetWebBrowserClosePath = "//div[@id='resetGuacamoleWindowContent']/div/form/div/div/div[2]/img"
        print "The General Test will now start."
        time.sleep(6)
        driver.find_element_by_link_text("System Settings").click()
        time.sleep(5)
        print "C11553 will now be tested."
        #have test check system settings
        print "C11554 will now be tested."
        driver.find_element_by_xpath(ResetBrowserPath).click()
        time.sleep(3)
        driver.find_element_by_xpath(ResetWebBrowserClosePath).click()
        """
        Test cases to implement:
        -System Information Should Display Correctly
        -Reset Browser Dialog
        -Reboot Dialog
        """

if __name__ == "__main__":
    BuildWrapper.config = selenium_config.default_config()
    unittest.main()
