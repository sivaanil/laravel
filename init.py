import os.path
#! /usr/bin/python

# To change this license header, choose License Headers in Project Properties.
# To change this template file, choose Tools | Templates
# and open the template in the editor.

import os
import subprocess
import textwrap
import getpass

def createDatabaseFromDump():
   # s = #"""
   # Here you may create a new database and import some initial data.
   # The initial dataset is very large and may take 15-45 minutes to complete.
   # Please note that when creating a new database, YOU WILL DROP ANY EXISTING DATABASE WITH THAT NAME
   #"""
    s = """
    Here you may create a new database and import some initial data.
    Please note that when creating a new database, YOU WILL DROP ANY EXISTING DATABASE WITH THAT NAME
    """
    print(textwrap.dedent(s).strip())
    optCreate = raw_input("Create a new database? (Y/N) [N]: ") or "N"
    if(optCreate not in ('Y', 'y')):
        print("Database not created")
        return
    dbName = raw_input("Please enter a database name: ")
    if(dbName == ""):
        print("No database name entered, not creating a new database")
        return
    optConfirm = raw_input("Creating database '" + dbName + "' and importing initial data. Are you sure? (Y/N) [N]: ") or "N"
    if(optConfirm not in ('Y', 'y')):
        print("Database creation aborted, no changes made")
        return
    dbConfig = getDatabaseCredentials()
    dbInitialDatafile = raw_input("Enter the full path to the initial sql data: ")#, OR use the default data set [default data set]: ") or "initData.sql"
    dbCmd = 'mysql -u' + dbConfig['dbUser'] + " -p" + dbConfig['dbPass'] + " -P " + dbConfig['dbPort'] + " "
    print("Dropping existing database, if present")
    subprocess.call([dbCmd + '-e "DROP DATABASE IF EXISTS ' + dbName  + '";'], shell=True)
    #print("DEBUG: "+ dbCmd + '-e "DROP DATABASE IF EXISTS \'' + dbName  + '\'";')
    print("Creating database")
    subprocess.call([dbCmd + '-e "CREATE DATABASE ' + dbName  + '";'], shell=True)
    #print("DEBUG: " + dbCmd + '-e "CREATE DATABASE \'' + dbName  + '\'";')
    if(dbInitialDatafile == ""):
        print("No data file specified! Creating empty database")
    else:
        print("Importing initial data. This will take a while!")
        subprocess.call([dbCmd + dbCmd + dbName  + ' < '+ dbInitialDatafile], shell=True)
        #print("DEBUG: " + dbCmd + dbName  + ' < '+ dbInitialDatafile)
    print("Finished creating database")
    return

def getDatabaseCredentials():
    config = {}
    config['dbUser'] = raw_input("Please enter database user [root]: ") or "root"
    config['dbPass'] = getpass.getpass("Please enter database password: ")
    config['dbPort'] = raw_input("Please enter database port [3306]: ") or "3306"
    return config

def setDatabaseCredentials():
    print("To configure the database connection options, edit the 'connections' => 'mysql' array values.")
    optConfig = raw_input("Do you wish to configure your database connection? (Y/N) [Y]: ") or "Y"
    if optConfig in ("Y", "y"):
        subprocess.call(['vi .env'], shell=True)



if __name__ == "__main__":
    print("This is the initialization process for the Unified Interface / SitePortal Mobile project.")
    print("This will bring a new project to debug-ready status. Note that the webserver will still need to be configured for this project")
    subprocess.call(['chmod -R 7777 storage'], shell=True)
    createDatabaseFromDump()
    print("Here you can alter the database credentials that Laravel will use on boot.")
    setDatabaseCredentials()
    print("Installing project dependencies (this will take a few minutes)")
    subprocess.call(['php composer.phar install'], shell=True)
    subprocess.call(['php composer.phar update'], shell=True)
    optMigrate = raw_input("Do you wish to run the database migrations? (Y/N) [Y]: ") or "Y"
    if(optMigrate in ('Y', 'y')):
        print("Running database migrations")
        subprocess.call(['php artisan migrate'], shell=True)
    else:
        print("Skipping database migrations")
    optSeed = raw_input("Do you wish to run the database seeds? (Y/N) [Y]: ") or "Y"
    if(optSeed in ('Y', 'y')):
        print("Seeding database")
        subprocess.call(['php artisan db:seed'], shell=True)
    else:
        print("Skipping database seeding")
    print("Generating IDE autoompletion hints file")
    subprocess.call(['php artisan ide-helper:generate tmp_1.php'], shell=True)
    subprocess.call(['php artisan ide-helper:model --filename="tmp_2.php" -N'], shell=True)
    subprocess.call(['cat tmp_1.php tmp_2.php > IDECompletionHints.php'], shell=True)
    subprocess.call(['rm tmp_1.php tmp_2.php'], shell=True)
    print("To use the autocompletion hint file, add the file to your IDE's project libraries")
    #if(os.path.isfile('initData.sql')):
    #    subprocess.call(['rm initData.sql'], shell=True)
    print("The initialization script is complete.")
