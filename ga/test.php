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

        echo '<h1>TOTAL PAGES VIEWED IN THIS TIME PERIOD: '.$pageTotal.'</h1>';

        $overallcount = 0;
        $overallurls = 0;

		$n = 0;
        $page = 0;
        $count = 0;

        echo '<table>';
		while($i < $pageTotal and $n < $items)
		{

			 $page = substr($data[$i][0],0,44);
			 $count = $data[$i][1];

			if ((strpos($page,'detail/u')> 0) or (strpos($page,'detail/U')> 0))
			 {
				if (!(strpos($page,'widget')> 0))
				{
                    if (!(strpos($page,'translate_c')> 0))
                    {
                        if (!(strpos($page,'QuickSearchA')> 0)) {

                            $jsonfound = true;
                            $page = "http://images.is.ed.ac.uk" . $page;
                            $iiifpage = str_replace('detail', 'iiif', $page);
                            $info = $iiifpage . '/info.json';

                            $json = true;

                            $json = file_get_contents($info);
                            $jobj = json_decode($json, true);
                            $error = json_last_error();

                            if (isset($jobj["@context"]))
                            {
                                $n++;
                                $reprotitle = '';

                                $j = 0;

                                $metadata = $jobj["metadata"];
                                $mdcount = count($metadata);
                                while ($j < $mdcount)
                                {
                                    if ($metadata[$j]["label"] == "Repro Title") {
                                        $reprotitle = $metadata[$j]["value"];
                                    }
                                    $j++;
                                }
                                if ($reprotitle == '')
                                {
                                    $j = 0;
                                    while ($j < $mdcount) {
                                        if ($metadata[$j]["label"] == "Title") {
                                            $reprotitle = $metadata[$j]["value"];
                                        }
                                        $j++;
                                    }
                                }

                                echo '<tr><td>' . $n . '</td><td><a href = "' . $page . '" target = "_blank"><img src = "' . $iiifpage . '/full/!150,150/0/default.jpg"/></a></td><td>' . $count . ' hits</td><td>' . $reprotitle . '</td></tr>';
                            }
                        }
                    }
				}
				
			 }
			 $i++;
			 
		}
        echo '</table>';
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
