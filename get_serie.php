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

	function get_serie_category($line)
	{
		$new = NULL;
		$pos = 0;
		$index = 0;
		$array = explode("Genre :", $line);
		if (empty($array[1]))
			$array = explode("Genres :", $line);
		$array[1] = trim($array[1], ' \t\r\n');
		if ($array[1][0] == "<")
		{
			$array_2 = explode('<span itemprop="genre">', $array[1]);
			foreach ($array_2 as $value)
			{
				$tmp = NULL;
				$pos = 0;
				$value = trim($value, ' \t\r\n');
				if ($value[0] != '<')
				{
					while ($value[$pos] != NULL && $value[$pos] != '<')
					{
						$tmp = ''.$tmp.''.$value[$pos].'';
						$pos++;
					}
					if (!empty($tmp))
					{
						if (!empty($array_2[($index + 1)]))
							$new = ''.$new.''.$tmp.',';
						else
							$new = ''.$new.''.$tmp.'';
					}
				}
				$index++;
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
			return ($new);
		}
	}

	function get_serie_actors($line)
	{
		echo "\e[0;31m";
		$array = explode("Avec ", $line);
		$index = 0;
		$pos = 0;
		$new = NULL;
		if ($array[1][0] == "<")
		{
			$array_2 = explode('<span itemprop="name">', $array[1]);
			foreach ($array_2 as $value)
			{
				$pos = 0;
				$tmp = NULL;
				if ($value[0] != '<')
				{
					while ($value[$pos] != NULL && $value[$pos] != "-" && $value[$pos] != "<" && $value[$pos] != ">" && $value[$pos] != '"')
					{
						$tmp = ''.$tmp.''.$value[$pos].'';
						$pos++;
					}
					$tmp = trim($tmp, ' \t\r\n');
					if (!empty($array_2[($index + 1)]))
							$new = ''.$new.''.$tmp.',';
						else
							$new = ''.$new.''.$tmp.'';
				}
				$index++;
			}
			return ($new);
		}
	}

	function get_serie_by($line)
	{
		$pos = 0;
		$index = 0;
		$new = NULL;
		$tmp = NULL;
		$array = explode("Créé", $line);
		if (strpos($line, 'itemprop="creator"'))
		{
			$array_2 = explode('<span itemprop="name">', $array[1]);
			$new = "Créé par ";
			foreach ($array_2 as $value)
			{
				if ($index)
				{
					while ($value[$pos] != NULL && $value[$pos] != "-" && $value[$pos] != "<" && $value[$pos] != ">" && $value[$pos] != '"')
					{
						$tmp = ''.$tmp.''.$value[$pos].'';
						$pos++;
					}
					$tmp = trim($tmp, ' \t\r\n');
					$new = ''.$new.''.$tmp.'';
					break ;				
				}
				$index++;
			}
			return ($new);
		}
		else
		{
			$new = "Créé";
			while ($array[1][$pos] != NULL && $array[1][$pos] != "-" && $array[1][$pos] != "<" && $array[1][$pos] != ">" && $array[1][$pos] != '"')
			{
				$new = ''.$new.''.$array[1][$pos].'';
				$pos++;
			}
			return ($new);
		}
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
		$serie_category = NULL;
		$serie_actors = NULL;
		$serie_by = NULL;
		$dpstream_link = $link;
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
				if (strpos($line, "Genre") || strpos($line, "Genrees"))
				{
					if (empty($serie_category))
						$serie_category = ''.$serie_category.''.get_serie_category($line).'';
				}
				if (strpos($line, "Avec "))
				{
					if (empty($serie_actors))
						$serie_actors = ''.$serie_actors.''.get_serie_actors($line).'';
				}
				if (strpos($line, "Créé"))
				{
					if (empty($serie_by))
						$serie_by = ''.$serie_by.''.get_serie_by($line).'';
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
		if (empty($serie_category))
			$serie_category = "Inconnu";

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
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_CATEGORY : ';
		echo "\e[0;34m";
		echo $serie_category;

		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_ACTORS : ';
		echo "\e[0;34m";
		echo $serie_actors;

		echo "\n";
		echo "\e[0;35m";
		echo '('.$serie_name.')';
		echo "\e[0;37m";
		echo ' SERIE_MADE_BY: ';
		echo "\e[0;34m";
		echo $serie_by;

		echo "\n";
		$link = mysql_connect("localhost", "root", "123456");
		mysql_select_db("strizzstream");
		mysql_query("SET NAMES UTF8");
		mysql_query('INSERT INTO series(serie_name,serie_summary,serie_img,serie_format,serie_country,serie_state,serie_category,serie_actors,serie_by,dpstream_link) VALUES("'.$serie_name.'", "'.$serie_summary.'", "'.$serie_img.'", "'.$serie_format.'", "'.$serie_country.'", "'.$serie_state.'", "'.$serie_category.'", "'.$serie_actors.'", "'.$serie_by.'", "'.$dpstream_link.'")');
	}
?>
