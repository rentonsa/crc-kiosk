<HTML>
	<HEAD>
		<TITLE>DIU Photography Ordering System</TITLE>
		<link rel="stylesheet" type ="text/css" href="diustyles.css">
		<meta name="author" content="Library Online Editor">
		<meta name="description" content="Edinburgh University Library Online: Book purchase request forms for staff: Medicine and Veterinary">
		<meta name="distribution" content="global">
		<meta name="resource-type" content="document">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</HEAD>
	<BODY BGCOLOR="#FFFFFF">
		<div align="center">
			<table border="0" cellpadding="0" cellspacing="0" width="680" summary="">
				<tr>
					<td bgcolor="#025193" align="left">
						<a href="index.html" title="Link to The DIU Web Area">
							<img src="images/header4.jpg" alt="The University of Edinburgh Image Collections" width="754" height="65" border="0">
						</a>
					</td>
				</tr>
				</table>
				<table cellpadding="5" cellspacing="0" border="0" width="754"  bgcolor="#f0f0f0">
				<tr>
<?php
// function get_and_fill($dbname)
// {
	// $luna_db_server = 'lac-live1.is.ed.ac.uk';
	// $luna_username = 'insight';
	// $luna_password = 'lepwom8';
	// $luna_database = $dbname;
	// $luna_link = mysql_connect($luna_db_server, $luna_username, $luna_password);
	// @mysql_select_db($luna_database) or die( "Unable to select database");	
	// $id_sql = "select  
		// m.imageid as id_imageid,
		// i.institutionid as id_institutionid ,
		// c.uniquecollectionid as id_collectionid ,
		// o.objectid as id_objectid, 
		// v.valuetext as id_valuetext
				// from IROBJECTIMAGEMAP m, 
					// IRCOLLECTIONMEDIAMAP c, 
					// IRCOLLECTIONCONFIGURATIONINFO i,
					// DTVALUES v, 
					// DTVALUETOOBJECT o, 
					// IRFIELDS f
				// where m.imageid = c.mediaid 
				// and c.uniquecollectionid = i.uniquecollectionid
				// and f.fieldid = v.fieldid
				// and o.valueid = v.valueid
				// and o.objectid = m.objectid
				// and f.displayname = 'Work Record ID';";
				
				// $id_result=mysql_query($id_sql,$luna_link) or die( "A MySQL error has occurred.<br />Your Query: " . $id_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
				// $id_numrows = mysql_num_rows($id_result);
				// $id_row=mysql_fetch_array($id_result);
				
				// $dbserver = 'lac-php-test1.is.ed.ac.uk';
				// $username = 'images';
				// $password = 'lepwom8';
				// $database = 'orders';

				// $link = mysql_connect($dbserver, $username, $password);
				// @mysql_select_db($database, $link) or die ( "Unable to select database");
				// $j = 0;				
				// while ($j < $id_numrows)
				// { 
					// $id_in_sql = "insert into orders.OBJECTIMAGE VALUES (".$id_objectid[$j].", '".$id_valuetext[$j]."','".$databaselive."','".$id_institutionid[$j]."',".$id_collectionid[$j].",".$id_imageid[$j].");";
					// $id_result=mysql_query($id_in_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $id_in_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					// $j++;
				// }	
// }
session_start();
require_once('GoogleClientApi/src/Google_Client.php');
require_once('GoogleClientApi/src/contrib/Google_AnalyticsService.php');

$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];

$client = new Google_Client();

$client->setAccessType('online'); // default: offline

$client->setApplicationName('Test App');

$client->setClientId('181724828737.apps.googleusercontent.com');

$client->setClientSecret('V87IYA4TthcyeDrxzN805Amg');

$client->setRedirectUri($scriptUri);

$client->setDeveloperKey('AIzaSyCqPPvpjGL59-Wb5BdHg54I5MCZBZ_XGxk'); // API key

// $service implements the client interface, has to be set before auth call
$service = new Google_AnalyticsService($client);

if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
	die('Logged out.');
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
	//changed set to get here
    $client->getAccessToken($token);
}

if (!$client->getAccessToken()) { // auth call to google
    $authUrl = $client->createAuthUrl();
    header("Location: ".$authUrl);
    die;
}


