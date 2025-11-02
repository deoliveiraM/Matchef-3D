<?php
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

// Cabeçalho
echo "<h2>Receitas</h2>";
echo '<p>Confira receitas deliciosas para praticar. Clique em uma carta para ver os detalhes.</p>';

// Estilos
echo '
<style>
.cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-top: 12px; }
.recipe-card { background:#fff; border-radius:12px; padding:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08); text-align:left; text-decoration:none; color:inherit; transition:transform .12s ease, box-shadow .12s ease; }
.recipe-card:hover { transform:translateY(-6px); box-shadow:0 18px 36px rgba(0,0,0,0.12); }
.recipe-thumb{ width:100%; height:140px; object-fit:cover; border-radius:8px; margin-bottom:8px; background:#f2f2f2; display:block; }
.recipe-title{ font-weight:700; margin:6px 0; }
.recipe-desc{ font-size:0.92rem; color:#555; margin-bottom:8px; }
.recipe-tag{ font-size:0.8rem; color:#777; background:#f2f2f2; border-radius:6px; padding:3px 8px; display:inline-block; margin-top:4px; }
</style>
';

echo '<div class="cards-grid">';

// Se houver receitas no banco, criar cards
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

        echo "
        <a class='recipe-card' href='?page=receita&id=$id'>
            <img class='recipe-thumb' src='images/$img' alt='$titulo'>
            <div class='recipe-title'>$titulo</div>
            <div class='recipe-desc'>$desc</div>
            <div class='recipe-tag'>Nível: $nivel</div>
        </a>
        ";
    }
} else {
    echo "<p style='color:gray;'>Nenhuma receita cadastrada ainda.</p>";
}

echo "</div>"; // fecha .cards-grid
$conn->close();
?>
