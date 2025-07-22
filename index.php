<?php include_once('config.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão Interna - Henrique Amaral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h1 class="mb-4">Lista de Usuários</h1>
        
        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
        <?php endif; ?>
        
        <a href="create.php" class="btn btn-primary mb-3">Novo Usuário</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
        
            <?php

                try {
                    $sql = "SELECT * FROM usuarios ORDER BY criado_em DESC";
                    $usuarios = $pdo->query($sql);
                    
                    while ($row = $usuarios->fetch()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $row['telefone'] ? htmlspecialchars($row['telefone']) : '-' ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile;
                } catch(PDOException $e) {
                    echo "<tr><td colspan='6' class='text-danger'>Erro ao carregar usuários: " . $e->getMessage() . "</td></tr>";
                }
                
            ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>