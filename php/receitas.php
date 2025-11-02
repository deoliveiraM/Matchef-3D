<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Matchef 3D - Cadastro</title>
    <link rel="stylesheet" href="../css/adm.css">
</head>
<body>

<?php
// Handler simples de submissão do formulário de receitas
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["acao"]) && $_POST["acao"] === "adicionar_receita") {
    // Conexão com o banco
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "tcc";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Pegando dados do formulário
    $titulo      = $_POST["titulo"];
    $ingredientes = $_POST["ingredientes"];
    $modo        = $_POST["modo"];
    $desafio     = $_POST["desafio"];

    // Transformar ingredientes em texto único
    if (is_array($ingredientes)) {
        $ingredientes = implode(", ", $ingredientes);
    }

    // Gerar próximo id_receita
    $res = $conn->query("SELECT COALESCE(MAX(id_receita),0)+1 AS nextid FROM receitas");
    $nextId = ($res) ? $res->fetch_assoc()["nextid"] : 1;

    // Inserir no banco
    $stmt = $conn->prepare("INSERT INTO receitas (id_receita, descricao, titulo, nivel_dificuldade, receitascol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $nextId, $modo, $titulo, $desafio, $ingredientes);

    if ($stmt->execute()) {
        echo "✅ Receita cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    $titulo = trim($_POST['titulo'] ?? '');
    $ingredientes = $_POST['ingredientes'] ?? [];
    $modo = trim($_POST['modo'] ?? '');
    $desafio = trim($_POST['desafio'] ?? '');
    $erro = '';

    // Validações básicas
    if ($titulo === '') { $erro = 'Informe o título da receita.'; }
    elseif (count(array_filter($ingredientes)) === 0) { $erro = 'Adicione ao menos um ingrediente.'; }
    elseif ($modo === '') { $erro = 'Informe o modo de preparo.'; }

    // Processa upload de imagem (opcional)
    $imagemPath = '';
    if (empty($erro) && !empty($_FILES['imagem']['name'])) {
        $uploadDir = realpath(__DIR__ . '/../images');
        if ($uploadDir === false) { $erro = 'Pasta de imagens não encontrada no servidor.'; }
        else {
            $tmp = $_FILES['imagem']['tmp_name'];
            $name = basename($_FILES['imagem']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif'];
            if (!in_array($ext, $allowed)) { $erro = 'Formato de imagem não permitido. Use jpg, png ou gif.'; }
            else {
                $newName = uniqid('r_') . '.' . $ext;
                $dest = $uploadDir . DIRECTORY_SEPARATOR . $newName;
                if (move_uploaded_file($tmp, $dest)) {
                    $imagemPath = '../images/' . $newName; // caminho relativo para uso em HTML
                } else {
                    $erro = 'Falha ao salvar a imagem.';
                }
            }
        }
    }
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'tcc';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    $erro = 'Erro ao conectar ao banco: ' . $conn->connect_error;
}
    if ($erro === '') {
        // Aqui você poderia salvar os dados em um banco de dados. Por enquanto apenas mostraremos um resumo.
        echo "<div class=\"message\" style=\"color:lightgreen\">✅ Receita <b>" . htmlspecialchars($titulo) . "</b> adicionada com sucesso.</div>";
        echo "<div class=\"message small\">Ingredientes: <b>" . htmlspecialchars(implode(', ', array_filter($ingredientes))) . "</b></div>";
        if ($imagemPath) echo "<div class=\"message\"><img class=\"preview-img\" src=\"$imagemPath\" alt=\"imagem receita\"></div>";
    } else {
        echo "<div class=\"message\" style=\"color:#ffdddd\">⚠️ " . htmlspecialchars($erro) . "</div>";
    }
}
?>

    <div class="sidebar">
        <div class="admin">
            👨‍🍳 <span>Administrador</span>
        </div>
        <button onclick="location.href='adm.php'">🔍 Buscar usuário</button>
        <button onclick="location.href='progresso.php'">📊 Progresso</button>
        <button onclick="location.href='receitas.php'">📖 Adicionar receitas</button>
    </div>

    <div class="content">
        <h1>Adicionar nova receita</h1>
        <div class="form-card">
            <form method="post" enctype="multipart/form-data" id="formReceita">
                <input type="hidden" name="acao" value="adicionar_receita">
                <div class="form-row">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>

                <div class="form-row ingredients-list">
                    <label>Ingredientes <span class="small">(adicione quantos precisar)</span></label>
                    <div id="ingredientesContainer">
                        <div class="ingredient-item">
                            <input type="text" name="ingredientes[]" placeholder="1 xícara de farinha">
                            <button type="button" class="btn secondary" onclick="removerIngrediente(this)">Remover</button>
                        </div>
                    </div>
                    <button type="button" class="btn" onclick="adicionarIngrediente()">Adicionar ingrediente</button>
                </div>

                <div class="form-row">
                    <label for="modo">Modo de preparo</label>
                    <textarea id="modo" name="modo" rows="6" required></textarea>
                </div>

                <div class="form-row">
                    <label for="desafio">Desafio</label>
                    <input type="text" id="desafio" name="desafio" placeholder="Um desafio ou observação para essa receita">
                </div>

                <div class="form-row">
                    <label for="imagem">Imagem</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                </div>

                <div style="text-align:center; margin-top:18px;">
                    <button type="submit" class="btn">Salvar receita</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function adicionarIngrediente() {
            const container = document.getElementById('ingredientesContainer');
            const div = document.createElement('div');
            div.className = 'ingredient-item';
            div.innerHTML = '<input type="text" name="ingredientes[]" placeholder="Ex: 1 colher de sopa de açúcar"> <button type="button" class="btn secondary" onclick="removerIngrediente(this)">Remover</button>';
            container.appendChild(div);
        }
        function removerIngrediente(btn) {
            const item = btn.closest('.ingredient-item');
            if (!item) return;
            const container = document.getElementById('ingredientesContainer');
            // Se for o único campo, apenas limpe-o
            if (container.querySelectorAll('.ingredient-item').length === 1) {
                item.querySelector('input').value = '';
            } else {
                item.remove();
            }
        }
    </script>

</body>
</html>