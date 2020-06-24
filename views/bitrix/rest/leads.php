<?php

$out = '<table><th>Лид</th><th>Статус</th>';
foreach ($leads as $lead) {
    $out .= "<td>$lead->name</td><td>$lead->status</td>";
}
$out .= "</table>";

print_r($out);die();
?>