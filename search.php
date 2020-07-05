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



<div class="ui divided items">
    <div class="item">
        <div class="content">
            <h1 class="ui header">日本研究：起斑點香蕉具「抗癌力」 香蕉變黑營養價值更高 | Heho ...</h1>
            <div class="meta">
                <span>2020年4月1日</span>
            </div>
            <div class="description">
                <p>吃香蕉好處多，許多人買香蕉時，都喜歡挑外表漂亮的香蕉，尤其還帶點青綠色，看到長了斑的或是變黑的香蕉，全都不選。最新日本研究指出熟透起...</p>
            </div>
            <div class="extra">
                <i class="big green check icon"></i>
                可信任機構
            </div>
        </div>
    </div>
    <div class="item">
        <div class="content">
            <h1 class="ui header">日本研究：起斑點香蕉具「抗癌力」 香蕉變黑營養價值更高 | Heho ...</h1>
            <div class="meta">
                <span>2020年4月1日</span>
            </div>
            <div class="description">
                <p>吃香蕉好處多，許多人買香蕉時，都喜歡挑外表漂亮的香蕉，尤其還帶點青綠色，看到長了斑的或是變黑的香蕉，全都不選。最新日本研究指出熟透起...</p>
            </div>
            <div class="extra">
            </div>
        </div>
    </div>
    <div class="item">
        <div class="content">
            <h1 class="ui header">日本研究：起斑點香蕉具「抗癌力」 香蕉變黑營養價值更高 | Heho ...</h1>
            <div class="meta">
                <span>2020年4月1日</span>
            </div>
            <div class="description">
                <p>吃香蕉好處多，許多人買香蕉時，都喜歡挑外表漂亮的香蕉，尤其還帶點青綠色，看到長了斑的或是變黑的香蕉，全都不選。最新日本研究指出熟透起...</p>
            </div>
            <div class="extra">
                <i class="green check icon"></i>
                可信任機構
            </div>
        </div>
    </div>
</div>
<?php

$output = shell_exec("/usr/local/bin/python3 google-search-crawler/app.py '" . $_GET["q"] . "' 1");
$output = preg_replace("{{.*}}", "", $output);
echo "<div class=\"ui segment\"><pre style=\"overflow-x: scroll;\">" . htmlspecialchars($output) . "</pre></div>";

?>

<?php
include_once("footer.php");
?>