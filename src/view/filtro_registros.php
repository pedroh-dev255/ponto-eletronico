<?php
    session_start();
    if(!isset($_SESSION['id'])){
        header('Location: ../configs/login.php');
    }
    include_once("../configs/conexao.php");
    if(isset($_GET['fun']) && isset($_GET['mes']) && isset($_GET['ano'])){
        $dias = cal_days_in_month(CAL_GREGORIAN, $_GET['mes'], $_GET['ano']); // 31
    }
    $diasemana = array('Domingo', 'Segunda Feira', 'Terça Feira', 'Quarta Feira', 'Quinta Feira', 'Sexta Feira', 'Sabado');


    

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../style/style.css">
    <title>Filtro de Registros</title>
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
    <form action="./filtro_registros.php" method="get">
        Funcionario: 
        <select name="fun" required>
            <option value=""></option>
            <?php
            include_once("../configs/conexao.php");
            $nome=$conn->query("select id,nome from usuarios")->fetchAll();
            
            foreach($nome as $nomes){
                $situacao="";
                if((isset($_GET['fun']) && $nomes['id'] == $_GET['fun'])){
                    $situacao="selected";
                }
                echo "<option value='$nomes[id]' ".$situacao.">$nomes[nome]</option>"; 
            }
            ?>
        </select>
        Mês: 
        <select name="mes" required>
            <option ></option>
            <option value="01" <?php if(isset($_GET['mes']) && $_GET['mes'] == '01'){ echo "selected";}?>>Janeiro</option>
            <option value="02" <?php if(isset($_GET['mes']) && $_GET['mes'] == '02'){ echo "selected";}?>>Fevereiro</option>
            <option value="03" <?php if(isset($_GET['mes']) && $_GET['mes'] == '03'){ echo "selected";}?>>Março</option>
            <option value="04" <?php if(isset($_GET['mes']) && $_GET['mes'] == '04'){ echo "selected";}?>>Abril</option>
            <option value="05" <?php if(isset($_GET['mes']) && $_GET['mes'] == '05'){ echo "selected";}?>>Maio</option>
            <option value="06" <?php if(isset($_GET['mes']) && $_GET['mes'] == '06'){ echo "selected";}?>>Junho</option>
            <option value="07" <?php if(isset($_GET['mes']) && $_GET['mes'] == '07'){ echo "selected";}?>>Julho</option>
            <option value="08" <?php if(isset($_GET['mes']) && $_GET['mes'] == '08'){ echo "selected";}?>>Agosto</option>
            <option value="09" <?php if(isset($_GET['mes']) && $_GET['mes'] == '09'){ echo "selected";}?>>Setembro</option>
            <option value="10" <?php if(isset($_GET['mes']) && $_GET['mes'] == '10'){ echo "selected";}?>>Outubro</option>
            <option value="11" <?php if(isset($_GET['mes']) && $_GET['mes'] == '11'){ echo "selected";}?>>Novembro</option>
            <option value="12" <?php if(isset($_GET['mes']) && $_GET['mes'] == '12'){ echo "selected";}?>>Dezembro</option>
        </select>
        Ano: 
        <select name="ano" required>
            <option></option>
            
            <?php
                for($i=2022;$i<=date('Y');$i++){
                    $situacao="";
                    if((isset($_GET['ano']) && $i == $_GET['ano'])){
                        $situacao="selected";
                    }
                    echo "<option value='$i' $situacao>$i</option>";
                }
            ?>
        </select>
        <button type="submit">Buscar</button>
    </form>
    <br>
    <?php 
        if(isset($_GET['fun'])){
            echo '<button onclick="imprimir()">Imprimir</button>';
        }
    ?>
    <!-- Script JavaScript -->
    <script>
    function imprimir() {
        // Abre a outra página em uma janela pop-up ou em um iframe oculto
        var janela = window.open('<?php if(isset($_GET['ano'])){echo "folha-de-ponto.php?id=".$_GET['fun']."&mes=".$_GET['mes']."&ano=".$_GET['ano'];}?>', '_blank');
    }
    </script>
    <table>
        <tr>
            <th>Data</th>
            <th>Dia da Semana</th>
            <th>registros</th>
            <th>Total de Horas</th>
            <th>Estado</th>
            <th>Edit/Cadastrar</th>
        </tr>
    <?php
        if(isset($dias)){
            for($i=1;$i<=$dias;$i++){
                $data = $_GET['ano'] . "-" . $_GET['mes'] . "-" . sprintf("%02d", $i );
                $data_formatada = sprintf("%02d", $i ) . "/" . $_GET['mes'] . "/" . $_GET['ano'];
                $data_formatada2 = sprintf("%02d", $i ) . "-" . $_GET['mes'] . "-" . $_GET['ano'];
                $ponto=$conn->query("select usuario_id,id,entrada,saida_intervalo,retorno_intervalo,saida from pontos where data_entrada = '$data' and usuario_id = $_GET[fun]")->fetchAll();
                foreach($ponto as $row){
                    if(date('w', strtotime($data)) == 6){
                        if(isset($row['entrada']) && isset($row['saida_intervalo'])){ 
                            $sum_horas = gmdate('H:i:s', strtotime( $row['saida_intervalo'] ) - strtotime( $row['entrada'] ));
                            $situacao = "Completo";
                        }else if(isset($row['entrada']) && !isset($row['saida_intervalo'])){
                            $sum_horas="Inconclusivo";
                            $situacao = "Incompleto";
                        }
                        echo "<tr> <td>".$data_formatada."</td><td>".$diasemana[date('w', strtotime($data))]."</td> <td>" . $row['entrada'] . ", " . $row['saida_intervalo'] . "</td><td> ".$sum_horas." </td> <td> ".$situacao." </td> <td><a href='../register/edicao.php?id=". $row['id'] ."'>Editar</a></td></tr>";
                    }else{
                        if(isset($row['entrada']) && isset($row['saida'])){ 
                            $sum_horas = gmdate('H:i:s', strtotime( $row['saida_intervalo'] ) - strtotime( $row['entrada'] ) + strtotime( $row['saida'] ) - strtotime( $row['retorno_intervalo'] ));
                            $situacao = "Completo";
                        }else if(isset($row['entrada']) && isset($row['saida_intervalo']) && !isset($row['saida'])){
                            $sum_horas = gmdate('H:i:s', strtotime( $row['saida_intervalo'] ) - strtotime( $row['entrada'] ));
                            $situacao = "Incompleto";
                        }else if (isset($row['entrada']) && !isset($row['saida_intervalo'])){
                            $situacao = "Incompleto";
                            $sum_horas="Inconclusivo";
                        }
                        echo "<tr> <td>".$data_formatada."</td><td>".$diasemana[date('w', strtotime($data))]."</td> <td>" . $row['entrada'] . ", " . $row['saida_intervalo'] . ", ". $row['retorno_intervalo'] . ", ". $row['saida'] . "</td> <td>".$sum_horas."</td>  <td> ".$situacao." </td> <td><a href='../register/edicao.php?id=". $row['id'] ."'>Editar</a></td></tr>";
                    }
                    
                }
                if(!isset($ponto) || $ponto == null){
                    if (date('w', strtotime($data)) == 0){
                        echo "<tr> <td>".$data_formatada."</td><td>".$diasemana[date('w', strtotime($data))]."</td><td>-</td>  <td>-</td> <td>Folga DSR</td> <td>---</td></tr>";

                    }else{
                        echo "<tr> <td>".$data_formatada."</td><td>".$diasemana[date('w', strtotime($data))]."</td><td>-</td>  <td>-</td> <td>Falta</td> <td><a href='../register/registro_manual.php?data_=". $data ."&form=".$data_formatada2."&id_=".$_GET['fun']."'>Registrar</a></td></tr>";

                    }
                }
            }
        }
    ?>
    </table>
</body>
</html>