<?php
require_once 'Hawk/LixiServiceClient.php';
require_once 'Log.php';
require_once 'HTTP/Request2/Observer/Log.php';

$log = Log::factory("console");

$endpoint = 'http://127.0.0.1:3000/lixi/';
$request = new HTTP_Request2($endpoint);
$observer = new HTTP_Request2_Observer_Log($log);
$request->attach($observer);

$hawk = new Hawk_LixiServiceClient($request, $endpoint);

$hawk->order("<xml>Hi</xml>");

$hawk->cancel("<xml>Hi</xml>");

$hawk->quote("<xml>Hi</xml>");

$hawk->update("<xml>Hi</xml>");