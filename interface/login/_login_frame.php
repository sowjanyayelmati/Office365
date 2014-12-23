<?php

/*$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
$params   = $_SERVER['QUERY_STRING'];
 
//$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
 
echo $params;
*/

echo "Hello!";

$ignoreAuth = true;
include_once ("../globals.php");
include('../../library/httpful/httpful.phar');
$codeac=$_SERVER['QUERY_STRING'];
session_start();
//$test=$codeac;


$parsed_url = parse_url($codeac);
$url_parts = explode('=',$parsed_url['path']);


$final_code=str_replace("&session_state","",$url_parts[1]);

$_SESSION['$final_code'] = $final_code;
//echo $final_code;
/**
* HttpFulClient
*/
//$final_code='AAABAAAAvPM1KaPlrEqdFSBzjqfTGEVai2KRLIsOi6tfmDIKYzTWYordicIpOYwg1OMacHNUr5h42BAsvBggR8NqRFL5V8moyHzoWz1CsUkC_5Gvjaegx9KkcpsfPCS9QruBDVBoMn02MM6AHmXBpcQpL2yTbRuQ4NrkVHLBUNGmpuGOjweE6sLVx4QOCalhryi0eGR4m3O53kA61DgF4luTwLedhDH-3xY0YQE1HCDMhAlA9UYYTya8gy3_LYv4oUpiXzdlFu4mVEA7Cm97WZNRMMa_XUDMbPFkFsP-tGYKfWrbr3GArN9A9HBGp6aA5BOL62vPn6jpmkMygTDpE_3LZQHR_D0ixlYxuoWsZxs4pY44hBGVj76LZ9p8l26MLUk1Q-s1PxEmvsR5NDCt2fb7b0VyTMIF5ZexiYdSNjuTBFqmups52wQeN2bueJ4c0ikRkjF9lttFP9HZ8PcIFqOgMbw5whh9FquqD7Pe0ot8ySf4v-dO2PQiuuCNqL-LvGPuUEtqhccODM8DXMAcf0JXDd1fOTY9tDy8a6eSKfaxy4jachUe6F-_p8uE271KHi10uhic5QhdIvAlUPtNLa8DK5D1bAi_xa4DETWxT6ZtJJrtg6kgAA';

class HttpFulClient 
{

  
  function getTokens($code, $token_uri)
  {
    # code...



    $token_uri = 'https://login.windows.net/common/oauth2/token';
    $redirect_uri = 'http%3A%2F%2Flocalhost%2Fopenemr';
    //$token_body = 'grant_type=client_credentials&redirect_uri='.$redirect_uri.'&client_id=7f28455e-8aa8-411d-9e07-3aad4377cd5b&client_secret=rfk4WK%2Fm2uoqJ8ktU4iCQ1B%2F0wjmkJP973qGZLP%2B3j4%3D&code='.$code.'&resource=https://outlook.office365.com';
    $token_body = 'grant_type=authorization_code&redirect_uri='.$redirect_uri.'&client_id=7f28455e-8aa8-411d-9e07-3aad4377cd5b&client_secret=rfk4WK%2Fm2uoqJ8ktU4iCQ1B%2F0wjmkJP973qGZLP%2B3j4%3D&code='.$code.'&resource=https://outlook.office365.com';

    $token_req_response = \Httpful\Request::post($token_uri)
      ->body($token_body)
      ->addHeader('Content-Type', 'application/x-www-form-urlencoded')
      ->send();
    
    echo 'token_req_response:--'
    echo $token_req_response->body->token_type;
    echo $token_req_response->body->access_token;
    echo $token_req_response->body->refresh_token;
   return $token_req_response;
  }
}

