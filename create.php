<?php

require_once __DIR__  .'/config.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    //validação básica
    if(empty($nome) || empty($email)) {

        $erro = 'Nome e email são obrigatórios';

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } else {
        try {
            $sql = "INSERT INTO usuarios (
            
                nome,
                email,
                telefone
            
            ) VALUES (
            
                :nome, 
                :email,
                :telefone
            
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            
            if ($stmt->execute()) {
                header('Location: index.php?sucesso=Usuário cadastrado com sucesso!');
                exit;
            }
        } catch(PDOException $msg) {
            if($msg->getCode() == 23000) {
                $erro = 'Email já cadastrado';
            } else {
                $erro = 'Erro ao cadastrar usuário: ' .$msg->getMessage();
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
    <title>Cadastrar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Cadastrar Usuário</h1>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif ?>

        <form action="#" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome*</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone">
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>