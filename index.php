<?php

session_start(); // Iniciar a sessao

// Definir um fuso horario padrao
date_default_timezone_set('America/Sao_Paulo');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Ponto</title>
    <link rel="stylesheet" href="src/style/style.css">
    <link rel="shortcut icon" href="./src/style/favicon.ico" type="image/x-icon">
    <style>
        @media only screen and (max-width: 600px) {
            .keypad {
                display: block;
            }
        }

        .keypad {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 5%;
            width: 80%;

        }

        .key {
            display: inline-block;
            width: 20%;
            height: 80%;
            margin: 3%;
            text-align: center;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .key.zero {
            width: 20%;
            /* Largura do botão zero ajustada */
            height: 50px;

        }

        .key.registrar {
            width: 20%;
            /* Largura do botão Registrar ajustada */
            height: 50px;

        }
    </style>
    <script>
        $('#num')
            .keyboard({
                layout: 'num',
                restrictInput: true, // Prevent keys not in the displayed keyboard from being typed in
                preventPaste: true,  // prevent ctrl-v and right click
                autoAccept: true
            })
            .addTyping();

        function insertNumber(num) {
            document.getElementById('num').value += num;
        }
        function clearInput() {
            document.getElementById('num').value = '';
        }
    </script>
</head>

<body style="display: flex;justify-content: center; align-items: center; font-size: 230%;">


    <div class="borda">

        <h2>Registrar ponto</h2>

        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <p id="horario">
            <?php echo date("d/m/Y H:i:s"); ?>
        </p>
        <form action="src/register/registrar_ponto.php" method="post">

            <video id="video" width="80%" autoplay></video>
            <canvas hidden id="canvas" width="640" height="480"></canvas>
            <br><br>
            <input id="num" style="height: 40px; width: 90%;" type="password" minlength="4" maxlength="4" name="pin"
                placeholder="Informe o pin" required>

            <br><br>
            <div class="keypad">
                <div class="key" onclick="insertNumber('1')">1</div>
                <div class="key" onclick="insertNumber('2')">2</div>
                <div class="key" onclick="insertNumber('3')">3</div><br>
                <div class="key" onclick="insertNumber('4')">4</div>
                <div class="key" onclick="insertNumber('5')">5</div>
                <div class="key" onclick="insertNumber('6')">6</div><br>
                <div class="key" onclick="insertNumber('7')">7</div>
                <div class="key" onclick="insertNumber('8')">8</div>
                <div class="key" onclick="insertNumber('9')">9</div><br>
                <div class="key zero" onclick="insertNumber('0')">0</div>
                <button class="key registrar" style="background-color: red;" onclick="clearInput()">❌</button>
                <button class="key registrar" style="background-color: green; color: white;" type="submit"
                    id="submit-btn" disabled><b>✅</b></button><br>
                <a href="./src" style="font-size: 20px;"><b>Painel ADM</b></a>
            </div>

        </form>
        <br>
    </div>

    <br>
    <!--
        <div class="borda2">
            <table>
                <h3>Registro de Ponto Diario</h3>
                <tr>
                    <th>Funcionario</th>
                    <th>Entrada</th>
                    <th>Saida Intervalo</th>
                    <th>Volta Intervalo</th>
                    <th>Saida</th>
                </tr>
                <?php
                /*
                include_once("src/configs/conexao.php");
                $verif_p = $conn->query("SELECT pontos.data_entrada, pontos.entrada, pontos.saida_intervalo, pontos.retorno_intervalo, pontos.saida, usuarios.nome FROM pontos INNER JOIN usuarios ON usuarios.id = pontos.usuario_id where pontos.data_entrada = '" . date('Y-m-d') . "'")->fetchAll();
                foreach ($verif_p as $row) {
                    if($row['saida_intervalo'] != null && $row['saida_intervalo'] != "00:00:00"){
                        $saida_i="✅";
                    }else{
                        $saida_i="❌";
                    }
                    if($row['retorno_intervalo'] != null && $row['retorno_intervalo'] != "00:00:00"){
                        $volta_i="✅";
                    }else{
                        $volta_i="❌";
                    }
                    if($row['saida'] != null && $row['saida'] != "00:00:00"){
                        $saida="✅";
                    }else{
                        $saida="❌";
                    }
                    echo "<tr> <th>".$row['nome']."</th> <th>✅</th> <th>". $saida_i ."</th> <th>". $volta_i ."</th> <th>". $saida ."</th> </tr>";
                }*/
                ?>
            </table>
            
        </div>
            -->
    <script>
        //var apHorario = document.getElementById("horario");

        function atualizarHorario() {
            var data = new Date().toLocaleString("pt-br", {
                timeZone: "America/Sao_Paulo"
            });
            //var formatarData = data.replace(", ", " - ");
            //apHorario.innerHTML = formatarData; 
            document.getElementById("horario").innerHTML = data.replace(", ", " - ");
        }

        setInterval(atualizarHorario, 1000);

        // obter o formulário e o botão de envio
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var submitBtn = document.getElementById('submit-btn');

        // Obter a webcam do usuário
        navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
            // Atribuir a origem da mídia do vídeo à fonte da webcam
            video.srcObject = stream;
            video.onloadedmetadata = function (e) {
                submitBtn.disabled = false; // reativar botão de envio
            };
        }).catch(function (error) {
            console.log(error);
        });

        // Adicionar um evento de clique ao botão de submit
        submitBtn.addEventListener('click', function (e) {
            if (!document.getElementById('num').value) {
                alert('Preencha o PIN.');
                return false;
            }

            e.preventDefault(); // Previne o envio padrão do formulário

            // Exibir a imagem da webcam no elemento canvas
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            // Converter o elemento canvas para uma imagem base64
            var imagemBase64 = canvas.toDataURL();

            // Adicionar a imagem base64 a um campo de input hidden no formulário
            var imagemInput = document.createElement('input');
            imagemInput.type = 'hidden';
            imagemInput.name = 'imagem';
            imagemInput.value = imagemBase64;
            document.querySelector('form').appendChild(imagemInput);

            // Submeter o formulário
            document.querySelector('form').submit();
        });
    </script>


    <!--<a class="button" href="src/">Painel ADM</a>-->

</body>

</html>