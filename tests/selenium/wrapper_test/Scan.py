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

class ScanWrapper(c2_test_case.C2TestCase):
    def test_device_info(self):
        driver = self.config.driver
        print "The Scan Wrapper Test will now begin. This will test C11527, C11528, C11529, C11530, C11531, C11534, C11535, C11536 and C134143."
        SiteGatePath = "//network-tree/div/ul/li/div"
        ScanAlarmsPath = "//div[@id='networkExplorer']/div/button[2]"
        ScanPropPath = "//div[@id='networkExplorer']/div/button[3]"
        AddDevicePath = "//div[@id='networkExplorer']/div/button"
        DeviceCancelBuildPath = "//div[@id='buildDeviceWindowContent']/div/form/div[3]/div[3]"
        DeviceInfoPath = "//li[@id='menuItem2']/div/a"
        FirstDevicePath = "//li/ul/li/div"
        DeviceType = "//div[@id='mainPanelView']/div/form/div[2]/div/div/div[2]/div"
        CreateDevicePath = "//div[@id='buildDeviceWindowContent']/div/form/div[3]/div"
        IPAddress = "166.158.9.82"
        ReadCommunity = "owner"
        WriteCommunity = "Apwd4own"
        ReadString = "public"
        WriteString = "public"
        HTTPPort = "8201"
        SNMPPort = "8261"
        PropScanSuccess = "No"
        time.sleep(5)
        """
        Element = driver.find_element_by_xpath(SiteGatePath)
        time.sleep(5)
        Element2 = driver.find_element_by_xpath(FirstDevicePath)
        time.sleep(5)
        print Element2.text
        print Element.text
        """
        time.sleep(5)
        driver.find_element_by_xpath(SiteGatePath).click()
        time.sleep(5)
        driver.find_element_by_xpath(DeviceInfoPath).click()

        try: #checks to see if SiteGate has an alarm scanner
            driver.find_element_by_xpath(ScanAlarmsPath).click()
            print "The SiteGate Node can be scanned for alarms - C11528 fails"
            time.sleep(50)
            driver.find_element_by_css_selector(".close-scan-device-button").click()
        except Exception:
            print "The SiteGate Node cannot be scanned for alarms - C11528 passes"
        time.sleep(3)
        try: #checks to see if SiteGate has a property scanner
            driver.find_element_by_xpath(ScanPropPath).click()
            print "The SiteGate Node can be scanned for properties - C11531 fails"
            time.sleep(50)
            driver.find_element_by_css_selector(".close-scan-device-button").click()
        except Exception:
            print "The SiteGate Node cannot be scanned for properties- C11531 passes"
        time.sleep(3)
        count4 = -1
        while(count4<25): #will check alarms scan
            try:
                count4 += 1
                newcount = (count4 + 1)
                DevicePath = "//li/ul/li[%d" % newcount
                DevicePath = "%s]/div" % DevicePath
                driver.find_element_by_xpath(DevicePath).click()
                time.sleep(5)
                count = 0
                if "Scan Alarms" not in self.config.driver.page_source:
                    print "The Scan Alarms dialog is not available for the first device in the network tree. C11527 fails."
                else:
                    driver.find_element_by_xpath(ScanAlarmsPath).click()
                    while(count < 50): #runs until time out or the device is successfully scanned
                        if str("Scan Completed Successfully!") in self.config.driver.page_source:
                            driver.find_element_by_css_selector(".close-scan-device-button").click() #closes window
                            count4 = 25
                            print "The device's alarms have been scanned successfully. C11536, C11527, C11535 and C11534"
                            break
                        else:
                            count += 1
                            print "The device is not done scanning. Will check again in 5 seconds." #continually checks until done
                            time.sleep(5)
                if count == 50:
                    print "The device is taking too long to scan or has failed."
                    time.sleep(3)
                    try:
                        driver.find_element_by_css_selector("div.cancel-scan-device-button").click()
                        time.sleep(5)
                    except Exception:
                        print "The device did not scan alarms in an appropriate amount of time."
            except Exception:
                print "The device failed to scan; will try the next device."
        time.sleep(5)
        count4 = -1
        while (count4<25): #will check property scan
            try: #tries this for each device in the list
                count4 += 1
                newcount = (count4 + 1)
                DevicePath = "//li/ul/li[%d" % newcount
                DevicePath = "%s]/div" % DevicePath
                driver.find_element_by_xpath(DevicePath).click()
                time.sleep(5)
                if "Properties" not in self.config.driver.page_source:
                    print "The Properties Alarms dialog is not available for this device."
                else:
                    driver.find_element_by_xpath(ScanPropPath).click() #checks if property scanner exists & clicks on it if it does
                    count = 0
                while(count < 50): #does the same as the scanner
                    if str("Scan Completed Successfully!") in self.config.driver.page_source: #checks if string is in page and if so runs below code
                        driver.find_element_by_css_selector(".close-scan-device-button").click()
                        print "The device's properties have been scanned successfully. (C11530 and C11529 pass)"
                        count4 = 25
                        break
                    else:
                        count += 1
                        print "The device is not done scanning. Will check again in 5 seconds."
                        time.sleep(15)
                if count == 50:
                    print "The device is taking too long to scan."
                    PropScanStatus = "Fail"
                    try:
                        driver.find_element_by_css_selector("div.cancel-scan-device-button").click()
                    except Exception:
                        print "Next device will scan"
                    time.sleep(5)
            except Exception:
                print("This device has no Properties Scan.")
        BuildTSUN = "Yes"
        count1 = 0
        while(count1<25):
            count1+=1
            DevicePath = "//li/ul/li[%d" % count1
            DevicePath = "%s]/div" % DevicePath
            TreeElement = driver.find_element_by_xpath(DevicePath)
            TreeElementText = TreeElement.text
            print TreeElementText
            if TreeElementText == "AUTO - TSUN4":
                count1 = 25
                count3 = 0
                while (count3<25):
                    driver.find_element_by_xpath(DeviceInfoPath).click()
                    time.sleep(5)
                    newcount = (count3 + 1)
                    count3 +=1
                    DevicePath = "//li/ul/li[%d" % newcount
                    DevicePath = "%s]/div" % DevicePath
                    driver.find_element_by_xpath(DevicePath).click()
                    time.sleep(5)
                    DeviceTypeElement = driver.find_element_by_xpath(DeviceType) #G
                    DeviceTypeText = DeviceTypeElement.text
                    print "The device type is %s" % DeviceTypeText
                    if DeviceTypeText == "Andrew TSUN4":
                        driver.find_element_by_xpath(ScanAlarmsPath).click()
                        time.sleep(10)
                        while(count4 < 50): #runs until time out or the device is successfully scanned
                            if str("Scan Completed Successfully!") in self.config.driver.page_source:
                                driver.find_element_by_css_selector(".close-scan-device-button").click() #closes window
                                count3 = 25
                                BuildTSUN = "No"
                                print "The device's alarms have been scanned successfully. C134143 passes."
                                break
                            else:
                                count4 += 1
                                print "The device is not done scanning. Will check again in 5 seconds." #continually checks until done
                                time.sleep(5)
                            if count4 == 50:
                                print "The device is taking too long to scan or has failed. C134143 fails."
                                time.sleep(3)
                                try:
                                    driver.find_element_by_css_selector("div.cancel-scan-device-button").click()
                                    time.sleep(5)
                                except Exception:
                                    print "The device did not scan alarms in an appropriate amount of time."
            print BuildTSUN
        if count1 == 25:
            BuildTSUN = "Yes"
        if BuildTSUN == "Yes":
            driver.find_element_by_xpath(SiteGatePath).click()
            time.sleep(5)
            driver.find_element_by_xpath(AddDevicePath).click()
            time.sleep(15)
            el = driver.find_element_by_id("deviceType")
            for option in el.find_elements_by_tag_name('option'): #dropdown
                if option.text == "Andrew ION-B/TSUN (auto-detect)":
                    option.click()
                    break
            driver.find_element_by_id("primaryIpAddress").click()
            driver.find_element_by_id("primaryIpAddress").clear()
            driver.find_element_by_id("primaryIpAddress").send_keys(IPAddress)
            driver.find_element_by_id("webUsername").click()
            driver.find_element_by_id("webUsername").clear()
            driver.find_element_by_id("webUsername").send_keys(ReadCommunity)
            driver.find_element_by_id("webPassword").click()
            driver.find_element_by_id("webPassword").clear()
            driver.find_element_by_id("webPassword").send_keys(WriteCommunity)
            driver.find_element_by_id("snmpRead").click()
            driver.find_element_by_id("snmpRead").clear()
            driver.find_element_by_id("snmpRead").send_keys(ReadString)
            driver.find_element_by_id("snmpWrite").click()
            driver.find_element_by_id("snmpWrite").clear()
            driver.find_element_by_id("snmpWrite").send_keys(WriteString)
            driver.find_element_by_name("devicePort_91").click()
            driver.find_element_by_name("devicePort_91").clear()
            driver.find_element_by_name("devicePort_91").send_keys(HTTPPort)
            driver.find_element_by_name("devicePort_92").click()
            driver.find_element_by_name("devicePort_92").clear()
            driver.find_element_by_name("devicePort_92").send_keys(SNMPPort)
            time.sleep(3)
            driver.find_element_by_xpath(CreateDevicePath).click()
            time.sleep(15)
            count4 = 0
            BuildStatus = ""
            while(count4 < 40):
                if str("Build Completed Successfully!") in self.config.driver.page_source:
                    driver.find_element_by_css_selector(".close-build-device-button").click()
                    time.sleep(5)
                    driver.find_element_by_xpath(ScanAlarmsPath).click()
                    count4 = 0
                    BuildStatus = "Yes"
                    break
                else:
                    count4 += 1
                    print "The device is not done building. Will check again in 5 seconds."
                    time.sleep(5)
                if count4 == 40:
                    print "The Andrew TSUN4 device failed to build therefore the scan test cannot be completed at this time."
                    driver.find_element_by_xpath(DeviceCancelBuildPath).click()
            if BuildStatus == "Yes":
                count3 = 0
                while (count3<25):
                    driver.find_element_by_xpath(DeviceInfoPath).click()
                    time.sleep(5)
                    newcount = (count3 + 1)
                    DevicePath = "//li/ul/li[%d" % newcount
                    DevicePath = "%s]/div" % DevicePath
                    count3 +=1
                    driver.find_element_by_xpath(DevicePath).click()
                    time.sleep(5)
                    DeviceTypeElement = driver.find_element_by_xpath(DeviceType) #G
                    DeviceTypeText = DeviceTypeElement.text
                    print "The device type is %s" % DeviceTypeText
                    print "This is working"
                    if DeviceType == "Andrew TSUN4":
                        driver.find_element_by_xpath(ScanAlarmsPath).click()
                        time.sleep(10)
                        while(count4 < 50): #runs until time out or the device is successfully scanned
                            if str("Scan Completed Successfully!") in self.config.driver.page_source:
                                driver.find_element_by_css_selector(".close-scan-device-button").click() #closes window
                                print "The device's alarms have been scanned successfully. C134143 passes."
                                count3 = 25
                                break
                            else:
                                count4 += 1
                                print "The device is not done scanning. Will check again in 5 seconds." #continually checks until done
                                time.sleep(5)
                            if count4 == 50:
                                print "The device is taking too long to scan or has failed. C134143 fails."
                                time.sleep(3)
                                try:
                                    driver.find_element_by_css_selector("div.cancel-scan-device-button").click()
                                    time.sleep(5)
                                except Exception:
                                    print "The device did not scan alarms in an appropriate amount of time. C134143 fails."

        print "The Scan Wrapper Test is now complete."
        """
        Test cases to implement:
        -SiteGate node should not have Scan Dialog *Done
        -Scan Alarms should open Dialog *Done
        -Scan Properties should open Scan Properties Dialog *Done
        -Scan Properties should only be available for devices w/ prop scanner...*put a check in to see about this
        -Andrew TSUN4 device -->use SiteGate devices spreadsheet

        """

if __name__ == "__main__":
    ScanWrapper.config = selenium_config.default_config()
    unittest.main()

