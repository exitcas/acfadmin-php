<?php include "start.php";
if (isset($_GET["root"])) {
  $resp = [];
  $db = $db->query("SELECT * FROM links");
  $f = false;
  foreach ($db as $link) {
    if ($link["root"] == $_GET["root"]) {
      if ($link["password"]) {
        if (isset($_GET["password"])) {
          $algo = "sm4";
          //try {
            //$iv = openssl_cipher_iv_length(openssl_random_pseudo_bytes());
            $url = openssl_decrypt($link["url"], $algo, $_GET["password"], 0, $link["password"]);
          //}
        }
        $resp["error"] = 2; // Password required
      } else {
        $resp["error"] = false;
        $resp["url"] = false;
      }
      $f = true;
    }
  }
  if (!$f) { $resp["error"] = 1; }
  die(json_encode($resp));
}