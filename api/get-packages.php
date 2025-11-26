<?php
header("Content-Type: application/json");

$conn = mysqli_connect("sql213.infinityfree.com","if0_38678399","6oh9qJmrXPB","if0_38678399_tm");

$q = mysqli_query($conn, "SELECT id,name,price,days FROM packages");

$data = [];
while($row = mysqli_fetch_assoc($q)) {
  $data[] = $row;
}

echo json_encode($data);
