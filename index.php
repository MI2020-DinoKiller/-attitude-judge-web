<?php
include_once("header.php");
?>

<form id="SearchForm" @submit="checkForm" class="ui form" action="search.php" method="GET">
    <div class="field">
        <div class="ui fluid icon input">
            <input name="q" v-model="q" v-on:input="trimremove" type="text" placeholder="開始搜尋......" required>
            <i class="search icon"></i>
        </div>
    </div>
    <button type="submit" class="massive ui primary button" v-bind:class="{loading: isActive, disabled: isActive}">搜尋</button>
</form>

<?php
include_once("footer.php");
?>