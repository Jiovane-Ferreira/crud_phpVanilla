<?php

require_once __DIR__  .'/config.php';

$erro = '';
$sucesso = '';

if (!isset($_GET['id'])) {
    header('Location: index.php?erro=ID do usuário não especificado');
    exit;
}

$id = $_GET['id'];

try {
    // Carrega os dados do usuário
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        header('Location: index.php?erro=Usuário não encontrado');
        exit;
    }
} catch(PDOException $msg) {
    header('Location: index.php?erro=Erro ao carregar usuário: ' . $msg->getMessage());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    
    // Validação básica
    if (empty($nome) || empty($email)) {
        $erro = 'Nome e email são obrigatórios!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } else {
        try {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                header('Location: index.php?sucesso=Usuário atualizado com sucesso!');
                exit;
            }
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $erro = 'Este email já está cadastrado para outro usuário!';
            } else {
                $erro = 'Erro ao atualizar usuário: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar Usuário</h1>
        
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome*</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>