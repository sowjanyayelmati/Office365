


<?php
/**
 * Login screen.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @author  Scott Wakefield <scott.wakefield@gmail.com>
 * @author  ViCarePlus <visolve_emr@visolve.com>
 * @author  Julia Longtin <julialongtin@diasp.org>
 * @author  cfapress
 * @author  markleeds
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

$ignoreAuth=true;
include_once("../globals.php");
include_once("$srcdir/sql.inc");

$query_str = $_SERVER['QUERY_STRING'];
$parsed_url = parse_url($query_str);
$url_parts = explode('=',$parsed_url['path']);
 $access_token = str_replace("&refresh_token", "", $url_parts[1]);
$refresh_token = $url_parts[2];

?>
<html>
<head>
<?php html_header_show();?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">

<script language='JavaScript' src="../../library/js/jquery-1.4.3.min.js"></script>
<script language='JavaScript'>
function transmit_form()
{
    document.forms[0].submit();
}
function imsubmitted() {
<?php if (!empty($GLOBALS['restore_sessions'])) { ?>
 // Delete the session cookie by setting its expiration date in the past.
 // This forces the server to create a new session ID.
 var olddate = new Date();
 olddate.setFullYear(olddate.getFullYear() - 1);
 document.cookie = '<?php echo session_name() . '=' . session_id() ?>; path=/; expires=' + olddate.toGMTString();
<?php } ?>
    return false; //Currently the submit action is handled by the encrypt_form(). 
}
</script>

</head>
<body onload="javascript:document.login_form.authUser.focus();" >
<span class="text"></span>
<center>
/*


<form method="POST"
 action="../main/main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>&access_token=<?php echo attr($access_token); ?>&refresh_token=<?php echo attr($refresh_token); ?>"
 target="_top" name="login_form" onsubmit="return imsubmitted();">

<input type='hidden' name='new_login_session_management' value='1' />


<?php
// collect groups
//echo $_SERVER['URL_QUERY'];
//echo $code;


/*POST https://login.windows.net/c56a2508-e4c8-49d9-951f-968caf6257b8/oauth2/token?
Content-Type: application/x-www-form-urlencoded
grant_type=authorization_code&client_id=2b30081d-606b-42ea-81cf-d100d87f6835&client_secret=d0I4HZ8k3kZUlHjC/BnTZqePq5E2v+0jFMQaC41w4nE=&code=AAABAAAAvPM1KaPlrEqdFSBzjqfTGJG0QlrZCRXcbrdL1lguY4ytX5W_yceRC4q-qkRvhxc-qHOZH9HFJ1MbK2K8kzRPqeInd9d4alsQADr3qkQ_C0nqJI8FxOOfgPKSQS7taaFNlx_HPaCOeQwaWwby9ATlY300lj9FUYVGNhyMkoh5caRY1RDVwGrQGbDdQdflf9gs5MvhMJoS5Y2Xq2MCfMG9edOcPG5os5W18wwFcyUyuQNaLbdflhftuF8V27l1SA8dWK_INBbOXlx05pQUIwTDTqSEXDmfuqPvpe4Skq0UpHxxQOWUCe2jvkBIMhcH87jA3qRi-5SF1A-XbIa1S1uG7SoZG2iqZudKIaii73MCvCVezd0EGULJqyhibPf6DyonC_1-RrnGGT_1WoMIDNgUo2ajg22johOGEXD07chKkWm4tBhJkVCp37QXyaLGlAn8T5ttZ3B5IqfB20ZfQUgowfX4noj1sICvxLCtGgWe0h5fDXfXzLWpZ8zlDBIKsTydF4Ac7wKrD0aI1oU9M_LAXdKY5VXChIeUmnDmwl8d6_0LzK9Gc8AlAesQUzOnzGTJSNzoi_3jpvjp5nBYi_W7akjpNiP4eZaWElKHXhss4eWFu79odr0CvlIXX1I-KVPExOAKunUYtKdKFk68uu_qnTQEV&redirect_uri=http://localhost:8080/interface/main/main_screen.php?auth=login&site=default&resource=https://outlook.office365.com
*/


$res = sqlStatement("select distinct name from groups");
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
	$result[$iter] = $row;
if (count($result) == 1) {
	$resvalue = $result[0]{"name"};
	echo "<input type='hidden' name='authProvider' value='" . attr($resvalue) . "' />\n";
}
// collect default language id
$res2 = sqlStatement("select * from lang_languages where lang_description = ?",array($GLOBALS['language_default']));
for ($iter = 0;$row = sqlFetchArray($res2);$iter++)
          $result2[$iter] = $row;
