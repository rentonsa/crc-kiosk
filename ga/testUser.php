<!DOCTYPE html>

<html lang="en">

<head>
    <TITLE>DIU Photography Ordering System</TITLE>
    <link rel="stylesheet" type ="text/css" href="../diustyles.css">
    <meta name="author" content="Library Online Editor">
    <meta name="description" content="Edinburgh University Library Online: Book purchase request forms for staff: Medicine and Veterinary">
    <meta name="distribution" content="global">
    <meta name="resource-type" content="document">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</HEAD>

<body>
<div class = "central">
<div class = "heading">
    <a href="index.html" title="Link to The DIU Web Area">
        <img src="../images/header4.jpg" alt="The University of Edinburgh Image Collections" width="754" height="65" border="0" />
    </a>
    <h2>TOP IMAGES FROM GOOGLE ANALYTICS</h2>
    <hr/>
</div>
<?php

function safe_array_merge ($rounddata, $data)
{
    $args = func_get_args();
    $result=array();
    foreach($args as &$array)
    {
        foreach($array as $key=>&$value)
        {
           echo 'KEYEKEYEHE'.$key;
            echo 'KEY'.$result[$key];
            if(isset($result[$key]))
            {
                $continue=TRUE;
                $fake_key=0;
                while($continue==TRUE)
                {
                    if(!isset($result[$key.'_'.$fake_key]))
                    {
                        $result[$key.'_'.$fake_key]=$value;
                        $continue=FALSE;
                    }
                    $fake_key++;
                }
            }
            else
            {
                $result[$key]=$value;
            }
        }
    }
    return $result;
}

		session_start();
		require_once('GoogleClientApi/src/Google_Client.php');

		require_once('GoogleClientApi/src/contrib/Google_AnalyticsService.php');
		$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];

		$client = new Google_Client();


		$client->setAccessType('online'); // default: offline

		$client->setApplicationName('edinburgh-luna');

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

			$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

			header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));

		}

		if (isset($_SESSION['token'])) { // extract token from session and configure client

			$token = $_SESSION['token'];
			//changed set to get here
			$client->setAccessToken($token);
		}

		if (!$client->getAccessToken()) { // auth call to google

			$authUrl = $client->createAuthUrl();
			header("Location: ".$authUrl);
			die;
		}

