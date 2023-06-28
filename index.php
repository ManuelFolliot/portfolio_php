<?php $auth = 0; ?>
<?php include 'lib/includes.php'; ?>
<?php include 'partials/header.php'; ?>

    <main>       
        <h1>Mes réalisations :</h1>
        <div>
            <?php 
            $select = $db->query('SELECT * FROM users');
            var_dump($select->fetch());
            ?>
        </div>
    </main>

<?php include 'lib/debug.php'; ?>
<?php include 'partials/footer.php'; ?>