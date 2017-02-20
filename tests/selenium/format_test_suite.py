__author__ = 'daniel.madden'
import os
import re
import sys

#======TO USE=======
# Open command line
# cd to root testing dir (where this file is located)
# Run "python format_test_suite.py 'directory_of_tests' ['new_test_suite_name']"
#
# Will create a new c2_test_suite file in the specified directory with the specified name
# If no suite name is specified, will use the specified dir name for the py suite name

def format_suite(directory, suite_name):
    filenames = []
    lines = []
    test_references = []

    # get valid test files in the specified dir
    for [root, dirs, files] in os.walk(directory):
        for name in files:
            if name != "__init__.py" and name.endswith("py"):
                filenames.append(os.path.basename(name).split(".")[0])

    for name in filenames:
        lines.append("import {}\n".format(name))

    lines.append("import unittest\n")
    lines.append("import sys\n")
    lines.append("sys.path.append('..')\n")
    lines.append("import c2_test_suite\n\n\n")
    lines.append("class {}(c2_test_suite.C2TestSuite):\n\n".format(suite_name))
    lines.append("    def test_all(self):\n")

    # Get the class names in each file using a regex
    for name in filenames:
        f = open("{}/{}.py".format(directory, name))
        for line in f:
            match = re.search("class ([\w_]+)", line)
            if match is not None:
                test_references.append("{}.{}".format(name, match.group(1)))
        f.close()

    for ref in test_references:
        lines.append("        self.add_test({})\n\n".format(ref))

    lines.append("        self.finalize_and_run_tests()\n\n\n")
    lines.append("if __name__ == '__main__':\n")
    lines.append("    driver_name = 'firefox'\n")
    lines.append("    if len(sys.argv) > 1:\n")
    lines.append("        driver_name = sys.argv[1]\n")
    lines.append("        sys.argv.pop(1)\n")
    lines.append("    {}.driver_name = driver_name\n".format(suite_name))
    lines.append("    unittest.main()")

    f = open("{}/{}.py".format(directory, suite_name), "w")
    for line in lines:
        f.write(line)
    f.close()

if __name__ == "__main__":
    if len(sys.argv) > 1:
        for arg in sys.argv[1::]:
            if arg.startswith("\"") and arg.endswith("\""):
                arg = arg[1:-1]
        directory = sys.argv[1]
        if len(sys.argv) > 2:
            suite_name = sys.argv[2]
        else:
            suite_name = directory
        format_suite(directory, suite_name)