
<?php
	/*
	// example usage of google_total_results.inc.php
	*/

	define("NL","<BR>");
	//define("NL","\n");
	
	include_once("C:\inetpub\wwwroot\GA\google_total_results.inc.php");

	echo "Search results for: Scott Renton".NL;
	$g = new GoogleTotalResults("Scott Renton");
	$results = $g->getResults();
	if ($results !== false)
		echo "Results: ".number_format($results,0).NL;
	else
		echo "Failed to get Results [".$g->getLastError()."].".NL;
	
	echo "Search results for: \"Les Demoiselles D'Avignon\"".NL;
	$g->setSearchTerm("\"Les Demoiselles D'Avignon\"");
	$results = $g->getResults();
	if ($results !== false)
	{
		echo "Results: ".number_format($results,0).NL;
		//echo 'Yeah';
	}
	else
	{
		echo "Failed to get Results [".$g->getLastError()."].".NL;
	}
?>