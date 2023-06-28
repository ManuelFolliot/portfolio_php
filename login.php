<?php $auth=0 ; 
include 'lib/includes.php'; 

/*********
 * Traitement du formulaire 
 ********/

    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $db->quote($_POST['username']);
        $password = sha1($_POST['password']);
        $select = $db->query("SELECT * FROM users WHERE username=$username AND password='$password'");
        if($select->rowCount() > 0){
            $_SESSION['auth'] = $select->fetch();
            setFlash('Vous êtes maintenant connecté');
            header('Location:' . WEBROOT . 'admin/index.php');
            die();
        };
    }
/**
 * Inclusion du header 
 **/

include 'partials/header.php'; 
?>

<form action="#" method="post">
    <div>
        <label for="username">Nom d'utilisateur</label>
        <?= input('username'); ?>
    </div>
    <div>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password">
    </div>
    <button type="submit">Se connecter</button>
</form>
<?php include 'lib/debug.php'; ?>
<?php include 'partials/footer.php'; ?>