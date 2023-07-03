<?php

include '../lib/includes.php';

if(isset($_POST['name']) && isset($_POST['slug'])){
    checkCSRF();
    $slug = $_POST['slug'];
    if(preg_match('/^[a-z\-0-9]+$/', $slug)){
        $name = $db->quote($_POST['name']);
        $slug = $db->quote($_POST['slug']);   
        $category_id = $db->quote($_POST['category_id']);
        $content = $db->quote($_POST['content']); 

        /**
         * SAUVGEGARDE DE LA REALISATION
         **/


        if(isset($_GET['id'])){
            $id = $db->quote($_GET['id']);
            $db->query("UPDATE works SET name=$name, slug=$slug, category_id=$category_id, content=$content WHERE id=$id");
        }else{
            $db->query("INSERT INTO works SET name=$name, slug=$slug, content=$content, category_id=$category_id");
            $_GET['id'] = $db->lastInsertId();
        }
        setFlash('La réalisation a été bien enregistré');

        /**
         *ENVOIE DE L'IMAGE
         **/

        $work_id = $db->quote($_GET['id']);
        $image = $_FILES['image'];
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        if(in_array($extension, array('jpg', 'png'))){
            $db->query("INSERT INTO images SET work_id=$work_id");
            $image_id = $db->lastInsertId();
            $image_name = $image_id . '.' . $extension;
            move_uploaded_file($image['tmp_name'], IMAGES . '/works/' . $image_name);
            $image_name = $db->quote($image_name);
            $db->query("UPDATE images SET name=$image_name WHERE id = $image_id");
        }
        header('Location:work.php');
        die();
    }else{
        setFlash('Le slug n\'est pas valide', 'danger');
    }
}

/**
 * RECUPERATION D'UNE REALISATION
 **/

if(isset($_GET['id'])){
    $id = $db->quote($_GET['id']);
    $select = $db->query("SELECT * FROM works WHERE id=$id");
    if($select->rowCount() == 0){
        setFlash("Il n'y a pas de réalisation avec cet ID", "danger");
        header('Location:work.php');
        die();
    }
    $_POST = $select->fetch();
}

/**
 * SUPPRESSION D'IMAGES
 **/

if(isset($_GET['delete_image'])){
    checkCSRF();
    $image_id = $_GET['delete_image'];
    $select = $db->query("SELECT name, work_id FROM images WHERE id=$image_id");
    $image = $select->fetch();
    unlink(IMAGES . '/works/' . $image['name']);
    $db->query("DELETE FROM images WHERE id=$image_id");
    setFlash("L'image a été bien supprimée");
    header('Location:work_edit.php?id=' . $image['work_id']);
    die();
}

/**
 * RECUPERATION DE LA LISTE DES CATEGORIES
 **/

$select = $db->query('SELECT id, name FROM categories ORDER BY name ASC');
$categories = $select->fetchAll();
$categories_list = array();
foreach($categories as $category){
    $categories_list[$category['id']] = $category['name'];
}

include '../partials/admin_header.php';

?>

<h1>Editer une réalisation</h1>

<form action="#" method="post" enctype="multipart/form-data">
    <div>
        <label for="name">Nom de la réalisation</label>
        <?= input('name'); ?>
    </div>
    <div>
        <label for="slug">URL de la réalisation</label>
        <?= input('slug'); ?>
    </div>
    <div>
        <label for="content">Contenu de la réalisation</label>
        <?= textarea('content'); ?>
    </div>
    <div>
        <label for="category_id">Catégorie</label>
        <?= select('category_id', $categories_list); ?>
    </div>
    <?= csrfInput(); ?>
    <div>
        <input type="file" name="image">
    </div>
    <button type="submit">Enregistrer</button>
</form>
<?php
    $work_id = isset($_GET['id']) ? $db->quote($_GET['id']) : null;
    $image_name = '';
    if ($work_id) {
        $image_name = $db->query("SELECT name FROM images WHERE work_id = $work_id")->fetchColumn();
    }    $image_path = WEBROOT . 'img/works/' . $image_name;
?>
<?php if ($image_name): ?>
    <a href="?delete_image=<?= $work_id ?>&<?= csrf();?>" onclick="return confirm('Sûr de sûr ?');">
        <img src="<?= WEBROOT . 'img/works/' . $image_name ?>" alt="workimage" width='100'>
    </a>
<?php endif; ?>
<?php include '../partials/footer.php'; ?>