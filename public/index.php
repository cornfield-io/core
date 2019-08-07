<?php
declare(strict_types=1);

use Cornfield\Core\Response\TextResponse;

define('ROOT', dirname(__DIR__));

require_once ROOT.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

TextResponse::fromScratch('test');
