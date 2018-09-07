<?php
#
# chilli - ChilliSpot.org. A Wireless LAN Access Point Controller
# Copyright (C) 2003, 2004 Mondru AB.
#
# The contents of this file may be used under the terms of the GNU
# General Public License Version 2, provided that the above copyright
# notice and this permission notice is included in all copies or
# substantial portions of the software.

# Redirects from ChilliSpot daemon:
#
# Redirection when not yet or already authenticated
#   notyet:  ChilliSpot daemon redirects to login page.
#  already: ChilliSpot daemon redirects to success status page.
#
# Response to login:
#   already: Attempt to login when already logged in.
#   failed:  Login failed
#   success: Login succeded
#
# logoff:  Response to a logout


# Shared secret used to encrypt challenge with. Prevents dictionary attacks.
# You should change this to your own shared secret.
$uamsecret = "change-me";

# Uncomment the following line if you want to use ordinary user-password
# for radius authentication. Must be used together with $uamsecret.
$userpassword=1;

# Our own path
//Login path to custom page
//$loginpath = $_SERVER['PHP_SELF'];
$loginpath = "/ci/hs/start";
$ChilliSpot="hotspot";
$title="Login";
$centerUsername="Username";
$centerPassword="Password";
$centerLogin="Login";
$centerPleasewait="";
$centerLogout="Logout";
$h1Login="$ChilliSpot Login";
//$h1Failed="$ChilliSpot Login Failed";
$h1Failed="Login Failed";
$h1Loggedin="Logged in";
$h1Loggingin="Please Wait...";
$h1Loggedout="Logged out";
$centerdaemon="Login must be performed through $ChilliSpot.";
$centerencrypted="Login must use encrypted connection";


# Make sure that the form parameters are clean
#$OK_CHARS='-a-zA-Z0-9_.@&=%!';
#$_ = $input = <STDIN>;
#s/[^$OK_CHARS]/_/go;
#$input = $_;

# Make sure that the get query parameters are clean
#$OK_CHARS='-a-zA-Z0-9_.@&=%!';
#$_ = $query=$ENV{QUERY_STRING};
#s/[^$OK_CHARS]/_/go;
#$query = $_;


# If she did not use https tell her that it was wrong.
/*if (!($_ENV['HTTPS'] == 'on')) {
#    echo "Content-type: text/html\n\n";
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
  <title>$title</title>
  <meta http-equiv=\"Cache-control\" content=\"no-cache\">
  <meta http-equiv=\"Pragma\" content=\"no-cache\">
</head>
<body bgColor = '#c0d8f4'>
  <h1 style=\"text-align: center;\">$h1Failed</h1>
  <center>
    $centerencrypted
  </center>
</body>
<!--
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<WISPAccessGatewayParam 
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xsi:noNamespaceSchemaLocation=\"http://www.acmewisp.com/WISPAccessGatewayParam.xsd\">
<AuthenticationReply>
<MessageType>120</MessageType>
<ResponseCode>102</ResponseCode>
<ReplyMessage>Login must use encrypted connection</ReplyMessage>
</AuthenticationReply>
</WISPAccessGatewayParam>
-->
</html>
";
    exit(0);
}*/


# Read form parameters which we care about
if (isset($_POST['username']))    $username    = $_POST['username'];
if (isset($_POST['password']))    $password    = $_POST['password'];
if (isset($_POST['challenge']))    $challenge    = $_POST['challenge'];
if (isset($_POST['button']))    $button        = $_POST['button'];
if (isset($_POST['logout']))    $logout        = $_POST['logout'];
if (isset($_POST['prelogin']))    $prelogin    = $_POST['prelogin'];
if (isset($_POST['res']))    $res        = $_POST['res'];
if (isset($_POST['uamip']))    $uamip        = $_POST['uamip'];
if (isset($_POST['uamport']))    $uamport    = $_POST['uamport'];
if (isset($_POST['userurl']))    $userurl    = $_POST['userurl'];
if (isset($_POST['timeleft']))    $timeleft    = $_POST['timeleft'];
if (isset($_POST['redirurl']))    $redirurl    = $_POST['redirurl'];

