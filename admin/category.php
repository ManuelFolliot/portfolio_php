<?php
include '../lib/includes.php';
include '../partials/admin_header.php';

$select = $db->query('SELECT id, name, slug FROM categories');
$categories = $select->fetchAll();
?>

<h1>Les catégories</h1>

<?= var_dump($categories) ?>

<table>

</table>

<?php include '../partials/footer.php'; ?>