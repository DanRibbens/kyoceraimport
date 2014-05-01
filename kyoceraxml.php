<?php

/*
*   Change these defines to match your own domain. 
*   You may have to create a new user with the right access or use any domain administrator account,
*   but be mindful of the domain password in cleartext here.
*/

define('LDAP_SERVER','mydomaincontroller');
define('LDAP_USER','my.domain.org/myorganizationalunit/myldapuser');
define('LDAP_PASS','mypassword');
define('DOMAIN','dc=my,dc=domain,dc=org');

/*
*   Choose a filter option. You can grab all domain users with the first option.
*   Alternately, you can specify the user group you wish to select by 
*   switching commented line  and changing myusergroup below.
*/

define('FILTER', "(objectCategory=person)");
//define('FILTER', "(&(objectCategory=person)(memberOf=CN=myusergroup,OU=DomainUsers,".DOMAIN."))";


// DEBUG - COMMENT OUT TO HIDE ERRORS //
error_reporting(E_ALL);
ini_set('display_errors', '1');
// END DEBUG /


//make a connection to the LDAP server
$ds=ldap_connect(LDAP_SERVER);

//set the protocol to version 3 (required for SSL or TLS)
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

//bind (log in) to ldap connection
$r=ldap_bind($ds, LDAP_USER, LDAP_PASS);

// Only return these fields
$columns = array('givenname', 'sn','mail');

//Search for the Username that was taken from URL by sAMAccountName
$sr = ldap_search($ds, DOMAIN, FILTER, $columns);


// Get values from the ldap_search fill in as missing if nothing is returned.

$info = ldap_get_entries($ds, $sr);


header("Content-type: text/xml");
echo "<?xml version=\"1.0\"?>";
echo "<DeviceAddressBook_v5_2>";

$id = 0;
for ($i = 0; $i < count($info); $i++) {
  
  if (isset($info[$i]["mail"][0])) {
  $id++;
    echo "
      <Item 
        Type=\"Contact\" 
        Id=\"$id\"
        DisplayName=\"".$info[$i]["sn"][0].", ".$info[$i]["givenname"][0]."\"
        SendKeisyou=\"0\"
        DisplayNameKana=\"".$info[$i]["sn"][0].", ".$info[$i]["givenname"][0]."\" 
        MailAddress=\"".$info[$i]["mail"][0]."\" 
        SendCorpName=\"\"
        SendPostName=\"\"
        SendAddrName=\"\"
        SmbHostName=\"\"
        SmbPath=\"\"
        SmbLoginName=\"\"
        SmbLoginPasswd=\"\"
        SmbPort=\"139\"
        FtpHostName=\"\"
        FtpLoginName=\"\"
        FtpLoginPasswd=\"\"
        FtpPort=\"21\"
        FaxNumber=\"\"
        FaxSubaddress=\"\"
        FaxPassword=\"\"
        FaxCommSpeed=\"BPS_33600\"
        FaxECM=\"On\"
        FaxEncryptKeyNumber=\"0\"
        FaxEncryption=\"Off\"
        FaxEncryptBoxEnabled=\"Off\"
        FaxEncryptBoxID=\"0000\"
        InetFAXAddr=\"\"
        InetFAXMode=\"Simple\" 
        InetFAXResolution=\"3\" 
        InetFAXFileType=\"TIFF_MH\" 
        InetFAXDataSize=\"1\" 
        InetFAXPaperSize=\"1\" 
        InetFAXResolutionEnum=\"Default\" 
        InetFAXPaperSizeEnum=\"Default\"
      />";
  }
}

echo "</DeviceAddressBook_v5_2>";

?>