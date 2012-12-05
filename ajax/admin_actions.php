<?php
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);

// actions
$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_username     = $GPXIN['username'];
$url_password     = $GPXIN['password'];
$url_email        = $GPXIN['email'];
$url_first_name   = $GPXIN['fname'];
$url_last_name    = $GPXIN['lname'];
$url_theme        = $GPXIN['theme'];
$url_language     = $GPXIN['language'];

// Create
if($url_do == 'create')
{
    require(DOCROOT.'/includes/classes/admins.php');
    $Admins  = new Admins;
    echo $Admins->create($url_username,$url_password,$url_email,$url_first_name,$url_last_name);
}

// Save
elseif($url_do == 'save')
{
    if(empty($url_id) || empty($url_username)) die('Insufficient info given!');
    
    #if(!empty($url_password)) $sql_pass = ',password = MD5(\''.$url_password.'\')';
    #else $sql_pass = '';
    
    @mysql_query("UPDATE admins SET last_updated = NOW(),username = '$url_username',theme = '$url_theme',language = '$url_language',email_address = '$url_email',first_name = '$url_first_name',last_name = '$url_last_name' WHERE id = '$url_id'") or die('Failed to update admin');
    
    // Update session
    $_SESSION['gpx_lang']   = strtolower($url_language);
    $_SESSION['gpx_theme']  = strtolower($url_theme);
    
    echo 'success';
}

// Delete
elseif($url_do == 'delete')
{
    // Cannot delete yourself
    if($gpx_userid == $url_id) die('You cannot delete your own account!');
    
    @mysql_query("UPDATE admins SET deleted = '1' WHERE id = '$url_id'") or die('Failed to delete the admin');
    
    echo 'success';
}

?>
