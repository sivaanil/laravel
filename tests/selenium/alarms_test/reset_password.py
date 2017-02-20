import sys
sys.path.append("..")
import unittest

import c2_test_case
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.action_chains import ActionChains

import time
import pymssql


class ResetPassword(c2_test_case.C2TestCase):
    def test_reset_password_displays_first_time_C141739(self):
        conn = pymssql.connect(server='yourserver.database.windows.net', user='yourusername@yourserver', password='yourpassword',
                               database='AdventureWorks')


if __name__ == "__main__":
    unittest.main()