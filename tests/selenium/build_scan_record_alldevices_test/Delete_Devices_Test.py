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


class DeleteDeviceTestDevice(c2_test_case.C2TestCase):
    def test_delete_devices_emf(self):
        count = 0
        for count in range(count, 100):
            driver = self.config.driver
            try:
                time.sleep(3)
                driver.find_element_by_xpath("//li/ul/li/div").click()
                print "Device #%d is being deleted." % count
                time.sleep(5)
                driver.find_element_by_xpath("//div[@id='networkExplorer']/div/button[4]").click()
                driver.find_element_by_id("remove-device-yes").click()
                time.sleep(3)
            except Exception:
                print "All the devices have been successfully deleted."
                break

if __name__ == "__main__":
    DeleteDeviceTestDevice.config = selenium_config.default_config()
    unittest.main()