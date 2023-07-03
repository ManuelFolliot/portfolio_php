<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= WEBROOT; ?>css/style.css">
    <title>Project training</title>
</head>
<body>
    <header>
        <h1>Mon administration</h1>
        <ul>
            <li>
                <a href="category.php">Catégories</a>
            </li>
            <li>
                <a href="work.php">Mes réalisations</a>
            </li>
        </ul>
    </header>

    <?= flash("Connexion réussie"); ?>