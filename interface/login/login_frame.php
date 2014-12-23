<?php
$ignoreAuth = true;
include_once ("../globals.php");
include('../../library/httpful/httpful.phar');

// $codeac = $_SERVER['QUERY_STRING'];
// session_start();
// $parsed_url = parse_url($codeac);
// $url_parts = explode('=',$parsed_url['path']);
// $final_code=str_replace("&session_state","",$url_parts[1]);
// $_SESSION['$final_code'] = $final_code;

// echo 'Auth Code:<br>';
// echo $final_code;

session_start();
$codeac = $_SERVER['QUERY_STRING'];
$parsed_url = parse_url($codeac);
$url_parts = explode('=',$parsed_url['path']);
$_SESSION['auth_code'] = $url_parts[2];

// echo "Session Auth Code:<br>";
// echo $_SESSION['auth_code'];

class HttpFulClient 
{
  function getTokens($code)
  {
    # code...
  	// echo '<br><br>Auth Code:<br>';
  	// echo $code;

    $token_uri = 'https://login.windows.net/common/oauth2/token';
    $redirect_uri = 'http%3A%2F%2Flocalhost%2Fopenemr';
    //$token_body = 'grant_type=client_credentials&redirect_uri='.$redirect_uri.'&client_id=7f28455e-8aa8-411d-9e07-3aad4377cd5b&client_secret=rfk4WK%2Fm2uoqJ8ktU4iCQ1B%2F0wjmkJP973qGZLP%2B3j4%3D&code='.$code.'&resource=https://outlook.office365.com';
    $token_body = 'grant_type=authorization_code&redirect_uri='.$redirect_uri.'&client_id=555629fa-2b38-4d70-b329-a8af9979c8e8&client_secret=4UxfCQMhN2X9uUSbIk%2Fm081zBF%2Bc0qB3zuaGoKhvqNw%3D&code='.$code.'&resource=https://outlook.office365.com';
    $token_req_response = \Httpful\Request::post($token_uri)
      ->body($token_body)
      ->addHeader('Content-Type', 'application/x-www-form-urlencoded')
      ->send();


    
    // echo '<br><br>Token Type:--<br>';
    // echo $token_req_response->body->token_type;
    // echo '<br><br>Access Token:--<br>';
    // echo $token_req_response->body->access_token;
    // echo '<br><br>Refresh Token:--<br>';
    // echo $token_req_response->body->refresh_token;
   //   echo "----------------";
//echo $token_req_response;
    return $token_req_response;
  }
}

try {

    $httpfulClient = new HttpFulClient();
    $httpfulClientResp = $httpfulClient->getTokens($_SESSION['auth_code']);
    $_SESSION["access_token"] = $httpfulClientResp->body->access_token;
    $_SESSION["refresh_token"] = $httpfulClientResp->body->refresh_token;
    $GLOBALS['access_token'] = $httpfulClientResp->body->access_token;
    $GLOBALS['refresh_token'] = $httpfulClientResp->body->refresh_token;
    // echo '<br><br>Access Token:--<br>';
    // echo $httpfulClientResp->body->access_token;
    // echo '<br><br>Refresh Token:--<br>';
    // echo $httpfulClientResp->body->refresh_token;

    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
}
 


?>
<HTML>
<head>
<?php html_header_show(); ?>
<TITLE><?php xl ('Login','e'); ?></TITLE>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">

</HEAD>

<frameset rows="<?php echo "$GLOBALS[logoBarHeight],$GLOBALS[titleBarHeight]" ?>,*" cols="*" frameborder="NO" border="0" framespacing="0">
  <frame class="logobar" src="<?php echo $rootdir;?>/login/filler.php" name="Filler Top" scrolling="no" noresize frameborder="NO">
  <frame class="titlebar" src="<?php echo $rootdir;?>/login/login_title.php" name="Title" scrolling="no" noresize frameborder="NO">
  <frame src="<?php echo $rootdir;?>/login/login.php?access_token=<?php echo $_SESSION["access_token"];?>&refresh_token=<?php echo $_SESSION["refresh_token"];?>" name="Login" scrolling="auto" frameborder="NO">
  <!-- <frame src="<?php echo $rootdir;?>/login/login.php" name="Login" scrolling="auto" frameborder="NO"> -->
  <!--<frame src="<?php echo $rootdir;?>/login/filler.php" name="Filler Bottom" scrolling="no" noresize frameborder="NO">-->
</frameset>

<noframes><body bgcolor="#FFFFFF">

</body></noframes>

</HTML>
