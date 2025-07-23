<?php 
// inserção da configuração com o banco de dados.
require_once 'config.php';

// Configuração da paginação, assumindo que se não houver, então o resultado é 1.
// retorna sempre 5 registros por página e define o offset

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 5;
$offset = ($pagina - 1) * $por_pagina;


// Busca os usuários com paginação e faz tratamento de erros utilizando try-catch
// faz o tratamento para evitar SQL injections utilizando o "bindParam".
try {
    $sql = "SELECT * FROM usuarios ORDER BY criado_em DESC LIMIT :offset, :por_pagina";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':por_pagina', $por_pagina, PDO::PARAM_INT);
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
    
    // Conta o total de registros e faz o arredondamento para ser exibido
    $total_sql = "SELECT COUNT(*) as total FROM usuarios";
    $total_stmt = $pdo->query($total_sql);
    $total = $total_stmt->fetch()['total'];
    $total_paginas = ceil($total / $por_pagina);
    
} catch(PDOException $e) {
    die("Erro ao carregar usuários: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuários</title>
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
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= $usuario['telefone'] ? htmlspecialchars($usuario['telefone']) : '-' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="delete.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Paginação -->
        <?php if ($total_paginas > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</body>
</html>