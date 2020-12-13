<?php
include_once("header.php");
?>

<div class="ui main text container">

    <h1 class="ui header" id="total_sent"></h1>
    <h1 class="ui header" id="title">網頁標題</h1>
    <a href="#" id="url" target="blank" class="ui inverted tag label">
        <i class="bug icon"></i> 按此到原文連結
    </a>
</div>

<div class="ui text container" id="article">
    <div class="ui segment" style="height: 200px" id="loading_screen">
        <p></p>
        <div class="ui active dimmer">
            <div class="ui text loader">Loading</div>
        </div>
    </div>
</div>
</br>
<div class="ui center aligned container">
    <!-- <a class="ui labeled icon large button" href="">
        <i class="arrow alternate circle left outline icon"></i>
        上一個
    </a> -->
    <a class="ui labeled icon large button" href="" onclick="history.go(-1); return false;">
        <i class="reply icon"></i>
        返回
    </a>
    <!-- <a class="ui right labeled icon large button" href="">
        <i class="arrow alternate circle right outline icon"></i>
        下一個
    </a> -->
</div>

<?php
include_once("footer.php");
?>