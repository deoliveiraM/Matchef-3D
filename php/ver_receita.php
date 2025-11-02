<?php
// Conexão com o banco de dados
include 'conexao.php'; // ou o arquivo onde você cria $conn

// Pega o ID da receita
$id = intval($_GET['id'] ?? 0);

if($id <= 0){
    echo "<p>ID inválido.</p>";
    exit;
}

// Consulta a receita no banco
$sql = "SELECT * FROM receitas WHERE id_receita = $id";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){
    $titulo = htmlspecialchars($row['titulo'], ENT_QUOTES);
    $desc = htmlspecialchars($row['descricao'], ENT_QUOTES);
    $nivel = htmlspecialchars($row['nivel_dificuldade'], ENT_QUOTES);

    // Escolher imagem da receita (ou padrão conforme dificuldade)
    $img = $row['imagem'] ?? ''; // Supondo que exista campo 'imagem' na tabela
    if(empty($img)){
        switch(strtolower($nivel)){
            case 'fácil': $img = 'logo amarela.png'; break;
            case 'médio': $img = 'logo azul.png'; break;
            case 'difícil': $img = 'logo vermelha.png'; break;
            default: $img = 'logo azul.png'; break;
        }
    }

    

    $imgPath = '../images/' . $img; // caminho da pasta de imagens
    echo $imgPath;
} else {
    echo "<p>Receita não encontrada.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title><?php echo $titulo; ?> | Receita</title>
<style>
body { font-family: Arial, sans-serif; background:#f9f9f9; padding:20px; }
.recipe-container { max-width:700px; margin:0 auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.08); }
.recipe-container img { width:100%; height:auto; border-radius:8px; margin-bottom:15px; }
.recipe-container h1 { margin-bottom:10px; }
.recipe-container p { line-height:1.6; color:#333; }
.recipe-container .nivel { font-weight:bold; margin-top:10px; }
.back-link { display:inline-block; margin-top:15px; text-decoration:none; color:#007BFF; }
.back-link:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="recipe-container">
    <h1><?php echo $titulo; ?></h1>
    <?php if(file_exists(__DIR__ . '/' . $imgPath)): ?>
        <img src="<?php echo $imgPath; ?>" alt="<?php echo $titulo; ?>">
    <?php else: ?>
        <div style="width:100%; height:200px; background:#ccc; display:flex; align-items:center; justify-content:center; border-radius:8px; color:#666;">Imagem não disponível</div>
    <?php endif; ?>
    <p><?php echo nl2br($desc); ?></p>
    <p class="nivel">Dificuldade: <?php echo $nivel; ?></p>
    <a class="back-link" href="usuario.php?page=receitas">← Voltar para Receitas</a>
</div>

</body>
</html>