# Read query parameters which we care about
if (isset($_GET['res']))    $res        = $_GET['res'];
if (isset($_GET['challenge']))    $challenge    = $_GET['challenge'];
if (isset($_GET['uamip']))    $uamip        = $_GET['uamip'];
if (isset($_GET['uamport']))    $uamport    = $_GET['uamport'];
if (isset($_GET['reply']))    $reply        = $_GET['reply'];
if (isset($_GET['userurl']))    $userurl    = $_GET['userurl'];
if (isset($_GET['timeleft']))    $timeleft    = $_GET['timeleft'];
if (isset($_GET['redirurl']))    $redirurl    = $_GET['redirurl'];


#$reply =~ s/\+/ /g;
#$reply =~s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/seg;

$userurldecode = $userurl;
#$userurldecode =~ s/\+/ /g;
#$userurldecode =~s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/seg;

$redirurldecode = $redirurl;
#$redirurldecode =~ s/\+/ /g;
#$redirurldecode =~s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/seg;

#$password =~ s/\+/ /g;
#$password =~s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/seg;

# If attempt to login
if ($button == 'Login') {
    $hexchal = pack ("H32", $challenge);
    if ($uamsecret) {
        $newchal = pack ("H*", md5($hexchal . $uamsecret));
    } else {
        $newchal = $hexchal;
    }
    $response = md5("\0" . $password . $newchal);
    $newpwd = pack("a32", $password);
    $pappassword = implode ("", unpack("H32", ($newpwd ^ $newchal)));
# sleep 5;
# echo 'Content-type: text/html\n\n';
    // LOGIN PART
    echo "
<html>
<head>
  <title>$title</title>
  <meta http-equiv=\"Cache-control\" content=\"no-cache\">
  <meta http-equiv=\"Pragma\" content=\"no-cache\">";
    if (isset($uamsecret) && isset($userpassword)) {
        echo "  <meta http-equiv=\"refresh\" content=\"0;url=http://$uamip:$uamport/logon?username=$username&password=$pappassword\">";
    } else {
        echo "  <meta http-equiv=\"refresh\" content=\"0;url=http://$uamip:$uamport/logon?username=$username&response=$response&userurl=$userurl\">";
    }
    echo "</head>
</body>
<!--
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<WISPAccessGatewayParam 
  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
  xsi:noNamespaceSchemaLocation=\"http://www.acmewisp.com/WISPAccessGatewayParam.xsd\">
<AuthenticationReply>
<MessageType>120</MessageType>
<ResponseCode>201</ResponseCode>
";
    if (isset($uamsecret) && isset($userpassword)) {
        echo "<LoginResultsURL>http://$uamip:$uamport/logon?username=$username&password=$pappassword</LoginResultsURL>";
    } else {
        echo "<LoginResultsURL>http://$uamip:$uamport/logon?username=$username&response=$response&userurl=$userurl</LoginResultsURL>";
    }
    echo "</AuthenticationReply> 
</WISPAccessGatewayParam>
-->
</html>
";
    exit(0);
}

switch($res) {
    case 'success':     $result =  1; break; // If login successful
    case 'failed':      $result =  2; break; // If login failed
    case 'logoff':      $result =  3; break; // If logout successful
    case 'already':     $result =  4; break; // If tried to login while already logged in
    case 'notyet':      $result =  5; break; // If not logged in yet
    case 'smartclient': $result =  6; break; // If login from smart client
    default: $result = 0; // Default: It was not a form request
}

