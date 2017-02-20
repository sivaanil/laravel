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

class BreadCrumb(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        DeviceInfo = "//li[@id='menuItem2']/div/a"
        DeviceType = "//div[@id='mainPanelView']/div/form/div[2]/div/div/div[2]/div"
        BreadCrumbCSS = "#lastCrumb > span.crumbText"
        SiteGatePath = "//network-tree/div/ul/li/div"
        SiteGateBreadCrumb = "span.crumbText"
        print "The Breadcrumb Test will now begin. This will test C10235 and C10236."
        time.sleep(5)
        driver.find_element_by_xpath(SiteGatePath).click()
        time.sleep(2)
        driver.find_element_by_xpath(DeviceInfo).click()
        time.sleep(5)
        DeviceTypeElement = driver.find_element_by_xpath(DeviceType) #G
        DeviceTypeText = DeviceTypeElement.text
        driver.find_element_by_css_selector(SiteGateBreadCrumb).click()
        time.sleep(5)
        BreadCrumbDeviceTypeElement = driver.find_element_by_xpath(DeviceType)
        BreadCrumbDeviceText = BreadCrumbDeviceTypeElement.text
        if DeviceTypeText != BreadCrumbDeviceText:
            print "The SiteGate breadcrumb did not go to the correct location."
        else:
            print "The SiteGate breadcrumb is working as expected."
        count = 0
        while (count<10): #will run as long as count is less than 10
            try:
                count += 1
                print count
                time.sleep(3)
                #DevicePath = "//li/ul/li[%d" % count #device path
                #DevicePath = "%s]/div" % DevicePath #adds the number in the list of the count/where the device is
                #driver.find_element_by_xpath(DevicePath).click()
                time.sleep(5)
                try:
                    if count == 1:
                        driver.find_element_by_xpath("//li/ul/li/span").click() #link to first device
                        time.sleep(2)
                        #SubDevicePath = "//li[%d" % (count+1)
                        #SubDevicePath = "%s]/ul/li/div" % SubDevicePath
                        driver.find_element_by_xpath("//li/ul/li/ul/li/div").click() #link to first subdevice
                        count = 10 #makes count equal to 10 if the device was clicked
                        time.sleep(5)
                    else:
                        DeviceArrowPath = "//li[%d" % count
                        DeviceArrowPath = "%s]/span" % DeviceArrowPath
                        driver.find_element_by_xpath(DeviceArrowPath).click()
                        time.sleep(3)
                        SubDevicePath = "//li[%d" % count
                        SubDevicePath = "%s]/ul/li/div" % SubDevicePath
                        driver.find_element_by_xpath(SubDevicePath).click() #link to first subdevice
                        #INSERT SUBDEVICESTUFF HERE
                        count = 10
                        time.sleep(5)
                except Exception:
                    print "This device does not have any sub-devices. The test will keep running until it finds a device with subdevices."
            except Exception:
                print "There are no devices built therefore this test can only test the root node BreadCrumb."
                break
            try:
                driver = self.config.driver
                driver.find_element_by_xpath(DeviceInfo).click()
                time.sleep(5)
                DeviceTypeElement = driver.find_element_by_xpath(DeviceType) #G
                DeviceTypeText = DeviceTypeElement.text
                driver.find_element_by_css_selector(BreadCrumbCSS).click()
                time.sleep(5)
                BreadCrumbDeviceTypeElement = driver.find_element_by_xpath(DeviceType)
                BreadCrumbDeviceText = BreadCrumbDeviceTypeElement.text
                if DeviceTypeText != BreadCrumbDeviceText:
                    print "The breadcrumb did not go to the correct location."
                else:
                    print "The breadcrumb is working as expected."
            except Exception:
                print "The breadcrumb test did not complete."
        print "The Bread Crumb Test is now complete."

if __name__ == "__main__":
    BreadCrumb.config = selenium_config.default_config()
    unittest.main()
