<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Matchef 3D - Cadastro</title>
    <link rel="stylesheet" href="../css/adm.css">

    </head>
<body>
<?php
// index.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = htmlspecialchars($_POST['usuario']);
    echo "<p style='color:white; text-align:center; font-size:18px;'>🔍 Você pesquisou: <b>$usuario</b></p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Matchef 3D</title>
  
</head>
<body>

    <div class="sidebar">
        <div class="admin">
            👨‍🍳 <span>Administrador</span>
        </div>
        <button onclick="location.href='adm.php'">🔍 Buscar usuário</button>
        <button onclick="location.href='progresso.php'">📊 Progresso</button>
        <button onclick="location.href='receitas.php'">📖 Adicionar receitas</button>
        <button onclick="location.href='receitas.php'">📖 Adicionar ingrediente</button>
    </div>

    <div class="content">
        <h1>Matchef 3D</h1>
        <h2>Encontrar usuário</h2>
        <form method="POST">
            <div class="search-box">
                <input type="text" name="usuario" placeholder="Digite o nome do usuário">
                <button type="submit">🔍</button>
            </div>
        </form>
    </div>

</body>
</html>