if($client->isAccessTokenExpired()) {
    // Don't think this is required for Analytics API V3
    //$_googleClient->refreshToken($_analytics->dbRefreshToken($_agencyId));
    echo 'Access Token Expired'; // Debug

    $client->authenticate();
    $NewAccessToken = json_decode($client->getAccessToken());
    $client->refreshToken($NewAccessToken->refresh_token);
}


		try {
			//echo 'here';
			//$props = $service->management_webproperties->listManagementWebproperties("~all");
		   // echo '<h1>Available Google Analytics projects</h1><ul>'."\n";
		   // foreach($props['items'] as $item) printf('<li>%1$s</li>', $item['name']);
		   // echo '</ul>';
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

        if (isset ($_REQUEST['from']))
        {

            $from = $_REQUEST['from'];
        }

        if (isset ($_REQUEST['to']))
        {
            $to = $_REQUEST['to'];
        }

		if (!isset($_REQUEST['days']))
		{

			$days =2;
		}
		else
		{
			$days = $_REQUEST['days'];
		}
		if (!isset($_REQUEST['items']))
		{
			$items =10;
		}
		else
		{
			$items = $_REQUEST['items'];
		}
		echo '
		<div class = "ga-box">
		<!--<p>Please ensure that you are logged out of Google before running this application. When prompted to sign in, enter username: edinburgh.lunaimaging and password: gab0r0ne.</p>-->
		<table ><form action = "test.php" method = "POST"><tr><td>Enter number of days back to see:
		<input type = "text" name = "days" value = "'.$days.'"></td></tr>
		<tr><td>OR enter date from (format YYYY-MM-DD): <input type = "text" name = "from" value = "'.$from.'"> and date to: <input type = "text" name = "to" value = "'.$to.'"></td></tr>
		<tr><td>Enter number of items to see:
		<input type = "text" name = "items" value = "'.$items.'"></td></tr>
		<tr><td><input type = "submit" name = "submit"></td>
		</tr>
		</form>
		</table>';

		if (isset($_REQUEST['submit']))
		{
            if ($from == null and $to == null)
            {
                $from = date('Y-m-d', time()-$days*24*60*60); // 2 days
                $to = date('Y-m-d'); // today
                echo'Showing data from '.$from.' to '.$to. '<br>';
            }
            else if ($from == null or $to == null)
            {
                    echo 'Please provide a from date and a to date';
            }
            else
            {
                echo'Showing data from '.$from.' to '.$to. '<br>';
            }


		/*
		$metrics = 'ga:visits,ga:pageviews,ga:bounces,ga:entranceBounceRate,ga:visitBounceRate,ga:avgTimeOnSite';
		$dimensions = 'ga:date,ga:year,ga:month,ga:day,ga:pagePath';

		*/
		$dimensions='ga:pagePath';
        $maxresults= 10000;
        $startindex = 1;
		$metrics='ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits';
		//sort=-ga:pageviews


        //SR 6/12/13 Try to find more than 10000
        $datafeeler = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:pageviews',  'start-index' => $startindex, 'max-results'=> $maxresults));

        $totalresults = $datafeeler['totalResults'];

        $rounds = $totalresults/$maxresults;
        $point = strpos($rounds,".");
        $number = substr($rounds,0, $point);
        $number++;

        $outfile = "ga.log";
        $file_handle_out = fopen($outfile, "w")or die("can't open this outfile");
        $rounddata = array();
        $data = array();
        $i = 0;
        //get total results, then divide by max-results and round up.
        $rounddata = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:pageviews',  'start-index' => $startindex, 'max-results'=> $maxresults));
        $data = array_merge($rounddata['rows'], $data);
        $i++;
        $startindex = $startindex + $maxresults;
           // print "<h1>Data</h1><pre>" . print_r($data, true) . "</pre>";
        while($i < $number)
        {
           //echo 'in here'.$startindex;
           $rounddata = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:pageviews',  'start-index' => $startindex, 'max-results'=> $maxresults));
           //print_r($rounddata);
           // //print_r($rounddata['rows'][0]);

            //echo 'TOPROW'.$toprow;
            //print "<h1>RoundData Before Re-INDex ".$i."</h1><pre>" . print_r($rounddata, true) . "</pre>";
            //while ($j < $maxresults)
            foreach($rounddata['rows'] as $key=>$value)
            {

                $newkey = $key + $startindex;
                $newkey--;
                $rounddata['rows'][$newkey] = $rounddata['rows'][$key];
                unset($rounddata['rows'][$key]);
                //$data = array_merge($rounddata, $data);
                //print "<h1>RoundData Rows ".$i."</h1><pre>" . print_r($rounddata['rows'], true) . "</pre>";
            }

            //$rounddata
           //fwrite($file_handle_out,$res);
           //print "<h1>RoundData ".$i."</h1><pre>" . print_r($rounddata, true) . "</pre>";
           $data = array_merge($rounddata['rows'], $data);
           //print "<h1>DataArray ".$i."</h1><pre>" . print_r($data, true) . "</pre>";
           //$data = $data + $rounddata;
           $startindex = $startindex + $maxresults;
           $i++;
        }



        //$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:pageviews',  'start-index' => $startindex, 'max-results'=> $maxresults));
		//print "<h1>Data</h1><pre>" . print_r($data, true) . "</pre>";
		$i = 0;
		$pageTotal = count($data);

        //echo '<h1>TOTAL PAGES VIEWED IN THIS TIME PERIOD: '.$pageTotal.'</h1>';

        $overallcount = 0;
        $overallurls = 0;

		$n = 0;
        $page = 0;
        $count = 0;
        $ecpCount = 0;
        $eclCount = 0;
            $chaCount = 0;
            $galCount = 0;
            $uoeCount = 0;
            $incCount = 0;
            $laiCount = 0;
            $wmmCount = 0;
            $hilCount = 0;
            $scoCount = 0;
            $salCount = 0;
            $shaCount = 0;
            $arcCount = 0;
            $ardCount = 0;
            $thoCount = 0;
            $oriCount = 0;
            $objCount = 0;
            $halCount = 0;
            $walCount = 0;
            $newCount = 0;
            $rosCount = 0;
            $arsCount = 0;
         $ecpPages = 0;
            $eclPages = 0;
            $chaPages = 0;
            $galPages = 0;
            $uoePages = 0;
            $incPages = 0;
            $laiPages = 0;
            $wmmPages = 0;
            $hilPages = 0;
            $scoPages = 0;
            $salPages = 0;
            $shaPages = 0;
            $arcPages = 0;
            $ardPages = 0;
            $thoPages = 0;
            $oriPages = 0;
            $objPages = 0;
            $halPages = 0;
            $walPages = 0;
            $newPages = 0;
            $rosPages = 0;
            $arsPages = 0;

		 while($i < $pageTotal)
		{
			 $page = $data[$i][0];
			 $count = $data[$i][1];

			if (strpos($page,'detail')> 0)
			 {
				if (!(strpos($page,'widget')> 0))
				{
                    if (!(strpos($page,'translate_c')> 0))
                    {
                        if (!(strpos($page,'QuickSearchA')> 0))
                        {
					//if ((!(strpos($page, 'UoEecp')) && (!(strpos($page, 'UoEecl') > 0)) && (!(strpos($page, 'UoEhal') > 0))&& (!(strpos($page, 'UoEcha') > 0))&& (!(strpos($page, 'UoEarc') > 0))&& (!(strpos($page, 'AMICO') > 0))&& (!(strpos($page, ':Leaf') > 0))))
					//{
						    $dataArray [$n][0]= $page;
						    $dataArray [$n][1]= $count;
                            $overallcount = $overallcount + $count;
                            $overallurls = $overallurls + $page;

                            if (strpos($page, 'detail/UoEecp')> 0)
                            {
                                $ecpCount = $ecpCount + $count;
                                $ecpPages++;
                            }
                            if (strpos($page, 'detail/UoEecl')> 0)
                            {
                                $eclCount = $eclCount + $count;
                                $eclPages++;
                            }
                            if (strpos($page, 'detail/UoEcha~1')> 0)
                            {
                                $chaCount = $chaCount + $count;
                                $chaPages++;
                            }
                            if (strpos($page, 'detail/UoEcha~2')> 0)
                            {
                                $arsCount = $arsCount + $count;
                                $arsPages++;
                            }
                            if (strpos($page, 'detail/UoEhal~1')> 0)
                            {
                                $halCount = $halCount + $count;
                                $halPages++;
                            }
                            if (strpos($page, 'detail/UoEhal~2')> 0)
                            {
                                $objCount = $objCount + $count;
                                $objPages++;
                            }
                            if (strpos($page, 'detail/UoEarc')> 0)
                            {
                                $arcCount = $arcCount + $count;
                                $arcPages++;
                            }
                            if (strpos($page, 'detail/UoEgal~2')> 0)
                            {

                                $incCount = $incCount + $count;
                                $incPages++;
                            }
                            if (strpos($page, 'detail/UoEgal~3')> 0)
                            {
                                $scoCount = $scoCount + $count;
                                $scoPages++;
                            }
                            if (strpos($page, 'detail/UoEgal~4')> 0)
                            {
                                $uoeCount = $uoeCount + $count;
                                $uoePages++;
                            }
                            if (strpos($page, 'detail/UoEgal~5')> 0)
                            {
                                $galCount = $galCount + $count;
                                $galPages++;
                            }
                            if (strpos($page, 'detail/UoEgal~6')> 0)
                            {
                                $rosCount = $rosCount + $count;
                                $rosPages++;
                            }
                            if (strpos($page, 'detail/UoEsha~1')> 0)
                            {
                                $shaCount = $shaCount + $count;
                                $shaPages++;
                            }
                            if (strpos($page, 'detail/UoEsha~2')> 0)
                            {
                                $thoCount = $thoCount + $count;
                                $thoPages++;
                            }
                            if (strpos($page, 'detail/UoEsha~3')> 0)
                            {
                                $newCount = $newCount + $count;
                                $newPages++;
                            }
                            if (strpos($page, 'detail/UoEsha~4')> 0)
                            {
                                $oriCount = $oriCount + $count;
                                $oriPages++;
                            }
                            if (strpos($page, 'detail/UoEwal~1')> 0)
                            {
                                $walCount = $walCount + $count;
                                $walPages++;
                            }
                            if (strpos($page, 'detail/UoEcar~2')> 0)
                            {
                                $salCount = $salCount + $count;
                                $salPages++;
                            }
                            if (strpos($page, 'detail/UoEcar~3')> 0)
                            {
                                $ardCount = $ardCount + $count;
                                $ardPages++;
                            }
                            if (strpos($page, 'detail/UoEcar~4')> 0)
                            {
                                $hilCount = $hilCount + $count;
                                $hilPages++;
                            }
                            if (strpos($page, 'detail/UoEwmm~1')> 0)
                            {
                                $wmmCount = $wmmCount + $count;
                                $wmmPages++;
                            }
                            if (strpos($page, 'detail/UoEwmm~2')> 0)
                            {
                                $laiCount = $laiCount + $count;
                                $laiPages++;
                            }



						$n++;
					//}
                        }
                    }
				}
				
			 }
			 $i++;
			 
		}

		  $sort = array();
		  foreach ($dataArray as $k => $v)
		  {

			$sort[1][$k]=$v[1];
			$sort[0][$k]=$v[0];
		  }
		array_multisort($sort[1], SORT_NUMERIC, SORT_DESC, $sort[0], SORT_ASC, $dataArray);
		$j = 0;
		$arrayTotal = count($dataArray);
		if ($items > $arrayTotal)
		{
			$viewTotal = $arrayTotal ;
		}
		else
		{
			$viewTotal = $items;
		}

        echo '<h1>TOTAL PAGEVIEWS IN THIS TIME PERIOD: '.$overallcount.'</h1>';

            echo '<h1>TOTAL PAGES VISITED IN THIS TIME PERIOD: '.$n.'</h1>';

        echo '<table>
                <tr>
                    <td>
                        <b>Collection</b>
                    </td>
                     <td>
                        <b>Distinct Pages</b>
                    </td>
                    <td>
                        <b>Total Views</b>
                    </td>
                    <td>
                        <b>Collection</b>
                    </td>
                    <td>
                        <b>Distinct Pages</b>
                    </td>
                    <td>
                        <b>Total Views</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        Archivision
                    </td>
                     <td>
                       '.$arcPages.'
                    </td>
                    <td>
                        '.$arcCount.'
                    </td>
                    <td>
                        Ars Anatomica
                    </td>
                    <td>
                        '.$arsPages.'
                    </td>
                    <td>
                        '.$arsCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        Charting The Nation
                    </td>
                     <td>
                       '.$chaPages.'
                    </td>
                    <td>
                        '.$chaCount.'
                    </td>
                    <td>
                        Edinburgh College of Art Image Library
                    </td>
                    <td>
                        '.$eclPages.'
                    </td>
                    <td>
                        '.$eclCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        Architectural Drawings
                    </td>
                     <td>
                       '.$ardPages.'
                    </td>
                    <td>
                        '.$ardCount.'
                    </td>
                    <td>
                        Edinburgh College of Art Photography Collection
                    </td>
                    <td>
                        '.$ecpPages.'
                    </td>
                    <td>
                        '.$ecpCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        CRC Gallimaufry
                    </td>
                     <td>
                       '.$galPages.'
                    </td>
                    <td>
                        '.$galCount.'
                    </td>
                    <td>
                        History of Art Image Library
                    </td>
                    <td>
                        '.$halPages.'
                    </td>
                    <td>
                        '.$halCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        Laing
                    </td>
                     <td>
                       '.$laiPages.'
                    </td>
                    <td>
                        '.$laiCount.'
                    </td>
                    <td>
                        Hill & Adamson
                    </td>
                    <td>
                        '.$hilPages.'
                    </td>
                    <td>
                        '.$hilCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        Incunabula
                    </td>
                     <td>
                       '.$incPages.'
                    </td>
                    <td>
                        '.$incCount.'
                    </td>
                    <td>
                        New College
                    </td>
                    <td>
                        '.$newPages.'
                    </td>
                    <td>
                        '.$newCount.'
                    </td>
                </tr>
                    <tr>
                    <td>
                        Object Lessons
                    </td>
                     <td>
                       '.$objPages.'
                    </td>
                    <td>
                        '.$objCount.'
                    </td>
                    <td>
                        Oriental Manuscripts
                    </td>
                    <td>
                        '.$oriPages.'
                    </td>
                    <td>
                        '.$oriCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        Roslin Institute
                    </td>
                     <td>
                       '.$rosPages.'
                    </td>
                    <td>
                        '.$rosCount.'
                    </td>
                    <td>
                        Salvesen
                    </td>
                    <td>
                        '.$salPages.'
                    </td>
                    <td>
                        '.$salCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        School of Scottish Studies
                    </td>
                     <td>
                       '.$scoPages.'
                    </td>
                    <td>
                        '.$scoCount.'
                    </td>
                    <td>
                        Shakespeare
                    </td>
                    <td>
                        '.$shaPages.'
                    </td>
                    <td>
                        '.$shaCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                        Thomson Walker
                    </td>
                     <td>
                       '.$thoPages.'
                    </td>
                    <td>
                        '.$thoCount.'
                    </td>
                    <td>
                        Western Medieval Manuscripts
                    </td>
                    <td>
                        '.$wmmPages.'
                    </td>
                    <td>
                        '.$wmmCount.'
                    </td>
                </tr>
                <tr>
                    <td>
                         University of Edinburgh (People & Places)
                    </td>
                    <td>
                        '.$uoePages.'
                    </td>
                    <td>
                        '.$uoeCount.'
                    </td>
                </tr>
               </table>
            <table >
		<tr><td><b>Pos</b></td><td><b>Views</b></td><td><b>LUNA Link</b></td><td><b>Title</b></td><td><b>Author</b></td><td><b>Page No</b></td><td><b>Shelfmark</b></td><td><b>Thumbnail</b></td><tr>';
		//print "<h1>Data</h1><pre>" . print_r($dataArray, true) . "</pre>";
		while ($j <  $viewTotal)
		{
				$dbserver = 'localhost';
				$username = 'images';
				$password = 'lepwom8';
				$database = 'orders';

				$link = mysql_connect($dbserver, $username, $password);
				@mysql_select_db($database, $link) or die ( "Unable to select database");
				$pageNow = $dataArray[$j][0];
				$countNow = $dataArray[$j][1];
				$inst = substr($pageNow, 21, 6);
				$coll = substr($pageNow, 28,1);
               // echo 'PAGE'.$pageNow;
				$objectid = substr(($pageNow),32,10);
				$tildapos = strpos($objectid,'~');
				$objectid =substr($objectid,0, $tildapos);
				$check_sql = "SELECT imageid from orders.REF where objectid = $objectid;";	 
				
				$check_result = mysql_query($check_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $check_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
				$check_rows = mysql_numrows($check_result);
				if ($check_rows > 0)
				{
					$imageid = mysql_result($check_result, 0, "imageid");
				
					$pos = $j + 1;
					//if (($imageid == null) or ($imageid > '0029999'))
					//{

					//	echo '<tr><td font face = "arial">'.$pos.'</td><td>'.$countNow.'</td><td>LEGACY IMAGE</td></tr>';
						 // echo $pageNow;
						// echo '<h2>OI'.$objectid.'</h2>';
					//}
					// else
					//{
						//echo '<tr><td>'.$pos.'</td><td>'.$countNow.'</td><td>DIU IMAGE</td></tr>';
					
						$get_sql = "SELECT i.image_id, i.title, i.author, i.page_no, i.shelfmark, i.jpeg_path from orders.REF r, orders.IMAGE i where 
						r.objectid = $objectid and r.imageid = i.image_id and collectionid = $coll and institutionid = '$inst';";
						$get_result=mysql_query($get_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $get_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
						//$get_view = mysql_fetch_array ($get_result); 
						
						while ($get_view = mysql_fetch_array ($get_result))
						{
						
							$title = $get_view['title'];
							$imageid = $get_view['image_id'];
							$author = $get_view['author'];
							$pageno = $get_view['page_no'];
							$shelfmark = $get_view['shelfmark'];
							$jpeg_path = $get_view['jpeg_path'];
							$jpeg_path = "../".$jpeg_path;
							//$jpeg_path = str_replace("/", "\\", $jpeg_path);
							$size = getimagesize($jpeg_path);

							$fullwidth = $size[0];
							$fullheight = $size[1];
                            $long_side = 130;
							if ($fullheight > $fullwidth)
							{
                                $aspect = $fullheight / $fullwidth;
                                $short_side = $long_side/$aspect;
								$divstyle = "height: ".$long_side."px; width: ".$short_side." px;";
							}
							else
							{
                                $aspect = $fullwidth / $fullheight;
                                $short_side = $long_side/$aspect;
								$divstyle = "height: ".$short_side." px; width: ".$long_side."px;";
							}
						}

                        if ($title == '')
                        {
                            $title = 'BOOK READER/CORSON IMAGE';
                            $author = '';
                            $pageno = '';
                            $shelfmark = '';
                            $jpeg_path = '';
                        }

                        echo '<tr><td >'.$pos.'</td><td>'.$countNow.'</td><td><a href="http://images.is.ed.ac.uk/'.$pageNow.'">'.$imageid.'</a></td><td>'.$title.'</td><td>'.$author.'</td><td>'.$pageno.'</td><td>'.$shelfmark.'</td><td align = "center"><a href= "'.$jpeg_path.'"><img src= "'.$jpeg_path.'" style = "'.$divstyle.'" ></a></td></tr>';
                        $title = '';
                    }
                    else
                    {
                        $pos = $j + 1;
                        $title = 'NON-DIU IMAGE';
                        $author = '';
                        $pageno = '';
                        $shelfmark = '';
                        $jpeg_path = '';
                        echo '<tr><td >'.$pos.'</td><td>'.$countNow.'</td><td><a href="http://images.is.ed.ac.uk/'.$pageNow.'">LUNA LINK</a></td><td>'.$title.'</td><td>'.$author.'</td><td>'.$pageno.'</td><td>'.$shelfmark.'</td><td align = "center"><a href= "'.$jpeg_path.'"><img src= "'.$jpeg_path.'" style = "'.$divstyle.'" ></a></td></tr>';

                    }

				//}
				$j++;		
			}
		}


		?>
                </table>
            </div>
            <div class = "footer">
                <hr/>
                <p>
                    <a href="../index.html">Home</a>
                <p>
            </div>
        </div>
    </body>

</html>
