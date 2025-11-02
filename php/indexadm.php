<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Matchef 3D - Cadastro</title>
    <link rel="stylesheet" href="../css/tcc3.css">
    </head>
<body>
    <?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
        header("Location: adm.php"); // redireciona para login
    } else {
        echo "Erro: " . $stmt->error;
    }
}
?>
    <!-- Botão de voltar -->
    <a href="inicio.php" class="back-btn">Voltar</a>

    <div class="container">
        <h1>Matchef 3D ADM</h1>
        <form method="POST" action="">
            <input type="text" name="nome" placeholder="Nome" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <input type="password" name="confirmar" placeholder="Confirmar senha" required><br>
            <button type="submit" class="btn">Cadastrar</button>
        </form>
        
    </div>
</body>
   </html>