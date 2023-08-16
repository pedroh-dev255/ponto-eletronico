<?php

    session_start();
    if(!isset($_SESSION['id'])){
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
        </script>
        <h2>Configurações</h2>
        <div style="margin: 20px;">
            <form action="" method="get">
                <table>
                    <tr>
                        <td>Enviar ponto por Whatsapp para o colaborador?</td>
                        <td>
                            <label class="switch">
                                <input name="zap_fun" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Enviar ponto por Whatsapp para diretoria?</td>
                        <td>
                            <label class="switch">
                                <input name="zap_dir" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Numero da diretoria com DDD</td>
                        <td>
                            <input  type="tel" name="fone" id="" maxlength="15" onkeyup="handlePhone(event)">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit">Salvar configurações</button>
                        </td>
                    </tr>
                
                    
                </table>
            </form>
        </div>
    </div>
</body>
