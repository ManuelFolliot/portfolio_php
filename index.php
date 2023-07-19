<?php $auth = 0; 
include 'lib/includes.php'; 
include 'partials/header.php'; 

$select = $db->query("SELECT * FROM works");
$works = $select->fetchAll();
?>

    <main>       
        <h1>Mes réalisations :</h1>
        <div>
            <?php foreach($works as $k => $work): ?>
                <div>
                    <a href="view.php?id=<?= $work['id']; ?>">
                        <h2><?= $work['name'];?></h2>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

<?php include 'partials/footer.php'; ?>