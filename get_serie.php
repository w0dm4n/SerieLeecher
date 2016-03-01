<?php
	function get_serie_name($line)
	{
		$pos = 0;
		$new = NULL;
		while ($line[$pos] != NULL && $line[$pos] != '>')
			$pos++;
		$pos++;
		while ($line[$pos] != NULL && $line[$pos] != '<')
		{
			$new = ''.$new.''.$line[$pos].'';
			$pos++;
		}
		return ($new);
	}

	function get_serie_summary($data, $position)
	{
		$new = NULL;
		while ($data[$position] != NULL && $data[$position] != '<')
		{
			$new = ''.$new.''.$data[$position].'';
			$position++;
		}
		$new = trim($new, ' \t\r\n');
		return ($new);
	}

	function get_serie_img($line)
	{
		$pos = 0;
		$new = NULL;
		while ($line[$pos] != NULL && $line[$pos] != '"')
			$pos++;
		$pos++;
		while ($line[$pos] != NULL && $line[$pos] != '"')
		{
			$new = ''.$new.''.$line[$pos].'';
			$pos++;
		}
		return ($new);
	}

	function get_serie_format($line)
	{
		$new = NULL;
		$pos = 0;
		if (strpos($line, "og:description"))
		{
			$array = explode("Format : ", $line);
			while ($array[1][$pos] != NULL && $array[1][$pos] != "-" && $array[1][$pos] != "<" && $array[1][$pos] != ">" && $array[1][$pos] != '"')
			{
				$new = ''.$new.''.$array[1][$pos].'';
				$pos++;
			}
			if (!strpos($new, "épisodes") && !strpos($new, "saison") && !strpos($new, "episodes"))
				return ($new);
		}
		else
		{
			$array = explode("Format : ", $line);
			if ($array[1][0] == "<")
			{
				while ($array[1][$pos] != NULL && $array[1][$pos] != '>')
					$pos++;
				$pos++;
				while ($array[1][$pos] != NULL && $array[1][$pos] != '<')
				{
					$new = ''.$new.''.$array[1][$pos].'';
					$pos++;
				}
				return ($new);
			}
			else
			{
				while ($array[1][$pos] != NULL && $array[1][$pos] != "-" && $array[1][$pos] != "<" && $array[1][$pos] != ">" && $array[1][$pos] != '"')
				{
					$new = ''.$new.''.$array[1][$pos].'';
					$pos++;
				}
				if (!strpos($new, "épisodes") && !strpos($new, "saison") && !strpos($new, "episodes"))
					return ($new);
			}
		}
	}

	function get_serie_country($line)
	{
		$new = NULL;
		$pos = 0;
		$array = explode("Pays / Nationalité : </b>", $line);
		if ($array[1][0] == "<")
			return ("Inconnu");
		else
		{
			while ($array[1][$pos] != NULL && $array[1][$pos] != '<')
			{
				$new = ''.$new.''.$array[1][$pos].'';
				$pos++;
			}
			return ($new);
		}
	}

	function get_serie_state($line)
	{
		$array = explode("Statut : </b>", $line);
		$new = NULL;
		$pos = 0;

		while ($array[1][$pos] != NULL && $array[1][$pos] != ' ' && $array[1][$pos] != '<')
		{
			$new = ''.$new.''.$array[1][$pos].'';
			$pos++;
		}
		$new = str_replace("Encours", "En cours", $new); 
		return ($new);
	}

	function get_serie($link)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $link); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$output = utf8_encode(curl_exec($ch)); 
		curl_close($ch);
		$line = NULL;
		$var = 0;
		$index = 0;
		$serie_name = NULL;
		$serie_summary = NULL;
		$serie_img = NULL;
		$serie_format = NULL;
		$serie_country = NULL;
		$serie_state = NULL;
		while ($output[$var] != NULL)
		{
			if (!strcmp($output[$var], "\n"))
			{
				$line = trim($line, ' \t\r\n');
				if (strpos($line, 'itemprop="name">'))
					$serie_name = ''.$serie_name.''.get_serie_name($line).'';
				if (strpos($line, 'itemprop="description">'))
					$serie_summary = ''.$serie_summary.''.get_serie_summary($output, $var).'';
				if (strpos($line, '/images/image-non-disponible.jpg'))
					$serie_img = ''.$serie_img.''.get_serie_img($line).'';
				if (strpos($line, 'Format'))
				{
					if (empty($serie_format))
					{
						$serie_format = ''.$serie_format.''.get_serie_format($line).'';
						if (strpos($serie_format, "mn"))
							$serie_format = str_replace("mn", "minutes", $serie_format);
						else if (strpos($serie_format, "min"))
							$serie_format = str_replace("min", "minutes", $serie_format);
					}
				}
				if (strpos($line, "Pays / Nationalité :"))
				{
					if (empty($serie_country))
						$serie_country = ''.$serie_country.''.get_serie_country($line).'';
				}
				if (strpos($line, "Statut :"))
				{
					if (empty($serie_state))
						$serie_state = ''.$serie_state.''.get_serie_state($line).'';
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
		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_SUMMARY : ';
		echo "\e[0;34m";
		echo $serie_summary;	

		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_IMG : ';
		echo "\e[0;34m";
		echo $serie_img;

		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_FORMAT : ';
		echo "\e[0;34m";
		echo $serie_format;

		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_COUNTRY : ';
		echo "\e[0;34m";
		echo $serie_country;

		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_STATE : ';
		echo "\e[0;34m";
		echo $serie_state;

		echo "\n";
	}
?>
