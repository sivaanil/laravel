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

class ResetSystem(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        ResetBrowserPath = "//div[@id='mainPanelView']/div[2]/form/div/div/div[2]/div/button[2]"
        ResetButtonPath = "//div[@id='resetGuacamoleWindowContent']/div/form/div/div/div"
        ResetWebBrowserClosePath = "//div[@id='resetGuacamoleWindowContent']/div/form/div/div/div[2]/img"
        print "The Reset test will now begin."
        time.sleep(6)
        driver.find_element_by_link_text("System Settings").click()
        time.sleep(6)
        print "C117061 and C117062 will now be tested."
        print "C11554 will now be tested."
        try:
            driver.find_element_by_xpath(ResetBrowserPath).click()
            print "Reset Browser dialog was opened. C11554 passes."
            time.sleep(3)
            driver.find_element_by_xpath(ResetWebBrowserClosePath).click()
            if ("Reset Web Browser" or "Are you sure you wish to reset the remote web interface?" or "Close" or "Reset") not in self.config.driver.page_source:
                print "The Reset Browser Dialog is displaying incorrectly. C11554 fails."
        except Exception:
            print "Reset Browser dialog did not open. C11554 fails."
        time.sleep(3)
        try:
            driver.find_element_by_xpath(ResetBrowserPath).click()
            print "Reset Browser dialog was opened. C11554 passes."
            time.sleep(3)
            driver.find_element_by_xpath(ResetButtonPath).click()
            #time.sleep(1)
            if ("The remote web interface is being reset. You may now close this window.") not in self.config.driver.page_source:
                print "The reset message is not displaying. C117061 fails."
            else:
                print "The reset message is displaying. C117061 passes."
            try:
                driver.find_element_by_xpath(ResetWebBrowserClosePath).click()
            except Exception:
                print "Close button doesn't close the dialog. C117062 fails."
            try:
                driver.find_element_by_xpath(ResetWebBrowserClosePath).click()
                print "Close button didn't close the dialog. C117062 fails."
            except Exception:
                print "Close button closed the dialog. C117062 passes."
        except Exception:
            print "Reset Browser dialog did not open. C11554 fails and C117061 and C117062 cannot be tested."
        time.sleep(3)

        """
        Test cases to implement:
        -Should display message C117061
        -Close Button should close dialog C117062
        """



if __name__ == "__main__":
    ResetSystem.config = selenium_config.default_config()
    unittest.main()
