<?php
include_once("header.php");
?>

<form id="SearchForm" class="ui form" action="search.php" method="GET">
    <div class="field">
        <div class="ui fluid icon input">
            <input name="q" type="text" placeholder="開始搜尋......" required>
            <i class="search icon"></i>
        </div>
    </div>
    <button class="massive ui primary button" v-bind:class="{loading: isActive, disabled: isActive}" v-on:click="submitForm">搜尋</button>
</form>

<script src="/js/index.js"></script>

<?php
include_once("footer.php");
?>