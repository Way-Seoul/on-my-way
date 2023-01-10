<?php
$title = 'Challenge List';
ob_start();
?>
<h1><?= $title ?></h1>
<div id="challenges">
    <form action="../on-my-way/index.php?action=list-challenges" method="POST">
        <?php for($i = 0; $i<count($places); $i++): 
            $challenges = $c_manager->getChallenges($places[$i]["id"]);    
        ?>
        <details>
            <summary><?= $places[$i]["name"] ?></summary>
            <ul>
                <?php foreach($challenges as $challenge): ?>
                <li>
                    <a href="index.php?action=challenge-specific&id=<?= $challenge['id']?>"><?= $challenge["name"] ?></a>
                    <div class="btns">
                        <button type="submit" name="delete" value="<?= $challenge['id'] ?>">DELETE</button>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
        </details>
        <?php endfor ?>
    </form>
</div>


<?php
    $html = ob_get_clean(); // give the code into a variable
    include 'template.php'; // and call the variable from the template
?>