# Otherwise it was not a form request
# Send out an error message
if ($result == 0) {
#    echo "Content-type: text/html\n\n";
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
  <title>$title</title>
  <meta http-equiv=\"Cache-control\" content=\"no-cache\">
  <meta http-equiv=\"Pragma\" content=\"no-cache\">
</head>
<!--body bgColor = '#c0d8f4'-->
  <!--h1 style=\"text-align: center;\">$h1Failed</h1-->

    $centerdaemon

</body>
</html>
";
    exit(0);
}

# Generate the output
#echo "Content-type: text/html\n\n";
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
  <title>$title</title>
  <meta http-equiv=\"Cache-control\" content=\"no-cache\">
  <meta http-equiv=\"Pragma\" content=\"no-cache\">
</head> ";

/*# begin debugging
  print "<center>THE INPUT (for debugging):<br>";
  foreach ($_GET as $key => $value) {
    print $key . "=" . $value . "<br>";
  }
  print "<br></center>";
# end debugging
*/
//Failed login
if ($result == 2) {
    if ($reply) {
       // echo "<center> $reply </center>";
    }
}
// Login page
if ($result == 5) {
    echo "";
}
//Failed login
if ($result == 2 || $result == 5) {
    echo "
  <form name=\"form1\" id=\"form\" method=\"post\" action=\"$loginpath\">
  <input type=\"hidden\" name=\"challenge\" value=\"$challenge\">
  <input type=\"hidden\" name=\"uamip\" value=\"$uamip\">
  <input type=\"hidden\" name=\"uamport\" value=\"$uamport\">
  <input type=\"hidden\" name=\"userurl\" value=\"$userurl\"> ";
    if (isset($_GET['reply']))    $reply        = $_GET['reply'];
    if ($result == 2) {
        if(isset($_GET['reply'])){
            echo "<input type = \"hidden\" name=\"reply\" value=\"$reply\">";
        } else {
            echo "<input type = \"hidden\" name=\"reply\" value=\"$h1Failed\">";
        }
    }
    echo "
  <center>
  <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" style=\"width: 217px;\">
    <tbody>
      <!--tr>
        <td align=\"right\">$centerUsername:</td>
        <td><input style=\"font-family: Arial\" type=\"text\" name=\"UserName\" size=\"20\" maxlength=\"128\"></td>
      </tr>
      <tr>
        <td align=\"right\">$centerPassword:</td>
        <td><input style=\"font-family: Arial\" type=\"password\" name=\"Password\" size=\"20\" maxlength=\"128\"></td>
      </tr-->
      <tr>
        <noscript><td align=\"center\" colspan=\"2\" height=\"23\"><input type=\"submit\" name=\"button\" value=\"Login\" \"></td> </noscript>
      </tr>
    </tbody>
  </table>
  </center>
  </form>
  <script>
    document.getElementById('form').submit(); // SUBMIT FORM
</script>
</body>
</html>";
}
//Logged in redirect
if (($result == 1) || ($result == 4)) {
    echo "
  <meta http-equiv=\"refresh\" content=\"0;url = http://miloaisdino.ml/ci/hs/status\">
  <!--meta http-equiv=\"refresh\" content=\"0;url = ". $userurl ."\"-->
  <p>Logged In</p>
  ";

    if ($reply) {
        //echo "<center> $reply </br></br></center>";
    }

    echo "
  <center>
    <noscript><a href=\"http://$uamip:$uamport/logoff\">Logout</a></noscript>
  </center>
</body>
</html>";
}


if ($result == 11) {
    echo "
  <!--h1 style=\"text-align: center;\"--><p>$h1Loggingin</p><!--/h1-->
  <center>
    $centerPleasewait
  </center>
</body>
</html>";
}


if (($result == 3) || ($result == 13)) {
    echo "
<meta http-equiv=\"refresh\" content=\"1;url=http://$uamip:$uamport/prelogin\">
  <h1 style=\"text-align: center;\">$h1Loggedout</h1>
  <noscript>
  <center>
    <a href=\"http://$uamip:$uamport/prelogin\">$centerLogin</a>
  </center>
  </noscript>
</body>
</html>";
}

exit(0);
?>