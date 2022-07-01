<?php
$db   = $_ENV["DB"];
$root = $_ENV["ROOT"];


session_start();
$db = new PDO("sqlite:$db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$res = $db->exec(
  "CREATE TABLE IF NOT EXISTS users (
    date TEXT NOT NULL,
    user TEXT NOT NULL UNIQUE PRIMARY KEY,
    password TEXT NOT NULL,
    suspended INTEGER NOT NULL,
    staff INTEGER NOT NULL,
    bio TEXT
  );
  CREATE TABLE IF NOT EXISTS links (
    date TEXT NOT NULL,
    root TEXT NOT NULL,
    by TEXT NOT NULL,
    url TEXT NOT NULL,
    password TEXT,
    uses INTEGER,
    max_uses INTEGER
  );
  CREATE TABLE IF NOT EXISTS invites (
    code TEXT NOT NULL,
    uses INTEGER NOT NULL,
    max_uses INTEGER NOT NULL
  );"
);
//UPDATE users SET password = \"\$2y$10\$Z5NEAFo/jHcoiufrSOnXQ.BCMpBXeqNwv/Oatvcj7ARSSPi274zJS\" WHERE user = \"luqaska\";
// INSERT INTO invites (code, uses, max_uses) VALUES (\"1\", 0, 1);
include $root . "vendor/autoload.php";
$key = new Cloudflare\API\Auth\APIKey($_ENV["MAIL"], $_ENV["TOKEN"]);
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
//print_r($adapter);
$user = new Cloudflare\API\Endpoints\User($adapter);
$zones = new Cloudflare\API\Endpoints\Zones($adapter);

// https://github.com/beikvar/genID
function genID($n) {
  $chars="0123456789abcdefghijklmnopqrstuvwxyz";
  $id = ''; $thing=0;
  for ($i=0; $i<$n; $i++) {
    $r = rand(0, strlen($chars)-1);
    $id .= $chars[$r];
  }
  return $id;
}

date_default_timezone_set('UTC');