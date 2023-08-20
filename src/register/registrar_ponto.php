<?php

session_start(); // Iniciar a sessao

// Limpar o buffer
ob_start();

// Definir um fuso horario padrao
date_default_timezone_set('America/Sao_Paulo');

// Gerar com PHP o horario atual
$horario_atual = date("H:i:s");
//var_dump($horario_atual);
 
// Gerar a data com PHP no formato que deve ser salvo no BD
$data_entrada_a = date('Y-m-d');

// Incluir a conexao com o banco de dados
include_once("../configs/conexao.php");

// ID do usuario fixo para testar
//echo $_POST['pin'];
$data = $conn->query("SELECT nome,fone,id FROM usuarios where pin = '$_POST[pin]'")->fetchAll();
foreach ($data as $row) {
    $nome = $row['nome']."<br />\n";
    $id_usuario = $row['id'];
}
if(!isset($row['nome'])){
    $_SESSION['msg'] = "<p style='color: red;'>USUARIO NÃO ENCONTRADO";
    header("Location: ../../");
}
$numero = $row['fone'];
$imagem = $_POST['imagem'];




// Recuperar o ultimo ponto do usuario
if(ISSET($id_usuario)){
   echo $query_ponto = "SELECT id AS id_ponto, data_entrada, saida_intervalo, retorno_intervalo, saida 
                    FROM pontos
                    WHERE usuario_id =:usuario_id
                    ORDER BY id DESC
                    LIMIT 1";

    // Preparar a QUERY
    $result_ponto = $conn->prepare($query_ponto);

    // Substituir o link da QUERY pelo valor
    $result_ponto->bindParam(':usuario_id', $id_usuario);

    // Executar a QUERY
    $result_ponto->execute();

    // Verificar se encontrou algum registro no banco de dados
    if (($result_ponto) and ($result_ponto->rowCount() != 0)) {
        // Realizar a leitura do registro
        $row_ponto = $result_ponto->fetch(PDO::FETCH_ASSOC);
        //var_dump($row_ponto);

        // Extrair para imprimir atraves do nome da chave no array
        extract($row_ponto);
        $data_entrada = $row_ponto['data_entrada'];
        // Verificar se o usuario bateu o ponto de saida para o intervalo
        if($data_entrada != $data_entrada_a){
            $tipo_registro = "entrada";
            $col_tipo_registro_ft = "ft_entrada";
            // Texto parcial que deve ser apresentado para o usuario
            $text_tipo_registro = "entrada";
        }else if (($saida_intervalo == "") or ($saida_intervalo == null)) {
            // Coluna que deve receber o valor
            $col_tipo_registro = "saida_intervalo";

            $col_tipo_registro_ft = "ft_saida_i";

            // Tipo de registro
            $tipo_registro = "editar";

            // Texto parcial que deve ser apresentado para o usuario
            $text_tipo_registro = "saída intervalo";
        } elseif (($retorno_intervalo == "") or ($retorno_intervalo == null)) { // Verificar se o usuario bateu o ponto de retorno do intervalo
            // Coluna que deve receber o valor
            $col_tipo_registro = "retorno_intervalo";
            $col_tipo_registro_ft = "ft_volta_i";
            // Tipo de registro
            $tipo_registro = "editar";

            // Texto parcial que deve ser apresentado para o usuario
            $text_tipo_registro = "retorno do intervalo";
        } elseif (($saida == "") or ($saida == null)) { // Verificar se o usuario bateu o ponto de saida
            // Coluna que deve receber o valor
            $col_tipo_registro = "saida";
            $col_tipo_registro_ft = "ft_saida";
            // Tipo de registro
            $tipo_registro = "editar";

            // Texto parcial que deve ser apresentado para o usuario
            $text_tipo_registro = "saída";
        } else { // Criar novo registro no BD com o horrario de entrada
            // Tipo de registro
            $tipo_registro = "entrada";
            $col_tipo_registro_ft = "ft_entrada";
            // Texto parcial que deve ser apresentado para o usuario
            $text_tipo_registro = "entrada";
        }
    } else {
        // Tipo de registro
        $tipo_registro = "entrada";
        $col_tipo_registro_ft = "ft_entrada";
        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "entrada";
    }

    // Verificar o tipo de registro, novo ponto ou editar registro existe
    switch ($tipo_registro) {
            // Acessa o case quando deve editar o registro
        case "editar":
            // Query para editar no banco de dados
            $query_horario = "UPDATE pontos SET $col_tipo_registro =:horario_atual, $col_tipo_registro_ft = '$imagem'
                        WHERE id=:id
                        LIMIT 1";

            


            // Preparar a QUERY
            $cad_horario = $conn->prepare($query_horario);

            // Substituir o link da QUERY pelo valor
            $cad_horario->bindParam(':horario_atual', $horario_atual);
            $cad_horario->bindParam(':id', $id_ponto);
            break;
        default:
            $dia = $conn->query("SELECT id,data_entrada,saida FROM pontos where usuario_id = '$id_usuario' && data_entrada = '$data_entrada_a'")->fetchAll();
            foreach ($dia as $r) {
                $dataa=$r['data_entrada'];
            }
            if(isset($dataa) && $r['saida'] != null){
                
                $_SESSION['msg'] = "<h2 style='color: green;'>JÁ BATEU O PONTO HOJE</h2>";
                header("Location: ../../");
            }else{
                $mensagem = "Hórario de entrada registrado: " . $horario_atual;
                // Query para cadastrar no banco de dados
                $query_horario = "INSERT INTO pontos (data_entrada, entrada, ft_entrada, usuario_id) VALUES ('$data_entrada_a', '$horario_atual', '$imagem', $id_usuario )";
                
                // Preparar a QUERY
                $cad_horario = $conn->prepare($query_horario);
            }
            break;
    }
}
// Executar a QUERY
$cad_horario->execute();

if($text_tipo_registro == "entrada"){
    $mensagem = "Hórario de entrada registrado: " . $horario_atual;
}else if($text_tipo_registro == "saída intervalo"){
    $mensagem = "Hórario do intervalor registrado: " . $horario_atual;
}else if($text_tipo_registro == "retorno do intervalo"){
    $mensagem = "Hórario do retorno do intervalor registrado: " . $horario_atual;
}else if($text_tipo_registro == "saída"){
    $mensagem = "Hórario de saida registrado: " . $horario_atual;
}
$verif_s = $conn->query("SELECT * FROM settings")->fetchAll();
foreach ($verif_s as $zap) {}
if($zap['zap_fun'] == 'on'){
    include_once("../configs/mensagem-zap.php");
}

if($zap['zap_dir'] == 'on'){
    $numero = $zap['fone_dir'];
    $text=$mensagem;
   echo  $mensagem = " Funcionario: " . $nome .". " . $mensagem;
    include_once("../configs/mensagem-zap.php");
}

// Acessa o IF quando cadastrar com sucesso
if ($cad_horario->rowCount()) {
    $_SESSION['msg'] = "<h2 style='color: green;'>" . $nome . "</h2><p style='color: green;'><b>seu horário de $text_tipo_registro cadastrado com sucesso!</b></p>";
   // header("Location: ../../");
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>" . $nome . " seu horário de $text_tipo_registro não cadastrado com sucesso! "; if($text_tipo_registro == "saída"){echo "Até Amanha!";} echo "</p>";
  //  header("Location: ../../");
}