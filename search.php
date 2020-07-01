<?php
include_once("header.php");
if (!isset($_GET["q"])) {
    header("Location: /");
}
?>

<form class="ui form" action="search.php" method="GET">
    <div class="field">
        <div class="ui fluid icon input">
            <input name="q" type="text" placeholder="開始搜尋......" value="<?php echo $_GET["q"]; ?>" required>
            <i class="search icon"></i>
        </div>
    </div>
    <button class="massive ui primary button" type="submit">搜尋</button>
</form>

<script src="/js/index.js"></script>

<?php

$output = shell_exec("/usr/local/bin/python3 google-search-crawler/app.py '" . $_GET["q"] . "' 1");
echo $output;

?>

<?php
include_once("footer.php");
?>