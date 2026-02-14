<?php
// Suppression d'un employé
require_once __DIR__ . '/../includes/employes.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        deleteEmploye($id);
        header("Location: index.php?msg=deleted");
    } catch (Exception $e) {
        header("Location: index.php?msg=error");
    }
} else {
    header("Location: index.php?msg=error");
}
exit;
