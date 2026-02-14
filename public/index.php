<?php
// Page principale - Liste des employés
require_once __DIR__ . '/../includes/employes.php';

$message = $_GET['msg'] ?? '';
$employes = getAllEmployes();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTech - Intranet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }
        .header { background: linear-gradient(135deg, #1a73e8, #0d47a1); color: #fff; padding: 20px 40px; }
        .header h1 { font-size: 1.5rem; }
        .header small { opacity: 0.8; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 0.95rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; border: none; cursor: pointer; font-weight: 500; }
        .btn-primary { background: #1a73e8; color: #fff; }
        .btn-primary:hover { background: #1557b0; }
        .btn-edit { background: #ffc107; color: #333; padding: 6px 14px; font-size: 0.85rem; }
        .btn-delete { background: #dc3545; color: #fff; padding: 6px 14px; font-size: 0.85rem; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        th { background: #f8f9fa; text-align: left; padding: 14px 16px; font-size: 0.85rem; text-transform: uppercase; color: #555; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px 16px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        tr:hover td { background: #f8f9ff; }
        .actions { display: flex; gap: 8px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: 500; }
        .badge-info { background: #e3f2fd; color: #1565c0; }
        .badge-rh { background: #fce4ec; color: #c62828; }
        .badge-fin { background: #e8f5e9; color: #2e7d32; }
        .badge-mkt { background: #fff3e0; color: #e65100; }
        .badge-dir { background: #f3e5f5; color: #6a1b9a; }
        .empty { text-align: center; padding: 60px 20px; color: #888; }
        .footer { text-align: center; padding: 30px; color: #999; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMARTTECH Intranet</h1>
        <small>Gestion des Employés — smarttech.sn</small>
    </div>
    <div class="container">
        <?php if ($message === 'created'): ?>
            <div class="alert alert-success">Employé ajouté avec succès.</div>
        <?php elseif ($message === 'updated'): ?>
            <div class="alert alert-success">Employé modifié avec succès.</div>
        <?php elseif ($message === 'deleted'): ?>
            <div class="alert alert-success">Employé supprimé avec succès.</div>
        <?php elseif ($message === 'error'): ?>
            <div class="alert alert-error">Une erreur est survenue.</div>
        <?php endif; ?>

        <div class="toolbar">
            <h2>Liste des Employés (<?= count($employes) ?>)</h2>
            <a href="form.php" class="btn btn-primary">+ Ajouter un employé</a>
        </div>

        <?php if (empty($employes)): ?>
            <div class="empty">
                <p>Aucun employé enregistré.</p>
                <br>
                <a href="form.php" class="btn btn-primary">Ajouter le premier employé</a>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Poste</th>
                    <th>Département</th>
                    <th>Embauche</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employes as $emp): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($emp['nom']) ?></strong></td>
                    <td><?= htmlspecialchars($emp['prenom']) ?></td>
                    <td><?= htmlspecialchars($emp['email']) ?></td>
                    <td><?= htmlspecialchars($emp['poste']) ?></td>
                    <td>
                        <?php
                        $badgeClass = match($emp['departement']) {
                            'Informatique' => 'badge-info',
                            'Ressources Humaines' => 'badge-rh',
                            'Finance' => 'badge-fin',
                            'Marketing' => 'badge-mkt',
                            'Direction' => 'badge-dir',
                            default => 'badge-info',
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($emp['departement']) ?></span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($emp['date_embauche'])) ?></td>
                    <td class="actions">
                        <a href="form.php?id=<?= $emp['id'] ?>" class="btn btn-edit">Modifier</a>
                        <a href="delete.php?id=<?= $emp['id'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer cet employé ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <div class="footer">
        &copy; 2025 SmartTech SN — Intranet v1.0
    </div>
</body>
</html>
