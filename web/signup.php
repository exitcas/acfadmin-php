<?php include "start.php";
/*$db->exec("INSERT INTO invites (code)
VALUES (\"1\");");*/
if (isset($_GET["invite"])) {
  $invites = $db->query("SELECT * FROM invites");
  $f = false;
  foreach ($invites as $invite) {
    if ($invite["code"] == $_GET["invite"]) {
      $f = true;
      if (isset($_POST["user"]) && isset($_POST["pass"])) {
        echo $_POST["user"] . "<hr>";
        $t = preg_replace("/[\n|\r|\s|\t]/", "&", $_POST["user"]);
        echo "<p style='color:yellow'>$t</p>";
        $t = str_split($t);
        //print_r($t);
        //die(implode("", $t));
        if ((count($t) > 0) && (count($t) < 17)) {
          $n = false;
          foreach ($t as $id => $char) {
            //echo $id;
            if (strtolower($char) != (
              "0"||"1"||"2"||"3"||"4"||"5"||"6"||"7"||"8"||"9" ||"a"||"b"||"c"||"d"||"e"||"f"||"g"||"h"||"i"||"j"||"k"||"l"||"m"||"n"||"o"||"p"||"q"||"r"||"s"||"t"||"u"||"v"||"w"||"x"||"y"||"z"||"_"
            )) {
              $n = true;
            }
          }
          if (!$n) {
            $t = implode("", $t);
            $n = false;
            try {
              $db->exec("INSERT INTO users (date, user, password, suspended, staff)
                VALUES (\"" . date(DATE_ATOM) . "\", \"$t\", \"" . password_hash($_POST["pass"], PASSWORD_BCRYPT) . "\", 0, 0);");
            } catch (PDOException $ex) {
              $n = true;
            } finally {
              if (!$n) {
                if ($invite["uses"] < $invite["max_uses"]) {
                  $db->exec("UPDATE invites SET `uses` = " . ($invite["uses"] + 1) . " WHERE `code` = \"$invite[code]\";");
                } else {
                  $db->exec("DELETE FROM invites WHERE `code` = \"" . $invite["code"] . "\";");
                }
              } else {
                $msg = "Error creating account. Username unavailable";
              }
            }
          } else {
            $msg = "You can only use latin letters, numbers and the underscore for your username";
          }
        } else {
          $msg = "Your username must has to be between 1-16 characters";
        }
      } else {
        $msg = "(If you don't complete both, the system would not take it)";
      }
    }
  }
  if (!$f) {
    $msg = "Invalid invite";
  }
} else {
  $msg = "No invite provided";
}
$db = null; ?>
<!DOCTYPE html>
<html>
<head><title>Sign up</title></head>
<body style="text-align:center">
<h1><img style="image-rendering:pixelated" width="80px" height="auto" src="acf.png" /><br />Sign up</h1>
<form method="POST" action="<?= $_SERVER["REQUEST_URI"] ?>">
  <?php if (isset($msg)) { echo "<p>$msg</p>"; } ?>
  <input type="text" placeholder="Username" name="user" /><br />
  <input type="password" placeholder="Password" name="pass" /><br />
  <input type="submit" />
</form>
<p>Ask the webmaster for access</p>
</body>
</html>