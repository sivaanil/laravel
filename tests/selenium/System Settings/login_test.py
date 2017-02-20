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

class Login(c2_test_case.C2TestCase):

    def test_login_success(self):
        driver = self.config.driver
        driver.get(self.config.base_url + "")
        # To ensure enough time for page load
        time.sleep(2)
        driver.find_element_by_id("username").clear()
        driver.find_element_by_id("username").send_keys("G8Keeper")
        driver.find_element_by_id("password").click()
        driver.find_element_by_id("password").clear()
        driver.find_element_by_id("password").send_keys("C2sYt_gA8")
        driver.find_element_by_id("login-button").click()
        time.sleep(8)

if __name__ == "__main__":
    unittest.main()