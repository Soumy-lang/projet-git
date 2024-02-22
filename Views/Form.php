<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

    print_r($_POST);

?>

<form
    method="<?= $config["config"]["method"]??"GET" ?>"
    action="<?= $config["config"]["action"]??"" ?>"
    class="<?= $config["config"]["class"]??"" ?>"
    id="<?= $config["config"]["id"]??"" ?>">


    <?php if(!empty($data)) :?>
    <div style="background-color: red">
        <?php foreach ($data as $error):?>
            <li><?= $error ?></li>
        <?php endforeach;?>
    </div>
    <?php endif;?>


    <?php foreach ($config["inputs"] as $name=>$configInput):?>

        <input
            name="<?= $name?>"
            type="<?= $configInput["type"]??"text"?>"
            id="<?= $configInput["id"]??""?>"
            class="<?= $configInput["class"]??""?>"
            placeholder="<?= $configInput["placeholder"]??""?>"
            <?= (!empty($configInput["required"]))?"required":""?>
        ><br>

    <?php endforeach;?>

    

    <input type="submit" name="submit" value="<?= $config["config"]["submit"]??"Se connecter" ?>">
</form>
<?php } ?>