class AccessTokenAuthentication {
    /*
     * Get the access token.
     *
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client ID.
     * @param string $authUrl      Oauth Url.
     *
     * @return string.
     */



    
        function getTokens($grantType, $clientID, $clientSecret, $authUrl, $final_code,$redirect_uri,$resource){

//echo $final_code;

        try {

            //Initialize the Curl Session.
            $ch = curl_init();
            //Create the request Array.
            $paramArr = array (
                 'grant_type'    => $grantType,
                 'client_id'     => $clientID,
                 'client_secret' => $clientSecret,
                 'code'          => $final_code,
                 'redirect_uri'  => 'http://localhost:8080/interface/login/login_frame.php?site=default/',
                 'resource'      => 'https://outlook.office365.com'
            );
            //Create an Http Query.//
            $paramArr = http_build_query($paramArr);
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
            //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    //echo $clientSecret;
    

            //Execute the  cURL session.
            $strResponse = curl_exec($ch);
            //Get the Error Code returned by Curl.
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            //Close the Curl Session.
            curl_close($ch);
            //Decode the returned JSON string.
            $objResponse = json_decode($strResponse);
            if ($objResponse->error){
                throw new Exception($objResponse->error_description);
            }
            return $objResponse->access_token;
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }
}
/*
 * Class:HTTPTranslator
 * 
 * Processing the translator request.
 */
Class HTTPTranslator {
    /*
     * Create and execute the HTTP CURL request.
     *
     * @param string $url        HTTP Url.
     * @param string $authHeader Authorization Header string.
     * @param string $postData   Data to post.
     *
     * @return string.
     *
     */
    function curlRequest($url, $authHeader, $postData=''){
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: application/x-www-form-urlencoded"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        if($postData) {
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $curlResponse;
    }
    /*
     * Create Request XML Format.
     *
     * @param string $languageCode  Language code
     *
     * @return string.
     */
    function createReqXML($languageCode) {
        //Create the Request XML.
        $requestXml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
        if($languageCode) {
            $requestXml .= "<string>$languageCode</string>";
        } else {
            throw new Exception('Language Code is empty.');
        }
        $requestXml .= '</ArrayOfstring>';
        return $requestXml;
    }
}
try {


    // $httpfulClient = new HttpFulClient();
    // $httpfulClientResp = $httpfulClient->getTokens($final_code);



   //  // //Client ID of the application.
   //   $clientID       = "7f28455e-8aa8-411d-9e07-3aad4377cd5b";
   //  // //Client Secret key of the application.
   //  $clientSecret = "rfk4WK%2Fm2uoqJ8ktU4iCQ1B%2F0wjmkJP973qGZLP%2B3j4%3D";
   //  // //OAuth Url.
   //  $authUrl      = "https://login.windows.net/common/oauth2/token";
   //  // //Application Scope Url
    
   //  // //Application grant type
   //   $grantType    = "authorization_code";
   //  // //Create the AccessTokenAuthentication object.
   //  $authObj      = new AccessTokenAuthentication();
   //  // //Get the Access token.
   //  $accessToken  = $authObj->getTokens($grantType, $clientID, $clientSecret, $authUrl, $final_code,$redirect_uri,$resource);
   // // echo $accessToken;


    // //Create the authorization Header string.
    // $authHeader = "Authorization: Bearer ". $accessToken;
    
    // //Create the Translator Object.
    // $translatorObj = new HTTPTranslator();
    
    // //Input String.
    // $inputStr = 'This is the sample string.';
    // //HTTP Detect Method URL.
    // $detectMethodUrl = "http://api.microsofttranslator.com/V2/Http.svc/Detect?text=".urlencode($inputStr);
    // //Call the curlRequest.
    // $strResponse = $translatorObj->curlRequest($detectMethodUrl, $authHeader);
    // //Interprets a string of XML into an object.
    // $xmlObj = simplexml_load_string($strResponse);
    // foreach((array)$xmlObj[0] as $val){
    //     $languageCode = $val;
    // }
    
    
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
  <frame src="<?php echo $rootdir;?>/login/login.php" name="Login" scrolling="auto" frameborder="NO">
  <!--<frame src="<?php echo $rootdir;?>/login/filler.php" name="Filler Bottom" scrolling="no" noresize frameborder="NO">-->
</frameset>

<noframes><body bgcolor="#FFFFFF">

</body></noframes>

</HTML>
