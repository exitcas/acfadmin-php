<?php include "start.php";
if (isset($_SESSION["user"])) {
  //$list = $db->query("SELECT * FROM links WHERE `by` = \"$_SESSION[user]\"");
  $list = $db->query("SELECT * FROM links");
  echo "<ul>";
  foreach ($list as $link) {
    if ($link["by"] == $_SESSION["user"]) {
      if (isset($_GET["delete"]) && ($link["root"] == $_GET["delete"])) {
        $db->exec("DELETE FROM links WHERE `root` = \"$link[root]\";");
        echo "DELETE FROM links WHERE `root` = \"$link[root]\";";
        //echo "DELETE FROM links WHERE root = \"$_GET[delete]\";";
      } else {
        echo "<li><b>/$link[root]</b> <i>by $link[by]</i> <button onclick=\"navigator.clipboard.writeText('https://á.cf/$link[root]')\">Copy</button><a href=\"?delete=$link[root]\"><button>Delete</button></a></li>";
      }
    }
  }
  echo "<div id='last'><div></ul>";
}
$db = null;