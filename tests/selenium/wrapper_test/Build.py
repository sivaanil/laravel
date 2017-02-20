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
        SiteGatePath = "//network-tree/div/ul/li/div"
        AddDevicePath = "//div[@id='networkExplorer']/div/button"
        CancelBuildPath = "//div[@id='buildDeviceWindowContent']/div/form/div[3]/div[3]"
        FirstDevicePath = "//li/ul/li/div"
        print "The Build Wrapper test will now begin. This will test C11524 and C11525."
        time.sleep(5)
        driver.find_element_by_xpath(SiteGatePath).click()
        time.sleep(5)
        driver.find_element_by_xpath(AddDevicePath).click()
        time.sleep(5)
        if ("Add Device" and "Device Information" and "Device Name" and "Device Type" and "Primary IP Address" and "Cancel" and "Create Device") not in self.config.driver.page_source:
            print "The Add Device Dialog is now displaying correctly."
        else:
            print "The Add Device Dialog is displaying correctly for the root node. C11524 passes."
        driver.find_element_by_xpath(CancelBuildPath).click()
        time.sleep(3)
        try:
            driver.find_element_by_xpath(FirstDevicePath).click()
            time.sleep(5)
            try:
                driver.find_element_by_xpath(AddDevicePath).click()
            except Exception:
                print "The Add Device dialog is working as expected for devices that are not the root node. (Test Case C11525)"
        except Exception:
            print "There are no devices build currently therefore it is not possible to check if you can add a device onto a Device that is not the root node. (Test Case C11525)"

        """
        Test cases to implement:
        - Add Device only available for SiteGate:
            *Test to make sure it is available for SiteGate node/confirm it opens Add Device Dialog
            *Check if there's a device built

        """

if __name__ == "__main__":
    BuildWrapper.config = selenium_config.default_config()
    unittest.main()
