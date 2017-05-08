<?php
//设备 token
$deviceToken = 'c40ec3dc92d2abe9f4e6caf540c24cf9cbe0a38b7aa80d25b5f2b5fc4b80bbc7';

//p12 证书密码
$passphrase = '123456';

// Put your alert message here:
$message = 'My notification';

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'allow_self_signed', true);
stream_context_set_option($ctx, 'ssl', 'verify_peer', false);

//这里 pem 文件名主要改成对应的文件名称，通过 p12 证书生成 pem 证书的命令： openssl pkcs12 -in XXXX.p12 -out XXXX.pem -nodes
stream_context_set_option($ctx, 'ssl', 'local_cert', 'APNs_dev.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
//这个为正式的发布地址
//$fp = stream_socket_client(“ssl://gateway.push.apple.com:2195“, $err, $errstr, 60, //STREAM_CLIENT_CONNECT, $ctx);

//这个是沙盒测试地址，发布到appstore后记得修改哦
 $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
//$fp=stream_socket_client("udp://127.0.0.1:1113", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
if (!$fp)
exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
'alert' => $message,
'sound' => 'defalt'
);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
echo 'Message not delivered' . PHP_EOL;
else
echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
?>
