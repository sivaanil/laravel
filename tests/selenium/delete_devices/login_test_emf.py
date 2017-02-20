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
        time.sleep(5)

        """
        timeout = 4
        time.sleep(timeout)
        expected_url = '{}{}'.format(self.config.base_url, 'home#/nodes/321')
        self.assertEqual(driver.current_url,
                         expected_url,
                         "{} FAILURE! URL Redirect to '{}' did not work within {} seconds.".format(__file__,
                                                                                                   expected_url,
                                                                                                   timeout))
        """

if __name__ == "__main__":
    unittest.main()