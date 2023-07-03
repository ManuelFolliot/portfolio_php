<?php
include '../lib/includes.php';
include '../partials/admin_header.php';

/**
 * SUPPRESSION
 **/

if(isset($_GET['delete'])){
    checkCSRF();
    $id = $db->quote($_GET['delete']);
    $db->query("DELETE FROM works WHERE id=$id");
    setFlash('La catégorie a bien été supprimée');
    header('Location:work.php');
    die();
}

/**
 * works 
 **/

$select = $db->query("SELECT id, name, slug FROM works");
$works = $select->fetchAll();

?>

<h1>Mes réalisations</h1>

<p><a href="work_edit.php">Ajouter une nouvelle réalisation</a></p>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($works as $category): ?>
        <tr>
            <td><?= $category['id']; ?></td>
            <td><?= $category['name']; ?> </td>
            <td>
                <a href="work_edit.php?id=<?=$category['id']; ?>">Editer</a>
                <a href="?delete=<?=$category['id']; ?>&<?=csrf(); ?>" onclick="return confirm('Êtes vous sûr-e ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../partials/footer.php'; ?>