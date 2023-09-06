<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ./configs/login.php');
}
if (isset($_GET['logout']) && $_GET['logout'] = 1) {
    unset($_SESSION['id']);
    header('Location: ./configs/login.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/style_dashboard.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Dashboard</title>
</head>

<body>
    <div class="navbar">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Registro</a>
                <div class="dropdown-content">
                    <a href="./register/cadastro.php">Cadastro de Funcionarios</a>
                    <a href="./register/funcionarios.php">Funcionarios</a>
                    <a href="./register/registro_manual.php">Ponto Manual</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">View</a>
                <div class="dropdown-content">
                    <a href="./view/Registros.php">Todos os Registros</a>
                    <a href="./view/filtro_registros.php">Filtrar Registros</a>
                </div>
            </li>
            <li><a href="./configs/settings.php">Configurações</a></li>

            <li class="di"><a href="./index.php?logout=1">Deslogar</a></li>
            <li class="di"><a href="../">Voltar ao Ponto</a></li>
        </ul>
    </div>
    <div style="display: flex; justify-content: center; align-items:center;">
        <div class="borda2">
            <table>
                <h3>Registro de Ponto Diario</h3>
                <tr>
                    <th>Funcionario</th>
                    <th>Entrada</th>
                    <th>Saida Intervalo</th>
                    <th>Volta Intervalo</th>
                    <th>Saida</th>
                </tr>
                <?php
                include_once("./configs/conexao.php");
                $verif_p = $conn->query("SELECT pontos.data_entrada, pontos.entrada, pontos.saida_intervalo, pontos.retorno_intervalo, pontos.saida, usuarios.nome FROM pontos INNER JOIN usuarios ON usuarios.id = pontos.usuario_id where pontos.data_entrada = '" . date('Y-m-d') . "'")->fetchAll();
                foreach ($verif_p as $row) {
                    if ($row['saida_intervalo'] != null) {
                        $saida_i = $row['saida_intervalo'];
                    } else {
                        $saida_i = "❌";
                    }
                    if ($row['retorno_intervalo'] != null) {
                        $volta_i = $row['retorno_intervalo'];
                    } else {
                        $volta_i = "❌";
                    }
                    if ($row['saida'] != null) {
                        $saida = $row['saida'];
                    } else {
                        $saida = "❌";
                    }
                    echo "<tr> <th>" . $row['nome'] . "</th> <th>" . $row['entrada'] . "</th> <th>" . $saida_i . "</th> <th>" . $volta_i . "</th> <th>" . $saida . "</th> </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>