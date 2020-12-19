<?php
include_once("./header.php");
?>
<div class="ui three statistics">
    <div class="statistic">
        <div class="label">
            等待的查詢句
        </div>
        <div class="value" id="searchQueue">
            0
        </div>
    </div>
    <div class="statistic">
        <div class="label">
            等待的結果數量
        </div>
        <div class="value" id="taskQueue">
            0
        </div>
    </div>
    <div class="statistic">
        <div class="label">
            等待時間
        </div>
        <div class="value" id="waitTime">
            0 MIN
        </div>
    </div>
</div>
<?php
include_once("./footer.php");
?>