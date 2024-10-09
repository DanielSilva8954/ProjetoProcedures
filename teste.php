<?php
// URL da API SMMRY com o texto a ser resumido
$url = "https://api.smmry.com/&SM_API_KEY=YOUR_API_KEY&sm_api_input=texto_aqui";

// Inicializando o cURL
$curl = curl_init($url);

// Configurações do cURL
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Enviar a requisição e obter a resposta
$response = curl_exec($curl);

// Verificando erros
if ($response === false) {
    echo 'Erro: ' . curl_error($curl);
} else {
    echo "Resumo: " . $response;
}

// Fechar a conexão cURL
curl_close($curl);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Proprietário</title>
    <style>

    </style>
</head>
<body>

</body>
</html>
