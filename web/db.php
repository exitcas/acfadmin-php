<?php include "start.php";
foreach ($db->query("SELECT * FROM users") as $user) {
  print_r($user); echo "<br>";
}
foreach ($db->query("SELECT * FROM invites") as $user) {
  print_r($user); echo "<br>";
}
foreach ($db->query("SELECT * FROM links") as $user) {
  print_r($user); echo "<br>";
}*/