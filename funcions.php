<?php
//error_reporting(0);  

define('CLAU' ,
'LCIxJyRUUXpsRzQ2KW1JNT9aLlBMJWJbR0Y8dn41Wzw7Vg==
bCNrNiV2ZE4jcCxqMlA0LQ==
InlmcmRUdkFbQykgRi1eN0Q7TXtkdGopW3M=
ZCU+bFE1Nmw2
RXhtVDYrMk5qV15MSSEkMGZrYmIxdGI=
Vy9BYDFKcCI6Qkw4d2VeNnpLV11LKF52OjJ6b0taL35rZW5qJW0=
YCJHVTwqeCU0NS9ePWUwNGRDajlXLFRPWGQhIW1DKiJJTzpdKkUqJw==
QGVMZVlzXksvbC13XiAoS247Rmx1JCklXToma1hEYXxCOkItSihzW2pPVS8=
KXtpLXFPJ1hPUmAhJXpSIzpiU2RBZE4hRFw5YzwqZw==
O0w5a29dOCx1W2k7JGhER1BxUUpF
PFZBKXone1k1MmckLWFWPEh2NjNVPyBhUUdCYFdDSHR+KjY=
aCxpWUI2fVpPen1lZ3FLXmwwITNKY2dx
eCd6dkl0b0tLLU1FdzpFYW8=
NDl0c2k=
NVB5MT03TXErfV9EVncmM34=');

function my_encrypt($data) {
	$encryption_key = base64_decode(CLAU);
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc"));
	$encrypted = openssl_encrypt($data, "aes-256-cbc", $encryption_key, 0, $iv);
	return base64_encode($encrypted . "::" . $iv);
}

function my_decrypt($data) {
	$encryption_key = base64_decode(CLAU);
	list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
	return openssl_decrypt($encrypted_data, "aes-256-cbc", $encryption_key, 0, $iv);
}

function EncriptaArch($Arxiu){
		$msg = file_get_contents($Arxiu);
		$msg_encrypted = my_encrypt($msg, CLAU);
		$file = fopen($Arxiu, "wb");
		fwrite($file, $msg_encrypted);
		fclose($file);
}

function DesEncriptaArch($Arxiu){
		$msg = file_get_contents($Arxiu);
		$msg_encrypted = my_decrypt($msg, CLAU);
		$file = fopen($Arxiu, "wb");
		fwrite($file, $msg_encrypted);
		fclose($file);
}



?>