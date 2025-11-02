<?php
session_start();

if (!isset($_SESSION['nome'])) {
    $_SESSION['nome'] = "Usuário Padrão"; // valor inicial
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Matchef 3D</title>
    <link rel="stylesheet" href="../css/tcc4.css">
</head>
<body>

<div class="sidebar">
    <div class="user-info">
        👨‍🍳 <strong><?php echo htmlspecialchars($_SESSION['nome']); ?></strong><br>
        Nível 1
    </div>
    
    <a href="?page=editar">✏️ Editar perfil</a>
    <a href="?page=progresso">📊 Progresso</a>
    <a href="?page=receitas">📖 Receitas</a>
    <a href="?page=aprenda">🎬 Aprenda mais</a>
</div>

<div class="main-content">
    <h1>Matchef 3D</h1>
    <p><a href="#">Quem somos?</a></p>

    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        switch ($page) {
            case 'editar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $_SESSION['nome'] = $_POST['nome'];
                    echo "<p>Nome atualizado com sucesso!</p>";
                }
                echo '<h2>Editar Perfil</h2>
                      <form method="POST">
                          <label for="nome">Nome:</label><br>
                          <input type="text" name="nome" value="' . htmlspecialchars($_SESSION['nome']) . '"><br><br>
                          <button type="submit">Salvar</button>
                      </form>';
                break;

            case 'progresso':
                header("Location: progresso.php");
    exit;
                echo "<h2>Progresso</h2><p>Acompanhe seu desempenho e evolução!</p>";
                break;

            case 'receitas':
               
// Conexão com o banco
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tcc";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<p style='color:red;'>Erro na conexão: " . $conn->connect_error . "</p>");
}

// Buscar receitas
$sql = "SELECT id_receita, titulo, descricao, nivel_dificuldade, receitascol FROM receitas ORDER BY id_receita DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id    = $row['id_receita'];
        $titulo = htmlspecialchars($row['titulo']);
        $desc  = htmlspecialchars($row['descricao']);
        $nivel = htmlspecialchars($row['nivel_dificuldade']);

        

        // Escolher uma imagem padrão conforme dificuldade
        switch (strtolower($nivel)) {
            case 'fácil': $img = 'logo amarela.png'; break;
            case 'médio': $img = 'logo azul.png'; break;
            case 'difícil': $img = 'logo vermelha.png'; break;
            default: $img = 'logo azul.png'; break;
        }



            // Adiciona ao array de cards
    $cards[] = [
        "id"    => $id,
        "title" => $titulo,
        "desc"  => $desc,
        "img"   => $img
    ];
     
    }
} else {
    echo "<p style='color:gray;'>Nenhuma receita cadastrada ainda.</p>";
}

               echo "<h2>Receitas</h2>";
                echo '<p>Confira receitas deliciosas para praticar. Clique em uma carta para ver os detalhes.</p>';
                echo '
                <style>
                /* Grid simples para as cartas */
                .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 18px; margin-top: 12px; }
                .recipe-card { background:#fff; border-radius:10px; padding:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08); text-align:left; text-decoration:none; color:inherit; transition:transform .12s ease, box-shadow .12s ease; }
                .recipe-card:hover { transform:translateY(-6px); box-shadow:0 18px 36px rgba(0,0,0,0.12); }
                .recipe-thumb{ width:100%; height:120px; object-fit:cover; border-radius:8px; margin-bottom:8px; background:#f2f2f2; display:block; }
                .recipe-title{ font-weight:700; margin:6px 0; }
                .recipe-desc{ font-size:0.92rem; color:#555; margin-bottom:8px; }
                </style>';

                echo '<div class="cards-grid">';
             
                foreach($cards as $c){
                    $link = "ver_receita.php?id=".intval($c['id']);
                    $img = htmlspecialchars($c['img'], ENT_QUOTES);
                    $imgPath = '../images/' . $img;
                    $title = htmlspecialchars($c['title'], ENT_QUOTES);
                    $desc = htmlspecialchars($c['desc'], ENT_QUOTES);
                    echo "<a class=\"recipe-card\" href=\"$link\">";
                    if(file_exists(__DIR__ . '/../images/' . $img)){
                        echo "<img class=\"recipe-thumb\" src=\"$imgPath\" alt=\"$title\" />";
                    } else {
                        echo "<div class=\"recipe-thumb\" style=\"display:flex;align-items:center;justify-content:center;color:#888;\">Imagem</div>";
                    }
                    echo "<div class=\"recipe-title\">$title</div>";
                    echo "<div class=\"recipe-desc\">$title</div>";
                    echo "</a>";
                }
                echo '</div>';
                break;
            case 'aprenda':
                echo "<h2>Aprenda mais</h2><p>Assista aos vídeos e melhore suas habilidades culinárias!</p>";
                break;
        }
    }
    ?>
</div>

</body>
</html>
