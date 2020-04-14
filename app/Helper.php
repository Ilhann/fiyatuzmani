<?php

use \Goutte\Client;

function GetGoutteForCrawler(){

    $client = new \Goutte\Client();
    $client->setHeader('User-Agent', env('HB_USERAGENT', "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36"));
    $client->setHeader('Sec-Fetch-Site', "none");
    $client->setHeader('Sec-Fetch-Mode', "navigate");
    $client->setHeader('Sec-Fetch-User', "?1");
    $client->setHeader('Sec-Fetch-Dest', "document");
    $client->setHeader('Accept-Encoding', "gzip, deflate, br");
    $client->setHeader('Accept-Language', "en-US,en;q=0.9");
    $client->setHeader('Upgrade-Insecure-Requests:', "1");
    $client->setHeader('Accept', "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9");

    return $client;
}