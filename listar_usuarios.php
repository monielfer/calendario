<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber o valor enviado na URL
$profissional = filter_input(INPUT_GET, 'profissional', FILTER_DEFAULT);

// Verificar se o parâmetro profissional foi enviado
if (!empty($profissional)) {

    // QUERY para recuperar os usuários
    $query_users = "SELECT id, name 
                FROM users 
                WHERE profissional = :profissional
                ORDER BY name ASC";
    //$query_users = "SELECT id, name FROM users WHERE id = 100 ORDER BY name ASC";

    // Prepara a QUERY
    $result_users = $conn->prepare($query_users);

    // Atribuir o valor do parâmetro
    $result_users->bindParam(':profissional', $profissional);
} else {
    // QUERY para recuperar os usuários
    $query_users = "SELECT id, name FROM users ORDER BY name ASC";
    //$query_users = "SELECT id, name FROM users WHERE id = 100 ORDER BY name ASC";

    // Prepara a QUERY
    $result_users = $conn->prepare($query_users);
}

// Executar a QUERY
$result_users->execute();

// Acessar o IF quando encontrar usuário no banco de dados
if (($result_users) and ($result_users->rowCount() != 0)) {

    // Ler os registros recuperado do banco de dados
    $dados = $result_users->fetchAll(PDO::FETCH_ASSOC);

    // Criar o array com o status e os dados
    $retorna = ['status' => true, 'dados' => $dados];
} else {

    // Criar o array com o status e os dados
    $retorna = ['status' => false, 'msg' => "Nenhum usuário encontrado"];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
