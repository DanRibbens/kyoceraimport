When working with certain Kyocera TaskAlpha printers, there wasn't a way to make the device pull an address book over LDAP so we had to make our own script for manually loading the users. This saved a lot of time over manually entry or maintaining our own XML file.

This is a standalone php script for connecting to an active directory server, pulling all active directory users and creating the XML file that is compatibile with certain Kyocera MFP units.

Steps:
    1. Copy script to a webserver with PHP and make sure PHP_LDAP extension is installed and enabled.
    2. Edit the script to change the domain settings for your environment.
    3. Browse to the script in a browser.
    4. Save the XML file when prompted.
    5. Browse to the IP of your printer and import the user XML file.
    
I hope somebody finds this helpful!