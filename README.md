# iOS-msg-push-test-php-sample

A sample of pushing message to iOS devices use PHP codes.


## configuration steps
* **config APNs certificate and export the push certicate as p12 format.**
* **convert the p12 certificate to pem format, and put the pem file in the some directory path with the AppPushNotification.php file**: 

 
  open your terminal and type the commnd like this  
  
  ```
  openssl pkcs12 -in XXXX.p12 -out XXXX.pem -nodes
  ```
  
* **modify some values of the AppPushNotification.php codes:**  

	* device token  
	
	```
	$deviceToken = 'XXXX your device token XXXX';
	```
	
	* p12 file password

	```
	$passphrase = 'XXX your p12 file password XXX';
	```
	
	* pem file name

	```
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'XXXXX.pem');
	```