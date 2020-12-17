<?php
include_once("header.php");
?>
<form id="myForm">
    <div class="ui form">
        <div class="field">
            <div class="ui massive fluid left icon input">
                <i class="search icon"></i>
                <input name="q" id="q" type="text" placeholder="開始搜尋......" required>
            </div>
        </div>
        <div class="field">
            <div class="ui massive fluid left icon input">
                <i class="mail icon"></i>
                <input name="e" id="e" type="email" placeholder="輸入您的 Email" required>
            </div>
        </div>
        <input type="button" class="massive ui primary button" onclick="send()" value="搜尋"></input>
    </div>
</form>
<?php
include_once("footer.php");
?>