<?php

try {
    $db = new PDO('mysql:host=localhost;dbname=portfolio', 'root', '');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    // Test de connexion à la base de données
    $query = $db->query("SELECT 1");
    if ($query !== false) {
        echo "Connexion à la base de données établie avec succès.";
    } else {
        echo "Erreur lors de la connexion à la base de données.";
    }
} catch (Exception $e) {
    echo 'Impossible de se connecter à la base de données';
    echo $e->getMessage();
    die();
}

var_dump($_GET['id']);

include '../lib/includes.php';

if(isset($_POST['name']) && isset($_POST['slug'])){
    checkCSRF();
    $slug = $_POST['slug'];
    if(preg_match('/^[a-z\-0-9]+$/', $slug)){
        $name = $db->quote($_POST['name']);
        $slug = $db->quote($_POST['slug']);    
        if(isset($_GET['id'])){
            $id = $db->quote($_GET['id']);
            $db->query("UPDATE categories SET name=$name, slug=$slug WHERE id=$id");
        }else{
            $db->query("INSERT INTO categories SET name=$name, slug=$slug");
        }
        setFlash('La catégorie a été bien enregistrée');
        header('Location:category.php');
        die();
    }else{
        setFlash('Le slug n\'est pas valide', 'danger');
    }
}

if(isset($_GET['id'])){
    $id = $db->quote($_GET['id']);
    $select = $db->query("SELECT * FROM categories WHERE id=$id");
    if($select->rowCount() == 0){
        setFlash("Il n'y a pas de catégorie avec cet ID", "danger");
        header('Location:category.php');
        die();
    }
    $_POST = $select->fetch();
}

include '../partials/admin_header.php';


?>

<h1>Editer une catégorie</h1>

<form action="#" method="post">
    <div>
        <label for="name">Nom de la catégorie</label>
        <?= input('name'); ?>
    </div>
    <div>
        <label for="slug">URL de la catégorie</label>
        <?= input('slug'); ?>
    </div>
    <?= csrfInput(); ?>
    <button type="submit">Enregistrer</button>
</form>

<?php include '../partials/footer.php'; ?>