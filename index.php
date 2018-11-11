<?php include('inc/head.php');

if (!isset($_GET["f"])) {
    $dirPath = "files";
} else {
    if (is_dir(($_GET["f"]))) {
        if (strpos($_GET["f"], "/..") != FALSE) {
            $dirPath = implode("/", explode("/", $_GET["f"], -2));
        } else {
            $dirPath = $_GET["f"];
        }
    } elseif (is_file(($_GET["f"]))) {
        $dirPath = implode("/", explode("/", $_GET["f"], -1));
        $fichierURL = $_GET["f"];
        $contentFile = file_get_contents($fichierURL);
    }
}

$dir = opendir("$dirPath");

while ($file = readdir($dir)) {
    if (is_dir($dirPath . "/" . $file)) {
        if ($file != ".." and $file != ".") {
            echo "<a href=?f=" . $dirPath . "/" . $file . ">" . $file . "</a><br>"; ?>

            <form action="" method="post">
                <input type="hidden" name="dirToDelete" value="<?php if (isset($_GET["f"])) {
                    echo $_GET["f"] . "/" . $file;
                } ?>"/>
                <button>Supprimer</button>
            </form>
            <?php echo "<br>";
            
            
        } elseif ($file != ".") {
            echo "<a href=?f=" . $dirPath . "/" . $file . ">" . $file . "</a><br>";
        }
        
    } else {
        echo "<div>" . $file . " - <a href=?f=" . $dirPath . "/" . $file . ">Editer</a><br>"; ?>

        <form action="index.php" method="post">
            <input type="hidden" name="fileToDelete" value="<?php if (isset($_GET["f"])) {
                echo $_GET["f"] . "/" . $file;
            } ?>"/>
            <input type="submit" value="Supprimer">
        </form>
        
        <?php echo "<br></div>";
        
        
    }
}


if (isset($_POST["contenu"])) {
    $fileToUpdate = $_POST["file"];
    $file = fopen($fileToUpdate, "w");
    fwrite($file, $_POST["contenu"]);
    fclose($file);
}

if (isset($_POST["fileToDelete"])) {
    echo $_POST["fileToDelete"];
    unlink($_POST["fileToDelete"]);
    
}

if (isset($_POST["dirToDelete"])) {
    array_map('unlink', glob($_POST["dirToDelete"] . "/*.txt"));
    rmdir($_POST["dirToDelete"]);
}


?>
    <br>
    <form action="#" method="post">
        <label for="edition">Edition</label>
        <textarea id="edition" name="contenu" style="width: 100%;">
        <?php echo $contentFile ?>
    </textarea>
        <input type="hidden" name="file" value="<?php if (isset($_GET["f"])) {
            echo $_GET["f"];
        } ?>"/>
        <button>Envoyer</button>

    </form>

<?php include('inc/foot.php'); ?>