<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../configs/login.php');
}
include_once "../configs/conexao.php";
$check = 0;
if (isset($_POST['nome'])) {
    $data = $conn->query("select * from usuarios")->fetchAll();
    foreach ($data as $row) {
        if (isset($row['pin']) && $row['pin'] == $_POST['pin']) {
            $_SESSION['msg'] = "<p style='color: red;'>Senha já em uso!</p>";
            $check = 1;
        }
        if (isset($row['email']) && $row['email'] == $_POST['email']) {
            $_SESSION['msg'] = "<p style='color: red;'>Email já em uso!</p>";
            $check = 1;
        }
        if (isset($row['CPF']) && $row['CPF'] == $_POST['cpf']) {
            $_SESSION['msg'] = "<p style='color: red;'>CPF já cadastrado!</p>";
            $check = 1;
        }
    }
    if (isset($_POST['super']) && $_POST['super'] == "on") {
        $super = 1;
    } else {
        $super = 0;
    }
    if ($check == 0) {
        $cad = "insert into usuarios (nome,email,CPF,fone,cargo,h_entrada,h_saida_i,h_volta_i,h_saida,h_sab_entrada,h_sab_saida,super,pin) values ('$_POST[nome]','$_POST[email]','$_POST[cpf]','$_POST[fone]','$_POST[cargo]','$_POST[h_entrada]','$_POST[h_saida_i]','$_POST[h_volta_i]','$_POST[h_saida]','$_POST[ent_sab]','$_POST[sai_sab]',$super,'$_POST[pin]')";
        $result_cad = $conn->prepare($cad);
        $result_cad->execute();

        if ($result_cad->rowCount()) {
            $_SESSION['msg'] = "<h2 style='color: green;'>" . $_POST['nome'] . "</h2><p style='color: green;'><b> cadastrado com sucesso!!</b></p>";
            $numero = $_POST['fone'];
            $mensagem = "Parabens " . $_POST['nome'] . ", Você foi contratado!";
            include_once("../configs/mensagem-zap.php");
            header("Location: ./funcionarios.php");
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <title>Cadastro</title>
    <script>
        function mascara(i) {

            var v = i.value;

            if (isNaN(v[v.length - 1])) { // impede entrar outro caractere que não seja número
                i.value = v.substring(0, v.length - 1);
                return;
            }

            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";

        }
    </script>
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

    <h1>Cadastro de Funcionario</h1>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <form action="./cadastro.php" method="post">
        <b>Dados do Funcionario</b><br>
        <input name="nome" type="text" placeholder="Nome" required>
        <input name="cpf" oninput="mascara(this)" type="text" maxlength="4" placeholder="CPF" required><br><br>
        <input type="text" name="cargo" placeholder="Informe o Cargo">
        <input type="tel" name="fone" placeholder="Telefone com DDD" required>
        <input name="email" type="email" placeholder="email" required><br><br>
        <b>Horarios Nominais(SEG a SEX):</b><br>
        <input name="h_entrada" type="time" value="08:00:00" required>
        <input name="h_saida_i" type="time" value="12:00:00" required>
        <input name="h_volta_i" type="time" value="14:00:00" required>
        <input name="h_saida" type="time" value="18:00:00" required><br><br>
        Horarios Nominais(Sab):<br>
        <input type='time' value='08:00:00' name='ent_sab' required>
        <input type='time' value='12:00:00' name='sai_sab' required>
        <br><br>
        Tem acesso ao painel ADM:
        <input type="checkbox" name="super" id=""><br><br>
        <b>Pin para Registro do ponto</b><br>
        <input name="pin" type="password" maxlength="4" placeholder="PIN de 4 digitos" required><br><br>
        <button type="submit">Salvar funcionario</button>
    </form>
</body>

</html>