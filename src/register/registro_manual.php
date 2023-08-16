<?php
session_start();

include_once('../configs/conexao.php');
include_once("../configs/img_edit.php");
if (!isset($_SESSION['id'])) {
    header('Location: ../configs/login.php');
}
$data = $conn->query("SELECT * FROM usuarios")->fetchAll();

if (isset($_POST['fun'])) {
    $verif = $conn->query("SELECT data_entrada,usuario_id,id FROM pontos")->fetchAll();
    $verificador = 0;
    foreach ($verif as $row) {
        if ($row['data_entrada'] == $_POST['data'] && $row['usuario_id'] == $_POST['fun']) {
            $verificador = 1;
            $id_dia = $row['id'];
        }
    }
    if ($verificador == 1) {
        $_SESSION['msg'] = "<br><div style='text-align: center; font-size: 20px;'><b>Dia já cadastrado, necessário fazer edição <a href='edicao.php?id=$id_dia'>Clique aqui para editar o dia</a></b></div><br>";
    } else {
        if(date('w', strtotime($_POST['data'])) == 6){
            $insert = "insert into pontos (data_entrada, entrada, ft_entrada, saida_intervalo, ft_saida_i, usuario_id, obs) values ('$_POST[data]','$_POST[entrada]','$img_edit','$_POST[saida_i]','$img_edit',$_POST[fun], 'DIA CADASTRADO MANUALMENTE')";
            $data_insert = $conn->query($insert)->fetchAll();
            $_SESSION['msg'] = "<br><div style='text-align: center; font-size: 20px;'><b>Dia Cadastrado</b></div><br>";
        }else{
            $insert = "insert into pontos (data_entrada, entrada, ft_entrada, saida_intervalo, ft_saida_i, retorno_intervalo, ft_volta_i, saida, ft_saida, usuario_id, obs) values ('$_POST[data]','$_POST[entrada]','$img_edit','$_POST[saida_i]','$img_edit','$_POST[volta_i]','$img_edit','$_POST[saida]','$img_edit',$_POST[fun], 'DIA CADASTRADO MANUALMENTE')";
            $data_insert = $conn->query($insert)->fetchAll();
            $_SESSION['msg'] = "<br><div style='text-align: center; font-size: 20px;'><b>Dia Cadastrado</b></div><br>";
        
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <title>Registro Manual</title>
    <script>
        function verificarDia() {
            var data = document.getElementById('data').value;
            
            var dataObj = new Date(data);
            
            var en1 = document.getElementById('en1');
            var sai1 = document.getElementById('sai1');
            var en2 = document.getElementById('en2');
            var sai2 = document.getElementById('sai2');


            var ent = document.getElementById('entrada01');
            var saida_i = document.getElementById('saida01');
            var volta_i = document.getElementById('entrada02');
            var saida = document.getElementById('saida02');

            var buttun = document.getElementById('buttun');

            // Verifica se o dia selecionado é um sábado (valor 6 representa sábado, de acordo com a função getDay())
            if (dataObj.getDay() === 5) {
                ent.disabled = false;
                ent.style.display = '';
                en1.style.display = '';

                saida_i.disabled = false;
                saida_i.style.display = '';
                sai1.style.display = '';

                volta_i.disabled = true;
                volta_i.style.display = 'none';
                en2.style.display = 'none';

                saida.disabled = true;
                saida.style.display = 'none';
                sai2.style.display = 'none';

                button.style.display = '';

                ent.value = "08:00:00";
                saida_i.value = "12:00:00";
                volta_i.value = null;
                saida.value = null;
            }else if(dataObj.getDay() === 6){
                ent.disabled = true;
                en1.style.display = 'none';
                ent.style.display = 'none';

                saida_i.disabled = true;
                sai1.style.display = 'none';
                saida_i.style.display = 'none';

                volta_i.disabled = true;
                en2.style.display = 'none';
                volta_i.style.display = 'none';

                saida.disabled = true;
                sai2.style.display = 'none';
                saida.style.display = 'none';

                button.style.display = 'none';

                ent.value = null;
                saida_i.value = null;
                volta_i.value = null;
                saida.value = null;
            } else {
                ent.disabled = false;
                ent.style.display = '';
                en1.style.display = '';

                saida_i.disabled = false;
                saida_i.style.display = '';
                sai1.style.display = '';

                volta_i.disabled = false;
                volta_i.style.display = '';
                en2.style.display = '';

                saida.disabled = false;
                saida.style.display = '';
                sai2.style.display = '';

                button.style.display = '';

                ent.value = "08:00:00";
                saida_i.value = "12:00:00";
                volta_i.value = "14:00:00";
                saida.value = "18:00:00";
            }
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
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    if(isset($_GET['data_'])){
        echo "<input type='button' value='Voltar ao Filtro' onClick='history.go(-1)'>";
    }
    ?>
    <br><br>
    <form style="text-align: center; font-size: 20px;" action="registro_manual.php<?php if(isset($_GET['data_'])){echo "?id_=".$_GET['id_']."&data_=".$_GET['data_'];}?>" method="post">
        <label for="fun">Funcionario:</label>
        <select name="fun" id="fun" required>
            <option value=""></option>
            <?php
            foreach ($data as $row) {
                $situacao="";
                if((isset($_GET['id_']) || isset($_POST['fun'])) && ($_GET['id_'] == $row['id'] || $_POST['fun'] == $row['id'])){
                    $situacao="selected";
                }
                echo "<option value='" . $row['id'] . "' ".$situacao.">" . $row['nome'] . "</option>";
            }
            ?>
        </select>
        Data:
        <input type="date" id="data" name="data" <?php if(isset($_GET['data_'])){echo "value=".$_GET['data_'];}?> required onchange="verificarDia()">
        <br><br>
        <b id="en1">Entrada 01:</b>
        <input type="time" name="entrada" id="entrada01" value="08:00:00" required>
        <b id="sai1">Saida 01:</b>
        <input type="time" name="saida_i" id="saida01" value="12:00:00" required>
        <b id="en2">Entrada 02:</b>
        <input type="time" name="volta_i" id="entrada02" value="14:00:00" required>
        <b id="sai2">Saida 02:</b>
        <input type="time" name="saida" id="saida02" value="18:00:00" required>
        <br><br>
        <button id="button" type="submit">Registrar ponto manual</button>
    </form>
    <script>
        <?php if(isset($_GET['data_'])){
                echo "verificarDia();";
            }?>
    </script>
</body>

</html>
