<?php
function roomTableList($conn)
{
  $rooms = array();
  $sonuc = mysqli_query($conn, "SHOW TABLES");
  if (!$sonuc) {
    return $rooms;
  }
  while ($satir = mysqli_fetch_array($sonuc)) {
    $table_name = $satir[0];
    if (strpos($table_name, "_room")) {
      $rooms[] = $table_name;
    }
  }
  return $rooms;
}

function isValidRoomTable($conn, $name)
{
  if (!is_string($name) || !preg_match('/^[A-Za-z0-9_]+$/', $name)) {
    return false;
  }
  return in_array($name, roomTableList($conn), true);
}

function isCreatableRoomTable($conn, $name)
{
  if (!is_string($name) || !preg_match('/^[A-Za-z0-9_]+_room$/', $name)) {
    return false;
  }
  $sonuc = mysqli_query($conn, "SHOW TABLES");
  if (!$sonuc) {
    return false;
  }
  while ($satir = mysqli_fetch_array($sonuc)) {
    if ($satir[0] === $name && !strpos($satir[0], "_room")) {
      return false;
    }
  }
  return true;
}
