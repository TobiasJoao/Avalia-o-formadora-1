<?php
session_start();
require "perguntas.php";

// Inicia sessão caso não exista
if (!isset($_SESSION["pontos"])) {
    $_SESSION["pontos"] = 0;
    $_SESSION["atual"] = 0;
}

// Reiniciar quiz
if (isset($_GET["reset"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Variável de feedback
$feedback = "";

// Verifica resposta enviada
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resposta = $_POST["resposta"];
    $indice = $_SESSION["atual"];

    if ($resposta == $perguntas[$indice]["correta"]) {
        $_SESSION["pontos"]++;
        $feedback = "<p class='certo'>✔ Você acertou!</p>";
    } else {
        $feedback = "<p class='errado'>✘ Você errou!</p>";
    }

    $_SESSION["atual"]++;
}

$totalPerguntas = count($perguntas);
$indice = $_SESSION["atual"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mini Quiz Backend</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Mini Quiz Backend</h1>

    <?php if ($indice >= $totalPerguntas): ?>
        <!-- Tela Final -->
        <h2>Quiz Finalizado!</h2>
        <p>Sua pontuação: <strong><?= $_SESSION["pontos"] ?> / <?= $totalPerguntas ?></strong></p>
        <a class="botao" href="index.php?reset=1">Reiniciar Quiz</a>

    <?php else: ?>
        <!-- Tela de Pergunta -->
        <h2>Pergunta <?= $indice + 1 ?> de <?= $totalPerguntas ?></h2>
        <p><?= $perguntas[$indice]["pergunta"] ?></p>

        <form method="post">
            <?php foreach ($perguntas[$indice]["alternativas"] as $key => $alternativa): ?>
                <label>
                    <input type="radio" name="resposta" value="<?= $key ?>" required>
                    <?= $alternativa ?>
                </label><br>
            <?php endforeach; ?>
            <button type="submit" class="botao">Enviar</button>
        </form>

        <!-- Feedback -->
        <?= $feedback ?>
    <?php endif; ?>
</div>
</body>
</html>
