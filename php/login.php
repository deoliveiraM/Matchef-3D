<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Segunda Página</title>
    <link rel="stylesheet" href="../css/tcc2.css">
</head>
<body>
    <?php
include("conexao.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = $user['nome'];
            $_SESSION['email'] = $user['email'];
            if($user['tipo']==1)
            header("Location: usuario.php"); // página protegida
            else
            header("Location: adm.php"); // página protegida
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}
?>
    <a href="inicio.php" class="back-btn">Voltar</a>

    <div class="container">
        <h1>Matchef 3D</h1>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <button href="usuario.php" type="submit" class="btn">Login</button>
        </form>
        <a href="recuperar.php" class="link">Recuperar senha</a>
    </div>
</body>
</html>
