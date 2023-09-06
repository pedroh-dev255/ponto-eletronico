<?php

session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../configs/login.php');
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../style/style_relatorio.css">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <title>Relatorios</title>
</head>

<body>
    <div class="navbar">
        <ul>
            <li><a href="../">Dashboard</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Registro</a>
                <div class="dropdown-content">
                    <a href="../register/cadastro.php">Cadastro de Funcionarios</a>
                    <a href="../register/funcionarios.php">Funcionarios</a>
                    <a href="../register/registro_manual.php">Ponto Manual</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">View</a>
                <div class="dropdown-content">
                    <a href="../view/Registros.php">Todos os Registros</a>
                    <a href="../view/filtro_registros.php">Filtrar Registros</a>
                </div>
            </li>
            <li><a href="../configs/settings.php">Configurações</a></li>
            <li class="di"><a href="../index.php?logout=1">Deslogar</a></li>
            <li class="di"><a href="../../">Voltar ao Ponto</a></li>
        </ul>
    </div>

    <table>
        <tr>
            <th>Id</th>
            <th>Data Registro</th>
            <th>Ent</th>
            <th>Foto Ent</th>
            <th>Saida para Int</th>
            <th>Foto Saida para Int</th>
            <th>Volta do Int</th>
            <th>Foto Volta do Int</th>
            <th>Saida</th>
            <th>Foto Saida</th>
            <th>id_user</th>
            <th>Obs</th>
            <th>Editar Registro</th>
        </tr>
        <?php
        include_once("../configs/conexao.php");
        $data = $conn->query("SELECT * FROM pontos")->fetchAll();
        foreach ($data as $row) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['data_entrada'] . "</td><td>" . $row['entrada'] . "</td><td><img width='50px' src='" . $row['ft_entrada'] . "'></td><td>" . $row['saida_intervalo'] . "</td><td><img width='50px' src='" . $row['ft_saida_i'] . "'></td><td>" . $row['retorno_intervalo'] . "</td><td><img width='50px' src='" . $row['ft_volta_i'] . "'></td><td>" . $row['saida'] . "</td><td><img width='50px' src='" . $row['ft_saida'] . "'></td><td>" . $row['usuario_id'] . "</td><td>" . $row['obs'] . "</td><td><a href='../register/edicao.php?id=" . $row['id'] . "'>Editar</a></td></tr>";
        }
        ?>
    </table>
</body>

</html>