<?php
session_start();

include_once("../configuration/configuration.php");
include("../configuration/includes.php");

$TITLE="YAAM Informations";
include("../configuration/header.php");



$answer = $bdd->query("SELECT COUNT(*) AS nb FROM users WHERE activated=1") or die(mysql_error());
$data = $answer->fetch();
$usernumber=$data['nb'];

$answer = $bdd->query("SELECT COUNT(*) AS nb FROM developers") or die(mysql_error());
$data = $answer->fetch();
$devnumber= $data['nb'];

$answer = $bdd->query("SELECT COUNT(*) AS nb FROM applications") or die(mysql_error());
$data = $answer->fetch();
$appnumber=$data['nb'];

$answer = $bdd->query("SELECT COUNT(*) AS nb FROM stats") or die(mysql_error());
$data = $answer->fetch();
$count=$data['nb'];

$answer = $bdd->query("SELECT COUNT(*) AS nb FROM applications WHERE price>0") or die(mysql_error());
$data = $answer->fetch();
$count2=$data['nb'];


?>

<h2>Analytics</h2>
<br />


<div id="chartdownloads" style="width: 100%; height: 400px">You must activate Javascript</div>

<br />

<u>Users number</u> : <?php echo $usernumber; ?>

<br /><br />

<u>Developpers number</u> : <?php echo $devnumber; ?>

<br /><br />

<u>Applications number</u> : <?php echo $appnumber; ?>

<br /><br />

<br />

<div id="chartlang" style="width: 100%; height: 400px">You must activate Javascript</div>

<br /><br />


<br />

<div id="chartprices" style="width: 100%; height: 400px">You must activate Javascript</div>

<br /><br />


<br />

<div id="chartphones" style="width: 100%; height: 400px">You must activate Javascript</div>




<?php

$values="";
$answer = $bdd->query("SELECT phone FROM stats GROUP BY phone ORDER BY COUNT(phone)/$count DESC") or die(mysql_error());

while ( $data =  $answer->fetch()  ) { 
    $answer2 = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE phone='".$data['phone']."'") or die(mysql_error());
    $data2 = $answer2->fetch();
    
    if($data2['nb']/$count*100 > 1)
    {
	    $values.="{
    name: '".$data['phone']."',
    data: [".round($data2['nb']/$count*100,2)."]
 },";
    }
}

$js="chart2 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartphones',
    defaultSeriesType: 'column'
 },
 title: {
    text: 'Devices used'
 },
 tooltip: {
        formatter: function() {
            return this.series.name+' : <b>'+this.y+'%</b>';
        }
    },
 xAxis: {
    categories: ['Value']
 },
 yAxis: {
    title: {
       text: 'Percentage'
    }
 },
 series: [";
    
$js.=$values;

$js.="]
});";








$values="";

$answer = $bdd->query("SELECT price FROM applications WHERE price>0 GROUP BY price ORDER BY COUNT(price)/$count2 DESC") or die(mysql_error());

while ( $data =  $answer->fetch() ) {
if($data['price']!=0)
{
    $answer2 = $bdd->query("SELECT COUNT(*) AS nb FROM applications WHERE price LIKE ".$data['price']) or die(mysql_error());
    $data2 = $answer2->fetch();
    
    $values.="{
    name: '".$data['price']."€',
    data: [".round($data2['nb']/$count2*100,2)."]
	 },";
}
}
$js.="chart3 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartprices',
    defaultSeriesType: 'column'
 },
 title: {
    text: 'Paid applications'
 },
 tooltip: {
        formatter: function() {
            return this.series.name+' : <b>'+this.y+'%</b>';
        }
    },
 xAxis: {
    categories: ['Value']
 },
 yAxis: {
    title: {
       text: 'Percentage'
    }
 },
 series: [";
    
$js.=$values;

$js.="]
});";


$values="";
$answer = $bdd->query("SELECT lang FROM stats GROUP BY lang ORDER BY COUNT(lang)/$count DESC") or die(mysql_error());

while ($data =  $answer->fetch() ) { 
   if($data['lang']!="")
   {
    $answer2 = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE lang='".$data['lang']."'") or die(mysql_error());
    $data2 = $answer2->fetch();
    
    if(round($data2['nb']/$count*100,2)>0.5)
    {
	    $values.="{
	    name: '".$data['lang']."',
	    data: [".round($data2['nb']/$count*100,2)."]
		 },";
    }
   }
}
$js.="chart4 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartlang',
    defaultSeriesType: 'column'
 },
 title: {
    text: 'Languages'
 },
 tooltip: {
        formatter: function() {
            return this.series.name+' : <b>'+this.y+'%</b>';
        }
    },
 xAxis: {
    categories: ['Value']
 },
 yAxis: {
    title: {
       text: 'Percentage'
    }
 },
 series: [";
    
$js.=$values;

$js.="]
});";









/////////////GRAPH DOWNLOADS/////////////
$values="";
$valuesUpdates="";
$days="";

$startTime = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
$endTime = mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
for($i=15;$i>=0;$i--)
{
	$startTime = mktime(0, 0, 0, date('m'), date('d')-$i, date('Y'));
	$endTime = mktime(23, 59, 59, date('m'), date('d')-$i, date('Y'));
	
	$days.="'".date("d/m",$startTime)."'";
	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE `update`=0 AND `time`>=$startTime AND `time`<=$endTime") or die(mysql_error());
	$data = $answer->fetch();
	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE `update`=1 AND `time`>=$startTime AND `time`<=$endTime") or die(mysql_error());
	$data2 = $answer->fetch();
	
	$values.=$data['nb'].",";
	
	$valuesUpdates.=$data2['nb'].",";
	
	if($i>0)
		$days.=',';
}

$js.="chart1 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartdownloads',
    defaultSeriesType: 'line'
 },
 title: {
    text: 'Number of downloads during last days'
 },
 tooltip: {
        formatter: function() {
            return '<b>'+this.y+' '+this.series.name+'</b> on '+ this.x;
        }
    },
 xAxis: {
    categories: [";

$js.=$days;

$js.="]
 },
 yAxis: {
    title: {
       text: 'Downloads'
    }
 },
 series: [{
    name: 'Downloads',
    data: [";
    

$js.=$values;


$js.="]
 },{
    name: 'Updates',
    data: [";
    

$js.=$valuesUpdates;


$js.="]
}]
});";

?>

<script type="text/javascript" src="/js/highcharts.js"></script>
<script>
<?php echo $js; ?>
</script>

<?php
include("../configuration/footer.php");
?>
