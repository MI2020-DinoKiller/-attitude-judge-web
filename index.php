<?php
include_once("header.php");
?>

<form class="ui form" action="search.php" method="GET">
    <div class="field">
        <div class="ui fluid icon input">
            <input name="q" type="text" placeholder="開始搜尋......" required>
            <i class="search icon"></i>
        </div>
    </div>
    <button class="massive ui primary button" type="submit">搜尋</button>
</form>

<?php
include_once("footer.php");
?>