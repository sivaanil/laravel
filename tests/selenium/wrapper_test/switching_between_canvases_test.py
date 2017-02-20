__author__ = 'emily.ford'

# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException
from selenium.common.exceptions import NoAlertPresentException
from selenium.webdriver.common.action_chains import ActionChains
import sys
sys.path.append('..')
import c2_test_case
import selenium_config
import unittest, time, re, subprocess, csv, os, select
from collections import defaultdict
from selenium.webdriver.support.select import Select
from selenium.webdriver.support.ui import WebDriverWait


#make this test search through devices in the array & have the scan array link up to the device array

class SwitchCanvases(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        print "The Switching Between Canvases Test will now begin."
        print "This test will not include C123364 since that test case was not yet been implemented when this test was written. It will test C11546."
        time.sleep(5)
        driver.find_element_by_link_text("Node Selection").click()
        time.sleep(5)
        if ("[Current] SiteGate") not in self.config.driver.page_source:
            print "The SiteGate node is not displaying on the Node Selection Canvas. The Node Selection Page may be blank."
        else:
            print "The Node Selection page is not blank."
        driver.find_element_by_link_text("Alarms").click()
        time.sleep(4)
        if ("Device Alarms" and "Active Alarms" and "Alarm History" and "All Alarms" and "Ignored Alarms" and "Custom") not in self.config.driver.page_source:
            print "Default text is not displaying. The Alarms Canvas may be blank."
        else:
            print "The Alarms Canvas is not blank."
        driver.find_element_by_link_text("Device Info").click()
        time.sleep(4)
        if ("Class" and "Type" and "Primary IP Address") not in self.config.driver.page_source:
            print "Default text is not displaying. The Device Info Canvas may be blank."
        else:
            print "The Device Info Canvas is not blank."
        driver.find_element_by_link_text("WAN Settings").click()
        time.sleep(4)
        if ("MAC Address" and "Link Status" and "Config Method" and "IP Address" and "Netmask" and "Gateway" and "DNS Server 1" and "DNS Server 2") not in self.config.driver.page_source:
            print "Default text is not displaying. The WAN Settings Canvas may be blank."
        else:
            print "The WAN Settings Canvas is not blank."
        driver.find_element_by_link_text("LAN Settings").click()
        time.sleep(4)
        if ("LAN Port Settings (Device Ports)" and "Local IP Address" and "Subnet Mask" and "MGMT Port Settings (Management Port)" and "IP Address" and "Subnet Mask") not in self.config.driver.page_source:
            print "Default text is not displaying. The LAN Settings Canvas may be blank."
        else:
            print "The LAN Settings Canvas page is not blank."
        driver.find_element_by_link_text("System Settings").click()
        time.sleep(4)
        if ("System Information" and "Disk Usage" and "Memory Usage" and "CPU Usage" and "SiteGate ID") not in self.config.driver.page_source:
            print "Default text is not displaying. The System Settings Canvas may be blank."
        else:
            print "The System Settings Canvas is not blank."
        print "The Switching Between Canvases Test is now complete."
        "Later implement the loading symbol but since it does not exist yet we cannot."


if __name__ == "__main__":
    SwitchCanvases.config = selenium_config.default_config()
    unittest.main()
