told-php-client
==========
[![Build Status](https://travis-ci.org/petershaw/told-php-client.png?branch=0.0.1)](https://travis-ci.org/petershaw/told-php-client)
[![Latest Stable Version](https://poser.pugx.org/petershaw/told-client/v/stable.png)](https://packagist.org/packages/petershaw/told-client)
---

 A client to log messates into a told log recorder.
 See <https://github.com/petershaw/told-LogRecorder> to lern more about told.

Description
----------
Sends a message to the server. Noting more, nothing less.

Installation
---------
###Composer ###
The easiest way is to use [Composer](http://getcomposer.org). Add 

```json
    "require": {
        petershaw/told-client
    }
```

to your composer.json file and run composer install on your project.

### Downlaod ###

As an alternative to composer, it is possible to download the latest and greatest version of [ToldClient.php](https://github.com/petershaw/told-php-client/blob/master/src/petershaw/told/ToldClient.php) from github. and require it from within your script.

```php
require('/path/to/your/downloaded//libs/ToldClient.php');
```

Usage
---------
###Configuration###
First of all you have to initiate the Client with a few minimal configurations. It is up to you how you set them. It is possible to pass an config array to the init method of the client, or to initiate the client blank and set the config-params later on. 

#### Example 1: ####

```php
	$conf = Array();
	$conf['host'] = "test://told.my-domain.com";
	$conf['type'] = "Application Name";
	$told = new ToldClient($config);
```

#### Example 2: ####

```php
     $told = new ToldClient();
     $told->setHost("test://told.my-domain.com");
     $told->setType("Application Name");
```

The 'host' parameter is **mandatory**.!
Optional parameters are: type, tags, defaulttage and debug.

| tag | Description |
| ------	| ------	|  
|type | Describes the type of this log message. Choose the type wisely, because you will group by type at the administration frontend. |  
|tags |   These tags will be send ALWAYS right next to the tags that are send by the call it self. It is a good decision to add your application-id and maybe customer-id as tags. |  
|defaultags | To set some default tags that will be overwritten by the call . If no tags are given, than the defaulttags take place. |  

### Send ###

After initialisation the client is ready to use.
To send a message with the default tags and type, just call

```php
$told->tell("This is my little test message");
```

Each call can have special types and tags. For example: to send a message with a type of 'Testing' and the Tag 'Honigkuchen' call

```php
$told->tell("This is my little test message", "Testing", "Honigkuchen");
```

Or to send multiple tags, like Honigkuchen and Zuckerschlecken it is possible to pass a array:

```php
$told->tell("This is my little test message", "Testing", Array("Honigkuchen", "Zuckerschlecken"));
```


 Protokoll
----------

As default, this client use http POST over tcp ip. 

Limitations
----------

The current [Told-LogRecorder](https://github.com/petershaw/told-LogRecorder) supports structured messages in schema less format. This feature is not supported by this client, yet.

