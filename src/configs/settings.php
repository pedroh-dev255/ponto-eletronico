<?php

    session_start();
    if(!isset($_SESSION['id'])){
        header('Location: ../configs/login.php'); 
    }
    include_once("../configs/conexao.php");
    if(isset($_GET['fone'])){
        if(!isset($_GET['zap_dir'])){
            $zap_dir = 'off';
        }else{
            $zap_dir = 'on';
        }
        if(!isset($_GET['zap_fun'])){
            $zap_fun = 'off';
        }else{
            $zap_fun = 'on';
        }
        if(!isset($_GET['bh'])){
            $bh = 'off';
        }else{
            $bh = 'on';
        }
        $fone = str_replace(array('(', ')', ' ', '-'), '', $_GET['fone']);

        $conn->query("UPDATE `ponto`.`settings` SET `zap_fun` = '$zap_fun',`bh` = '$bh', `zap_dir` = '$zap_dir',`fone_dir` = '$fone' WHERE (`id` = '1');")->fetchAll();
        header("Location: settings.php");

    }
    
    $verif_s = $conn->query("SELECT * FROM settings")->fetchAll();
    foreach ($verif_s as $row) {}


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <title>Settings</title>
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
    <br><br>
    <div style="margin: 20px;">
        <script>
            const handlePhone = (event) => {
                let input = event.target
                input.value = phoneMask(input.value)
            }

            const phoneMask = (value) => {
                if (!value) return ""
                value = value.replace(/\D/g,'')
                value = value.replace(/(\d{2})(\d)/,"($1) $2")
                value = value.replace(/(\d)(\d{4})$/,"$1-$2")
                return value
            }

            // Função para carregar automaticamente a máscara de telefone após o carregamento da página
            const applyPhoneMaskOnLoad = () => {
                const phoneInput = document.querySelector('input[name="fone"]');
                if (phoneInput) {
                    phoneInput.value = phoneMask(phoneInput.value);
                }
            };

            // Use o evento 'DOMContentLoaded' para chamar a função após a página ser carregada
            document.addEventListener('DOMContentLoaded', applyPhoneMaskOnLoad);
        </script>
        <h2>Configurações</h2>
        <div style="margin: 20px;">
            <form action="" method="get">
                <table>
                    <tr>
                        <td>Enviar ponto por Whatsapp para o colaborador?</td>
                        <td>
                            <label class="switch">
                                <input name="zap_fun" type="checkbox" <?php if($row['zap_fun'] == 'on'){echo "checked";}?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Enviar ponto por Whatsapp para diretoria?</td>
                        <td>
                            <label class="switch">
                                <input name="zap_dir" id="zapDirSwitch" type="checkbox" <?php if($row['zap_dir'] == 'on'){echo "checked";}?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Numero da diretoria com DDD</td>
                        <td>
                            <input  type="tel" name="fone" id="foneInput" maxlength="15" onkeyup="handlePhone(event)" value="<?php echo $row['fone_dir']?>">
                        </td>
                    </tr>
                    <tr>
                    <tr>
                        <td>Ativar banco de horas?</td>
                        <td>
                            <label class="switch">
                                <input name="bh" type="checkbox" <?php if($row['bh'] == 'on'){echo "checked";}?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                        <td></td>
                        <td>
                            <button type="submit">Salvar configurações</button>
                        </td>
                    </tr>
                
                    
                </table>
            </form>
        </div>
    </div>
    <script>
        
        const foneInput = document.getElementById('foneInput');
        const zapDirSwitch = document.getElementById('zapDirSwitch');

        zapDirSwitch.addEventListener('change', function () {
            foneInput.required = this.checked;
            
        });
    </script>
</body>
