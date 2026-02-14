<?php
// Fonctions CRUD pour les employés
require_once __DIR__ . '/db.php';

function getAllEmployes(): array {
    $db = getDB();
    return $db->query("SELECT * FROM employes ORDER BY nom, prenom")->fetchAll();
}

function getEmployeById(int $id): ?array {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM employes WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

function createEmploye(array $data): int {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO employes (nom, prenom, email, poste, departement, date_embauche) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['nom'], $data['prenom'], $data['email'],
        $data['poste'], $data['departement'], $data['date_embauche']
    ]);
    $id = (int)$db->lastInsertId();
    logAction('CREATE', 'employes', $id, "Nouvel employé: {$data['prenom']} {$data['nom']}");
    return $id;
}

function updateEmploye(int $id, array $data): bool {
    $db = getDB();
    $stmt = $db->prepare("UPDATE employes SET nom=?, prenom=?, email=?, poste=?, departement=?, date_embauche=? WHERE id=?");
    $result = $stmt->execute([
        $data['nom'], $data['prenom'], $data['email'],
        $data['poste'], $data['departement'], $data['date_embauche'], $id
    ]);
    logAction('UPDATE', 'employes', $id, "Employé modifié: {$data['prenom']} {$data['nom']}");
    return $result;
}

function deleteEmploye(int $id): bool {
    $db = getDB();
    $emp = getEmployeById($id);
    $nom = $emp ? "{$emp['prenom']} {$emp['nom']}" : "ID=$id";
    $stmt = $db->prepare("DELETE FROM employes WHERE id = ?");
    $result = $stmt->execute([$id]);
    logAction('DELETE', 'employes', $id, "Employé supprimé: $nom");
    return $result;
}
