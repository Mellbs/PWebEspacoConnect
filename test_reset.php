<?php
// Test script to call reset_password.php
$url = 'http://localhost/2ids/v100_EspacoConnect/api/auth/reset_password.php';

$data = [
    'email' => 'Usuario1@gmail.com',
    'nova_senha' => 'NovaSenha123'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Erro ao chamar a API\n";
} else {
    echo "Resposta da API: " . $result . "\n";
}
?>
