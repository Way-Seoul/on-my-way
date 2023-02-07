<?php
$h1 = 'CHALLENGE LIST';
$title = 'ON MY WAY | ' . $h1;
ob_start();
?>
<script src="./public/responsive_search_bar.js" defer></script>
<script>
    let locations = [];
    <?php foreach($places as $place): ?>
        locations.push(['<?= $place['name'] ?>', <?= $place['latitude'] ?>, <?= $place['longitude'] ?>])
    <?php endforeach ?>
</script>

<h1><?= $h1 ?></h1>

<!--NEW CONTENT: Responsive Search Bar-->
<form id="search_form">
    <input id="search" type="text" class="input" placeholder="search..."/>
    <button id="clear" type="button" class="clear-results">clear</button>
</form>

<div id="challenges">
    <?php if(isset($delete_msg)): ?> 
    <p style="font-size:1.1em; margin-bottom:10px;color:red;"><?= $delete_msg ?></p>
    <?php endif ?>
    <?php for($i = 0; $i<count($places); $i++): 
        $challenges = $c_manager->getChallenges($places[$i]["id"]);    
    ?>
    <details open>
        <summary><?= $places[$i]["name"] ?></summary>
        <ul>
            <?php foreach($challenges as $challenge): ?>
            <li>
                <a href="<?= CHALLENGE_PATH ?>&id=<?= $challenge['id']?>"><?= $challenge["name"] . ' >' ?></a>
            </li>
            <?php endforeach ?>
        </ul>
    </details>
    <?php endfor ?>
</div>
<div id="logged-in-map"></div>
<div id="map"></div>


<?php
    $html = ob_get_clean(); // give the code into a variable
    include 'template.php'; // and call the variable from the template
?>