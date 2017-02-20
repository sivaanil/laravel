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

class GeneralSystem(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        DiskUsagePath = "//div[@id='mainPanelView']/div[2]/form/div/div/div/div/div"
        MemoryUsagePath = "//div[@id='mainPanelView']/div[2]/form/div/div/div/div[2]/div"
        CPUUsagePath = "//div[@id='mainPanelView']/div[2]/form/div/div/div/div[3]/div"
        SiteGateIDPath = "//div[@id='mainPanelView']/div[2]/form/div/div/div/div[4]/div"
        print "The General Test will now start."
        time.sleep(25)
        driver.find_element_by_link_text("System Settings").click()
        time.sleep(5)
        print "C11553 will now be tested."
        #have test check system settings
        DiskUsageValue = driver.find_element_by_xpath(DiskUsagePath).text
        MemoryUsageValue = driver.find_element_by_xpath(MemoryUsagePath).text
        CPUUsageValue = driver.find_element_by_xpath(CPUUsagePath).text
        SiteGateIDValue = driver.find_element_by_xpath(SiteGateIDPath).text
        print "The disk usage value is: %s" % DiskUsageValue
        print "The memory usage value is: %s" % MemoryUsageValue
        print "The CPU usage value is: %s" % CPUUsageValue
        print "The SiteGate ID value is: %s" % SiteGateIDValue
        DiskPass = ""
        MemoryPass = ""
        CPUPass = ""
        SiteGateIDPass = ""
        if DiskUsageValue == ("" or "%"):
            DiskPass = "No"
        else:
            if DiskUsageValue > 100:
                DiskPass = "No"
            else:
                DiskPass = "Yes"
        if MemoryUsageValue == ("" or "%"):
            MemoryPass = "No"
        else:
            if MemoryUsageValue > 100:
                MemoryPass = "No"
            else:
                MemoryPass = "Yes"
        if CPUUsageValue == ("" or "%"):
            CPUPass = "No"
        else:
            if CPUUsageValue > 100:
                CPUPass = "No"
            else:
                CPUPass = "Yes"
        if SiteGateIDValue == ("" or "%"):
            print "The disk usage value is blank. C11553 fails."
        else:
            SiteGateIDPass = "Yes"
        if (DiskPass == "Yes") and (MemoryPass == "Yes") and (CPUPass == "Yes") and (SiteGateIDPass == "Yes"):
            print "The System Settings values are correct. C11553 passes."
        else:
            print "The System Settings values are incorrect. C11553 fails."

        """
        Test cases to implement:
        -System Information Should Display Correctly
        """

if __name__ == "__main__":
    GeneralSystem.config = selenium_config.default_config()
    unittest.main()
