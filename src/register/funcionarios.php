<?php
    session_start();

    if(!isset($_SESSION['id'])){
        header('Location: ../configs/login.php');
    }

    include_once("../configs/conexao.php");

    if(isset($_POST['id'])){
        $ss="UPDATE `ponto`.`usuarios` SET `nome` = '".$_POST['nome']."',`email` = '".$_POST['email']."',`cargo` = '".$_POST['cargo']."',`fone` = '".$_POST['telefone']."',`pin` = '".$_POST['pin']."',`h_entrada` = '".$_POST['ent01']."',`h_saida_i` = '".$_POST['sai01']."',`h_volta_i` = '".$_POST['ent02']."',`h_saida` = '".$_POST['sai02']."',`h_sab_entrada` = '".$_POST['ent_sab']."',`h_sab_saida` = '".$_POST['sai_sab']."'  WHERE (`id` = '".$_POST['id']."');";
        if($conn->query($ss) == true){
            echo "registro atualizado";
        }else{
            echo "erro: ".error;
        }
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <link rel="stylesheet" href="../style/style.css">
    <title>Funcionarios</title>
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
    <br>
    <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
        <h1>Lista de Funcionarios</h1>
        <br><br>
        <table border="1px" style="width: 90%;">
                <tr>
                    <th>Nome Completo</th>
                    <th>Cargo</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>PIN</th>
                    <th>Edit</th>
                </tr>
                <?php 
                    $sql=$conn->query("SELECT * FROM usuarios");
                    foreach($sql as $row){
                        echo "<tr><td>". $row['nome'] ."</td><td>". $row['cargo'] ."</td><td>". $row['email'] ."</td><td>". $row['fone'] ."</td><td>". $row['pin'] ."</td><td><a href='./funcionarios.php?id=".$row['id']."'>Editar</a></td></tr>";
                    }
                ?>
        </table>
        <?php
            if(isset($_GET['id'])){
                $select=$conn->query("select * from usuarios where id = $_GET[id]");
                foreach($select as $ro){
                    echo "
                    <h1>Edição de Funcionario</h1>
                    <form action='./funcionarios.php' method='post' style='flex-direction: row;'>
                        Dados pessoais:<br><br>
                        <input type='hidden' value='$_GET[id]' name='id'>
                        <input type='text' value='$ro[nome]' name='nome' required>
                        <input type='text' value='$ro[cargo]' name='cargo' required>
                        <br>
                        <input type='mail' value='$ro[email]' name='email' required>
                        <input type='tel' value='$ro[fone]' name='telefone' required>
                        <br><br>PIN:<br>
                        <input type='text' minlength='4' maxlength='4' value='$ro[pin]' name='pin' required>
                        <br><br>Horarios Nominais(SEG-SEX):<br>
                        <input type='time' value='$ro[h_entrada]' name='ent01' required>
                        <input type='time' value='$ro[h_saida_i]' name='sai01' required>
                        <input type='time' value='$ro[h_volta_i]' name='ent02' required>
                        <input type='time' value='$ro[h_saida]' name='sai02' required>
                        <br><br>
                        Horarios Nominais(Sab):<br>
                        <input type='time' value='$ro[h_sab_entrada]' name='ent_sab' required>
                        <input type='time' value='$ro[h_sab_saida]' name='sai_sab' required>
                        <br><br>
                        <button type='submit'>Salvar Dados</button>
                    </form>
                ";
                }
               
            }
        
        ?>
    </div>
</body>
</html>