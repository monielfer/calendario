<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviado pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Recuperar os dados do usuário no banco de dados
$query_user = "SELECT id, name, email FROM users WHERE id =:id LIMIT 1";

// Prepara a QUERY
$result_user = $conn->prepare($query_user);

// Substituir o link pelo valor
$result_user->bindParam(':id', $dados['cad_user_id']);

// Executar a QUERY
$result_user->execute();

// Ler os dados do usuário
$row_user = $result_user->fetch(PDO::FETCH_ASSOC);


// Recuperar os dados do cliente no banco de dados
$query_client = "SELECT id, name, email FROM users WHERE id =:id LIMIT 1";

// Prepara a QUERY
$result_client = $conn->prepare($query_client);

// Substituir o link pelo valor
$result_client->bindParam(':id', $dados['cad_client_id']);

// Executar a QUERY
$result_client->execute();

// Ler os dados do cliente
$row_client = $result_client->fetch(PDO::FETCH_ASSOC);

// Criar a QUERY cadastrar evento no banco de dados
$query_cad_event = "INSERT INTO events (title, color, start, end, obs, user_id, client_id) VALUES (:title, :color, :start, :end, :obs, :user_id, :client_id)";

// Prepara a QUERY
$cad_event = $conn->prepare($query_cad_event);

// Substituir o link pelo valor
$cad_event->bindParam(':title', $dados['cad_title']);
$cad_event->bindParam(':color', $dados['cad_color']);
$cad_event->bindParam(':start', $dados['cad_start']);
$cad_event->bindParam(':end', $dados['cad_end']);
$cad_event->bindParam(':obs', $dados['cad_obs']);
$cad_event->bindParam(':user_id', $dados['cad_user_id']);
$cad_event->bindParam(':client_id', $dados['cad_client_id']);

// Verificar se consegui cadastrar corretamente
if ($cad_event->execute()) {
    $retorna = [
        'status' => true, 
        'msg' => 'Evento cadastrado com sucesso!', 
        'id' => $conn->lastInsertId(), 
        'title' => $dados['cad_title'], 
        'color' => $dados['cad_color'], 
        'start' => $dados['cad_start'], 
        'end' => $dados['cad_end'], 
        'obs' => $dados['cad_obs'], 
        'user_id' => $row_user['id'], 
        'name' => $row_user['name'], 
        'email' => $row_user['email'], 
        'client_id' => $row_client['id'], 
        'client_name' => $row_client['name'], 
        'client_email' => $row_client['email']
    ];
} else {
    $retorna = ['status' => false, 'msg' => 'Erro: Evento não cadastrado!'];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
