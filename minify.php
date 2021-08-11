<?php
//Para minificar todos os arquivos da pasta basta digitar o comando
//php minify.php
//Para minificar um arquivo específico basta colocar o nome do arquivo sem a extensão
//php minify.php app

$param = $argv[1] ?? "";
$dir = dir(__DIR__ . '/minify');

//Se não passar parâmetro minifica todos os arquivos da pasta
if (empty($param)) {
    while ($arch = $dir->read()) {
        require $dir . '/' . $arch;
    }
    $dir->close();
    echo "success";
    return;
}

//Se o arquivo existir na pasta, faz a minificação
if (file_exists(__DIR__ . '/minify/' . $param . '.php')) {
    require __DIR__ . '/minify/' . $param . '.php';
    echo "success";
    return;
}

echo "File not found";

