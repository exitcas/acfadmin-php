<?php include "start.php";
if (!isset($_SESSION["user"])) {
  if (isset($_POST["user"]) && isset($_POST["password"])) {
    $f = false;
    $db = $db->query("SELECT * FROM users");
    foreach ($db as $user) {
      //echo $user["user"];
      if (
        (strtolower($user["user"]) == strtolower($_POST["user"])) &&
        password_verify($_POST["password"], $user["password"])
      ) {
        $_SESSION["user"] = $user["user"];
        header("location: /");
      } else {
        $msg = "Invalid username/password";
      }
    }
  } ?>
<!DOCTYPE html>
<html>
<head><title>Log in</title></head>
<body style="text-align:center">
<h1><img style="image-rendering:pixelated" width="80px" height="auto" src="acf.png" /><br />Log in</h1>
<form method="POST">
  <?php if (isset($msg)) { echo "<p>$msg</p>"; } ?>
  <input type="text" placeholder="Username" name="user" /><br />
  <input type="password" placeholder="Password" name="password" /><br />
  <input type="submit" />
</form>
<p>Ask the webmaster for access</p>
</body>
</html>
<?php } else { ?>
<!DOCTYPE html>
<html>
<head>
<style>
  header {
    display: flex;
    align-items: center;
  }
  header p {
    margin-left: 10px;
  }
</style>
</head>
<body>
<header><h1>&aacute;.cf</h1><p><a href="?">Home</a> - <a href="?new_link">New link</a></p></header>
<?php if (isset($_GET["news"])) {
    //a
  } elseif (isset($_GET["new_link"])) {
    if (isset($_POST["url"])) {
      if (!isset($_POST["root"]) || (!$_POST["root"] && $_POST["root"] != "0")) {
        $h = 0;
        $links = $db->query("SELECT * FROM links");
        while ($h < 1) {
          $root = genID(7);
          $a = false;
          foreach ($links as $link) {
            if (strtolower($link["root"]) == strtolower($root)) {
              $a = true;
            }
          }
          if (!$a) {
            $h++;
          }
        }
        $root = $root;
      } else {
        if (preg_match("/[\n|\s|\t|\?|\#|\!]/", $_POST["root"])) {
          $msg = "Invalid characters detected on your root";
        } else {
          $e = false;
          $links = $db->query("SELECT * FROM links");
          foreach ($links as $link) {
            if (strtolower($link["root"]) == strtolower($_POST["root"])) {
              echo strtolower($link["root"]) . "<br>" . $_POST["root"];
              $e = true;
            }
          }
          if (!$e) {
            if (filter_var($_POST["url"], FILTER_VALIDATE_URL)) {
              $url = $_POST["url"];
              $root = $_POST["root"];
            } else {
              $msg = "The link provided is not a valid link";
            }
          } else {
            $msg = "Link already exists";
          }
        }
      }
      if (isset($url) && isset($root)) {
        if (isset($_POST["max_uses"]) && is_int($_POST["max_uses"]) && $_POST["max_uses"] && ((int)$_POST["max_uses"] < 0)) {
          $m_uses = ", max_uses, uses";
          $max_uses = ", :max_uses, :uses";
        } else {
          $m_uses = "";
          $max_uses = "";
        }
        $stmt = $db->prepare(
          "INSERT INTO links (date, root, by, url$m_uses) 
            VALUES (:date, :root, :by, :url$max_uses)"
        );
        $stmt->bindValue(':date', date(DATE_ATOM), SQLITE3_TEXT);
        $stmt->bindValue(':root', $root, SQLITE3_TEXT);
        $stmt->bindValue(':by', $_SESSION["user"], SQLITE3_TEXT);
        $stmt->bindValue(':url', $url, SQLITE3_TEXT);
        if ($max_uses) {
          $stmt->bindValue(':max_uses', (int)$_POST["max_uses"], SQLITE3_INTEGER);
          $stmt->bindValue(':uses', 0, SQLITE3_INTEGER);
        }
        $stmt->execute();
      }
    } ?>
<form method="POST">
  <?php if (isset($msg)) { echo "<div>$msg</div>"; } ?>
  <table>
    <tr>
      <td><label for="url">URL<span title="Required" style="color:red">*</span>: </label></td>
      <td><input name="url" type="url" placeholder="eg. https://example.com" /></td>
    </tr>
    <tr>
      <td width="5%"><label for="root">Root: </label></td>
      <td><input name="root" type="text" placeholder="eg. hello" /></td>
    </tr>
    <tr>
      <td><label for="max_uses">Max. uses: </label></td>
      <td><input name="max_uses" type="number" placeholder="eg. 69" min="1" style="width:50px" /></td>
    </tr>
  </table>
  <p><input type="submit" value="SHORT!" /> <input type="reset" /></p>
</form>
<?php
  } elseif (isset($_GET["ayuda"])) {
    echo "<h2>Ayuda</h2>";
  } else {
    echo '<h1>Your links</h1><iframe width="100%" height="300px" allow="clipboard-read;clipboard-write" src="links.php#last"></iframe>';
  }
?>
<hr />&copy;2022 <a href="https://atico.ga">Luqaska</a>
</body>
</html>
<?php
}