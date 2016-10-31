<?

/*echo strtotime ("now"), "<br>";
echo strtotime ("10 September 2000"), "<br>";
echo strtotime ("+1 day"), "<br>";
echo strtotime ("+1 week 2 days 4 hours 2 seconds"), "<br>";
echo strtotime ("next Thursday"), "<br>";
echo strtotime ("last Monday"), "<br>"; */

$fecha_vencimiento = date('Y-m-d', strtotime ("-12 week"));
$query_del = "DELETE FROM seccion_voto WHERE fecha_voto < '$fecha_vencimiento' ";

echo "Se eliminaron los registros de votación anteriores al día ".date('d/m/Y', strtotime ("-12 week")).".";

?>