<?php
session_start();

if (isset($_SESSION['id'])) {
    header('Location: ../');
}

if (isset($_POST['email']) && isset($_POST['pin'])) {

    include_once("./conexao.php");

    $quey = $conn->query("SELECT id,super FROM usuarios WHERE email = '$_POST[email]' AND pin = '$_POST[pin]';")->fetchAll();
    foreach ($quey as $row) {
        if ($row['id'] > 0 && $row['super'] == 1) {
            $_SESSION['id'] = $row['id'];
            header('Location: ../');
        } else if ($row['id'] > 0 && $row['super'] == 0) {
            echo "Usuario não tem permição para Acessar Painel ADM";
        }
    }
    if (!isset($row['id'])) {
        echo "Email ou Senha Incorreto!";
    }
}
?>
<a href="../../">Voltar para ponto</a>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form action="./login.php" method="post"><br><br><br>
        <h2>Login</h2>
        <input type="email" name="email" id="email" placeholder="Incira seu e-mail" required><br><br>
        <input type="password" name="pin" minlength="4" maxlength="4" id="pin" placeholder="PIN de 4 digitos"
            required><br><br><br>
        <button type="submit">Login</button>
    </form>

</body>

</html>