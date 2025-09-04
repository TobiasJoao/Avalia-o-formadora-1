<?php
session_start();

// Array de perguntas, alternativas e resposta correta
$perguntas = [
    [
        "pergunta" => "Qual é a capital da França?",
        "alternativas" => ["Berlim", "Paris", "Londres"],
        "correta" => 1
    ],
    [
        "pergunta" => "Quanto é 5 + 7?",
        "alternativas" => ["10", "12", "15"],
        "correta" => 1
    ],
    [
        "pergunta" => "Qual planeta é conhecido como Planeta Vermelho?",
        "alternativas" => ["Marte", "Vênus", "Júpiter"],
        "correta" => 0
    ]
];

// Se não existir sessão, inicializa
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

// Verifica envio de resposta
$feedback = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resposta = $_POST["resposta"];
    $indice = $_SESSION["atual"];

    if ($resposta == $perguntas[$indice]["correta"]) {
        $_SESSION["pontos"]++;
        $feedback = "<p class='certo'>✔ Resposta correta!</p>";
    } else {
        $feedback = "<p class='errado'>✘ Resposta errada!</p>";
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
        <!-- Pergunta Atual -->
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