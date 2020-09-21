<?php
include 'Connection.php';

$query = "SELECT * FROM contacts";
$result = mysqli_query($con,$query);

$arr = array();
if(mysqli_num_rows($result) > 0){
    while($contacts = mysqli_fetch_assoc($result)){
        $arr[] = $contacts;
    }
}
// print_r($arr);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment;filename=content.csv');
$output = fopen('php://output','w');
fputcsv($output, array('ID','Name','Email','Phone','Country'));

if(count($arr) > 0){
    foreach($arr as $row){
        fputcsv($output, $row);
    }
}

?>