__author__ = 'daniel.madden'
import unittest
import selenium_config


class C2TestSuite(unittest.TestCase):

    driver_name = None
    config = None

    def setUp(self):
        if not self.config:
            if C2TestSuite.driver_name is not None:
                if C2TestSuite.config is None:
                    self.config = selenium_config.SeleniumConfigManager().get_config(C2TestSuite.driver_name)
                else:
                    self.config = C2TestSuite.config
            else:
                if C2TestSuite.config is None:
                    self.config = selenium_config.default_config()
                else:
                    self.config = C2TestSuite.config
        self.tests = []
        self.loaded_tests = []

    def add_test(self, test_reference):
        self.tests.append(test_reference)

    def finalize_and_run_tests(self):
        self.apply_config_to_tests()
        self.load_tests_to_run()
        suite = unittest.TestSuite()
        suite.addTests(self.get_loaded_tests())
        runner = unittest.TextTestRunner()
        runner.run(suite)

    def apply_config_to_tests(self):
        for test in self.tests:
            test.config = self.config

    def load_tests_to_run(self):
        for test in self.tests:
            self.loaded_tests.append(unittest.defaultTestLoader.loadTestsFromTestCase(test))

    def get_loaded_tests(self):
        return self.loaded_tests

    def tearDown(self):
        if __name__ == "__main__":
            self.config.driver.quit()