if (count($result2) == 1) {
          $defaultLangID = $result2[0]{"lang_id"};
          $defaultLangName = $result2[0]{"lang_description"};
}
else {
          //default to english if any problems
          $defaultLangID = 1;
          $defaultLangName = "English";
}
// set session variable to default so login information appears in default language
$_SESSION['language_choice'] = $defaultLangID;
// collect languages if showing language menu
if ($GLOBALS['language_menu_login']) {
    
        // sorting order of language titles depends on language translation options.
        $mainLangID = empty($_SESSION['language_choice']) ? '1' : $_SESSION['language_choice'];
        if ($mainLangID == '1' && !empty($GLOBALS['skip_english_translation']))
        {
          $sql = "SELECT *,lang_description as trans_lang_description FROM lang_languages ORDER BY lang_description, lang_id";
	  $res3=SqlStatement($sql);
        }
        else {
          // Use and sort by the translated language name.
          $sql = "SELECT ll.lang_id, " .
            "IF(LENGTH(ld.definition),ld.definition,ll.lang_description) AS trans_lang_description, " .
	    "ll.lang_description " .
            "FROM lang_languages AS ll " .
            "LEFT JOIN lang_constants AS lc ON lc.constant_name = ll.lang_description " .
            "LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND " .
            "ld.lang_id = ? " .
            "ORDER BY IF(LENGTH(ld.definition),ld.definition,ll.lang_description), ll.lang_id";
          $res3=SqlStatement($sql, array($mainLangID));
	}
    
        for ($iter = 0;$row = sqlFetchArray($res3);$iter++)
               $result3[$iter] = $row;
        if (count($result3) == 1) {
	       //default to english if only return one language
               echo "<input type='hidden' name='languageChoice' value='1' />\n";
        }
}
else {
        echo "<input type='hidden' name='languageChoice' value='".attr($defaultLangID)."' />\n";   
}
?>

<table width="100%" height="90%">
<td align='center' valign='middle' width='34%'>
<div class="login-box">
<div class="logo-left"><?php echo $logocode;?></div>

<div class="table-right">
<table width="100%">
<?php if (count($result) != 1) { ?>
<tr>
<td><span class="text"><?php echo xlt('Group:'); ?></span></td>
<td>
<select name=authProvider>
<?php
	foreach ($result as $iter) {
		echo "<option value='".attr($iter{"name"})."'>".text($iter{"name"})."</option>\n";
	}
?>
</select>
</td></tr>
<?php } ?>

<?php if (isset($_SESSION['loginfailure']) && ($_SESSION['loginfailure'] == 1)): ?>
<tr><td colspan='2' class='text' style='color:red'>
<?php echo xlt('Invalid username or password'); ?>
</td></tr>
<?php endif; ?>

<?php if (isset($_SESSION['relogin']) && ($_SESSION['relogin'] == 1)): ?>
<tr><td colspan='2' class='text' style='color:red;background-color:#dfdfdf;border:solid 1px #bfbfbf;text-align:center'>
<b><?php echo xlt('Password security has recently been upgraded.'); ?><br>
<?php echo xlt('Please login again.'); ?></b>
<?php unset($_SESSION['relogin']); ?>
</td></tr>
<?php endif; ?>

<tr>
<td><span class="text"><?php echo xlt('Username:'); ?></span></td>
<td>
<input class="entryfield" type="text" value='admin' size="10" name="authUser">
</td></tr><tr>
<td><span class="text"><?php echo xlt('Password:'); ?></span></td>
<td>
<input class="entryfield" type="password" value='openemr' size="10" name="clearPass">
</td></tr>

<?php
if ($GLOBALS['language_menu_login']) {
if (count($result3) != 1) { ?>
<tr>
<td><span class="text"><?php echo xlt('Language'); ?>:</span></td>
<td>
<select class="entryfield" name=languageChoice size="1">
<?php
        echo "<option selected='selected' value='" . attr($defaultLangID) . "'>" . xlt('Default') . " - " . xlt($defaultLangName) . "</option>\n";
        foreach ($result3 as $iter) {
	        if ($GLOBALS['language_menu_showall']) {
                    if ( !$GLOBALS['allow_debug_language'] && $iter[lang_description] == 'dummy') continue; // skip the dummy language
                    echo "<option value='".attr($iter['lang_id'])."'>".text($iter['trans_lang_description'])."</option>\n";
		}
	        else {
		    if (in_array($iter[lang_description], $GLOBALS['language_menu_show'])) {
                        if ( !$GLOBALS['allow_debug_language'] && $iter['lang_description'] == 'dummy') continue; // skip the dummy language
		        echo "<option value='".attr($iter['lang_id'])."'>" . text($iter['trans_lang_description']) . "</option>\n";
		    }
		}
        }
?>
</select>
</td></tr>
<?php }} ?>

<tr><td>&nbsp;</td><td>
<input class="button large" type="submit" onClick="transmit_form()" value="<?php echo xla('Login');?>">
</td></tr>
<tr><td colspan='2' class='text' style='color:red'>
<?php
$ip=$_SERVER['REMOTE_ADDR'];

?>
</div>
</td></tr>

</div>
<div style="clear: both;"> </div>
<div class="version">
<?php echo "v".text($openemr_version) ?> | <a  href="../../acknowledge_license_cert.html" target="main"><?php echo xlt('Acknowledgments, Licensing and Certification');
 ?></a>
</div>
</div>
<div class="demo">
		<!-- Uncomment this for the OpenEMR demo installation
		<p><center>login = admin
		<br>password =openemr
    xmlhttp.open("POST","http://localhost:8080/interface/main/main_screen.php?auth=login&site=default",true);
    xmlhttp.send();	-->	
</div>
</td>
</tr>
</table>
</form>
</center>
</body>
</html>
