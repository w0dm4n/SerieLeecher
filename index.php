<?php
require_once("get_serie.php");
	function handle_line_and_send_link($line)
	{
		$pos = 0;
		$new = NULL;
		while ($line[$pos] != NULL && $line[$pos] != '"')
			$pos++;
		$pos++;
		while ($line[$pos] != NULL && $line[$pos] != '"')
			$pos++;
		$pos++;
		while ($line[$pos] != NULL && $line[$pos] != '"')
			$pos++;
		$pos++;
		while ($line[$pos] != NULL && $line[$pos] != '"')
		{
			$new = ''.$new.''.$line[$pos].'';
			$pos++;
		}
		if (!strpos($line, "ZZ à supprimer") && !strpos($line, "ZZZ A SUPPRIMER") && !strpos($line, "ZZAsupprimer"))
		{
			echo "\e[3;32m";
			echo "\nNEW SERIE FOUND";
			echo "\e[0;37m";
			echo "\n______________\n\n";
			echo 'SERIE PRINCIPAL LINK : ';
			echo "\e[0;34m";
			echo 'http://www.dpstream.net/'.$new.'';
			echo "\n";
			get_serie('http://www.dpstream.net/'.$new.'');
		}
	}

	$url = 'http://www.dpstream.net/liste-series-en-streaming.html';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = utf8_encode(curl_exec($ch)); 
	curl_close($ch);
	$line = NULL;
	$var = 0;
	$index = 0;
	while ($output[$var] != NULL)
	{
		if (!strcmp($output[$var], "\n"))
		{
			$line = trim($line, ' \t\r\n');
			if (strpos($line, 'serie-') && strpos($line, "class") && !strpos($line, "serie-lettre"))
			{
				handle_line_and_send_link($line);
				//break ;
			}
			$index = 0;
			$line = NULL;
		}
		else
		{
			$line = ''.$line.''.$output[$var].'';
			$index++;
		}
		$var++;
	}
?>