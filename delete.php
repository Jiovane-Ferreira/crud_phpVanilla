<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php?erro=ID do usuário não especificado');
    exit;
}

$id = $_GET['id'];

try {
    // Verifica se o usuário existe
    $sql = "SELECT id FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        header('Location: index.php?erro=Usuário não encontrado');
        exit;
    }
    
    // Exclui o usuário
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header('Location: index.php?sucesso=Usuário excluído com sucesso!');
    } else {
        header('Location: index.php?erro=Erro ao excluir usuário');
    }
} catch(PDOException $e) {
    header('Location: index.php?erro=Erro ao excluir usuário: ' . $e->getMessage());
}
exit;