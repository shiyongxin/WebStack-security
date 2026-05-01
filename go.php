<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
date_default_timezone_set('Asia/Shanghai');

$url = isset($_GET['url']) ? $_GET['url'] : '';
$b = '';

if ( ! empty($url) ) {
    $decoded_url = base64_decode($url);

    if ( filter_var($decoded_url, FILTER_VALIDATE_URL) ) {
        $parsed = parse_url($decoded_url);
        $scheme = isset($parsed['scheme']) ? strtolower($parsed['scheme']) : '';
        $host = isset($parsed['host']) ? strtolower($parsed['host']) : '';

        $allowed_protocols = array('http', 'https');

        // Check if host is an internal IP using ip2long
        $host_ip = gethostbyname($host);
        $is_internal = false;

        // Block localhost, loopback, and link-local addresses
        $blocked_patterns = array(
            '/^127\./',                           // 127.0.0.0/8 (loopback)
            '/^10\./',                            // 10.0.0.0/8 (private)
            '/^172\.(1[6-9]|2[0-9]|3[0-1])\./',  // 172.16.0.0/12 (private)
            '/^192\.168\./',                      // 192.168.0.0/16 (private)
            '/^169\.254\./',                      // 169.254.0.0/16 (link-local)
            '/^0\./',                             // 0.0.0.0/8
            '/^224\./',                           // 224.0.0.0/4 (multicast)
            '/^240\./',                           // 240.0.0.0/4 (reserved)
            '/^::1$/',                            // IPv6 loopback
            '/^fe80:/i',                          // IPv6 link-local
            '/^fc00:/i',                          // IPv6 unique local
            '/^fd00:/i',                          // IPv6 unique local
        );

        foreach ( $blocked_patterns as $pattern ) {
            if ( preg_match($pattern, $host) || preg_match($pattern, $host_ip) ) {
                $is_internal = true;
                break;
            }
        }

        // Double-check: if host resolves to a private IP, also block
        if ( !$is_internal && $host_ip != $host ) {
            foreach ( $blocked_patterns as $pattern ) {
                if ( preg_match($pattern, $host_ip) ) {
                    $is_internal = true;
                    break;
                }
            }
        }

        if ( in_array($scheme, $allowed_protocols, true) && ! $is_internal ) {
            $b = $decoded_url;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta http-equiv="refresh" content="0.1;url=<?php echo esc_url($b); ?>">
<meta name="robots" content="noindex,follow">
<title><?php _e('加载中','i_theme') ?></title>

<script type="text/javascript">
var msg = document.title;
msg = "" + msg;pos = 0;
function scrollMSG() {
	document.title = msg.substring(pos, msg.length) + msg.substring(0, pos);
	pos++;
	if (pos >  msg.length) pos = 0
	window.setTimeout("scrollMSG()",200);
}
scrollMSG();
</script>

<style>body{overflow:hidden;background:#17607D}.container{display:flex;justify-content:center;align-items:center;height:100vh;overflow:hidden;animation-delay:1s}.sk-cube-grid {width: 60px;height: 60px;margin-top:-45px;}.sk-cube-grid .sk-cube {width: 33.33%;height: 33.33%;background-color: #fff;float: left;-webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;}.sk-cube-grid .sk-cube1 {-webkit-animation-delay: 0.2s;animation-delay: 0.2s;}.sk-cube-grid .sk-cube2 {-webkit-animation-delay: 0.3s;animation-delay: 0.3s;}.sk-cube-grid .sk-cube3 {-webkit-animation-delay: 0.4s;animation-delay: 0.4s;}.sk-cube-grid .sk-cube4 {-webkit-animation-delay: 0.1s;animation-delay: 0.1s;}.sk-cube-grid .sk-cube5 {-webkit-animation-delay: 0.2s;animation-delay: 0.2s;}.sk-cube-grid .sk-cube6 {-webkit-animation-delay: 0.3s;animation-delay: 0.3s;}.sk-cube-grid .sk-cube7 {-webkit-animation-delay: 0.0s;animation-delay: 0.0s;}.sk-cube-grid .sk-cube8 {-webkit-animation-delay: 0.1s;animation-delay: 0.1s;}.sk-cube-grid .sk-cube9 {-webkit-animation-delay: 0.2s;animation-delay: 0.2s;}@-webkit-keyframes sk-cubeGridScaleDelay {0%,70%,100% {-webkit-transform: scale3D(1, 1, 1);transform: scale3D(1, 1, 1);}35% {-webkit-transform: scale3D(0, 0, 1);transform: scale3D(0, 0, 1);}}@keyframes sk-cubeGridScaleDelay {0%,70%,100% {-webkit-transform: scale3D(1, 1, 1);transform: scale3D(1, 1, 1);}35% {-webkit-transform: scale3D(0, 0, 1);transform: scale3D(0, 0, 1);}}</style>
</head>
<body>
	<div class="container">
		<div class="sk-cube-grid">
        	<div class="sk-cube sk-cube1"></div>
        	<div class="sk-cube sk-cube2"></div>
        	<div class="sk-cube sk-cube3"></div>
        	<div class="sk-cube sk-cube4"></div>
        	<div class="sk-cube sk-cube5"></div>
        	<div class="sk-cube sk-cube6"></div>
        	<div class="sk-cube sk-cube7"></div>
        	<div class="sk-cube sk-cube8"></div>
        	<div class="sk-cube sk-cube9"></div>
      	</div>
	</div>
</body>
</html>