try {
    $props = $service->management_webproperties->listManagementWebproperties("~all");
    echo '<h1>Available Google Analytics projects</h1><ul>'."\n";
    foreach($props['items'] as $item) printf('<li>%1$s</li>', $item['name']);
    echo '</ul>';
	// $accounts = $service->management_accounts->listManagementAccounts();
  // print "<h1>Accounts</h1><pre>" . print_r($accounts, true) . "</pre>";

  // $segments = $service->management_segments->listManagementSegments();
  // print "<h1>Segments</h1><pre>" . print_r($segments, true) . "</pre>";

  // $goals = $service->management_goals->listManagementGoals("~all", "~all", "~all");
  // print "<h1>Segments</h1><pre>" . print_r($goals, true) . "</pre>";


} catch (Exception $e) {
    die('An error occured: ' . $e->getMessage()."\n");
}
$projectId = '64041348';

// metrics
$_params[] = 'date';
$_params[] = 'date_year';
$_params[] = 'date_month';
$_params[] = 'date_day';
// dimensions
$_params[] = 'visits';
$_params[] = 'pageviews';
$_params[] = 'bounces';
$_params[] = 'entrance_bounce_rate';
$_params[] = 'visit_bounce_rate';
$_params[] = 'avg_time_on_site';
$_params[] = 'page_path';

$from = date('Y-m-d', time()-2*24*60*60); // 2 days
$to = date('Y-m-d'); // today
/*
$metrics = 'ga:visits,ga:pageviews,ga:bounces,ga:entranceBounceRate,ga:visitBounceRate,ga:avgTimeOnSite';
$dimensions = 'ga:date,ga:year,ga:month,ga:day,ga:pagePath';

*/
$dimensions='ga:pagePath';
$metrics='ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits';
//sort=-ga:pageviews
$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions));
//print "<h1>Data</h1><pre>" . print_r($data, true) . "</pre>";
$i = 0;
$pageTotal = count($data['rows']);


 while($i < $pageTotal)
{
	 $page = $data['rows'][$i][0];
	 $count = $data['rows'][$i][1];

	if (strpos($page,'detail')> 0)
	 {
		$dataArray [$i][0]= $page;
		$dataArray [$i][1]= $count;
		//echo '<tr><td>Page <a href="images.is.ed.ac.uk\''.$page.'">'.$page.'</a>  got '. $count.' hits this week.</tr></td>';

		 
	  }
	 $i++;
  }
  
  $sort = array();
  foreach ($dataArray as $k => $v)
  {
	$sort[1][$k]=$v[1];
	$sort[0][$k]=$v[0];
}
array_multisort($sort[1], SORT_DESC, $sort[0], SORT_ASC, $dataArray);
$j = 0;
			//get_and_fill('lac_galli');
			//	get_and_fill('lac_wmman');
			//	get_and_fill('lac_carwatson');
			//	get_and_fill('lac_shake');
			//	get_and_fill('lac_walter');
$arrayTotal = count($dataArray[0]);
echo $arrayTotal;
while ($j <  $arrayTotal)
{
		$dbserver = 'lac-php-test1.is.ed.ac.uk';
		$username = 'images';
		$password = 'lepwom8';
		$database = 'orders';

		//$link = mysql_connect($dbserver, $username, $password);
		//@mysql_select_db($database, $link) or die ( "Unable to select database");
		$pageNow = $dataArray[$j][0];
		echo '<tr>'.$pageNow.'</td>';
		$objectid = substr(($pageNow),32,10);
		echo '<td>'.$objectid.'</td>';
		$tildapos = strpos($objectid,'~');
		echo '<td>'.$tildapos.'</td>';
		$objectid =substr($objectid,0, $tildapos);
		echo '<td>'.$objectid.'</td></tr>';
		$j++;
		
}



  
	//$i= 0;
//while ($i < 10)
//{
//echo $data[$i][$i];
//$i++;
//}
// foreach($data['rows'] as $row) {
   // $dataRow = array();
   // echo $dataRow['rows'];
  //foreach($_params as $colNr => $column) echo $column . ': '.$row[$colNr].', ';
// }

?>
</table>
</body>
</html>