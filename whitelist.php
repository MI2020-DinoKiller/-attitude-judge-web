<?php
include_once("./header.php");
?>
<div class="ui form">
  <div class="field">
    <div class="ui selection fluid dropdown whitelistclass">
      <input type="hidden" name="whitelistclass">
      <i class="dropdown icon"></i>
      <div class="default text">請選擇白名單種類</div>
    </div>
  </div>

  <form action="api/whitelist.php">
    <div class="field">
      <div class="ui selection dropdown whitelist fluid disabled search selection">
        <input type="hidden" name="r">
        <i class="dropdown icon"></i>
        <div class="default text">請選擇單位</div>
      </div>
    </div>
    <button type="submit" class="massive ui primary button disabled">前往</button>
  </form>
</div>
<?php
include_once("footer.php");
?>