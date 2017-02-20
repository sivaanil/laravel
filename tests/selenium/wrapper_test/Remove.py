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

class RemoveWrapper(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        SiteGatePath = "//network-tree/div/ul/li/div"
        RemoveDevice = "//div[@id='networkExplorer']/div/button[4]"
        AddDevicePath = "//div[@id='networkExplorer']/div/button"
        FirstDevicePath = "//li/ul/li/div"
        DeviceInfoPath = "//li[@id='menuItem2']/div/a"
        CreateDevice = "div.ng-binding"
        RemoveArray = ["Alcatel-Lucent 7750 Trap Handler","Solid DMS Rel6 Trap Receiver"]
        DeviceName = "1"
        IPAddress = "111.111.111.111"
        RemoveTest = ""
        print "The Remove Wrapper Test will now begin. This will test C11532 and C11533."
        time.sleep(5)
        driver.find_element_by_xpath(SiteGatePath).click()
        time.sleep(5)
        try:
            driver.find_element_by_xpath(RemoveDevice).click()
            driver.find_element_by_id("remove-device-no").click()
            print "The SiteGate Node can be deleted - C11533"
        except Exception:
            print "The SiteGate Node cannot be deleted - C11533 passes"
        #The following is for C11532 - Not quite sure if I can get it to pass
        count3 = 0
        while (count3<25): #will delete all devices called 1
            Element = driver.find_element_by_xpath(FirstDevicePath)
            time.sleep(5)
            ElementText = Element.text
            print ElementText
            if ElementText == "1": #checks if there are any devices that would be in beginning
                driver.find_element_by_xpath(FirstDevicePath).click()
                time.sleep(5)
                driver.find_element_by_xpath(RemoveDevice).click()
                time.sleep(1)
                driver.find_element_by_id("remove-device-yes").click()
                time.sleep(6)
                count3 += 1
            else:
                count3 = 25
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
                        RemoveTest = "Yes"
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
        if RemoveTest == "Yes":
                try: #tries this for each device in the list
                    driver.find_element_by_xpath(FirstDevicePath).click()
                    time.sleep(6)
                    Element = driver.find_element_by_xpath(FirstDevicePath)
                    time.sleep(7)
                    ElementText = Element.text
                    print ElementText
                    if ElementText == "1":
                        driver.find_element_by_xpath(RemoveDevice).click()
                        time.sleep(1)
                        driver.find_element_by_id("remove-device-yes").click()
                        time.sleep(10)
                        count2 = 25
                except Exception:
                    print "Did not delete"
        else:
            print "No device was built therefore we cannot know which device to delete."
        Element2 = driver.find_element_by_xpath(FirstDevicePath)
        if Element2.text == "1":
            print "The device was not removed. This test failed."


        print "The Remove Wrapper Test is now complete."
        """
        Test cases to be implemented w/ this test:
        -Remove Device Button should remove device from Network Tree *** How...? :: Put in a few trap devs
        -Remove Device button should not be available for SiteGate Node
        """
if __name__ == "__main__":
    RemoveWrapper.config = selenium_config.default_config()
    unittest.main()
