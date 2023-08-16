<?php
    session_start();
    if(!isset($_SESSION['id'])){
        header('Location: ../configs/login.php');
    }

    if(isset($_GET['id'])){
        include_once("../configs/conexao.php");
        $select = $conn->query("SELECT pontos.data_entrada, pontos.entrada, pontos.saida_intervalo, pontos.retorno_intervalo, pontos.saida, usuarios.nome FROM pontos INNER JOIN usuarios ON usuarios.id = pontos.usuario_id where pontos.id = '" . $_GET['id'] . "'")->fetchAll();
    }else{
        header("Location: ../view/filtro_registros.php");
    }
    foreach($select as $row){}

    if(isset($_POST['en01'])){
        include_once("../configs/img_edit.php");
        if(date('w', strtotime($row['data_entrada'])) == 6){
            $edit=$conn->query("UPDATE `ponto`.`pontos` SET `entrada`='$_POST[en01]',`saida_intervalo`='$_POST[sa01]',`retorno_intervalo`=null,`saida`=null,`ft_entrada`=' $img_edit',`ft_saida_i`=' $img_edit',`ft_volta_i`=null,`ft_saida` = null, obs = '__EDITADO__' WHERE (`id` = '$_GET[id]');")->fetchAll();

        }else{
            $edit=$conn->query("UPDATE `ponto`.`pontos` SET `entrada`='$_POST[en01]',`saida_intervalo`='$_POST[sa01]',`retorno_intervalo`='$_POST[en02]',`saida`='$_POST[sa02]',`ft_entrada`=' $img_edit',`ft_saida_i`=' $img_edit',`ft_volta_i`=' $img_edit',`ft_saida` = ' $img_edit', obs = '__EDITADO__' WHERE (`id` = '$_GET[id]');")->fetchAll();
        }
        header("Location: ./edicao.php?id=$_GET[id]");
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <title>Edição</title>
    <script>
        function verificarDia() {
            var data = "<?php echo $row['data_entrada']?>";
            var dataObj = new Date(data);

            var ent = document.getElementById('entrada01');
            var saida_i = document.getElementById('saida01');
            var volta_i = document.getElementById('entrada02');
            var saida = document.getElementById('saida02');

            var buttun = document.getElementById('buttun');

            // Verifica se o dia selecionado é um sábado (valor 6 representa sábado, de acordo com a função getDay())
            if (dataObj.getDay() === 5) {
                ent.disabled = false;
                ent.style.display = '';


                saida_i.disabled = false;
                saida_i.style.display = '';


                volta_i.disabled = true;
                volta_i.style.display = 'none';

                saida.disabled = true;
                saida.style.display = 'none';

                button.style.display = '';

                ent.value = "<?php echo $row['entrada']?>";
                saida_i.value = "<?php echo $row['saida_intervalo']?>";
                volta_i.value = null;
                saida.value = null;
            }else if(dataObj.getDay() === 6){
                ent.disabled = true;
                ent.style.display = 'none';

                saida_i.disabled = true;
                saida_i.style.display = 'none';

                volta_i.disabled = true;
                volta_i.style.display = 'none';

                saida.disabled = true;
                saida.style.display = 'none';

                button.style.display = 'none';

                ent.value = null;
                saida_i.value = null;
                volta_i.value = null;
                saida.value = null;
            } else {
                ent.disabled = false;
                ent.style.display = '';

                saida_i.disabled = false;
                saida_i.style.display = '';

                volta_i.disabled = false;
                volta_i.style.display = '';

                saida.disabled = false;
                saida.style.display = '';

                button.style.display = '';

                ent.value = "<?php echo $row['entrada']?>";
                saida_i.value = "<?php echo $row['saida_intervalo']?>";
                volta_i.value = "<?php echo $row['retorno_intervalo']?>";
                saida.value = "<?php echo $row['saida']?>";
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
    <br>
    
    <form style="margin: 20px;" action="./edicao.php?id=<?php echo $_GET['id'];?>" method="post">
        <input type="button" value="Voltar" onClick="history.go(-1)"> <br><br><br>
        <?php
            
                echo $row['nome'] . " - " . $row['data_entrada'];
            
        ?>
        <br>
        <input type="time" name="en01" id="entrada01">
        <input type="time" name="sa01" id="saida01">
        <input type="time" name="en02" id="entrada02">
        <input type="time" name="sa02" id="saida02">
        <button type="submit" id="button">Salvar Edição</button>
    </form>
    <script>verificarDia()</script>
</body>
</html>
