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
import unittest, time, re, subprocess, csv, os, select, time
from collections import defaultdict
from selenium.webdriver.support.select import Select
from selenium.webdriver.support.ui import WebDriverWait
from datetime import datetime

class AddDeviceTestDevice(c2_test_case.C2TestCase):
    def test_add_device_test_device_emf(self):
        columns = defaultdict(list)
        with open('SiteGateDevices.csv') as f: #opens text file and reads and labels/numbers the rows
            reader = csv.DictReader(f)
            for row in reader:
                for (k, v) in row.items():
                    columns[k].append(v)
            RowAmount = len(open('SiteGateDevices.csv').readlines()) #counts the rows
            print "This is the row amount: %d" % RowAmount #pritns row amount\
        Build = [] #for success/fail of build
        for i in range(RowAmount-1):
            Build.append("UnBuilt")
        time.sleep(2)
        driver = self.config.driver
        yesorno = raw_input("Would you like to build a certain amount of devices? Y/N: ")
        if yesorno == 'Y':
            RowAmount = raw_input("Please print the number of devices you would like to build: ")
            RowAmount = int(RowAmount)
        #print "This is build at line 38 %s" % Build
        print "The build devices test will now begin."
        OneElement = "tr.ng-scope > td:nth-child(2) > div:nth-child(1) > input:nth-child(1)"
        FirstElement = "tr.ng-scope:nth-child(2) > td:nth-child(2) > div:nth-child(1) > input:nth-child(1)"
        SecondElement = "tr.ng-scope:nth-child(3) > td:nth-child(2) > div:nth-child(1) > input:nth-child(1)"
        ThirdElement = "tr.ng-scope:nth-child(4) > td:nth-child(2) > div:nth-child(1) > input:nth-child(1)"
        FourthElement = "tr.ng-scope:nth-child(5) > td:nth-child(2) > div:nth-child(1) > input:nth-child(1)"
        FifthElement = "tr.ng-scope:nth-child(6) > td:nth-child(2) > div:nth-child(1) > input:nth-child(1)"
        DeviceType = "//div[@id='mainPanelView']/div/form/div[2]/div/div/div[2]/div"
        AddDevicePath = "//div[@id='networkExplorer']/div/button"
        BuildStatus = ""
        time.sleep(6)
        for count in range(0, RowAmount): #goes from 0 to total cells
            DeviceName = columns['Device Type'][count]
            DeviceValue = columns['id'][count]
            ReadCommunity = columns['Read Community'][count]
            WriteCommunity = columns['Write Community'][count]
            HTTPPort = columns['HTTP Port'][count]
            SNMPPort = columns['SNMP Port'][count]
            HTTPSPort = columns['HTTPS Port'][count]
            TelnetPort = columns['Telnet Port'][count]
            SSHPort = columns['SSH Port'][count]
            print "The build test is currently at: %s" % DeviceName
            DeviceTypeName = ""
            try:
                if DeviceName == '' and DeviceValue == '': #checks if device doesn't have credentials in excel file
                    print "The device on row %d does not have necessary credentials" % count
                else: #runs for every other device
                    time.sleep(5)
                    if count>0:
                        driver.find_element_by_xpath("//network-tree/div/ul/li/div").click()
                    driver.find_element_by_xpath(AddDevicePath).click()
                    time.sleep(15)
                    el = driver.find_element_by_id("deviceType")
                    if (DeviceName == 'ADRF 25K' or DeviceName == 'ADRF 25K_S1' or DeviceName == 'ADRF ADX'):
                        DeviceTypeName = 'ADRF (auto-detect)'
                    elif DeviceName == 'Axell MINI':
                        DeviceTypeName = 'Axell (Auto-Builder)'
                    elif (DeviceName == 'ADRF AXM ICS 700' or DeviceName == 'ADRF AXM2100' or DeviceName == 'ADRF AXM700'):
                        DeviceTypeName = 'ADRF AXM(auto detect)'
                    elif (DeviceName == 'CradlePoint IBR/PHS' or DeviceName == 'CradlePoint IBR600' or DeviceName == 'CradlePoint IBR650E'):
                        DeviceTypeName = 'Cradlepoint (auto-detect)'
                    elif (DeviceName == 'CSI CSI-DSP85' or DeviceName == 'CSI CSI-DSP85-251'):
                        DeviceTypeName = 'CSI CSI-DSP(auto-detect)'
                    else:
                        DeviceTypeName = DeviceName
                    for option in el.find_elements_by_tag_name('option'): #dropdown
                        if option.text == DeviceTypeName:
                            option.click()
                            break
                    time.sleep(15)
                    DisabledDeviceName = driver.find_element_by_id("deviceName")
                    try:
                        driver.find_element_by_id("deviceName").clear()
                        driver.find_element_by_id("deviceName").click()
                        driver.find_element_by_id("deviceName").send_keys(columns['Device Type'][count])
                    except Exception:
                        print "Device doesn't have a device name input."
                    driver.find_element_by_id("primaryIpAddress").click()
                    driver.find_element_by_id("primaryIpAddress").clear()
                    driver.find_element_by_id("primaryIpAddress").send_keys(columns['IP Address'][count])
                    driver.find_element_by_id("webUsername").click()
                    driver.find_element_by_id("webUsername").clear()
                    driver.find_element_by_id("webUsername").send_keys(columns['Username'][count])
                    driver.find_element_by_id("webPassword").click()
                    driver.find_element_by_id("webPassword").clear()
                    driver.find_element_by_id("webPassword").send_keys(columns['Password'][count])
                    #try and use tries instead???
                    if ReadCommunity != '':
                        driver.find_element_by_id("snmpRead").click()
                        driver.find_element_by_id("snmpRead").clear()
                        driver.find_element_by_id("snmpRead").send_keys(ReadCommunity)
                    elif WriteCommunity != '':
                        driver.find_element_by_id("snmpWrite").click()
                        driver.find_element_by_id("snmpWrite").clear()
                        driver.find_element_by_id("snmpWrite").send_keys(WriteCommunity)
                    elif (HTTPPort != '' and SNMPPort == '' and HTTPSPort == '' and TelnetPort == '' and SSHPort == ''): #http
                        driver.find_element_by_css_selector(OneElement).click()
                        driver.find_element_by_css_selector(OneElement).clear()
                        driver.find_element_by_css_selector(OneElement).send_keys(HTTPPort)
                    elif (HTTPPort == '' and SNMPPort == '' and HTTPSPort != '' and TelnetPort == '' and SSHPort == ''): #https
                        driver.find_element_by_css_selector(OneElement).click()
                        driver.find_element_by_css_selector(OneElement).clear()
                        driver.find_element_by_css_selector(OneElement).send_keys(HTTPSPort)
                    elif (HTTPPort == '' and SNMPPort == '' and HTTPSPort == '' and TelnetPort != '' and SSHPort == ''): #telnet
                        driver.find_element_by_css_selector(OneElement).click()
                        driver.find_element_by_css_selector(OneElement).clear()
                        driver.find_element_by_css_selector(OneElement).send_keys(TelnetPort)
                    elif (HTTPPort == '' and SNMPPort != '' and HTTPSPort == '' and TelnetPort == '' and SSHPort == ''): #snmp
                        driver.find_element_by_css_selector(OneElement).click()
                        driver.find_element_by_css_selector(OneElement).clear()
                        driver.find_element_by_css_selector(OneElement).send_keys(SNMPPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort == '' and TelnetPort == '' and SSHPort == ''): #http&snmp
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(SNMPPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort == ''and TelnetPort == '' and SSHPort != ''): #http&snmp&ssh
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(SSHPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(SNMPPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort != '' and TelnetPort == '' and SSHPort == ''): #http&snmp&https
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(SNMPPort)
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(HTTPSPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort != '' and TelnetPort != '' and SSHPort == ''): #telnet,http,snmp,https
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(TelnetPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(SNMPPort)
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(HTTPSPort)
                    elif (HTTPPort != '' and SNMPPort == '' and HTTPSPort == '' and TelnetPort != '' and SSHPort == ''): #telnet&http
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(TelnetPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(HTTPPort)
                    elif (HTTPPort != '' and SNMPPort == '' and HTTPSPort != '' and TelnetPort == '' and SSHPort == ''): #http&https
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(TelnetPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(HTTPPort)
                    elif (HTTPPort != '' and SNMPPort == '' and HTTPSPort == '' and TelnetPort != '' and SSHPort == ''): #http&telnet
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(TelnetPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort == '' and TelnetPort != '' and SSHPort == ''): #telnet,http,snmp
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(TelnetPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(SNMPPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort != '' and TelnetPort == '' and SSHPort == ''): #http,snmp,https
                        driver.find_element_by_css_selector(FirstElement).click()
                        driver.find_element_by_css_selector(FirstElement).clear()
                        driver.find_element_by_css_selector(FirstElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(SecondElement).click()
                        driver.find_element_by_css_selector(SecondElement).clear()
                        driver.find_element_by_css_selector(SecondElement).send_keys(SNMPPort)
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(HTTPSPort)
                    elif (HTTPPort != '' and SNMPPort != '' and HTTPSPort != '' and  TelnetPort == '' and SSHPort == ''): #http,snmp,https
                        driver.find_element_by_css_selector(ThirdElement).click()
                        driver.find_element_by_css_selector(ThirdElement).clear()
                        driver.find_element_by_css_selector(ThirdElement).send_keys(HTTPPort)
                        driver.find_element_by_css_selector(FourthElement).click()
                        driver.find_element_by_css_selector(FourthElement).clear()
                        driver.find_element_by_css_selector(FourthElement).send_keys(SNMPPort)
                        driver.find_element_by_css_selector(FifthElement).click()
                        driver.find_element_by_css_selector(FifthElement).clear()
                        driver.find_element_by_css_selector(FifthElement).send_keys(HTTPSPort)
                    driver.find_element_by_css_selector("div.ng-binding").click()
                    time.sleep(10)
                    count2 = 0
                    while(count2 < 40):
                        if str("Build Completed Successfully!") in self.config.driver.page_source:
                            driver.find_element_by_css_selector(".close-build-device-button").click()
                            BuildStatus = "Successfully Built"
                            time.sleep(5)
                            break
                    #elif str("") put in elif about if the build failed
                        else:
                            count2 += 1
                            print "The device is not done building. Will check again in 5 seconds."
                            time.sleep(5)
                    if count2 == 40:
                        print "The device failed to build from the while loop."
                        BuildStatus = "Failure to Build"
                    print BuildStatus
            except Exception:
                print "The device failed to build"
            for number in range(0,(RowAmount-1)): #loop to sync up device type & set scan and prop statuses to the device arrays in excel
                if columns['Device Type'][number] == DeviceName:
                    Build[number] = BuildStatus
                    print "This is build at this point: " % Build
                    break
        #to create new Excel file for build:
        with open('SiteGateDevices.csv') as f: #opens text file and reads and labels/numbers the rows
            reader = csv.DictReader(f)
            for row in reader:
                for (k, v) in row.items():
                    columns[k].append(v)
            RowAmount = len(open('SiteGateDevices.csv').readlines()) #counts the rows
            print "This is the row amount: %d" % RowAmount #row amount
            with open('BuildStatus.csv', 'wb') as w:
                fieldnames = ['Device Type', 'Build']
                writer = csv.DictWriter(w, fieldnames=fieldnames)
                writer.writeheader()
                count1 = 0
                for count1 in range(0, (RowAmount-1)):
                    writer.writerow({'Device Type' : (columns['Device Type'][count1]), 'Build' : (Build[count1])})
        print "The build devices test is now complete."

if __name__ == "__main__":
    AddDeviceTestDevice.config = selenium_config.default_config()
    unittest.main()
