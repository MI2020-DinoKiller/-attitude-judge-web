<?php
include_once("header.php");
if (!isset($_GET["id"])) {
    header("Location: /");
}
?>



<!-- <form id="SearchForm" @submit="checkForm" class="ui form" action="search.php" method="GET">
    <div class="field">
        <div class="ui fluid icon input">
            <input name="q" v-model="q" type="text" placeholder="開始搜尋......" required>
            <i class="search icon"></i>
        </div>
    </div>
    <button type="submit" class="massive ui primary button" v-bind:class="{loading: isActive, disabled: isActive}">搜尋</button>
</form> -->
<?php
include_once("config.php");
$sql = "SELECT SearchString FROM search WHERE SearchId = ?";
$sth = $conn->prepare($sql);
$sth->execute(array(intval($_GET['id'])));
$result = $sth->fetchAll();
?>

<h1 class="ui header">搜尋：<?= htmlspecialchars($result[0][0]) ?>
</h1>
<h2 class="ui header" id="status">
    <div class="ui active inline loader"></div>
    載入中......
</h2>

<div class="ui two column very relaxed grid" style="padding-top: 20px">
    <div class="column">
        <h2 class="ui blue center aligned top attached header">
            <i class="smile icon"></i>
            正向
        </h2>
        <table class="ui fixed single line celled padded table large selectable">
            <thead>
                <tr>
                    <th class="single line thirteen wide"><i class="file outline icon"></i>標題</th>
                    <th class="single line three wide"><i class="star outline icon"></i>分數</th>
                </tr>
            </thead>
            <tbody id="positive">
                <!-- <tr onclick="window.open('https://google.com');">
                    <td>ekwdnkleasnmddewdewdwedwedwedwedweklasnmkldjasldjskladjklasjdklasjdiklsa</td>
                    <td>0.0005</td>
                </tr> -->
            </tbody>
            <!-- <tfoot>
                <tr>
                    <th colspan="2">
                        <div class="ui pagination menu">
                            <a class="active item">
                                1
                            </a>
                            <div class="disabled item">
                                ...
                            </div>
                            <a class="item">
                                10
                            </a>
                            <a class="item">
                                11
                            </a>
                            <a class="item">
                                12
                            </a>
                        </div>
                    </th>
                </tr>
            </tfoot> -->
        </table>
    </div>
    <div class="column">
        <h2 class="ui orange center aligned top attached header">
            <i class="frown icon"></i>
            反向
        </h2>
        <table class="ui fixed single line celled padded table large selectable">
            <thead>
                <tr>
                    <th class="single line thirteen wide"><i class="file outline icon"></i>標題</th>
                    <th class="single line three wide"><i class="star outline icon"></i>分數</th>
                </tr>
            </thead>
            <tbody id="negative">
                <!-- <tr>
                    <td>7777777777</td>
                    <td>-6.0005</td>
                </tr> -->
            </tbody>
            <!-- <tfoot>
                <tr>
                    <th colspan="2">
                        <div class="ui pagination menu">
                            <a class="active item">
                                1
                            </a>
                            <div class="disabled item">
                                ...
                            </div>
                            <a class="item">
                                10
                            </a>
                            <a class="item">
                                11
                            </a>
                            <a class="item">
                                12
                            </a>
                        </div>
                    </th>
                </tr>
            </tfoot> -->
        </table>
    </div>
</div>

<?php
include_once("footer.php");
?>