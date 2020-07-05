<?php
include_once("header.php");
if (!isset($_GET["q"])) {
    header("Location: /");
}
?>

<script type="application/javascript">
    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }
    var myDataFromPhp = {
        'q': decodeEntities("<?php echo htmlspecialchars($_GET["q"]); ?>")
    };
</script>


<form id="SearchForm" @submit="checkForm" class="ui form" action="search.php" method="GET">
    <div class="field">
        <div class="ui fluid icon input">
            <input name="q" v-model="q" type="text" placeholder="開始搜尋......" required>
            <i class="search icon"></i>
        </div>
    </div>
    <button type="submit" class="massive ui primary button" v-bind:class="{loading: isActive, disabled: isActive}">搜尋</button>
</form>



<?php

$output = shell_exec("/usr/local/bin/python3 google-search-crawler/app.py '" . $_GET["q"] . "' 1");
echo $output;

?>

<?php
include_once("footer.php");
?>