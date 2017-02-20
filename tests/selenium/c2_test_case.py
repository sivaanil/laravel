__author__ = 'daniel.madden'
import unittest
import selenium_config


class C2TestCase(unittest.TestCase):

    def setUp(self):
        if not self.config:
            self.config = selenium_config.default_config()
        self.verificationErrors = []
        self.accept_next_alert = True

    def tearDown(self):
        self.assertEqual([], self.verificationErrors)