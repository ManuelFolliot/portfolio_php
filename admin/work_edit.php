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
         * SAUVEGARDE DE LA REALISATION
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

        if (isset($_FILES['images'])){
            $work_id = $db->quote($_GET['id']);

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $image = $_FILES['images'];
                $extension = pathinfo($image['name'][$key], PATHINFO_EXTENSION);

                if(in_array($extension, array('jpg', 'png'))) {
                    $db->query("INSERT INTO images set work_id=$work_id");
                    $image_id = $db->lastInsertId();
                    $image_name = $image_id . '.' . $extension;
                    move_uploaded_file($tmp_name, IMAGES . '/works/' . $image_name);
                    $image_name = $db->quote($image_name);
                    $db->query("UPDATE images SET name=$image_name WHERE id = $image_id");
                }
            }
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

 if (isset($_GET['delete_image'])) {
    checkCSRF();
    $image_id = $_GET['delete_image'];

    $query = $db->prepare("SELECT name, work_id FROM images WHERE id = :image_id");
    $query->bindParam(":image_id", $image_id);
    $query->execute();
    $image = $query->fetch();

    if ($image) {
        $image_name = $image['name'];
        $work_id = $image['work_id'];

        $delete_query = $db->prepare("DELETE FROM images WHERE id = :image_id");
        $delete_query->bindParam(":image_id", $image_id);
        $delete_query->execute();

        // Supprimer l'image du dossier
        $image_path = IMAGES . '/works/' . $image_name;
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        setFlash("L'image a été bien supprimée");
        header('Location: work_edit.php?id=' . $work_id);
        die();
    } else {
        setFlash("L'image n'a pas pu être trouvée", "danger");
        header('Location: work.php');
        die();
    }
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
        <label for="image">Image</label>
        <input type="file" name="images[]" multiple>
    </div>
    <button type="submit">Enregistrer</button>
</form>
<?php
    $work_id = isset($_GET['id']) ? $db->quote($_GET['id']) : null;
    $image_names = array();
    if ($work_id) {
        $image_names = $db->query("SELECT name FROM images WHERE work_id = $work_id")->fetchAll(PDO::FETCH_COLUMN);
    }   
    foreach($image_names as $image_name) {
        $image_path = WEBROOT . 'img/works/' . $image_name;
    
        $query = $db->prepare("SELECT id FROM images WHERE name = :image_name");
        $query->bindParam(":image_name", $image_name);
        $query->execute();
        $image = $query->fetch();
    
        if ($image) {
            $image_id = $image['id'];
            
            echo '<a href="?delete_image=' . $image_id . '&amp;' . csrf() . '" onclick="return confirm(\'Sûr de sûr ?\');">';
            echo '<img src="' . $image_path . '" alt="workimage" width="100">';
            echo '</a>';
        }
    }
    
?>
<?php include '../partials/footer.php'; ?>