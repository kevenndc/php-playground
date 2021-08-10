<?php

echo 'Iniciando script...' . PHP_EOL;
    
$ch = curl_init('https://br.investing.com/indices/bovespa-components');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0');

echo 'Buscando dados...' . PHP_EOL;
$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);
curl_close($ch);

echo 'Dados carregados!' . PHP_EOL;
echo 'Convertendo dados...' . PHP_EOL;

$document = new DOMDocument();
@$document->loadHTML($body);
$xpath = new DOMXPath($document);

$rows = $xpath->evaluate('//*[contains(concat(" ",normalize-space(@class)," ")," crossRatesTbl ")]/tbody/tr');

$items = [];

foreach ($rows as $row) {
    $items[] = @getContentFromNodeList($row->childNodes);
}

echo 'Escrevendo dados em arquivo JSON...' . PHP_EOL;

$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
fclose($fp);

echo 'Processo finalizado!' . PHP_EOL;

function getContentFromNodeList($nodeList) {
    return [
        'nome' => $nodeList->item(1)->textContent,
        'ultimo' => $nodeList->item(2)->textContent,
        'maxima' => $nodeList->item(3)->textContent,
        'minima' => $nodeList->item(4)->textContent,
        'var' => $nodeList->item(5)->textContent,
        'var_pctg' => $nodeList->item(6)->textContent,
        'vol' => $nodeList->item(7)->textContent,
        'hora' => $nodeList->item(8)->textContent,
    ];
}