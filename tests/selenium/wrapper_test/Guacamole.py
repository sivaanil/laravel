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

class GuacWrapper(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        SiteGatePath = "//network-tree/div/ul/li/div"
        AddDevicePath = "//div[@id='networkExplorer']/div/button"
        DeviceInfoPath = "//li[@id='menuItem2']/div/a"
        LaunchWebInterfacePath = "//div[@id='networkExplorer']/div/button[5]"
        CreateDevice = "div.ng-binding"
        RemoveArray = ["Alcatel-Lucent 7750 Trap Handler","Solid DMS Rel6 Trap Receiver"]
        DeviceName = "guac"
        IPAddress = "111.111.111.111"
        FirstDevicePath = "//li/ul/li/div"
        WebInterface = "No"
        time.sleep(3)
        driver.find_element_by_xpath(FirstDevicePath).click()
        time.sleep(5)
        count = 0
        while (count < 2):
            try:
                driver.find_element_by_xpath(SiteGatePath).click()
                time.sleep(5)
                driver.find_element_by_xpath(AddDevicePath).click()
                time.sleep(15)
                el = driver.find_element_by_id("deviceType")
                for option in el.find_elements_by_tag_name('option'): #dropdown
                    if option.text == RemoveArray[count]:
                        option.click()
                        break
                time.sleep(15)
                count += 1
                driver.find_element_by_id("deviceName").clear()
                driver.find_element_by_id("deviceName").click()
                driver.find_element_by_id("deviceName").send_keys(DeviceName)
                driver.find_element_by_id("primaryIpAddress").click()
                driver.find_element_by_id("primaryIpAddress").clear()
                driver.find_element_by_id("primaryIpAddress").send_keys(IPAddress)
                driver.find_element_by_css_selector(CreateDevice).click()
                time.sleep(10)
                count2 = 0
                while(count2 < 40):
                    if str("Build Completed Successfully!") in self.config.driver.page_source:
                        driver.find_element_by_css_selector(".close-build-device-button").click()
                        count = 2
                        WebInterface = "Yes"
                        time.sleep(5)
                        break
                    #elif str("") put in elif about if the build failed
                    else:
                        count2 += 1
                        print "The device is not done building. Will check again in 5 seconds."
                        time.sleep(5)
                if count2 == 40:
                    print "The device failed to build from the while loop."
            except Exception:
                print "The first trap receiver failed."
        if count == 3:
            print "The delete a device test did not work as expected. Please test C11532 manually. There could be an issue with Trap Receivers."
        if WebInterface == "Yes":
                try: #tries this for each device in the list
                    driver.find_element_by_xpath(FirstDevicePath).click()
                    time.sleep(6)
                    Element = driver.find_element_by_xpath(FirstDevicePath)
                    time.sleep(7)
                    ElementText = Element.text
                    print ElementText
                    if ElementText == "guac":
                        driver.find_element_by_xpath(LaunchWebInterfacePath).click()
                        time.sleep(10)
                        count2 = 25
                except Exception:
                    print "Did not delete"

        """
        Test cases to implement:


        """

if __name__ == "__main__":
    GuacWrapper.config = selenium_config.default_config()
    unittest.main()
