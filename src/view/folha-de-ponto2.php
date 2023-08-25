<?php
    session_start();

    if(!isset($_SESSION['id'])){
        header('Location: ../configs/login.php');
    }

    include_once("../configs/conexao.php");
    if(isset($_GET['id']) && isset($_GET['mes']) && isset($_GET['ano'])){
        $dias = cal_days_in_month(CAL_GREGORIAN, $_GET['mes'], $_GET['ano']); // 31
    }
    $diasemana = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');

    $barra=$conn->query("SELECT * FROM usuarios WHERE id= '$_GET[id]'");
    foreach($barra as $title){}

    //formulas para calculos de horas por dia e somas totais;
    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $_GET['mes'], $_GET['ano']);
    
    
    if(isset($dias_mes)){
        for($i=1;$i<=$dias_mes;$i++){
            $data = $_GET['ano'] . "-" . $_GET['mes'] . "-" . sprintf("%02d", $i );
            $data_formatada = sprintf("%02d", $i ) . "/" . $_GET['mes'] . "/" . $_GET['ano'];
            $data_formatada2 = sprintf("%02d", $i ) . "-" . $_GET['mes'] . "-" . $_GET['ano'];
            $ponto=$conn->query("select id,usuario_id,data_entrada,saida,retorno_intervalo,saida_intervalo,entrada,obs from pontos where data_entrada = '$data' and usuario_id = $_GET[id]")->fetchAll();
            foreach($ponto as $row){
                
                if($row['entrada'] && $row['saida_intervalo'] && $row['retorno_intervalo'] && $row['saida'] && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) != 6){
                    // dias em que o usuario bateu todos os pontos e é um dia de seg a sex
                    echo $diasemana[date('w', strtotime($data))] . $data . " 4 ponto se<BR>";
                }
                if($row['entrada'] && !isset($row['saida_intervalo']) && !isset($row['retorno_intervalo']) && !isset($row['saida']) && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) != 6){
                    // dias em que o usuario bateu um PONTOS e é um dia de seg a sex
                    echo $diasemana[date('w', strtotime($data))] . $data . " 1 ponto se<BR>";
                }
                
                if($row['entrada'] && $row['saida_intervalo'] && !isset($row['retorno_intervalo']) && !isset($row['saida']) && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) != 6){
                    // dias em que o usuario bateu DOIS PONTOS e é um dia de seg a sex
                    echo $diasemana[date('w', strtotime($data))] . $data . " 2 pontos se<BR>";
                }
                if($row['entrada'] && $row['saida_intervalo'] && $row['retorno_intervalo'] && !isset($row['saida']) && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) != 6){
                    // dias em que o usuario bateu tres PONTOS e é um dia de seg a sex
                    echo $diasemana[date('w', strtotime($data))] . $data . " 3 pontos se<BR>";
                }
                
                //sabado
                if($row['entrada'] && $row['saida_intervalo'] && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) == 6){
                    // dias em que o usuario bateu todos os pontos e é um dia de SABADO
                    echo $diasemana[date('w', strtotime($data))] . $data . " 2 sab<BR>";
                    
                }
                if($row['entrada'] && !isset($row['saida_intervalo']) && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) == 6){
                    // dias em que o usuario bateu 1 ponto e é um dia de SABADO
                    echo $diasemana[date('w', strtotime($data))] . $data . " 1 sab<BR>";
                    
                }
                
                
                
            }
            if(!isset($ponto) || $ponto == null && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) != 6){
                // dias em que o usuario NÃO bateu PONTO e é um dia de seg a sex
                echo $diasemana[date('w', strtotime($data))] . $data . " 0 pontos se<BR>";
            }
            if(!isset($ponto) || $ponto == null && date('w', strtotime($data)) != 0 && date('w', strtotime($data)) == 6){
                // dias em que o usuario n bateu 0 ponto e é um dia de SABADO
                echo $diasemana[date('w', strtotime($data))] . $data . " 0 sab<BR>";
                
            }
            if(date('w', strtotime($data)) == 0){
                // dias em que o usuario n bateu 0 ponto e é um dia de SABADO
                echo $diasemana[date('w', strtotime($data))] . $data . "  dom<BR>";
                
            }
            
        }
    }



?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style copy.css">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <title>Folha de Ponto</title>
    <style>
        
        tr{
            font-size: 8px;
        };
        td{
            font-size: 5px;
        };
    </style>
</head>
<body>
    <div id="print" class="conteudo">
            <table>
                <tr>
                    <td><b><h2>Folha de Ponto</h2></b></td>
                    <td></td>
                    <td></td>
                    <td>Periodo: 01<?php echo "/".$_GET['mes']."/". $_GET['ano']." a ".$dias."/".$_GET['mes']."/". $_GET['ano'];?></td>
                </tr>
                <tr>
                    <th>Dados do Colaborador</th>
                    <th></th>
                    <th>Dados do Empregador</th>
                    <th></th>
                </tr>
                <tr>
                    <td><b>Nome</b></td>
                    <td><?php echo $title['nome'];?></td>
                    <td><b>Razão Social</b></td>
                    <td>INSTITUTO DO CORAÇÃO LTDA</td>
                </tr>
                <tr>
                    <td><b>CPF</b></td>
                    <td><?php echo $title['cpf'];?></td>
                    <td><b>Endereço</b></td>
                    <td>Quadra ACSO 11 Avenida LO 3, 111</td>
                </tr>
                <tr>
                    <td><b>Equipe</b></td>
                    <td>Instituto do Coração Ltda</td>
                    <td><b>CNPJ</b></td>
                    <td>04.292.026/0001-01</td>
                </tr>
                <tr>
                    <td><b>Cargo</b></td>
                    <td><?php echo $title['cargo'];?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Turno</b></td>
                    <td colspan="3"><?php echo $title['h_entrada'] . " " . $title['h_saida_i'] . " " . $title['h_volta_i'] . " " . $title['h_saida'] . " ";
                        for($i=1;$i<=5;$i++){
                            echo "[". $diasemana[$i]." ".$title['h_entrada']." ".$title['h_saida']." ".$title['h_saida_i']." ".$title['h_volta_i']." ] ";
                        }
                        echo "[". $diasemana[$i]." ".$title['h_sab_entrada']." ".$title['h_sab_saida']." ]";
                    ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>