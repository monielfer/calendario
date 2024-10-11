<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber o id do usuário
$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

// Receber o id do cliente
$client_id = filter_input(INPUT_GET, 'client_id', FILTER_SANITIZE_NUMBER_INT);

// Verificar se o parâmetro user_id tem valor e client_id está vazio
if (!empty($user_id) and empty($client_id)) {

    // QUERY para recuperar os eventos
    $query_events = "SELECT evt.id, evt.title, evt.color, evt.start, evt.end, evt.obs, evt.user_id, evt.client_id,
                usr.name, usr.email,
                cli.name AS name_cli, cli.email AS email_cli
                FROM events AS evt
                INNER JOIN users AS usr ON usr.id = evt.user_id
                INNER JOIN users AS cli ON cli.id = evt.client_id
                WHERE evt.user_id = :user_id";

    // Prepara a QUERY
    $result_events = $conn->prepare($query_events);

    // Atribuir o valor do parâmetro
    $result_events->bindParam(':user_id', $user_id, PDO::PARAM_INT);

} elseif (empty($user_id) and !empty($client_id)) { // Verificar se o parâmetro user_id está vazio e client_id tem valor 

    // QUERY para recuperar os eventos
    $query_events = "SELECT evt.id, evt.title, evt.color, evt.start, evt.end, evt.obs, evt.user_id, evt.client_id,
                usr.name, usr.email,
                cli.name AS name_cli, cli.email AS email_cli
                FROM events AS evt
                INNER JOIN users AS usr ON usr.id = evt.user_id
                INNER JOIN users AS cli ON cli.id = evt.client_id
                WHERE evt.client_id = :client_id";

    // Prepara a QUERY
    $result_events = $conn->prepare($query_events);

    // Atribuir o valor do parâmetro
    $result_events->bindParam(':client_id', $client_id, PDO::PARAM_INT);

} elseif (!empty($user_id) and !empty($client_id)) { // Verificar se o parâmetro user_id tem valor e client_id tem valor 

    // QUERY para recuperar os eventos
    $query_events = "SELECT evt.id, evt.title, evt.color, evt.start, evt.end, evt.obs, evt.user_id, evt.client_id,
                usr.name, usr.email,
                cli.name AS name_cli, cli.email AS email_cli
                FROM events AS evt
                INNER JOIN users AS usr ON usr.id = evt.user_id
                INNER JOIN users AS cli ON cli.id = evt.client_id
                WHERE evt.user_id = :user_id
                AND evt.client_id = :client_id";

    // Prepara a QUERY
    $result_events = $conn->prepare($query_events);

    // Atribuir o valor do parâmetro
    $result_events->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $result_events->bindParam(':client_id', $client_id, PDO::PARAM_INT);

} else {

    // QUERY para recuperar os eventos
    $query_events = "SELECT evt.id, evt.title, evt.color, evt.start, evt.end, evt.obs, evt.user_id, evt.client_id,
                usr.name, usr.email,
                cli.name AS name_cli, cli.email AS email_cli
                FROM events AS evt
                INNER JOIN users AS usr ON usr.id = evt.user_id
                INNER JOIN users AS cli ON cli.id = evt.client_id";

    // Prepara a QUERY
    $result_events = $conn->prepare($query_events);
}

// Executar a QUERY
$result_events->execute();

// Criar o array que recebe os eventos
$eventos = [];

// Percorrer a lista de registros retornado do banco de dados
while ($row_events = $result_events->fetch(PDO::FETCH_ASSOC)) {

    // Extrair o array
    extract($row_events);

    $eventos[] = [
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $start,
        'end' => $end,
        'obs' => $obs,
        'user_id' => $user_id,
        'name' => $name,
        'email' => $email,
        'client_id' => $client_id,
        'client_name' => $name_cli,
        'client_email' => $email_cli
    ];
}

echo json_encode($eventos);
