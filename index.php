<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// Set the site ID if required.  This must be done before any database
// access is attempted.

// SESSION RELATED EXPERIMENTS:
// echo '<br><br>Set session:<br>';
// // echo session_save_path('G:\Windows\tempSessionOpenEMR');
// echo '<br><br>Set Name:<br>';
// echo session_name("badar");
// echo '<br><br>Start session:<br>';
echo session_start();
$codeac = $_SERVER['QUERY_STRING'];
$parsed_url = parse_url($codeac);
$url_parts = explode('=',$parsed_url['path']);
$final_code=str_replace("&session_state","",$url_parts[1]);
$_SESSION['auth_code'] = $final_code;

// echo '<br><br>Auth Code:<br>';
// echo $final_code;
// echo '<br><br>';
// echo 'SESSION:<br>';
// echo $_SESSION['auth_code'];
// // echo session.save_path;
// echo '<br><br>Session Name:<br>';
// echo '<br><br>Session Elements:<br>';

// echo print_r($_SESSION);

if (!empty($_GET['site']))
    $site_id = $_GET['site'];
else if (is_dir("sites/" . $_SERVER['HTTP_HOST']))
    $site_id = $_SERVER['HTTP_HOST'];
else
    $site_id = 'default';

if (empty($site_id) || preg_match('/[^A-Za-z0-9\\-.]/', $site_id))
    die("Site ID '".htmlspecialchars($site_id,ENT_NOQUOTES)."' contains invalid characters.");

require_once "sites/$site_id/sqlconf.php";

if ($config == 1) {
    header("Location: interface/login/login_frame.php?site=$site_id&auth_code=$final_code");
} else {
    header("Location: setup.php?site=$site_id");
}
