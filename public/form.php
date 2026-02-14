<?php
// Formulaire d'ajout / modification d'un employé
require_once __DIR__ . '/../includes/employes.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$employe = $id > 0 ? getEmployeById($id) : null;
$isEdit = $employe !== null;
$errors = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom'           => trim($_POST['nom'] ?? ''),
        'prenom'        => trim($_POST['prenom'] ?? ''),
        'email'         => trim($_POST['email'] ?? ''),
        'poste'         => trim($_POST['poste'] ?? ''),
        'departement'   => $_POST['departement'] ?? '',
        'date_embauche' => $_POST['date_embauche'] ?? '',
    ];

    // Validation
    if (empty($data['nom']))           $errors[] = "Le nom est requis.";
    if (empty($data['prenom']))        $errors[] = "Le prénom est requis.";
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (empty($data['poste']))         $errors[] = "Le poste est requis.";
    if (empty($data['departement']))   $errors[] = "Le département est requis.";
    if (empty($data['date_embauche'])) $errors[] = "La date d'embauche est requise.";

    if (empty($errors)) {
        try {
            if ($isEdit) {
                updateEmploye($id, $data);
                header("Location: index.php?msg=updated");
            } else {
                createEmploye($data);
                header("Location: index.php?msg=created");
            }
            exit;
        } catch (Exception $e) {
            $errors[] = "Erreur : " . $e->getMessage();
        }
    }
} else {
    $data = $employe ?? [
        'nom' => '', 'prenom' => '', 'email' => '',
        'poste' => '', 'departement' => '', 'date_embauche' => ''
    ];
}

$departements = ['Informatique', 'Ressources Humaines', 'Finance', 'Marketing', 'Direction'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Modifier' : 'Ajouter' ?> un employé - SmartTech</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }
        .header { background: linear-gradient(135deg, #1a73e8, #0d47a1); color: #fff; padding: 20px 40px; }
        .header h1 { font-size: 1.5rem; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        .card { background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .card h2 { margin-bottom: 20px; color: #1a73e8; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 0.9rem; }
        .form-group input, .form-group select {
            width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px;
            font-size: 0.95rem; transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group select:focus { border-color: #1a73e8; outline: none; }
        .btn { display: inline-block; padding: 10px 24px; border-radius: 6px; font-size: 0.95rem; border: none; cursor: pointer; font-weight: 500; text-decoration: none; }
        .btn-primary { background: #1a73e8; color: #fff; }
        .btn-primary:hover { background: #1557b0; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #545b62; }
        .btn-group { display: flex; gap: 12px; margin-top: 10px; }
        .errors { background: #f8d7da; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb; }
        .errors li { margin-left: 16px; color: #721c24; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMARTTECH Intranet</h1>
    </div>
    <div class="container">
        <div class="card">
            <h2><?= $isEdit ? 'Modifier l\'employé' : 'Nouvel employé' ?></h2>

            <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($data['nom']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($data['prenom']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="poste">Poste</label>
                    <input type="text" name="poste" id="poste" value="<?= htmlspecialchars($data['poste']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="departement">Département</label>
                    <select name="departement" id="departement" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($departements as $dep): ?>
                        <option value="<?= $dep ?>" <?= ($data['departement'] === $dep) ? 'selected' : '' ?>><?= $dep ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_embauche">Date d'embauche</label>
                    <input type="date" name="date_embauche" id="date_embauche" value="<?= htmlspecialchars($data['date_embauche']) ?>" required>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Ajouter' ?></button>
                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
