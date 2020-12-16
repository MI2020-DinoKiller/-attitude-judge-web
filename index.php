<?php
include_once("header.php");
?>
<form id="myForm">
    <div class="ui form">
        <div class="field">
            <div class="ui fluid icon input">
                <input name="q" id="q" type="text" placeholder="開始搜尋......" required>
                <i class="search icon"></i>
            </div>
        </div>
        <div class="field">
            <div class="ui fluid icon input">
                <input name="e" id="e" type="email" placeholder="輸入您的 Email" required>
                <i class="mail icon"></i>
            </div>
        </div>
        <input type="button" class="massive ui primary button" onclick="send()" value="搜尋"></input>
    </div>
</form>
<?php
include_once("footer.php");
?>