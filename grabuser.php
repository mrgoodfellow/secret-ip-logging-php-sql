<?php
//SQL database information
$servername = "localhost";
$username = "logman";
$password = "nf39hf34908ht0349ty33goj4904jgt3094jg0934jg34";
$dbname = "iplog";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
// Get the page URL
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
// Get the page Name
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$ip = get_client_ip();
$local = getHostByName(getHostName());
$date = new DateTime();
$timestamp = $date->format('Y-m-d H:i:s');
$url = curPageURL().curPageName();
$agent = $_SERVER['HTTP_USER_AGENT'];
require 'whitelist.php';
if (in_array (array($ip, $local), $whitelist)) {
}else{
$sql = "INSERT INTO `".$dbname"`.`Log` (`Timestamp`,`External IP`,`URL`,`Internal IP`,`Agent`) VALUES ('" . $timestamp . "', '" . $ip . "', '" . $url . "', '" . $local . "', '" . $agent . "')";
$conn->query($sql);
$conn->close();
}
?>
