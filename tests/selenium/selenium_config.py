__author__ = 'daniel.madden'
from selenium import webdriver

import csv


# Creates an singleton WebDriver, and provides a SeleniumConfig object.
# Use the "get_config" and "get_default_config" methods only.
class SeleniumConfigManager:

    def __init__(self):
        self.driver = None

        # ACB added
        # loads a user config file and processes the file into easy to use arrays
        config_file = csv.DictReader(open("../config_file.csv"))
        item_array = []
        value_array = []
        code_array = []
        for row in config_file:
            item_array.append(row["ITEM"])
            value_array.append(row["VALUE"])
            code_array.append(row["CODE"])

        # Sets up the user configured items
        self.ip_address = SeleniumConfigManager.choose_url(self, item_array, value_array, code_array)
        self.base_url = "https://" + self.ip_address + "/"
        self.long_timeout = SeleniumConfigManager.choose_timeout(self, "long timeout", item_array, value_array)
        self.mid_timeout = SeleniumConfigManager.choose_timeout(self, "mid timeout", item_array, value_array)
        self.short_timeout = SeleniumConfigManager.choose_timeout(self, "short timeout", item_array, value_array)
        self.root_node = SeleniumConfigManager.choose_root_node(self, item_array, value_array)
        self.skip_builds = SeleniumConfigManager.choose_skip_build(self, item_array, value_array)

    def get_driver(self, driver_name=None):
        if not self.driver:
            driver_name = driver_name.lower()
            if driver_name == "firefox":
                self.driver = webdriver.Firefox()
            elif driver_name == "chrome":
                self.driver = webdriver.Chrome()
            else:
                self.driver = webdriver.Firefox()
        return self.driver

    def set_up(self):
        pass

    def tear_down(self):
        pass

    # The only method you should need to use to get a SeleniumConfig
    def get_config(self, driver):
        return SeleniumConfig(self.get_driver(driver), self.base_url, self.long_timeout, self.mid_timeout, self.short_timeout, self.root_node,
                              self.skip_builds)

    # Called by TestSuites if no driver was initially specified
    def get_default_config(self):
        return SeleniumConfig(self.get_driver("firefox"), self.ip_address, self.base_url, self.long_timeout, self.mid_timeout, self.short_timeout,
                              self.root_node, self.skip_builds)


    ## ACB added methods ##
    def choose_url(self, item_array, value_array, code_array):
        # Loop through the item array searching for the ip address setting
        for index in range(0, len(item_array)):
            if (item_array[index] == "ip address"):
                # "choose" means the user wishes to set a custom ip or use a preset one from the list
                if (value_array[index].find("choose") != -1):
                    # Print header information and the list of preset SiteGates and their corresponding code
                    if (value_array[index].find("no menu") == -1):
                        SeleniumConfigManager.print_preset_sitegate_list(self, item_array, code_array)

                    # Ask user for the code or ip address and store the response
                    ip_address = raw_input("Enter SiteGate code or ip address: ")

                    # if the ip address has a '.' assume this is not a preset ip and return the url with the entered address. Else look up the
                    # code in the preset ips list and return the url with the corresponding ip address.
                    if (ip_address.find('.') != -1):
                        return(ip_address)
                    else:
                        for code_index in range(0, len(code_array)):
                            if (code_array[code_index] == ip_address):
                                return(value_array[code_index])
                # If the value for ip address contains a '.' then assume the user has set a specific ip and just return a url with that ip
                elif (value_array[index].find('.') != -1):
                    return(value_array[index])
                # Else the user has entered a code for a preset SiteGate, look up the code in the preset list and return the url with the
                # corresponding ip address.
                else:
                    for code_index in range(0, len(code_array)):
                        if (code_array[code_index] == value_array[index]):
                            return(value_array[code_index])

        # If something didn't go right let the user know and return the url for QC-1
        print("Code not found! Defaulting to QC-1")
        return("192.168.8.192")

    def print_preset_sitegate_list(self, item_array, code_array):
        print("Stored SiteGates:")
        print("Name.....................Code")
        print("_____________________________")
        for code_index in range(0, len(code_array)):
            if (item_array[code_index] != "choose" and item_array[code_index] != "choose no menu" and
                        item_array[code_index] != "ip address"):
                if (item_array[code_index] == ""):
                    break
                print_line = item_array[code_index]
                for index in range(0, 20 - (len(item_array[code_index]) - 5)):
                    print_line += "."
                print_line += code_array[code_index]
                print(print_line)

    def choose_root_node(self, item_array, value_array):
        # Loop through the items looking for the root node then return its corresponding value
        for index in range(0, len(item_array)):
            if (item_array[index] == "root node"):
                return(value_array[index])

        # If the root node item couldn't be found let the user know and return the default 5000
        print("Root node not found! Using default 5000")
        return("5000")

    def choose_timeout(self, timeout_name, item_array, value_array):
        # Loop through the items to look for the timeout specified once found return its corresponding value
        for index in range(0, len(item_array)):
            if (item_array[index] == timeout_name):
                return(int(value_array[index]))

        # If the timeout item couldn't be found print a message to let the user know and then return the default value for the specific
        # timeout with the largest being the else case
        if (timeout_name.find("short") != -1):
            print("Short timeout not found! Using default 5")
            return(5)
        elif (timeout_name.find("mid") != -1):
            print("Mid timeout not found! Using default 10")
            return(10)
        else:
            print(timeout_name + "not found! Using default 20")
            return (20)

    def choose_skip_build(self, item_array, value_array):
        # Loop through the items to look for the timeout specified once found return its corresponding value
        for index in range(0, len(item_array)):
            if (item_array[index] == "skip builds"):
                if (value_array[index].lower() == "no" or value_array[index].lower() == "false"):
                    return (False)
                else:
                    return (True)

        print ("Skip builds not found! Using default False")
        return (False)



# The class that is passed down to each test case. Just a container.
class SeleniumConfig:

    def __init__(self, driver, ip, url, longT, midT, shortT, root, skip):
        self.driver = driver
        self.ip_address = ip
        self.base_url = url
        self.long_timeout = longT # ACB added so that all timeouts are the same and can be changed in one place
        self.mid_timeout = midT # ACB added so that all timeouts are the same and can be changed in one place
        self.short_timeout = shortT # ACB added so that all timeouts are the same and can be changed in one place
        self.root_node = root # ACB added in case the root node ever changes
        self.skip_builds = skip # ACB added so that a user can run tests without building devices



def default_config():
    return SeleniumConfigManager().get_default_config()