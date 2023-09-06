<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../configs/login.php');
}

include_once("../configs/conexao.php");
if (isset($_GET['id']) && isset($_GET['mes']) && isset($_GET['ano'])) {
    $dias = cal_days_in_month(CAL_GREGORIAN, $_GET['mes'], $_GET['ano']); // 31
}
$diasemana = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');

$barra = $conn->query("SELECT * FROM usuarios WHERE id= '$_GET[id]'");
foreach ($barra as $title) {
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
        tr {
            font-size: 8px;
        }

        ;

        td {
            font-size: 5px;
        }

        ;
    </style>
</head>

<body>
    <div id="print" class="conteudo">
        <table>
            <tr>
                <td><b>
                        <h2>Folha de Ponto</h2>
                    </b></td>
                <td></td>
                <td></td>
                <td>Periodo: 01
                    <?php echo "/" . $_GET['mes'] . "/" . $_GET['ano'] . " a " . $dias . "/" . $_GET['mes'] . "/" . $_GET['ano']; ?>
                </td>
            </tr>
            <tr>
                <th>Dados do Colaborador</th>
                <th></th>
                <th>Dados do Empregador</th>
                <th></th>
            </tr>
            <tr>
                <td><b>Nome</b></td>
                <td>
                    <?php echo $title['nome']; ?>
                </td>
                <td><b>Razão Social</b></td>
                <td>INSTITUTO DO CORAÇÃO LTDA</td>
            </tr>
            <tr>
                <td><b>CPF</b></td>
                <td>
                    <?php echo $title['cpf']; ?>
                </td>
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
                <td>
                    <?php echo $title['cargo']; ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Turno</b></td>
                <td colspan="3">
                    <?php echo $title['h_entrada'] . " " . $title['h_saida_i'] . " " . $title['h_volta_i'] . " " . $title['h_saida'] . " ";
                    for ($i = 1; $i <= 5; $i++) {
                        echo "[" . $diasemana[$i] . " " . $title['h_entrada'] . " " . $title['h_saida'] . " (" . $title['h_saida_i'] . " " . $title['h_volta_i'] . ") ] ";
                    }
                    echo "[" . $diasemana[$i] . " " . $title['h_sab_entrada'] . " " . $title['h_sab_saida'] . " ]";
                    ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table>
            <tr>
                <th>Data</th>
                <th>Registros</th>
                <th>H.T.</th>
                <th>H.E.</th>
                <th>H.F.</th>
                <th>Situação</th>
                <th>H.E. 1</th>
                <th>H.E. 2</th>
                <th>A.N.</th>
                <th>Saldo</th>
                <th>Motivo</th>
            </tr>
            <?php
            if (isset($dias)) {

                for ($i = 1; $i <= $dias; $i++) {
                    $data = $_GET['ano'] . "-" . $_GET['mes'] . "-" . sprintf("%02d", $i);
                    $data_formatada = sprintf("%02d", $i) . "/" . $_GET['mes'] . "/" . $_GET['ano'];
                    $data_formatada2 = sprintf("%02d", $i) . "-" . $_GET['mes'] . "-" . $_GET['ano'];
                    $ponto = $conn->query("select id,usuario_id,data_entrada,saida,retorno_intervalo,saida_intervalo,entrada,obs from pontos where data_entrada = '$data' and usuario_id = $_GET[id]")->fetchAll();
                    foreach ($ponto as $row) {
                        if (date('w', strtotime($data)) == 6) {
                            if (isset($row['entrada']) && isset($row['saida_intervalo'])) {
                                $horas_t = gmdate('H:i:s', strtotime($title['h_sab_saida']) - strtotime($title['h_sab_entrada']));
                                $sum_horas_ht = gmdate('H:i:s', strtotime($row['saida_intervalo']) - strtotime($row['entrada']));
                                if (strtotime($horas_t) < strtotime($sum_horas_ht)) {
                                    $sum_horas_he = gmdate('H:i:s', strtotime($sum_horas_ht) - strtotime($horas_t));
                                    $horas_hf = "00:00:00";
                                } else if (strtotime($horas_t) > strtotime($sum_horas_ht)) {
                                    $sum_horas_hf = gmdate('H:i:s', strtotime($horas_t) - strtotime($sum_horas_ht));
                                    $horas_he = "00:00:00";
                                } else {
                                    $horas_he = "00:00:00";
                                    $horas_hf = "00:00:00";
                                }
                                $situacao = "Completo";
                            } else if (isset($row['entrada']) && !isset($row['saida_intervalo'])) {
                                $sum_horas_ht = "Inconclusivo";
                                $horas_he = "Inconclusivo";
                                $horas_hf = "Inconclusivo";
                                $situacao = "Incompleto";
                            }
                            //10 minutos de tolerancia;
                            if (isset($sum_horas_hf) && $sum_horas_hf > strtotime("00:10:00")) {
                                $horas_hf = $sum_horas_hf;
                            } else {
                                $horas_hf = "00:00:00";
                            }
                            if (isset($sum_horas_he) && $sum_horas_he > strtotime("00:10:00")) {
                                $horas_he = $sum_horas_he;
                            } else {
                                $horas_he = "00:00:00";
                            }

                            //Sabado
                            echo "<tr> <td>" . $data_formatada . " - " . $diasemana[date('w', strtotime($data))] . "</td><td>" . $row['entrada'] . ", " . $row['saida_intervalo'] . "</td><td> " . $sum_horas_ht . " </td><td>" . $horas_he . "</td><td>" . $horas_hf . "</td> <td> " . $situacao . " </td> <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
                        } else {
                            $horas_he = "00:00:00";
                            $horas_hf = "00:00:00";
                            $sum_horas_he = "00:00:00";
                            $sum_horas_hf = "00:00:00";
                            //total nominal
                            $horas_t = gmdate('H:i:s', (strtotime($title['h_saida']) - strtotime($title['h_entrada'])) - (strtotime($title['h_volta_i']) - strtotime($title['h_saida_i'])));
                            if (isset($row['entrada']) && isset($row['saida'])) {
                                //total real
                                $sum_horas_ht = gmdate('H:i:s', (strtotime($row['saida']) - strtotime($row['entrada'])) - (strtotime($row['retorno_intervalo']) - strtotime($row['saida_intervalo'])));
                                //verificar se tem hora extra ou faltante
                                if (strtotime($horas_t) < strtotime($sum_horas_ht)) {
                                    $sum_horas_he = gmdate('H:i:s', strtotime($sum_horas_ht) - strtotime($horas_t));
                                    $horas_hf = "00:00:00";
                                } else if (strtotime($horas_t) > strtotime($sum_horas_ht)) {
                                    $sum_horas_hf = gmdate('H:i:s', strtotime($horas_t) - strtotime($sum_horas_ht));
                                    $horas_he = "00:00:00";
                                } else {
                                    $horas_he = "00:00:00";
                                    $horas_hf = "00:00:00";
                                }
                                $situacao = "Completo";
                            } else if (isset($row['entrada']) && isset($row['saida_intervalo']) && !isset($row['saida'])) {

                                $sum_horas_ht = gmdate('H:i:s', strtotime($row['saida_intervalo']) - strtotime($row['entrada']));

                                if (strtotime($horas_t) < strtotime($sum_horas_ht)) {
                                    $sum_horas_he = gmdate('H:i:s', strtotime($sum_horas_ht) - strtotime($horas_t));
                                    $horas_hf = "00:00:00";
                                } else if (strtotime($horas_t) > strtotime($sum_horas_ht)) {
                                    $sum_horas_hf = gmdate('H:i:s', strtotime($horas_t) - strtotime($sum_horas_ht));
                                    $horas_he = "00:00:00";
                                } else {
                                    $horas_he = "00:00:00";
                                    $horas_hf = "00:00:00";
                                }
                                $situacao = "Incompleto";
                            }
                            //tolerancia de 10 minutos
                            if (isset($sum_horas_hf) && strtotime($sum_horas_hf) > strtotime("00:10:00")) {
                                $horas_hf = $sum_horas_hf;
                                $saldo = "-" . $sum_horas_hf;
                            } else {
                                $horas_hf = "00:00:00";
                                $saldo = "00:00:00";
                            }
                            if (isset($sum_horas_he) && strtotime($sum_horas_he) > strtotime("00:10:00")) {
                                $horas_he = $sum_horas_he;
                                $saldo = $sum_horas_he;
                            } else {
                                $horas_he = "00:00:00";
                                $saldo = "00:00:00";
                            }
                            if (isset($row['entrada']) && !isset($row['saida_intervalo'])) {
                                $situacao = "Incompleto";
                                $sum_horas_ht = "Inconclusivo";
                                $horas_he = "Inconclusivo";
                                $horas_hf = "Inconclusivo";
                            }

                            //Dia da semana
                            echo "<tr> <td>" . $data_formatada . "-" . $diasemana[date('w', strtotime($data))] . "</td><td>" . $row['entrada'] . ", " . $row['saida_intervalo'] . ", " . $row['retorno_intervalo'] . ", " . $row['saida'] . "</td> <td>" . $sum_horas_ht . "</td><td>" . $horas_he . "</td><td>" . $horas_hf . "</td>  <td> " . $situacao . " </td> <td>00:00:00</td><td>" . $horas_he . "</td><td>00:00:00</td><td>" . $saldo . "</td><td>" . $row['obs'] . "</td></tr>";
                        }

                    }
                    if (!isset($ponto) || $ponto == null) {
                        if (date('w', strtotime($data)) == 0) {
                            //Domingo
                            echo "<tr> <td>" . $data_formatada . " - " . $diasemana[date('w', strtotime($data))] . "</td><td></td><td>00:00:00</td><td>00:00:00</td><td>00:00:00</td><td>Completo</td><td>00:00:00</td><td>00:00:00</td><td>00:00:00</td><td>00:00:00</td><td>D.S.R/FOLGA</td></tr>";

                        } else if (date('w', strtotime($data)) == 6) {
                            //Falta no sabado
                            $horas_t = gmdate('H:i:s', (strtotime($title['h_sab_saida']) - strtotime($title['h_sab_entrada'])));
                            $horas_he = "00:00:00";
                            $horas_hf = $horas_t;
                            $sum_horas_ht = "-" . $horas_t;
                            echo "<tr> <td>" . $data_formatada . " - " . $diasemana[date('w', strtotime($data))] . "</td><td></td><td>" . $sum_horas_ht . "</td><td>" . $horas_he . "</td><td>" . $horas_hf . "</td><td>Falta</td><td>00:00:00</td><td>00:00:00</td><td>00:00:00</td><td>" . $sum_horas_ht . "</td><td>-</td></tr>";
                        } else {
                            //Falta semanal
                            $horas_t = gmdate('H:i:s', (strtotime($title['h_saida']) - strtotime($title['h_entrada'])) - (strtotime($title['h_volta_i']) - strtotime($title['h_saida_i'])));
                            $horas_he = "00:00:00";
                            $horas_hf = $horas_t;
                            $sum_horas_ht = "-" . $horas_t;
                            echo "<tr> <td>" . $data_formatada . " - " . $diasemana[date('w', strtotime($data))] . "</td><td></td><td>" . $sum_horas_ht . "</td><td>" . $horas_he . "</td><td>" . $horas_hf . "</td><td>Falta</td><td>00:00:00</td><td>00:00:00</td><td>" . $horas_he . "</td><td>" . $sum_horas_ht . "</td><td>-</td></tr>";
                        }
                    }
                }
            }
            ?>
        </table>
        <table>
            <tr>
                <th><b>Tabela de Horas</b></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>H.T.</td>
                <td>H.N.</td>
                <td>H.E. *</td>
                <td>H.F. *</td>
                <td>H.E. 1</td>
                <td>H.E. 2</td>
                <td>A.N.</td>
                <td>Saldo</td>
                <td>Saldo Acumulado</td>
            </tr>
            <tr>
                <td>00:00:00</td>
                <td>00:00:00</td>
                <td>00:00:00</td>
                <td>00:00:00</td>
                <td>00:00:00</td>
                <td>H00:00:00</td>
                <td>00:00:00</td>
                <td>00:00:00</td>
                <td>00:00:00</td>
            </tr>
        </table>
    </div>


    <script>
       // window.print();
    </script>
</body>

</html>