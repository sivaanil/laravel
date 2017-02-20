__author__ = 'daniel.madden'
import os
import re
# ======TO USE=======
# Open command line
# cd to root testing dir (where this file is located)
# Run "python format_selenium_test.py 'directory/name_of_test_file.py'"
#
# Will overwrite the specified file with the newly formatted test case


def format_test(filename):
    f = open(filename)
    new_lines = []
    deleting_lines = False
    class_name = None
    for line in f:
        if "unittest.TestCase" in line:
            line = line.replace("unittest.TestCase", "c2_test_case.C2TestCase")
        elif "unittest" in line and "unittest.main()" not in line:
            # add necessary imports for C2TestCase
            new_lines.append("import sys\n")
            new_lines.append("sys.path.append('..')\n")
            new_lines.append("import c2_test_case\n")
            new_lines.append("import selenium_config\n")
        if "self.driver" in line:
            line = line.replace("self.driver", "self.config.driver")
        if "self.base_url" in line:
            line = line.replace("self.base_url", "self.config.base_url")
        if "unittest.main()" in line:
            new_lines.append("    {}.config = selenium_config.default_config()\n".format(class_name))
        if "class " in line:
            # get the class name using a regex
            match = re.search("class ([\w_]+)", line)
            if match is not None:
                class_name = match.group(1)
        if "from selenium" in line:
            line = ""
        # If the line is a method declaration, don't keep it and anything beneath it
        if "def " in line:
            deleting_lines = True
        # Keep lines if you come across the actual test method or the "main" procedure
        if "test_" in line or "if __name__" in line:
            deleting_lines = False
        if not deleting_lines:
            new_lines.append(line)
    f.close()
    os.remove(filename)
    f = open(filename, "w")
    for line in new_lines:
        f.write(line)

if __name__ == "__main__":
    import sys
    if len(sys.argv) > 1:
        for arg in sys.argv[1::]:
            if arg.startswith("\"") and arg.endswith("\""):
                arg = arg[1:-1]
        format_test(sys.argv[1])