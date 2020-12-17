<?php
include_once("header.php");
?>

<a class="ui huge labeled icon button" href="search.php?id=<?= $_GET['id'] ?>">
    <i class="undo icon"></i>
    返回結果
</a>

<div id="chartContainer" style="height: 800px; width: 100%;"></div>

<?php
include_once("footer.php");
?>