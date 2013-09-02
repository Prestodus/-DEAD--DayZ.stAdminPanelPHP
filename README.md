DayZ.stAdminPanelPHP (Current version: 1.4)
===========================================
Manage your admin users and permissions through the admin panel!

Default admin account: username "admin", password "admin". Please change this after you login for the first time!

About / Features
================
This is an administration panel for servers hosted on DayZ.st.
The current features include:
   -   Support for all worlds
   -   Secure admin login with multiple users and editable permissions
   -   Respawn broken vehicles
   -   Link to the dayz.st map of your server
   -   Link to database with instant login and database selection
   -   Edit the starting loadout
   -   Survivor list with search option and survivor information (only alive survivors)
   -   Edit the inventory per survivor
   -   Stop / start / restart server
   -   Edit survivor page with the following options:
   -   -   Change blood (with slider on Google Chrome)
   -   -   Change humanity (with slider on Google Chrome)
   -   -   Edit medical stats (Uncounscious, infected, pain, heal completely)
   -   -   Change skin
   -   -   Show position on dayzdb.com's map

Installation
============
0: Pre-install checks
----------
First, go to this website: http://www.ismycomputeron.com/ and make sure it says "YES".

Afterwards, make sure this website: http://hasthelargehadroncolliderdestroyedtheworldyet.com/ says "NOPE".

If both are true, proceed to the next step.

1: Webhost (Skip if you already have one)
----------
To install this, you will need a webhost.
The requirements are that it should have PHP support and preferrably FTP access (could do with browser-based file manager).
It should also allow external database access and have the module pdo_mysql installed.
I recommend http://www.000webhost.com, it does the job quite well.

2: FTP client (Skip if you already have one)
-------------
After that, you will receive an FTP host, username and password.
Next, you will need an FTP client. If you don't have an FTP client yet, and you don't know much about this, I recommend CoffeeCup (http://www.coffeecup.com/free-ftp/) due to it being super-simple yet effective.
Download and install it.

3: Editing config.php
---------------------
Method 1 (easiest):


Go to http://rubencoolen.be/dayzadminpanel/configcreator.php and insert all the necessary data.

Method 2:


Now you should download this git and extract it to a local folder.
Open the config.php file with a text editor of your choice. I recommend Notepad++.
Here's what you need to edit: (You can find this information in the dayz.st control panel)
- Line 4: $DB_CONNSTRING (2nd one): The database host:port. If you host with dayz.st this should be db.dayz.st:3306
- Line 6: $DB_CONNSTRING (4th one): The database name. This is probably the same as the database username. For dayz.st: bliss_**** where **** is a number.
- Line 7: $DB_USERNAME: The database username. Probably the same as the database name.
- Line 8: $DB_PASSWORD: The database password
- Line 9: $ST_PASSWORD: Your dayz.st password
- Line 11: define("URLVARS", "u=dayzstuser&p=dayzstpasswordhash");
Go to your control panel and click the minimap. A new browser tab should open.
In the new browser tab, copy the part of the url after http://dayz.st/map?****** where ****** is what you need to copy.
It should be in the format of u=***&p=***.
Now replace u=dayzstuser&p=dayzstpasswordhash on line 11 with what you just copied.

4: Uploading to the webhost
---------------------------
Open up your FTP program.
Add a new server. For CoffeeCup FTP:
- Click on servers.
- Click on the green plus.
- Enter a nickname for the server.
- Enter the server host, username and password.
- Connect.
- Navigate to where you want to upload the website (Can be a folder called htdocs, public_html, or something else, depending on your webhost).
- Upload everything.
- Navigate to the correct URL in your browser.
- Profit.

Troubleshooting
===============
1: PDOException: Unknown MySQL server host
----------------------------
If you get an error like the following:
```php
Fatal error: Uncaught exception 'PDOException' with message 'SQLSTATE[HY000] [2005] Unknown MySQL server host 'db.dayz.st:1111' (3)' in /public_html/admin/index.php:10 Stack trace: #0 /home/a9156499/public_html/admin/index.php(10): PDO->__construct('mysql:host=db.d...', 'bliss_111', '11111111') #1 {main} thrown in /public_html/admin/index.php on line 10
```

Try removing :port from the $DB_CONNSTRING on line 4 in config.php and reuploading it.

Contact
=======
I have stopped development and support for the admin panel. I appreciate all of the good responses I had from the community, but i just didn't have the time to continue. I have other projects now for which I receive money, while I did this for free. I hope the current version will continue to work. There might be a next one when I have more time, but until then, I'm off. Goodbye.


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/Prestodus/dayz.stadminpanelphp/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

