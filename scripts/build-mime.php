<?php
require __DIR__ . '/../vendor/autoload.php';

use ngyuki\FunkyBuiltinWebserver\MimeResolver;
use ngyuki\FunkyBuiltinWebserver\Resource;

$res = new Resource();
$obj = new MimeResolver(false);
$obj->load($res->filename('mime.types'));
$obj->load($res->filename('mime.types.override'));
$obj->save();
