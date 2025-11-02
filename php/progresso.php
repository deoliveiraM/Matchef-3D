
<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];


$sql_usuario = "SELECT * FROM usuarios WHERE nome = '$usuario'";
$result_usuario = mysqli_query($conn, $sql_usuario);
$dados_usuario = mysqli_fetch_assoc($result_usuario);
$id_usuario = $dados_usuario['id_usuario'] ?? 0;


$sql_desafios = "SELECT COUNT(*) AS total FROM progresso_usuarios WHERE usuarios_id_usuario = '$id_usuario' AND fez_receita = 1";
$sql_receitas_total = "SELECT COUNT(*) AS total FROM receitas";
$sql_receitas_concluidas = "SELECT COUNT(DISTINCT receitas_id_receita) AS concluidas FROM progresso_usuarios WHERE usuarios_id_usuario = '$id_usuario' AND fez_receita = 1";

$desafios = mysqli_fetch_assoc(mysqli_query($conn, $sql_desafios));
$receitas_total = mysqli_fetch_assoc(mysqli_query($conn, $sql_receitas_total));
$receitas_concluidas = mysqli_fetch_assoc(mysqli_query($conn, $sql_receitas_concluidas));

$total_desafios = $desafios['total'] ?? 0;
$total_receitas = $receitas_total['total'] ?? 0;
$concluidas = $receitas_concluidas['concluidas'] ?? 0;


$progresso = ($total_receitas > 0) ? round(($concluidas / $total_receitas) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Acompanhar Progresso</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #004b93;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
        }

        h1 {
            background-color: #ff8c00;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-size: 26px;
        }

        .card {
            background-color: #fff;
            color: #003366;
            border-radius: 15px;
            padding: 20px;
            width: 400px;
            margin: 15px 0;
            text-align: center;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .usuario {
            display: flex;
            align-items: center;
            background-color: #004b93;
            color: white;
            width: 400px;
            margin: 20px 0;
            padding: 10px;
            border-radius: 10px;
        }

        .usuario img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .barra {
            background-color: #b0c4de;
            border-radius: 20px;
            overflow: hidden;
            width: 400px;
            height: 25px;
            margin-top: 10px;
        }

        .progresso {
            background-color: #ff8c00;
            height: 100%;
            width: <?= $progresso ?>%;
            text-align: right;
            padding-right: 10px;
            border-radius: 20px;
            color: #fff;
            font-weight: bold;
            transition: width 1s ease;
        }

        .voltar {
            margin-top: 20px;
        }

        .voltar a {
            color: white;
            text-decoration: none;
            background-color: #ff8c00;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
        }

        .voltar a:hover {
            background-color: #ffa733;
        }
    </style>
</head>
<body>

    <h1>Acompanhar Progresso</h1>

    <div class="usuario">
        <div>
            <p><strong>Usuário:</strong> <?= htmlspecialchars($usuario) ?></p>
        </div>
    </div>

    <div class="card">
        <span>Desafios Completos</span>
        <span><?= $total_desafios ?></span>
    </div>

    <div class="card">
        <span>Receitas concluídas</span>
        <span><?= $concluidas ?>/<?= $total_receitas ?></span>
    </div>

    <div class="card" style="flex-direction: column; align-items: flex-start;">
        <span style="width: 100%; font-weight: bold; margin-bottom: 8px;">Progresso geral</span>
        <div class="barra">
            <div class="progresso"><?= $progresso ?>%</div>
        </div>
    </div>

    <div class="voltar">
        <a href="usuario.php">Voltar</a>
    </div>

</body>
</html>

