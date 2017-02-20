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

class ScanDevice(c2_test_case.C2TestCase):
    def test_scan_device_test_device_emf(self):
        columns = defaultdict(list)
        with open('SiteGateDevices.csv') as f: #opens text file and reads and labels/numbers the rows
            reader = csv.DictReader(f)
            for row in reader:
                for (k, v) in row.items():
                    columns[k].append(v)
        RowAmount = len(open('SiteGateDevices.csv').readlines()) #counts the rows
        driver = self.config.driver
        time.sleep(7)
        DeviceInfo = "//li[@id='menuItem2']/div/a"
        ScanAlarmsPath = "//div[@id='networkExplorer']/div/button[2]"
        ScanPropPath = "//div[@id='networkExplorer']/div/button[3]"
        DeviceType = "//div[@id='mainPanelView']/div/form/div[2]/div/div/div[2]/div"
        driver.find_element_by_xpath(DeviceInfo).click() #will open device info canvas
        ScanStatus = ["Scan Status"]
        PropScanStatus = ["Prop Scan Status"]
        print "The scan all devices test will now begin."
        time.sleep(5)
        Scan = [] #creates array for Scan that will have UnScanned initial values
        for i in range(RowAmount-1): #appends unscanned for the amount of rows found in the file
            Scan.append("UnScanned")
        PropScan = [] #creates array for Prop Scan that will have UnScanned/no Prop Scanner as intitial values
        for l in range(RowAmount-1):
            PropScan.append("UnScanned/no Prop Scanner")
        for count in range(0, (RowAmount-1)): #for loop that will run through every device/scan each one
            driver = self.config.driver
            time.sleep(6)
            #if "Scan Device" in self.config.driver.page_source:
            try: #tries this for each device in the list
                newcount = (count + 1)
                DevicePath = "//li/ul/li[%d" % newcount
                DevicePath = "%s]/div" % DevicePath
                driver.find_element_by_xpath(DevicePath).click()
                time.sleep(5)
                DeviceTypeElement = driver.find_element_by_xpath(DeviceType) #G
                DeviceTypeText = DeviceTypeElement.text
                print "The device type is %s" % DeviceTypeText
                print "This is working"
            except Exception:
                print("All the devices have been scanned.")
                break
            time.sleep(7)
            driver.find_element_by_xpath(ScanAlarmsPath).click() #opens alarm scanner
            count2 = 0
            while(count2 < 50): #runs until time out or the device is successfully scanned
                if str("Scan Completed Successfully!") in self.config.driver.page_source:
                    driver.find_element_by_css_selector(".close-scan-device-button").click() #closes window
                    ScanStatus = "Success"
                    print "The device's alarms have been scanned successfully."
                    break
                else:
                    count2 += 1
                    print "The device is not done scanning. Will check again in 5 seconds." #continually checks until done
                    time.sleep(5)
            if count2 == 50:
                    print "The device is taking too long to scan or has failed."
                    ScanStatus = "Fail"
                    time.sleep(3)
                    try:
                        driver.find_element_by_css_selector("div.cancel-scan-device-button").click()
                        time.sleep(5)
                    except Exception:
                        print "Next device will scan"
            time.sleep(4)
            try:
                driver.find_element_by_xpath(ScanPropPath).click() #checks if property scanner exists & clicks on it if it does
                while(count2 < 50): #does the same as the scanner
                    if str("Scan Completed Successfully!") in self.config.driver.page_source: #checks if string is in page and if so runs below code
                        driver.find_element_by_css_selector(".close-scan-device-button").click()
                        PropScanStatus = "Success"
                        print "The device's properties have been scanned successfully."
                        break
                    else:
                        count2 += 1
                        print "The device is not done scanning. Will check again in 5 seconds."
                        time.sleep(5)
                if count2 == 50:
                    print "The device is taking too long to scan."
                    PropScanStatus = "Fail"
                    try:
                        driver.find_element_by_css_selector("div.cancel-scan-device-button").click()
                    except Exception:
                        print "Next device will scan"
                    time.sleep(5)
            except Exception:
                print "There is no property scanner for this device." #will show when there's no property scanner
                PropScanStatus = "No Property Scanner"
            print "The scan status is: %s" % ScanStatus
            print "The property scan status is: %s" % PropScanStatus
            for number in range(0,(RowAmount-1)): #loop to sync up device type & set scan and prop statuses to the device arrays in excel
                if columns['Device Type'][number] == DeviceTypeText:
                    Scan[number] = ScanStatus
                    PropScan[number] = PropScanStatus
                    break
        with open('SiteGateDevices.csv') as f: #opens text file and reads and labels/numbers the rows
            reader = csv.DictReader(f)
            for row in reader:
                for (k, v) in row.items():
                    columns[k].append(v)
            RowAmount = len(open('SiteGateDevices.csv').readlines()) #counts the rows
            print "This is the row amount: %d" % RowAmount #row amount
            print "These are the Device Types: %s" % columns['Device Type']
            with open('ScanStatus.csv', 'wb') as w:
                fieldnames = ['Device Type', 'Alarm Scan', 'Property Scan']
                writer = csv.DictWriter(w, fieldnames=fieldnames)
                writer.writeheader()
                count1 = 0
                for count1 in range(0, (RowAmount-1)):
                    writer.writerow({'Device Type' : (columns['Device Type'][count1]), 'Alarm Scan' : (Scan[count1]), 'Property Scan' : (PropScan[count1])})
        print "The scan devices test is now complete."

if __name__ == "__main__":
    ScanDevice.config = selenium_config.default_config()
    unittest.main()
