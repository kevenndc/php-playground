<?php
    
$ch = curl_init('https://br.investing.com/indices/bovespa-components');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0');

$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);
curl_close($ch);

$document = new DOMDocument();
$document->loadHTML($body);
$xpath = new DOMXPath($document);

var_dump($xpath);