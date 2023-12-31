<?php
include '../lib/includes.php';
include '../partials/admin_header.php';

/**
 * SUPPRESSION
 **/

if(isset($_GET['delete'])){
    checkCSRF();
    $id = $db->quote($_GET['delete']);
    $db->query("DELETE FROM categories WHERE id=$id");
    setFlash('La catégorie a bien été supprimée');
    header('Location:category.php');
    die();
}

/**
 * CATEGORIES 
 **/

$select = $db->query("SELECT id, name, slug FROM categories");
$categories = $select->fetchAll();

?>

<h1>Les catégories</h1>

<p><a href="category_edit.php">Ajouter une nouvelle catégorie</a></p>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categories as $category): ?>
        <tr>
            <td><?= $category['id']; ?></td>
            <td><?= $category['name']; ?> </td>
            <td>
                <a href="category_edit.php?id=<?=$category['id']; ?>">Editer</a>
                <a href="?delete=<?=$category['id']; ?>&<?=csrf(); ?>" onclick="return confirm('Êtes vous sûr-e ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../partials/footer.php'; ?>