<?
////////////////////////////////////////////////////////////////////////////////////////////////////
// database config

// created 05 - 19 - 2012

////////////////////////////////////////////////////////////////////////////////////////////////////
// MySQL Settings
$database['username']	= "root";
$database['password']	= "Mimi!#&@";
$database['database']	= "botretargeter";
$database['hostname']	= "37.235.32.75"; // local
////////////////////////////////////////////////////////////////////////////////////////////////////
// MySQL Connection

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$error_message = '<div style="border: 1px dashed #cc0000;font-family:Tahoma;background-color:#FBEEEB;width:99%;padding:10px;color:#cc0000;">
<strong>Connection Error</strong><br>
Opps, looked like something went wrong, please try again.<br />
<br />
<strong>Error Code:</strong> 00119<br />
<strong>Error URL:</strong> '.$url.'<br />
<br />
If this error persists, please submit a support ticket to <a href="http://support.themailingcompany.com" target="_blank">http://support.themailingcompany.com</a> and quote the above error code and error URL.
</div>
';

$connection = @mysql_connect($database['hostname'],$database['username'],$database['password']); // DONT TOUCH THIS
@mysql_select_db($database['database']) or die($error_message.'<br />'.mysql_error()); // DONT TOUCH THIS

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);