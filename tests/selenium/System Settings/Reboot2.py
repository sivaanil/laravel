__author__ = 'emily.ford'

# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException
from selenium.common.exceptions import NoAlertPresentException
import unittest, time, re
import sys
sys.path.append('..')
import c2_test_case
import selenium_config


class RebootSystem(c2_test_case.C2TestCase):

    def test_deleteme(self):
        driver = self.config.driver
        RebootPath = "//div[@id='mainPanelView']/div[2]/form/div/div/div[2]/div/button"
        RebootConfirmPath = "//form/div/div[2]/div"
        RebootClosePath = "//form/div/div[2]/div[2]"
        print "The Reboot test will now begin."
        print "C11556 will now be tested."
        time.sleep(6)
        driver.find_element_by_link_text("System Settings").click()
        time.sleep(6)
        print "C11555 will now be tested."
        try:
            driver.find_element_by_xpath(RebootPath).click()
            print "Reboot dialog was opened. C11555 passes."
            time.sleep(3)
            driver.find_element_by_xpath(RebootClosePath).click()
        except Exception:
            print "Reboot dialog did not open. C11555 fails."
        driver.find_element_by_xpath(RebootPath).click()
        time.sleep(3)
        driver.find_element_by_xpath(RebootConfirmPath).click()
        self.assertEqual("Please type REBOOT in the box to confirm.", self.close_alert_and_get_its_text())
        time.sleep(2)
        if ("Are you sure you wish to reboot the SiteGate?" or "Type REBOOT to confirm") not in self.config.driver.page_source:
            print "The confirm message still displays. C11556 passes."
        else:
            print "SiteGate rebooted anyways w/out entering REBOOT. C11556 fails."
        driver.find_element_by_xpath(RebootClosePath).click()
        print "C11557 will now be tested."
        try:
            driver.find_element_by_xpath(RebootClosePath).click()
            print "Close dialog did not close the dialog. C11557 fails."
        except:
            print "Close dialog works as expected. C11557 passes."
        print "C11558 will now be tested. The SiteGate will be rebooted."
        #driver.find_element_by_xpath(RebootPath).click()
        #time.sleep(3)
        #driver.find_element_by_id("rebootConfirm").click()
        #driver.find_element_by_id("rebootConfirm").clear()
        #driver.find_element_by_id("rebootConfirm").send_keys("REBOOT")
        #driver.find_element_by_xpath(RebootConfirmPath).click()
        #time.sleep(10)
        print "C11559 will now be tested."
        #open new SiteGate tab and confirm it does not load
        driver.find_element_by_tag_name('body').send_keys(Keys.CONTROL + 't')
        driver.get(self.config.base_url + "")
        time.sleep(5)
        C11634 = ""
        if ("SiteGate" or "Please enter your credentials" or "Username" or "Password") in self.config.driver.page_source:
            print "The SiteGate was not properly rebooted. C11559 fails."
            C11634test = "No"
        else:
            print "The SiteGate is unreachable. C11559 passes."
            C11634test = "Yes"
        driver.find_element_by_tag_name('body').send_keys(Keys.CONTROL + 'w')
        if C11634test == "Yes":
            print "C11634 will now be tested."
            time.sleep(20)
            count = 0
            while (count<20):
                count += 1
                driver.find_element_by_tag_name('body').send_keys(Keys.CONTROL + 't')
                driver.get(self.config.base_url + "")
                time.sleep(5)
                if ("SiteGate" or "Please enter your credentials" or "Username" or "Password") in self.config.driver.page_source:
                    print "The SiteGate is back up and running. C11634 passes."
                    break
                else:
                    print "The SiteGate is still down. Will check again in 15 seconds."
                    driver.find_element_by_tag_name('body').send_keys(Keys.CONTROL + 'w')
                    time.sleep(15)
            if count == 20:
                print "The SiteGate is taking longer than expected to reboot. This needs to be looked into. C11634 may be failing."

        #open new SiteGate tab and confirm it loads. Put in a while loop that adds 60 more seconds to wait for reboot.
        #*************Control W closes out a tab on firefox-use this for opening to check if it is ready
    def is_element_present(self, how, what):
        try: self.config.driver.find_element(by=how, value=what)
        except NoSuchElementException, e: return False
        return True

    def is_alert_present(self):
        try: self.config.driver.switch_to_alert()
        except NoAlertPresentException, e: return False
        return True

    def close_alert_and_get_its_text(self):
        try:
            alert = self.config.driver.switch_to_alert()
            alert_text = alert.text
            if self.accept_next_alert:
                alert.accept()
            else:
                alert.dismiss()
            return alert_text
        finally: self.accept_next_alert = True

    #def tearDown(self):
    #    self.config.driver.quit()
    #    self.assertEqual([], self.verificationErrors)

if __name__ == "__main__":
    RebootSystem.config = selenium_config.default_config()
    unittest.main()
