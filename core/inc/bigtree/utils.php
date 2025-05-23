<?php
	/*
		Class: BigTree
			A utilities class with many useful functions.

	*/

	class BigTree {

		public static $StateList = ['AL' => "Alabama", 'AK' => "Alaska", 'AZ' => "Arizona", 'AR' => "Arkansas", 'CA' => "California", 'CO' => "Colorado", 'CT' => "Connecticut", 'DE' => "Delaware", 'DC' => "District Of Columbia", 'FL' => "Florida", 'GA' => "Georgia", 'HI' => "Hawaii", 'ID' => "Idaho", 'IL' => "Illinois", 'IN' => "Indiana", 'IA' => "Iowa", 'KS' => "Kansas", 'KY' => "Kentucky", 'LA' => "Louisiana", 'ME' => "Maine", 'MD' => "Maryland", 'MA' => "Massachusetts", 'MI' => "Michigan", 'MN' => "Minnesota", 'MS' => "Mississippi", 'MO' => "Missouri", 'MT' => "Montana", 'NE' => "Nebraska", 'NV' => "Nevada", 'NH' => "New Hampshire", 'NJ' => "New Jersey", 'NM' => "New Mexico", 'NY' => "New York", 'NC' => "North Carolina", 'ND' => "North Dakota", 'OH' => "Ohio", 'OK' => "Oklahoma", 'OR' => "Oregon", 'PA' => "Pennsylvania", 'RI' => "Rhode Island", 'SC' => "South Carolina", 'SD' => "South Dakota", 'TN' => "Tennessee", 'TX' => "Texas", 'UT' => "Utah", 'VT' => "Vermont", 'VA' => "Virginia", 'WA' => "Washington", 'WV' => "West Virginia", 'WI' => "Wisconsin", 'WY' => "Wyoming"];
		public static $CountryList = ["United States", "Afghanistan", "Åland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia, Plurinational State of", "Bonaire, Sint Eustatius and Saba", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, The Democratic Republic of the", "Cook Islands", "Costa Rica", "Côte d'Ivoire", "Croatia", "Cuba", "Curaçao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran, Islamic Republic of", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kosovo", "Kuwait", "Kyrgyzstan", "Lao People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Occupied Palestinian Territory", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Réunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthélemy", "Saint Helena, Ascension and Tristan da Cunha", "Saint Kitts and Nevis", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre and Miquelon", "Saint Vincent and The Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Sint Maarten (Dutch part)", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Timor-Leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela, Bolivarian Republic of", "Viet Nam", "Virgin Islands, British", "Virgin Islands, U.S.", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];
		public static $CountryListWithAbbreviations = ["AF" => "Afghanistan (‫افغانستان‬‎)", "AX" => "Åland Islands (Åland)", "AL" => "Albania (Shqipëri)", "DZ" => "Algeria (‫الجزائر‬‎)", "AS" => "American Samoa", "AD" => "Andorra", "AO" => "Angola", "AI" => "Anguilla", "AQ" => "Antarctica", "AG" => "Antigua and Barbuda", "AR" => "Argentina", "AM" => "Armenia (Հայաստան)", "AW" => "Aruba", "AC" => "Ascension Island", "AU" => "Australia", "AT" => "Austria (Österreich)", "AZ" => "Azerbaijan (Azərbaycan)", "BS" => "Bahamas", "BH" => "Bahrain (‫البحرين‬‎)", "BD" => "Bangladesh (বাংলাদেশ)", "BB" => "Barbados", "BY" => "Belarus (Беларусь)", "BE" => "Belgium (België)", "BZ" => "Belize", "BJ" => "Benin (Bénin)", "BM" => "Bermuda", "BT" => "Bhutan (འབྲུག)", "BO" => "Bolivia", "BA" => "Bosnia and Herzegovina (Босна и Херцеговина)", "BW" => "Botswana", "BV" => "Bouvet Island", "BR" => "Brazil (Brasil)", "IO" => "British Indian Ocean Territory", "VG" => "British Virgin Islands", "BN" => "Brunei", "BG" => "Bulgaria (България)", "BF" => "Burkina Faso", "BI" => "Burundi (Uburundi)", "KH" => "Cambodia (កម្ពុជា)", "CM" => "Cameroon (Cameroun)", "CA" => "Canada", "IC" => "Canary Islands (islas Canarias)", "CV" => "Cape Verde (Kabu Verdi)", "BQ" => "Caribbean Netherlands", "KY" => "Cayman Islands", "CF" => "Central African Republic (République centrafricaine)", "EA" => "Ceuta and Melilla (Ceuta y Melilla)", "TD" => "Chad (Tchad)", "CL" => "Chile", "CN" => "China (中国)", "CX" => "Christmas Island", "CP" => "Clipperton Island", "CC" => "Cocos (Keeling) Islands (Kepulauan Cocos (Keeling))", "CO" => "Colombia", "KM" => "Comoros (‫جزر القمر‬‎)", "CD" => "Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)", "CG" => "Congo (Republic) (Congo-Brazzaville)", "CK" => "Cook Islands", "CR" => "Costa Rica", "CI" => "Côte d’Ivoire", "HR" => "Croatia (Hrvatska)", "CU" => "Cuba", "CW" => "Curaçao", "CY" => "Cyprus (Κύπρος)", "CZ" => "Czech Republic (Česká republika)", "DK" => "Denmark (Danmark)", "DG" => "Diego Garcia", "DJ" => "Djibouti", "DM" => "Dominica", "DO" => "Dominican Republic (República Dominicana)", "EC" => "Ecuador", "EG" => "Egypt (‫مصر‬‎)", "SV" => "El Salvador", "GQ" => "Equatorial Guinea (Guinea Ecuatorial)", "ER" => "Eritrea", "EE" => "Estonia (Eesti)", "ET" => "Ethiopia", "FK" => "Falkland Islands (Islas Malvinas)", "FO" => "Faroe Islands (Føroyar)", "FJ" => "Fiji", "FI" => "Finland (Suomi)", "FR" => "France", "GF" => "French Guiana (Guyane française)", "PF" => "French Polynesia (Polynésie française)", "TF" => "French Southern Territories (Terres australes françaises)", "GA" => "Gabon", "GM" => "Gambia", "GE" => "Georgia (საქართველო)", "DE" => "Germany (Deutschland)", "GH" => "Ghana (Gaana)", "GI" => "Gibraltar", "GR" => "Greece (Ελλάδα)", "GL" => "Greenland (Kalaallit Nunaat)", "GD" => "Grenada", "GP" => "Guadeloupe", "GU" => "Guam", "GT" => "Guatemala", "GG" => "Guernsey", "GN" => "Guinea (Guinée)", "GW" => "Guinea-Bissau (Guiné Bissau)", "GY" => "Guyana", "HT" => "Haiti", "HM" => "Heard & McDonald Islands", "HN" => "Honduras", "HK" => "Hong Kong (香港)", "HU" => "Hungary (Magyarország)", "IS" => "Iceland (Ísland)", "IN" => "India (भारत)", "ID" => "Indonesia", "IR" => "Iran (‫ایران‬‎)", "IQ" => "Iraq (‫العراق‬‎)", "IE" => "Ireland", "IM" => "Isle of Man", "IL" => "Israel (‫ישראל‬‎)", "IT" => "Italy (Italia)", "JM" => "Jamaica", "JP" => "Japan (日本)", "JE" => "Jersey", "JO" => "Jordan (‫الأردن‬‎)", "KZ" => "Kazakhstan (Казахстан)", "KE" => "Kenya", "KI" => "Kiribati", "XK" => "Kosovo (Kosovë)", "KW" => "Kuwait (‫الكويت‬‎)", "KG" => "Kyrgyzstan (Кыргызстан)", "LA" => "Laos (ລາວ)", "LV" => "Latvia (Latvija)", "LB" => "Lebanon (‫لبنان‬‎)", "LS" => "Lesotho", "LR" => "Liberia", "LY" => "Libya (‫ليبيا‬‎)", "LI" => "Liechtenstein", "LT" => "Lithuania (Lietuva)", "LU" => "Luxembourg", "MO" => "Macau (澳門)", "MK" => "Macedonia (FYROM) (Македонија)", "MG" => "Madagascar (Madagasikara)", "MW" => "Malawi", "MY" => "Malaysia", "MV" => "Maldives", "ML" => "Mali", "MT" => "Malta", "MH" => "Marshall Islands", "MQ" => "Martinique", "MR" => "Mauritania (‫موريتانيا‬‎)", "MU" => "Mauritius (Moris)", "YT" => "Mayotte", "MX" => "Mexico (México)", "FM" => "Micronesia", "MD" => "Moldova (Republica Moldova)", "MC" => "Monaco", "MN" => "Mongolia (Монгол)", "ME" => "Montenegro (Crna Gora)", "MS" => "Montserrat", "MA" => "Morocco (‫المغرب‬‎)", "MZ" => "Mozambique (Moçambique)", "MM" => "Myanmar (Burma) (မြန်မာ)", "NA" => "Namibia (Namibië)", "NR" => "Nauru", "NP" => "Nepal (नेपाल)", "NL" => "Netherlands (Nederland)", "NC" => "New Caledonia (Nouvelle-Calédonie)", "NZ" => "New Zealand", "NI" => "Nicaragua", "NE" => "Niger (Nijar)", "NG" => "Nigeria", "NU" => "Niue", "NF" => "Norfolk Island", "MP" => "Northern Mariana Islands", "KP" => "North Korea (조선 민주주의 인민 공화국)", "NO" => "Norway (Norge)", "OM" => "Oman (‫عُمان‬‎)", "PK" => "Pakistan (‫پاکستان‬‎)", "PW" => "Palau", "PS" => "Palestine (‫فلسطين‬‎)", "PA" => "Panama (Panamá)", "PG" => "Papua New Guinea", "PY" => "Paraguay", "PE" => "Peru (Perú)", "PH" => "Philippines", "PN" => "Pitcairn Islands", "PL" => "Poland (Polska)", "PT" => "Portugal", "PR" => "Puerto Rico", "QA" => "Qatar (‫قطر‬‎)", "RE" => "Réunion (La Réunion)", "RO" => "Romania (România)", "RU" => "Russia (Россия)", "RW" => "Rwanda", "BL" => "Saint Barthélemy (Saint-Barthélemy)", "SH" => "Saint Helena", "KN" => "Saint Kitts and Nevis", "LC" => "Saint Lucia", "MF" => "Saint Martin (Saint-Martin (partie française))", "PM" => "Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)", "WS" => "Samoa", "SM" => "San Marino", "ST" => "São Tomé and Príncipe (São Tomé e Príncipe)", "SA" => "Saudi Arabia (‫المملكة العربية السعودية‬‎)", "SN" => "Senegal (Sénégal)", "RS" => "Serbia (Србија)", "SC" => "Seychelles", "SL" => "Sierra Leone", "SG" => "Singapore", "SX" => "Sint Maarten", "SK" => "Slovakia (Slovensko)", "SI" => "Slovenia (Slovenija)", "SB" => "Solomon Islands", "SO" => "Somalia (Soomaaliya)", "ZA" => "South Africa", "GS" => "South Georgia & South Sandwich Islands", "KR" => "South Korea (대한민국)", "SS" => "South Sudan (‫جنوب السودان‬‎)", "ES" => "Spain (España)", "LK" => "Sri Lanka (ශ්‍රී ලංකාව)", "VC" => "St. Vincent & Grenadines", "SD" => "Sudan (‫السودان‬‎)", "SR" => "Suriname", "SJ" => "Svalbard and Jan Mayen (Svalbard og Jan Mayen)", "SZ" => "Swaziland", "SE" => "Sweden (Sverige)", "CH" => "Switzerland (Schweiz)", "SY" => "Syria (‫سوريا‬‎)", "TW" => "Taiwan (台灣)", "TJ" => "Tajikistan", "TZ" => "Tanzania", "TH" => "Thailand (ไทย)", "TL" => "Timor-Leste", "TG" => "Togo", "TK" => "Tokelau", "TO" => "Tonga", "TT" => "Trinidad and Tobago", "TA" => "Tristan da Cunha", "TN" => "Tunisia (‫تونس‬‎)", "TR" => "Turkey (Türkiye)", "TM" => "Turkmenistan", "TC" => "Turks and Caicos Islands", "TV" => "Tuvalu", "UM" => "U.S. Outlying Islands", "VI" => "U.S. Virgin Islands", "UG" => "Uganda", "UA" => "Ukraine (Україна)", "AE" => "United Arab Emirates (‫الإمارات العربية المتحدة‬‎)", "GB" => "United Kingdom", "US" => "United States", "UY" => "Uruguay", "UZ" => "Uzbekistan (Oʻzbekiston)", "VU" => "Vanuatu", "VA" => "Vatican City (Città del Vaticano)", "VE" => "Venezuela", "VN" => "Vietnam (Việt Nam)", "WF" => "Wallis and Futuna", "EH" => "Western Sahara (‫الصحراء الغربية‬‎)", "YE" => "Yemen (‫اليمن‬‎)", "ZM" => "Zambia", "ZW" => "Zimbabwe"];
		public static $MonthList = ["1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August", "9" => "September", "10" => "October", "11" => "November", "12" => "December"];
		public static $JSONEncoding = false;
		public static $SavedMemoryLimit = null;
		public static $SUTestResult = null;

		/*
			Function: arrayToXML
				Turns a PHP array into an XML string.

			Parameters:
				array - The array to convert.
				tab - Current tab depth (for recursion).

			Returns:
				A string of XML.
		*/

		public static function arrayToXML($array, $tab = "") {
			$xml = "";
			foreach ($array as $key => $val) {
				if (is_array($val)) {
					$xml .= "$tab<$key>\n".static::arrayToXML($val, "$tab\t")."$tab</$key>\n";
				} else {
					if (strpos($val, ">") === false && strpos($val, "<") === false && strpos($val, "&") === false) {
						$xml .= "$tab<$key>$val</$key>\n";
					} else {
						$xml .= "$tab<$key><![CDATA[$val]]></$key>\n";
					}
				}
			}

			return $xml;
		}

		/*
			Function: arrayFilterRecursive
				Recursively filters an array.

			Parameters:
				array - A multi-dimensional array

			Returns:
				A filtered array
		*/

		public static function arrayFilterRecursive($array) {
			if (!is_array($array)) {
				return [];
			}

			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$array[$key] = static::arrayFilterRecursive($value);
				}
			}

			return array_filter($array);
		}

		/*
			Function: classAutoLoader
				Internal function to automatically load module classes as needed.
		*/

		public static function classAutoLoader($class) {
			global $bigtree;

			if (isset($bigtree["other_classes"][$class])) {
				include_once BigTree::path($bigtree["other_classes"][$class]);

				return;
			} elseif (isset($bigtree["module_list"][$class])) {
				$route = $bigtree["module_list"][$class];

				if (strpos($route, "*") !== false) {
					[$extension, $class] = explode("*", $route);
					$path = SERVER_ROOT."extensions/$extension/classes/$class.php";
					if (file_exists($path)) {
						include_once $path;

						return;
					}
				} else {
					$path = static::path("inc/modules/$route.php");
					if (file_exists($path)) {
						include_once $path;

						return;
					}
				}
			}

			// Clear the module class list just in case we're missing something.
			@unlink(SERVER_ROOT."cache/bigtree-module-class-list.json");
		}

		/*
			Function: cleanFile
				Makes sure that a file path doesn't contain abusive characters (i.e. ../)

			Parameters:
				file - A file name

			Returns:
				Cleaned up string.
		*/

		public static function cleanFile($file) {
			$pieces = array_filter(explode(DIRECTORY_SEPARATOR, $file), function ($val) {
				// Let empties through
				if (!trim($val)) {
					return true;
				}

				// Strip path manipulation
				if (trim(str_replace(".", "", $val)) === "") {
					return false;
				}

				return true;
			});

			return implode(DIRECTORY_SEPARATOR, $pieces);
		}

		/*
			Function: colorMesh
				Returns a color a % of the way between two colors.

			Parameters:
				first_color - The first color.
				second_color - The second color.
				percentage - The percentage between the first color and the second you want to move.

			Returns:
				A hex value color between the first and second colors.
		*/

		public static function colorMesh($first_color, $second_color, $percentage) {
			$percentage = intval(str_replace("%", "", $percentage));
			$first_color = ltrim($first_color, "#");
			$second_color = ltrim($second_color, "#");

			// Get the RGB values for the colors
			$fc_r = hexdec(substr($first_color, 0, 2));
			$fc_g = hexdec(substr($first_color, 2, 2));
			$fc_b = hexdec(substr($first_color, 4, 2));

			$sc_r = hexdec(substr($second_color, 0, 2));
			$sc_g = hexdec(substr($second_color, 2, 2));
			$sc_b = hexdec(substr($second_color, 4, 2));

			$r_diff = ceil(($sc_r - $fc_r) * $percentage / 100);
			$g_diff = ceil(($sc_g - $fc_g) * $percentage / 100);
			$b_diff = ceil(($sc_b - $fc_b) * $percentage / 100);

			$new_color = "#".str_pad(dechex($fc_r + $r_diff), 2, "0", STR_PAD_LEFT).str_pad(dechex($fc_g + $g_diff), 2, "0", STR_PAD_LEFT).str_pad(dechex($fc_b + $b_diff), 2, "0", STR_PAD_LEFT);

			return strtoupper($new_color);
		}

		/*
			Function: copyDirectory
				Copies a directory and sets writable permissions.

			Parameters:
				source - The location of the directory to copy.
				destination - The new folder location.

			Returns:
				true if the copy was successful
		*/

		public static function copyDirectory($source, $destination) {
			if (!static::isDirectoryWritable($destination) || !is_dir($source) || !is_readable($source) || file_exists($destination)) {
				return false;
			}

			$source = rtrim(rtrim($source, "/"), "\\")."/";
			$contents = static::directoryContents($source);
			$destination = rtrim(rtrim($destination, "/"), "\\")."/";

			mkdir($destination);
			static::setPermissions($destination);

			foreach ($contents as $file) {
				$new_location = $destination.str_replace($source, "", $file);

				if (is_dir($file)) {
					mkdir($new_location);
					static::setPermissions($new_location);
				} else {
					BigTree::copyFile($file, $destination.str_replace($source, "", $file));
				}
			}

			return true;
		}

		/*
			Function: copyFile
				Copies a file into a directory, even if that directory doesn't exist yet.

			Parameters:
				from - The current location of the file.
				to - The location of the new copy.

			Returns:
				true if the copy was successful, false if the directories were not writable or the source was not readable.
		*/

		public static function copyFile($from, $to) {
			if (!static::isDirectoryWritable($to)) {
				return false;
			}

			// If the origin is a protocol agnostic URL, add http:
			if (substr($from, 0, 2) == "//") {
				$from = "http:".$from;
			}

			// is_readable doesn't work on URLs
			if (substr($from, 0, 7) != "http://" && substr($from, 0, 8) != "https://" && !is_readable($from)) {
				return false;
			}
			$pathinfo = static::pathInfo($to);
			$directory = $pathinfo["dirname"];
			BigTree::makeDirectory($directory);

			$success = @copy($from, $to);
			static::setPermissions($to);

			return $success;
		}

		/*
			Function: cURL
				Posts to a given URL and returns the response.
				Wrapper for cURL.
				The last response code is stored in $bigtree["last_curl_response_code"].
				The last error is stored in $bigtree["last_curl_error"].

			Parameters:
				url - The URL to retrieve / POST to.
				post - A key/value pair array of things to POST (optional).
				options - A key/value pair of extra cURL options (optional).
				strict_security - SSL verification of the host and peer if true (defaults to true).
				output_file - A file location to dump the output of the request to (optional, replaces return value).

			Returns:
				The string response from the URL.
		*/

		public static function cURL($url, $post = false, $options = [], $strict_security = true, $output_file = false) {
			global $bigtree;

			$cert_bundle = static::cURLCertificateBundle();

			// Startup cURL and set the URL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			// Determine whether we're forcing valid SSL on the peer and host
			if ($strict_security) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			    curl_setopt($ch, CURLOPT_CAINFO, $cert_bundle);
			    curl_setopt($ch, CURLOPT_CAPATH, $cert_bundle);
			} else {
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			}

			// Limit request to 5 seconds less than max execution time
			$max_execution_time = intval(ini_get("max_execution_time"));

			if ($max_execution_time !== 0) {
				curl_setopt($ch, CURLOPT_TIMEOUT,  $max_execution_time - 5);
			}

			// If we're returning to a file we setup a file pointer rather than waste RAM capturing to a variable
			if ($output_file) {
				$file_pointer = fopen($output_file, "w");
				curl_setopt($ch, CURLOPT_FILE, $file_pointer);
			} else {
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			}

			// Setup post data
			if ($post !== false) {
				// Use cURLFile for any file uploads
				if (function_exists("curl_file_create") && is_array($post)) {
					foreach ($post as &$post_field) {
						if (substr($post_field, 0, 1) == "@" && file_exists(substr($post_field, 1))) {
							$post_field = curl_file_create(substr($post_field, 1));
						}
					}
					unset($post_field);
				}

				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}

			// Any additional cURL options
			if (is_array($options) && count($options)) {
				foreach ($options as $key => $opt) {
					curl_setopt($ch, $key, $opt);
				}
			}

			$output = curl_exec($ch);
			$bigtree["last_curl_response_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if (empty($bigtree["last_curl_response_code"])) {
				$bigtree["last_curl_error"] = curl_error($ch);
			}

			curl_close($ch);

			// If we're outputting to a file, close the handle and return nothing
			if ($output_file) {
				fclose($file_pointer);

				return;
			}

			return $output;
		}

		public static function cURLCertificateBundle() {
			$cache_cert_bundle = SERVER_ROOT."cache/bigtree-ca-cert.pem";
			$core_cert_bundle = SERVER_ROOT."core/cacert.pem";
			$cert_bundle = $cache_cert_bundle;

			// If we have a cached cert bundle, and it's recent, use it
			if (
				file_exists($cache_cert_bundle) &&
				filemtime($cache_cert_bundle) >= strtotime("-1 month") &&
				strpos(file_get_contents($cache_cert_bundle), "-----BEGIN CERTIFICATE-----") !== false
			) {
				return $cache_cert_bundle;
			}

			// If we don't have a cached cert bundle, use the core bundle which may be out of date to grab the latest remote bundle
			if (
				!file_exists($cache_cert_bundle) ||
				strpos(file_get_contents($cache_cert_bundle), "-----BEGIN CERTIFICATE-----") === false
			) {
				$cert_bundle = $core_cert_bundle;
			}

			$fetch = function($bundle = "", $skip_ssl = false) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://curl.se/ca/cacert.pem");
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

				if (!$skip_ssl) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

					if ($bundle) {
						curl_setopt($ch, CURLOPT_CAINFO, $bundle);
						curl_setopt($ch, CURLOPT_CAPATH, $bundle);
					}
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				}

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);

				if (strpos($output, "-----BEGIN CERTIFICATE-----") !== false) {
					return $output;
				}

				return null;
			};

			// First try to get the certificate bundle with the existing bundle
			$new_bundle = $fetch($cert_bundle);

			if (!$new_bundle) {
				// If that fails, try with the system bundle
				$new_bundle = $fetch();

				if (!$new_bundle) {
					// If that fails, try without SSL verification altogether
					$new_bundle = $fetch("", true);
				}
			}

			// If we successfully got a new bundle, store it
			if ($new_bundle) {
				file_put_contents($cache_cert_bundle, $new_bundle);

				return $cache_cert_bundle;
			}

			return $core_cert_bundle;
		}

		/*
			Function: currentURL
				Return the current active URL with correct protocall and port

			Parameters:
				port - Whether to return the port for connections not on port 80 (defaults to false)
		*/

		public static function currentURL($port = false) {
			$protocol = static::getIsSSL() ? "https://" : "http://";

			if ($_SERVER["SERVER_PORT"] != "80" && $port) {
				return $protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				return $protocol.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
		}

		/*
			Function: dateFormat
				Formats a date that originates in the config defined date format into another.

			Parameters:
				date - Date (in any format that strtotime understands or a unix timestamp)
				format - Format (in any format that PHP's date function understands, defaults to Y-m-d H:i:s)

			Returns:
				A date string or false if date parsing failed
		*/

		public static function dateFormat($date, $format = "Y-m-d H:i:s") {
			global $bigtree;

			$date_object = DateTime::createFromFormat($bigtree["config"]["date_format"], $date);

			// Fallback to SQL standards for handling pre 4.2 values
			if (!$date_object) {
				$date_object = DateTime::createFromFormat("Y-m-d", $date);
			}

			// Fallback to full format
			if (!$date_object) {
				$date_object = DateTime::createFromFormat("Y-m-d H:i:s", $date);
			}

			if ($date_object) {
				return $date_object->format($format);
			}

			return false;
		}

		/*
			Function: dateFromOffset
				Returns a formatted date from a date and an offset.
				e.g. "January 1, 2015" and "2 months" returns "2015-03-01 00:00:00"

			Parameters:
				start_date - Date to start at (in any format that strtotime understands or a unix timestamp)
				offset - Offset (in any "relative" PHP time format)
				format - Format for returned date (in any format that PHP's date function understands, defaults to Y-m-d H:i:s)

			Returns:
				A date string

			See Also:
				http://php.net/manual/en/datetime.formats.php (for strtotime formats)
				http://php.net/manual/en/datetime.formats.relative.php (for relative time formats)
				http://php.net/manual/en/function.date.php (for date formats)
		*/

		public static function dateFromOffset($start_date, $offset, $format = "Y-m-d H:i:s") {
			$time = is_numeric($start_date) ? $start_date : strtotime($start_date);
			$date = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s", $time));
			$date->add(DateInterval::createFromDateString($offset));

			return $date->format($format);
		}

		/*
			Function: deleteDirectory
				Deletes a directory including everything in it.

			Parameters:
				dir - The directory to delete.

			Returns:
				true if successful
		*/

		public static function deleteDirectory($dir) {
			if (!file_exists($dir)) {
				return false;
			}

			// Make sure it has a trailing /
			$dir = rtrim($dir, "/")."/";
			$r = opendir($dir);
			while ($file = readdir($r)) {
				if ($file != "." && $file != "..") {
					if (is_dir($dir.$file)) {
						static::deleteDirectory($dir.$file);
					} else {
						unlink($dir.$file);
					}
				}
			}

			return rmdir($dir);
		}

		/*
			Function: describeTable
				Gives in depth information about a MySQL table's structure and keys.

			Parameters:
				table - The table name.

			Returns:
				An array of table information.
		*/

		public static function describeTable($table) {
			$result["columns"] = [];
			$result["indexes"] = [];
			$result["foreign_keys"] = [];
			$result["primary_key"] = false;

			$f = sqlfetch(sqlquery("SHOW CREATE TABLE `".str_replace("`", "", $table)."`"));
			if (!$f) {
				return false;
			}

			$lines = explode("\n", $f["Create Table"]);
			// Line 0 is the create line and the last line is the collation and such. Get rid of them.
			$main_lines = array_slice($lines, 1, -1);
			foreach ($main_lines as $line) {
				$column = [];
				$line = rtrim(trim($line), ",");
				if (strtoupper(substr($line, 0, 3)) == "KEY" || strtoupper(substr($line, 0, 10)) == "UNIQUE KEY") { // Keys
					if (strtoupper(substr($line, 0, 10)) == "UNIQUE KEY") {
						$line = substr($line, 12); // Take away "KEY `"
						$unique = true;
					} else {
						$line = substr($line, 5); // Take away "KEY `"
						$unique = false;
					}
					// Get the key's name.
					$key_name = static::nextSQLColumnDefinition($line);
					// Get the key's content
					$line = substr($line, strlen($key_name) + substr_count($key_name, "`") + 4); // Skip ` (`
					$line = substr(rtrim($line, ","), 0, -1); // Remove trailing , and )
					$key_parts = [];
					$part = true;
					while ($line && $part) {
						$part = static::nextSQLColumnDefinition($line);
						$size = false;
						// See if there's a size definition, include it
						if (substr($line, strlen($part) + 1, 1) == "(") {
							$line = substr($line, strlen($part) + 1);
							$size = substr($line, 1, strpos($line, ")") - 1);
							$line = substr($line, strlen($size) + 4);
						} else {
							$line = substr($line, strlen($part) + substr_count($part, "`") + 3);
						}
						if ($part) {
							$key_parts[] = ["column" => $part, "length" => $size];
						}
					}
					$result["indexes"][$key_name] = ["unique" => $unique, "columns" => $key_parts];
				} elseif (strtoupper(substr($line, 0, 7)) == "PRIMARY") { // Primary Keys
					$line = substr($line, 14); // Take away PRIMARY KEY (`
					$key_parts = [];
					$part = true;
					while ($line && $part) {
						$part = static::nextSQLColumnDefinition($line);
						$line = substr($line, strlen($part) + substr_count($part, "`") + 3);
						if ($part) {
							if (strpos($part, "KEY_BLOCK_SIZE=") === false) {
								$key_parts[] = $part;
							}
						}
					}
					$result["primary_key"] = $key_parts;
				} elseif (strtoupper(substr($line, 0, 10)) == "CONSTRAINT") { // Foreign Keys
					$line = substr($line, 12); // Remove CONSTRAINT `
					$key_name = static::nextSQLColumnDefinition($line);
					$line = substr($line, strlen($key_name) + substr_count($key_name, "`") + 16); // Remove ` FOREIGN KEY (`

					// Get local reference columns
					$local_columns = [];
					$part = true;
					$end = false;
					while (!$end && $part) {
						$part = static::nextSQLColumnDefinition($line);
						$line = substr($line, strlen($part) + 1); // Take off the trailing `
						if (substr($line, 0, 1) == ")") {
							$end = true;
						} else {
							$line = substr($line, 2); // Skip the ,`
						}
						$local_columns[] = $part;
					}

					// Get other table name
					$line = substr($line, 14); // Skip ) REFERENCES `
					$other_table = static::nextSQLColumnDefinition($line);
					$line = substr($line, strlen($other_table) + substr_count($other_table, "`") + 4); // Remove ` (`

					// Get other table columns
					$other_columns = [];
					$part = true;
					$end = false;
					while (!$end && $part) {
						$part = static::nextSQLColumnDefinition($line);
						$line = substr($line, strlen($part) + 1); // Take off the trailing `
						if (substr($line, 0, 1) == ")") {
							$end = true;
						} else {
							$line = substr($line, 2); // Skip the ,`
						}
						$other_columns[] = $part;
					}

					$line = substr($line, 2); // Remove )

					// Setup our keys
					$result["foreign_keys"][$key_name] = ["local_columns" => $local_columns, "other_table" => $other_table, "other_columns" => $other_columns];

					// Figure out all the on delete, on update stuff
					$pieces = explode(" ", $line);
					$on_hit = false;
					$current_key = "";
					$current_val = "";
					foreach ($pieces as $piece) {
						if ($on_hit) {
							$current_key = strtolower("on_".$piece);
							$on_hit = false;
						} elseif (strtoupper($piece) == "ON") {
							if ($current_key) {
								$result["foreign_keys"][$key_name][$current_key] = $current_val;
								$current_key = "";
								$current_val = "";
							}
							$on_hit = true;
						} else {
							$current_val = trim($current_val." ".$piece);
						}
					}
					if ($current_key) {
						$result["foreign_keys"][$key_name][$current_key] = $current_val;
					}
				} elseif (substr($line, 0, 1) == "`") { // Column Definition
					$line = substr($line, 1); // Get rid of the first `
					$key = static::nextSQLColumnDefinition($line); // Get the column name.
					$line = substr($line, strlen($key) + substr_count($key, "`") + 2); // Take away the key from the line.

					$size = "";
					// We need to figure out if the next part has a size definition
					$parts = explode(" ", $line);
					if (strpos($parts[0], "(") !== false) { // Yes, there's a size definition
						$type = "";
						// We're going to walk the string finding out the definition.
						$in_quotes = false;
						$finished_type = false;
						$finished_size = false;
						$x = 0;
						$options = [];
						while (!$finished_size) {
							$c = substr($line, $x, 1);
							if (!$finished_type) { // If we haven't finished the type, keep working on it.
								if ($c == "(") { // If it's a (, we're starting the size definition
									$finished_type = true;
								} else { // Keep writing the type
									$type .= $c;
								}
							} else { // We're finished the type, working in size definition
								if (!$in_quotes && $c == ")") { // If we're not in quotes and we encountered a ) we've hit the end of the size
									$finished_size = true;
								} else {
									if ($c == "'") { // Check on whether we're starting a new option, ending an option, or adding to an option.
										if (!$in_quotes) { // If we're not in quotes, we're starting a new option.
											$current_option = "";
											$in_quotes = true;
										} else {
											if (substr($line, $x + 1, 1) == "'") { // If there's a second ' after this one, it's escaped.
												$current_option .= "'";
												$x++;
											} else { // We closed an option, add it to the list.
												$in_quotes = false;
												$options[] = $current_option;
											}
										}
									} else { // It's not a quote, it's content.
										if ($in_quotes) {
											$current_option .= $c;
										} elseif ($c != ",") { // We ignore commas, they're just separators between ENUM options.
											$size .= $c;
										}
									}
								}
							}
							$x++;
						}
						$line = substr($line, $x);
					} else { // No size definition
						$type = $parts[0];
						$line = substr($line, strlen($type) + 1);
					}

					$column["name"] = $key;
					$column["type"] = $type;
					if ($size) {
						$column["size"] = $size;
					}
					if ($type == "enum") {
						$column["options"] = $options;
					}
					$column["allow_null"] = true;
					$extras = explode(" ", $line);
					for ($x = 0; $x < count($extras); $x++) {
						$part = strtoupper($extras[$x]);
						if ($part == "NOT" && strtoupper($extras[$x + 1]) == "NULL") {
							$column["allow_null"] = false;
							$x++; // Skip NULL
						} elseif ($part == "CHARACTER" && strtoupper($extras[$x + 1]) == "SET") {
							$column["charset"] = $extras[$x + 2];
							$x += 2;
						} elseif ($part == "DEFAULT") {
							$default = "";
							$x++;
							if (substr($extras[$x], 0, 1) == "'") {
								while (substr($default, -1, 1) != "'") {
									$default .= " ".$extras[$x];
									$x++;
								}
							} else {
								$default = $extras[$x];
							}
							$column["default"] = trim(trim($default), "'");
						} elseif ($part == "COLLATE") {
							$column["collate"] = $extras[$x + 1];
							$x++;
						} elseif ($part == "ON") {
							$column["on_".strtolower($extras[$x + 1])] = $extras[$x + 2];
							$x += 2;
						} elseif ($part == "AUTO_INCREMENT") {
							$column["auto_increment"] = true;
						} elseif ($part == "UNSIGNED") {
							$column["unsigned"] = true;
						}
					}

					$result["columns"][$key] = $column;
				}
			}

			$last_line = substr(end($lines), 2);
			$parts = explode(" ", $last_line);
			foreach ($parts as $part) {
				$part_parts = explode("=", $part);
				$key = $part_parts[0] ?? "";
				$value = $part_parts[1] ?? "";

				if ($key && $value) {
					$result[strtolower($key)] = $value;
				}
			}

			return $result;
		}

		/*
			Function: directoryContents
				Returns a directory's files and subdirectories (with their files) in a flat array with file paths.

			Parameters:
				directory - The directory to search
				recursive - Set to false to not recurse subdirectories (defaults to true).
				extension - Limit the results to a specific file extension (defaults to false).
				include_git - .git and .gitignore will be ignored unless set to true (defaults to false).

			Returns:
				An array of files/folder paths.
				Returns false if the directory cannot be read.
		*/

		public static function directoryContents($directory, $recurse = true, $extension = false, $include_git = false) {
			$contents = [];
			$d = @opendir($directory);

			if (!$d) {
				return false;
			}

			while ($r = readdir($d)) {
				if ($r != "." && $r != ".." && $r != ".DS_Store" && $r != "__MACOSX") {
					if ($include_git || ($r != ".git" && $r != ".gitignore")) {
						$path = rtrim($directory, "/")."/".$r;

						if ($extension === false || substr($path, -1 * strlen($extension)) == $extension) {
							$contents[] = $path;
						}

						if (is_dir($path) && $recurse) {
							$contents = array_merge($contents, BigTree::directoryContents($path, $recurse, $extension, $include_git));
						}
					}
				}
			}

			return $contents;
		}

		/*
			Function: formatBytes
				Formats bytes into larger units to make them more readable.

			Parameters:
				size - The number of bytes.

			Returns:
				A string with the number of bytes in kilobytes, megabytes, or gigabytes.
		*/

		public static function formatBytes($size) {
			$units = [' B', ' KB', ' MB', ' GB', ' TB'];
			for ($i = 0; $size >= 1024 && $i < 4; $i++) {
				$size /= 1024;
			}

			return round($size, 2).$units[$i];
		}

		/*
			Function: formatCSS3
				Replaces CSS3 delcarations with vendor appropriate ones to reduce CSS redundancy.

			Parameters:
				css - CSS string.

			Returns:
				A string of CSS with vendor prefixes.
		*/

		public static function formatCSS3($css) {
			global $bigtree;

			// Background Gradients - background-gradient: #top #bottom
			$css = preg_replace_callback('/background-gradient:([^\"]*);/iU', function ($data) {
				$d = trim($data[1]);
				[$stop, $start] = explode(" ", $d);
				$start_rgb = (substr($start, 0, 1) == "#") ? "rgb(".hexdec(substr($start, 1, 2)).",".hexdec(substr($start, 3, 2)).",".hexdec(substr($start, 5, 2)).")" : $start;
				$stop_rgb = (substr($stop, 0, 1) == "#") ? "rgb(".hexdec(substr($stop, 1, 2)).",".hexdec(substr($stop, 3, 2)).",".hexdec(substr($stop, 5, 2)).")" : $stop;
				$response = "background-image: -webkit-gradient(linear,left top,left bottom, color-stop(0, $start_rgb), color-stop(1, $stop_rgb)); background-image: -moz-linear-gradient(center top, $start_rgb 0%, $stop_rgb 100%); background-image: -ms-linear-gradient(top, $start_rgb 0%, $stop_rgb 100%);";
				if (substr($start_rgb, 0, 4) != "rgba" && substr($stop_rgb, 0, 4) != "rgba") {
					$response .= "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=$start, endColorstr=$stop);-ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorstr=$start, endColorstr=$stop)\"; zoom:1;";
				}

				return $response;
			}, $css);

			// Border Radius - border-radius: 0px 0px 0px 0px
			$css = preg_replace_callback('/border-radius:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Box Shadow - box-shadow: 0px 0px 5px #color
			$css = preg_replace_callback('/box-shadow:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Column Count - column-count: number
			$css = preg_replace_callback('/column-count:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Column Rule - column-rule: 1px solid color
			$css = preg_replace_callback('/column-rule:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Column Gap - column-gap: number
			$css = preg_replace_callback('/column-gap:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Transition - transition: definition
			$css = preg_replace_callback('/transition:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Transform - transform: definition
			$css = preg_replace_callback('/transform:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// User Select - user-select: none | text | toggle | element | elements | all | inherit
			$css = preg_replace_callback('/user-select:([^\"]*);/iU', 'BigTree::formatVendorPrefixes', $css);

			// Replace roots
			$css = str_replace(["www_root/", "admin_root/", "static_root/"], [$bigtree["config"]["www_root"], $bigtree["config"]["admin_root"], $bigtree["config"]["static_root"]], $css);

			return $css;
		}

		/*
			Function: formatVendorPrefixes
				A preg_replace function for transforming a standard CSS3 entry into a vendor prefixed string.

			Parameters:
				data - preg data

			Returns:
				Replaced string.
		*/

		public static function formatVendorPrefixes($data) {
			$p = explode(":", $data[0]);
			$d = trim($data[1]);

			$return = $p[0].": $d; ";
			$return .= "-webkit-".$p[0].": $d; ";
			$return .= "-moz-".$p[0].": $d; ";
			$return .= "-ms-".$p[0].": $d; ";
			$return .= "-o-".$p[0].": $d; ";

			return $return;
		}

		/*
			Function: geocodeAddress
				Returns a latitude and longitude for a given address.
				This method is deprecated and exists only for backwards compatibility (BigTreeGeocoding should be used directly).

			Parameters:
				address - The address to geocode.

			Returns:
				An associative array with "latitude" and "longitude" keys (or false if geocoding failed).
		*/

		public static function geocodeAddress($address) {
			$geocoder = new BigTreeGeocoding;

			return $geocoder->geocode($address);
		}

		/*
			Function: getAvailableFileName
				Gets a web safe available file name in a given directory.

			Parameters:
				directory - The destination directory.
				file - The desired file name.
				prefixes - A list of file prefixes that also need to be accounted for when checking file name availability.

			Returns:
				An available, web safe file name.
		*/

		public static function getAvailableFileName($directory, $file, $prefixes = []) {
			$parts = static::pathInfo($directory.$file);

			// Clean up the file name
			$clean_name = BigTreeCMS::urlify($parts["filename"]);

			if (strlen($clean_name) > 50) {
				$clean_name = substr($clean_name, 0, 50);
			}

			if (!empty($parts["extension"])) {
				$file = $clean_name.".".strtolower($parts["extension"]);
			} else {
				$file = $clean_name;
			}

			// Just find a good filename that isn't used now.
			$x = 2;

			while (!$file || file_exists($directory.$file)) {
				$file = $clean_name."-$x.".strtolower($parts["extension"]);

				// Check prefixes
				foreach ($prefixes as $prefix) {
					if (file_exists($directory.$prefix.$file)) {
						$file = false;
					}
				}

				$x++;
			}

			return $file;
		}

		/*
			Function: getCookie
				Gets a cookie set by setCookie and decodes it.

			Parameters:
				id - The id of the set cookie
		*/

		public static function getCookie($id) {
			if (strpos($id, "[") !== false) {
				$pieces = explode("[", $id);
				$cookie = $_COOKIE;

				foreach ($pieces as $piece) {
					$piece = str_replace("]", "", $piece);
					$cookie = $cookie[$piece] ?? "null";
				}

				return json_decode($cookie, true);
			} else {
				return !empty($_COOKIE[$id]) ? json_decode($_COOKIE[$id], true) : null;
			}
		}

		/*
			Function: getFieldSelectOptions
				Get the <select> options of all the fields in a table.

			Parameters:
				table - The table to draw the fields for.
				default - The currently selected value.
				sorting - Whether to duplicate fields into "ASC" and "DESC" versions.
		*/

		public static function getFieldSelectOptions($table, $default = "", $sorting = false) {
			$table_description = static::describeTable($table);
			if (!$table_description) {
				echo '<option>ERROR: Table Missing</option>';

				return;
			}
			echo '<option></option>';
			foreach ($table_description["columns"] as $col) {
				if ($sorting) {
					if ($default == $col["name"]." ASC" || $default == "`".$col["name"]."` ASC") {
						echo '<option selected="selected">`'.$col["name"].'` ASC</option>';
					} else {
						echo '<option>`'.$col["name"].'` ASC</option>';
					}

					if ($default == $col["name"]." DESC" || $default == "`".$col["name"]."` DESC") {
						echo '<option selected="selected">`'.$col["name"].'` DESC</option>';
					} else {
						echo '<option>`'.$col["name"].'` DESC</option>';
					}
				} else {
					if ($default == $col["name"]) {
						echo '<option selected="selected">'.$col["name"].'</option>';
					} else {
						echo '<option>'.$col["name"].'</option>';
					}
				}
			}
		}

		/*
			Function: getTableSelectOptions
				Get the <select> options for all of tables in the database excluding bigtree_ prefixed tables.

			Parameters:
				default - The currently selected value.
		*/

		public static function getTableSelectOptions($default = false) {
			global $bigtree;

			$q = sqlquery("SHOW TABLES");
			while ($f = sqlfetch($q)) {
				$table_name = current($f);
				if (isset($bigtree["config"]["show_all_tables_in_dropdowns"]) || ((substr($table_name, 0, 8) !== "bigtree_")) || $table_name == $default) {
					if ($default == $table_name) {
						echo '<option selected="selected">'.$table_name.'</option>';
					} else {
						echo '<option>'.$table_name.'</option>';
					}
				}
			}
		}

		/*
			Function: getIsSSL
				Returns whether BigTree believes it's being served over SSL or not.
		*/

		public static function getIsSSL() {
			if (!empty($_SERVER["HTTPS"]) && $_SERVER['HTTPS'] !== "off") {
				return true;
			}

			if (!empty($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 443) {
				return true;
			}

			if (!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") {
				return true;
			}

			if (!empty($_SERVER["HTTP_X_FORWARDED_PORT"]) && $_SERVER["HTTP_X_FORWARDED_PORT"] == 443) {
				return true;
			}

			return false;
		}

		/*
			Function: globalizeArray
				Globalizes all the keys of an array into global variables without compromising super global ($_) variables.
				Optionally runs a list of functions (passed in after the array) on the data.

			Parameters:
				array - An array with key/value pairs.
				functions - Pass in additional arguments to run functions (i.e. "htmlspecialchars") on the data

			See Also:
				<globalizeGETVars>
				<globalizePOSTVars>
		*/

		public static function globalizeArray($array) {
			if (!is_array($array)) {
				return false;
			}

			// We don't want to lose track of our array while globalizing, so we're going to save things into $bigtree
			// Since we're not in the global scope, it doesn't matter that we're junking up $bigtree
			$bigtree = ["functions" => array_slice(func_get_args(), 1), "array" => $array];

			foreach ($bigtree["array"] as $bigtree["key"] => $bigtree["val"]) {
				// Prevent messing with super globals
				if (substr($bigtree["key"], 0, 1) != "_" && !in_array($bigtree["key"], ["admin", "bigtree", "cms"])) {
					// Fix for PHP 7
					$__bigtree_internal_key = $bigtree["key"];
					global $$__bigtree_internal_key;

					if (is_array($bigtree["val"])) {
						$$__bigtree_internal_key = static::globalizeArrayRecursion($bigtree["val"], $bigtree["functions"]);
					} else {
						foreach ($bigtree["functions"] as $bigtree["function"]) {
							// Backwards compatibility with old array passed syntax
							if (is_array($bigtree["function"])) {
								foreach ($bigtree["function"] as $bigtree["f"]) {
									$bigtree["val"] = is_null($bigtree["val"]) ? null : $bigtree["f"]($bigtree["val"]);
								}
							} else {
								$bigtree["val"] = @$bigtree["function"]($bigtree["val"]);
							}
						}
						$$__bigtree_internal_key = $bigtree["val"];
					}
				}
			}

			return true;
		}

		/*
			Function: globalizeArrayRecursion
				Used by globalizeArray for recursion.
		*/

		public static function globalizeArrayRecursion($data, $functions) {
			foreach ($data as $key => $val) {
				if (is_array($val)) {
					$data[$key] = static::globalizeArrayRecursion($val, $functions);
				} else {
					foreach ($functions as $func) {
						// Backwards compatibility with old array passed syntax
						if (is_array($func)) {
							foreach ($func as $f) {
								$val = is_null($val) ? null : $f($val);
							}
						} else {
							$val = $func($val);
						}
					}
					$data[$key] = $val;
				}
			}

			return $data;
		}

		/*
			Function: globalizeGETVars
				Globalizes all the $_GET variables without compromising $_ variables.
				Optionally runs a list of functions passed in as arguments on the data.

			Parameters:
				functions - Pass in additional arguments to run functions (i.e. "htmlspecialchars") on the data

			See Also:
				<globalizeArray>
				<globalizePOSTVars>

		*/

		public static function globalizeGETVars() {
			$args = func_get_args();

			return call_user_func_array("BigTree::globalizeArray", array_merge([$_GET], $args));
		}

		/*
			Function: globalizePOSTVars
				Globalizes all the $_POST variables without compromising $_ variables.
				Optionally runs a list of functions passed in as arguments on the data.

			Parameters:
				functions - Pass in additional arguments to run functions (i.e. "htmlspecialchars") on the data

			See Also:
				<globalizeArray>
				<globalizeGETVars>
		*/

		public static function globalizePOSTVars() {
			$args = func_get_args();

			return call_user_func_array("BigTree::globalizeArray", array_merge([$_POST], $args));
		}

		/*
			Function: gravatar
				Returns a properly formatted gravatar url.

			Parameters:
				email - User's email address.
				size - Image size; defaults to 56
				default - Default profile image; defaults to BigTree icon
				rating - Defaults to "g" (options include "g", "pg", "r", "x")
		*/

		public static function gravatar($email, $size = 56, $default = false, $rating = "g") {
			if (!$default) {
				global $bigtree;
				$default = !empty($bigtree["config"]["default_gravatar"]) ? $bigtree["config"]["default_gravatar"] : "https://www.bigtreecms.org/images/bigtree-gravatar.png";
			}

			return "https://secure.gravatar.com/avatar/".md5(strtolower($email))."?s=$size&d=".urlencode($default)."&rating=$rating";
		}

		/*
			Function: isDirectoryWritable
				Extend's PHP's is_writable to support directories that don't exist yet.

			Parameters:
				path - The path to check the writable status of.

			Returns:
				true if the directory exists and is writable or could be created, otherwise false.
		*/

		public static function isDirectoryWritable($path) {
			// Windows improperly returns writable status based on read-only flag instead of ACLs so we need our own version for Windows
			if (isset($_SERVER["OS"]) && stripos($_SERVER["OS"], "windows") !== false) {
				// Directory exists, check to see if we can create a temporary file inside it
				if (is_dir($path)) {
					$file = rtrim($path, "/")."/".uniqid().".tmp";
					$success = @touch($file);
					if ($success) {
						unlink($file);

						return true;
					}

					return false;
					// Remove the last directory from the path and then run isDirectoryWritable again
				} else {
					$parts = explode("/", $path);
					array_pop($parts);
					if (count($parts)) {
						return static::isDirectoryWritable(implode("/", $parts));
					}

					return false;
				}
			} else {
				// Directory exists, return its writable state
				if (is_dir($path)) {
					return is_writable($path);
				}
				// Remove the last directory from the path and try again
				$parts = explode("/", $path);
				array_pop($parts);

				return static::isDirectoryWritable(implode("/", $parts));
			}
		}

		/*
			Function: isExternalLink
				Check if URL is external, relative to site root

			Parameters:
				url - The URL to test.

			Returns:
				true if link is external
		*/

		public static function isExternalLink($url) {
			return ((substr($url, 0, 7) == "http://" || substr($url, 0, 8) == "https://") && strpos($url, WWW_ROOT) === false);
		}

		/*
			Function: json
				Encodes a variable as JSON. Uses pretty print if available. Optionally escapes for SQL.

			Parameters:
				var - Variable to JSON encode.
				sql - Whether to SQL escape the JSON (defaults to false).

			Returns:
				A JSON encoded string.
		*/

		public static function json($var, $sql = false) {
			// Only run version compare once in case we're encoding a lot of JSON
			if (static::$JSONEncoding === false) {
				if (version_compare(PHP_VERSION, "5.4.0") >= 0) {
					static::$JSONEncoding = 1;
				} else {
					static::$JSONEncoding = 0;
				}
			}

			// Use pretty print if we have PHP 5.4 or higher
			$json = (static::$JSONEncoding) ? json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : json_encode($var);
			// SQL escape if requested
			if ($sql) {
				return sqlescape($json);
			}

			return $json;
		}

		/*
			Function: jsonMaybeDecode
				Recurses through an array and tries to decode all properties as JSON arrays.
				If they parse correctly it returns the decoded value.

			Properties:
				data - An array of data

			Returns:
				An array
		*/

		public static function jsonMaybeDecode($data) {
			foreach ($data as $key => $value) {
				if (is_string($value)) {
					$value = htmlspecialchars_decode($value);
					$first_char = substr($value, 0, 1);

					if ($first_char == "{" || $first_char == "[") {
						$value = json_decode($value, true);

 						if (json_last_error() == JSON_ERROR_NONE) {
 							$data[$key] = $value;
 						}
					}
				}

				if (is_array($value)) {
					$data[$key] = static::jsonMaybeDecode($value);
				}
			}

			return $data;
		}

		/*
			Function: makeDirectory
				Makes a directory (and all applicable parent directories).
				Sets permissions to 777.

			Parameters:
				directory - The full path to the directory to be made.
		*/

		public static function makeDirectory($directory) {
			if (file_exists($directory)) {
				return;
			}

			// Windows systems aren't going to start with /
			if (substr($directory, 0, 1) == "/") {
				$dir_path = "/";
			} else {
				$dir_path = "";
			}

			$dir_parts = explode("/", trim($directory, "/"));
			foreach ($dir_parts as $part) {
				$dir_path .= $part;

				// Silence situations with open_basedir restrictions.
				if (!@file_exists($dir_path)) {
					@mkdir($dir_path);
					static::setPermissions($dir_path);
				}

				$dir_path .= "/";
			}
		}

		/*
			Function: moveFile
				Moves a file into a directory, even if that directory doesn't exist yet.

			Parameters:
				from - The current location of the file.
				to - The location of the new copy.

			Returns:
				true if the move was successful, false if the directories were not writable.
		*/

		public static function moveFile($from, $to) {
			$success = static::copyFile($from, $to);

			if (!$success) {
				return false;
			}

			unlink($from);

			return true;
		}

		/*
			Function: nextSQLColumnDefinition
				Return the next SQL name definition from a string.

			Parameters:
				string - A string with the name definition being terminated by a single `

			Returns:
				A string.
		*/

		public static function nextSQLColumnDefinition($string) {
			$key_name = "";
			$i = 0;
			$found_key = false;
			// Apparently we can have a backtick ` in a column name... ugh.
			while (!$found_key && $i < strlen($string)) {
				$char = substr($string, $i, 1);
				$second_char = substr($string, $i + 1, 1);
				if ($char != "`" || $second_char == "`") {
					$key_name .= $char;
					if ($char == "`") { // Skip the next one, this was just an escape character.
						$i++;
					}
				} else {
					$found_key = true;
				}
				$i++;
			}

			return $key_name;
		}

		/*
			Function: parsedFilesArray
				Parses the $_FILES array and returns an array more like a normal $_POST array.

			Parameters:
				part - (Optional) The key of the file tree to return.

			Returns:
				A more sensible array, or a piece of that sensible array if "part" is set.
		*/

		public static function parsedFilesArray($part = false) {
			$clean = [];

			foreach ($_FILES as $key => $first_level) {
				// Hurray, we have a first level entry, just save it to the clean array.
				if (!is_array($first_level["name"])) {
					$clean[$key] = $first_level;
				} else {
					$clean[$key] = static::parsedFilesArrayLoop($first_level["name"], $first_level["tmp_name"], $first_level["type"], $first_level["error"], $first_level["size"]);
				}
			}

			if ($part) {
				return $clean[$part] ?? null;
			}

			return $clean;
		}

		/*
			Function: parseFilesArrayLoop
				Private method used by parseFilesArray.
		*/

		private static function parsedFilesArrayLoop($name, $tmp_name, $type, $error, $size) {
			$array = [];
			foreach ($name as $k => $v) {
				if (!is_array($v)) {
					$array[$k]["name"] = $v;
					$array[$k]["tmp_name"] = $tmp_name[$k];
					$array[$k]["type"] = $type[$k];
					$array[$k]["error"] = $error[$k];
					$array[$k]["size"] = $size[$k];
				} else {
					$array[$k] = static::parsedFilesArrayLoop($name[$k], $tmp_name[$k], $type[$k], $error[$k], $size[$k]);
				}
			}

			return $array;
		}

		/*
			Function: path
				Get the proper path for a file based on whether a custom override exists.

			Parameters:
				file - File path relative to either core/ or custom/

			Returns:
				Hard file path to a custom/ (preferred) or core/ file depending on what exists.
		*/

		public static function path($file) {
			if (file_exists(SERVER_ROOT."custom/".$file)) {
				return SERVER_ROOT."custom/".$file;
			} else {
				// Backwards compatibility for 4.2 and below field types
				if (strpos($file, "admin/form-field-types/draw/") === 0) {
					$file = "admin/field-types/".pathinfo($file, PATHINFO_FILENAME)."/draw.php";
				} elseif (strpos($file, "admin/form-field-types/process/") === 0) {
					$file = "admin/field-types/".pathinfo($file, PATHINFO_FILENAME)."/process.php";
				} elseif (strpos($file, "admin/ajax/developer/field-options/") === 0) {
					$file = "admin/field-types/".pathinfo($file, PATHINFO_FILENAME)."/settings.php";
				}

				return SERVER_ROOT."core/".$file;
			}
		}

		/*
			Function: pathInfo
				Wrapper for PHP's pathinfo to make sure it supports returning "filename"

			Parameters:
				file - The full file path.

			Returns:
				Everything PHP's pathinfo() returns (with "filename" even when PHP doesn't support it).

			See Also:
				<http://php.net/manual/en/function.pathinfo.php>
		*/

		public static function pathInfo($file) {
			if (empty($file)) {
				return null;
			}

			$parts = pathinfo($file);

			if (!defined('PATHINFO_FILENAME')) {
				$parts["filename"] = substr($parts["basename"], 0, strrpos($parts["basename"], '.'));
			}

			return $parts;
		}

		/*
			Function: phpDateTojQuery
				Converts a PHP date() format to jQuery date picker format.

			Parameters:
				format - PHP date() formatting string

			Returns:
				jQuery date picker formatting string.
		*/

		public static function phpDateTojQuery($format) {
			$new_format = "";
			for ($i = 0; $i < strlen($format); $i++) {
				$c = substr($format, $i, 1);
				// Day with leading zeroes
				if ($c == "d") {
					$new_format .= "dd";
					// Day without leading zeroes
				} elseif ($c == "j") {
					$new_format .= "d";
					// Full day name (i.e. Sunday)
				} elseif ($c == "l") {
					$new_format .= "DD";
					// Numeric day of the year (0-365)
				} elseif ($c == "z") {
					$new_format .= "o";
					// Full month name (i.e. January)
				} elseif ($c == "F") {
					$new_format .= "MM";
					// Month with leading zeroes
				} elseif ($c == "m") {
					$new_format .= "mm";
					// Month without leading zeroes
				} elseif ($c == "n") {
					$new_format .= "m";
					// 4 digit year
				} elseif ($c == "Y") {
					$new_format .= "yy";
					// Many others are the same or not a date format part
				} else {
					$new_format .= $c;
				}
			}

			return $new_format;
		}

		/*
			Function: placeholderImage
				Generates placeholder image data.

			Parameters:
				width - The width of desired image
				height - The height of desired image
				bg_color - The background color; must be full 6 charachter hex value
				text_color - The text color; must be full 6 charachter hex value
				icon_path - Image to render, disables text rendering, must be gif, jpeg, or png
				text_string - Text to render; overrides default dimension display

			Returns:
				Nothing; Renders a placeholder image
		*/

		public static function placeholderImage($width, $height, $bg_color = false, $text_color = false, $icon_path = false, $text_string = false) {
			// Check size
			$width = ($width > 2000) ? 2000 : $width;
			$height = ($height > 2000) ? 2000 : $height;

			// Check colors
			$bg_color = (!$bg_color && $bg_color != "000" && $bg_color != "000000") ? "CCCCCC" : ltrim($bg_color, "#");
			$text_color = (!$text_color && $text_color != "000" && $text_color != "000000") ? "666666" : ltrim($text_color, "#");

			// Set text
			$text = $text_string;
			if ($icon_path) {
				$text = "";
			} else {
				if (!$text_string) {
					$text = $width." X ".$height;
				}
			}

			// Create image
			$image = imagecreatetruecolor($width, $height);
			// Build rgba from hex
			$bg_color = imagecolorallocate($image, base_convert(substr($bg_color, 0, 2), 16, 10), base_convert(substr($bg_color, 2, 2), 16, 10), base_convert(substr($bg_color, 4, 2), 16, 10));
			$text_color = imagecolorallocate($image, base_convert(substr($text_color, 0, 2), 16, 10), base_convert(substr($text_color, 2, 2), 16, 10), base_convert(substr($text_color, 4, 2), 16, 10));
			// Fill image
			imagefill($image, 0, 0, $bg_color);

			// Add icon if provided
			if ($icon_path) {
				$icon_size = getimagesize($icon_path);
				$icon_width = $icon_size[0];
				$icon_height = $icon_size[1];
				$icon_x = ($width - $icon_width) / 2;
				$icon_y = ($height - $icon_height) / 2;

				$ext = strtolower(substr($icon_path, -3));
				if ($ext == "jpg" || $ext == "peg") {
					$icon = imagecreatefromjpeg($icon_path);
				} elseif ($ext == "gif") {
					$icon = imagecreatefromgif($icon_path);
				} else {
					$icon = imagecreatefrompng($icon_path);
				}
				imagesavealpha($icon, true);
				imagealphablending($icon, true);
				imagecopyresampled($image, $icon, $icon_x, $icon_y, 0, 0, $icon_width, $icon_height, $icon_width, $icon_height);
				// Add text if provided or default to size
			} elseif ($text) {
				$font = BigTree::path("inc/lib/fonts/arial.ttf");
				$fontsize = ($width > $height) ? ($height / 15) : ($width / 15);
				$textpos = imageTTFBbox($fontsize, 0, $font, $text);
				imagettftext($image, $fontsize, 0, (($width - $textpos[2]) / 2), (($height - $textpos[5]) / 2), $text_color, $font, $text);
			}

			// Serve image and die
			header("Content-Type: image/png");
			imagepng($image);
			imagedestroy($image);
			die();
		}

		/*
			Function: postMaxSize
				Returns in bytes the maximum size of a POST.
		*/

		public static function postMaxSize() {
			$post_max_size = ini_get("post_max_size");
			if (!is_integer($post_max_size)) {
				$post_max_size = static::unformatBytes($post_max_size);
			}

			return $post_max_size;
		}

		/*
			Function: prefixFile
				Prefixes a file name with a given prefix.

			Parameters:
				file - A file name or full file path.
				prefix - The prefix for the file name.

			Returns:
				The full path or file name with a prefix appended to the file name.
		*/

		public static function prefixFile($file, $prefix) {
			$pinfo = static::pathInfo($file);
			// Remove notices
			$pinfo["dirname"] = isset($pinfo["dirname"]) ? $pinfo["dirname"] : "";

			return $pinfo["dirname"]."/".$prefix.$pinfo["basename"];
		}

		/*
			Function: putFile
				Writes data to a file, even if that directory for the file doesn't exist yet.
				Sets the file permissions to 777 if the file did not exist.

			Parameters:
				file - The location of the file.
				contents - The data to write.

			Returns:
				true if the move was successful, false if the directories were not writable.
		*/

		public static function putFile($file, $contents) {
			if (!static::isDirectoryWritable($file)) {
				return false;
			}

			$pathinfo = static::pathInfo($file);
			$directory = $pathinfo["dirname"];
			BigTree::makeDirectory($directory);

			if (!file_exists($file)) {
				file_put_contents($file, $contents);
				static::setPermissions($file);
			} else {
				file_put_contents($file, $contents);
			}

			return true;
		}

		/*
			Function: randomString
				Returns a random string.

			Parameters:
				length - The number of characters to return.
				seeds - The seed set to use ("alpha" for lowercase letters, "numeric" for numbers, "alphanum" for uppercase letters and numbers, "hexidec" for hexidecimal)

			Returns:
				A random string.
		*/

		public static function randomString($length = 8, $seeds = 'alphanum') {
			// Possible seeds
			$seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
			$seedings['numeric'] = '0123456789';
			$seedings['alphanum'] = 'ABCDEFGHJKLMNPQRTUVWXY0123456789';
			$seedings['hexidec'] = '0123456789abcdef';

			// Choose seed
			if (isset($seedings[$seeds])) {
				$seeds = $seedings[$seeds];
			}

			// Seed generator
			[$usec, $sec] = explode(' ', microtime());
			$seed = (float) $sec + ((float) $usec * 100000);
			mt_srand((int) $seed);

			// Generate
			$str = '';
			$seeds_count = strlen($seeds);
			for ($i = 0; $length > $i; $i++) {
				$str .= $seeds[mt_rand(0, $seeds_count - 1)];
			}

			return $str;
		}

		/*
			Function: redirect
				Simple URL redirect via header with proper code #

			Parameters:
				url - The URL to redirect to.
				code - The status code of redirect, defaults to normal 302 redirect.
		*/

		public static function redirect($url = false, $codes = ["302"]) {
			global $bigtree;

			// If we're presently in the admin we don't want to allow the possibility of a redirect outside our site via malicious URLs
			if (defined("BIGTREE_ADMIN_ROUTED")) {
				// Multiple redirect domains allowed
				if (count($bigtree["config"]["sites"])) {
					$ok = false;
					$pieces = explode("/", $url);

					foreach ($bigtree["config"]["sites"] as $site_data) {
						$bt_domain_pieces = explode("/", $site_data["domain"]);

						if (!empty($pieces[2]) && !empty($bt_domain_pieces[2]) &&
							strtolower($pieces[2]) == strtolower($bt_domain_pieces[2])
						) {
							$ok = true;
						}
					}

					if (!$ok) {
						return false;
					}
				} else {
					$pieces = explode("/", $url);
					$bt_domain_pieces = explode("/", DOMAIN);

					if (strtolower($pieces[2]) != strtolower($bt_domain_pieces[2])) {
						return false;
					}
				}
			}

			$status_codes = [
				"200" => "OK",
				"300" => "Multiple Choices",
				"301" => "Moved Permanently",
				"302" => "Found",
				"303" => "See Other",
				"304" => "Not Modified",
				"307" => "Temporary Redirect",
				"400" => "Bad Request",
				"401" => "Unauthorized",
				"403" => "Forbidden",
				"404" => "Not Found",
				"410" => "Gone",
				"500" => "Internal Server Error",
				"501" => "Not Implemented",
				"503" => "Service Unavailable",
				"550" => "Permission denied"
			];
			if (!$url) {
				return false;
			}
			if (!is_array($codes)) {
				$codes = [$codes];
			}
			foreach ($codes as $code) {
				if ($status_codes[$code]) {
					header($_SERVER["SERVER_PROTOCOL"]." $code ".$status_codes[$code]);
				}
			}
			header("Location: $url");
			die();
		}

		/*
			Function: relativeTime
				Turns a timestamp into "… hours ago" formatting.

			Parameters:
				time - A date/time stamp understandable by strtotime

			Returns:
				A string describing how long ago the passed time was.
		*/

		public static function relativeTime($time) {
			$minute = 60;
			$hour = 3600;
			$day = 86400;
			$month = 2592000;
			$delta = strtotime(date('r')) - strtotime($time);

			if ($delta < 2 * $minute) {
				return "1 min ago";
			} elseif ($delta < 45 * $minute) {
				$minutes = floor($delta / $minute);

				return $minutes == 1 ? "1 minute ago" : "$minutes minutes ago";
			} elseif ($delta < 24 * $hour) {
				$hours = floor($delta / $hour);

				return $hours == 1 ? "1 hour ago" : "$hours hours ago";
			} elseif ($delta < 30 * $day) {
				$days = floor($delta / $day);

				return $days == 1 ? "yesterday" : "$days days ago";
			} elseif ($delta < 12 * $month) {
				$months = floor($delta / $day / 30);

				return $months == 1 ? "1 month ago" : "$months months ago";
			} else {
				$years = floor($delta / $day / 365);

				return $years == 1 ? "1 year ago" : "$years years ago";
			}
		}

		/*
			Function: remoteIP
				Returns the remote user's IP address (works with load balancers).

			Returns:
				An IP address
		*/

		public static function remoteIP() {
			if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (!empty($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (!empty($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (!empty($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} elseif (!empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
				return $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
			} elseif (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				return $_SERVER["HTTP_CF_CONNECTING_IP"];
			} elseif (!empty($_SERVER["REMOTE_ADDR"])) {
				return $_SERVER["REMOTE_ADDR"];
			}

			return null;
		}

		/*
			Function: route
				Returns the proper file to include based on existence of subdirectories or .php files with given route names.
				Used by the CMS for routing ajax and modules.

			Parameters:
				directory - Root directory to begin looking in.
				path - An array of routes.

			Returns:
				An array with the first element being the file to include and the second element being an array containing extraneous routes from the end of the path.
		*/

		public static function route($directory, $path) {
			$commands = [];
			$inc_file = $directory;
			$inc_dir = $directory;
			$ended = false;
			$found_file = false;
			foreach ($path as $piece) {
				// Prevent path exploitation
				if ($piece == "..") {
					die();
				}
				// We're done, everything is a command now.
				if ($ended) {
					$commands[] = $piece;
					// Keep looking for directories.
				} elseif (is_dir($inc_dir.$piece)) {
					$inc_file .= $piece."/";
					$inc_dir .= $piece."/";
					// File exists, we're ending now.
				} elseif ($piece != "_header" && $piece != "_footer" && file_exists($inc_file.$piece.".php")) {
					$inc_file .= $piece.".php";
					$ended = true;
					$found_file = true;
					// Couldn't find a file or directory.
				} else {
					$commands[] = $piece;
					$ended = true;
				}
			}

			if (!$found_file) {
				// If we have default in the routed directory, use it.
				if (file_exists($inc_dir."default.php")) {
					$inc_file = $inc_dir."default.php";
					// See if we can change the directory name into .php file in case the directory is empty but we have .php
				} elseif (file_exists(rtrim($inc_dir, "/").".php")) {
					$inc_file = rtrim($inc_dir, "/").".php";
					// We couldn't route anywhere apparently.
				} else {
					return [false, []];
				}
			}

			return [$inc_file, $commands];
		}

		/*
			Function: runningAsSU
				Checks if the current script is running as the owner of the script.
				Useful for determining whether you need to 777 a file you're creating.

			Returns:
				true if PHP is running as the user that owns the file
		*/

		public static function runningAsSU() {
			// Already ran the test
			if (!is_null(static::$SUTestResult)) {
				return static::$SUTestResult;
			}
			// Only works on systems that support posix_getuid
			if (function_exists("posix_getuid")) {
				if (posix_getuid() == getmyuid()) {
					static::$SUTestResult = true;
				} else {
					static::$SUTestResult = false;
				}
			} else {
				static::$SUTestResult = false;
			}

			return static::$SUTestResult;
		}

		/*
			Function: runParser
				Evaluates code in a function scope with $item and $value
				Used mostly internally in the admin for parsers.

			Parameters:
				item - Full array of data
				value - The value to be manipulated and returned
				code - The code to be run in eval()

			Returns:
				Modified $value
		*/

		public static function runParser($item, $value, $code) {
			eval($code);

			return $value;
		}

		/*
			Function: safeEncode
				Modifies a string so that it is safe for display on the web (tags and quotes modified for usage inside attributes) without double-encoding.
				Ensures that other html entities (like &hellip;) turn into UTF-8 characters before encoding.
				Only to be used when your website's character set is UTF-8.

			Parameters:
				string - String to encode

			Returns:
				Encoded string.
		*/

		public static function safeEncode($string) {
			if (!is_string($string)) {
				return is_numeric($string) ? $string : "";
			}

			return htmlspecialchars(htmlspecialchars_decode(html_entity_decode($string, ENT_COMPAT, "UTF-8")));
		}

		/*
			Function: sendEmail
				Sends an email using PHPMailer

			Parameters:
				to - String or array of recipient email address(es)
				subject - Subject line text
				html - HTML Email Body
				text - Text Email Body
				from - From email address
				return - Return email address (if different than from)
				cc - String or array of carbon copy email address(es)
				bcc - String or array of blind carbon copy email address(es)
				headers - Key/value pair array of extra headers
				smtp - SMTP server information

			Returns:
				true if email is sent, otherwise false.
		*/

		public static function sendEmail($to, $subject, $html, $text = "", $from = false, $return = false, $cc = false, $bcc = false, $headers = [], $smtp = []) {
			$mailer = new PHPMailer;

			if (function_exists("mb_detect_encoding") && mb_detect_encoding($html, ["ASCII"], true) === false) {
				$mailer->CharSet = "UTF-8";
				$mailer->Encoding = "base64";
			}

			if (is_array($smtp) && count($smtp)) {
				$mailer->isSMTP();
				$mailer->Host = $smtp["host"];
				$mailer->Port = $smtp["port"] ?: 25;
				$mailer->SMTPSecure = $smtp["security"] ?: null;

				if ($smtp["user"]) {
					$mailer->SMTPAuth = true;
					$mailer->Username = $smtp["user"];
					$mailer->Password = $smtp["password"];
				} else {
					$mailer->SMTPAuth = false;
				}
			}

			foreach ($headers as $key => $val) {
				$mailer->addCustomHeader($key, $val);
			}

			$mailer->Subject = $subject;
			if ($html) {
				$mailer->isHTML(true);
				$mailer->Body = $html;
				$mailer->AltBody = $text;
			} else {
				$mailer->Body = $text;
			}

			if (!$from) {
				$from = "no-reply@".(isset($_SERVER["HTTP_HOST"]) ? str_replace("www.", "", $_SERVER["HTTP_HOST"]) : str_replace(["http://www.", "https://www.", "http://", "https://"], "", DOMAIN));
				$from_name = "BigTree CMS";
			} else {
				// Parse out from and reply-to names
				$from_name = false;
				$from = trim($from);

				if (strpos($from, "<") !== false && substr($from, -1, 1) == ">") {
					$from_pieces = explode("<", $from);
					$from_name = trim($from_pieces[0]);
					$from = substr($from_pieces[1], 0, -1);
				}
			}

			$mailer->From = $from;
			$mailer->FromName = $from_name;
			$mailer->Sender = $from;

			if ($return) {
				$return_name = false;
				$return = trim($return);

				if (strpos($return, "<") !== false && substr($return, -1, 1) == ">") {
					$return_pieces = explode("<", $return);
					$return_name = trim($return_pieces[0]);
					$return = substr($return_pieces[1], 0, -1);
				}

				$mailer->addReplyTo($return, $return_name);
			}

			if ($cc) {
				if (is_array($cc)) {
					foreach ($cc as $item) {
						$mailer->addCC($item);
					}
				} else {
					$mailer->addCC($cc);
				}
			}

			if ($bcc) {
				if (is_array($bcc)) {
					foreach ($bcc as $item) {
						$mailer->addBCC($item);
					}
				} else {
					$mailer->addBCC($bcc);
				}
			}

			if (is_array($to)) {
				foreach ($to as $item) {
					$mailer->addAddress($item);
				}
			} else {
				$mailer->addAddress($to);
			}

			return $mailer->send();
		}

		/*
			Function: setCookie
				Sets a site-wide cookie with support for arrays.
				Cookies set by setCookie should be retrieved via getCookie (all values are JSON encoded).

			Parameters:
				id - The cookie identifier
				value - The value to set for the cookie
				expiration - Cookie expiration time (in seconds since UNIX epoch) or a string value compatible with strtotime (defaults to session expiration)
		*/

		public static function setCookie($id, $value, $expiration = 0) {
			$expiration = is_string($expiration) ? strtotime($expiration) : $expiration;
			setcookie($id, json_encode($value), $expiration, str_replace(DOMAIN, "", WWW_ROOT));
		}

		/*
			Function: setDirectoryPermissions
				Sets writable permissions for a whole directory.
				If the web server is not running as the owner of the current script, permissions will be 777.

			Parameters:
				location - The directory to set permissions on.
		*/

		public static function setDirectoryPermissions($location) {
			$contents = static::directoryContents($location);
			foreach ($contents as $file) {
				static::setPermissions($file);
			}
		}

		/*
			Function: setPermissions
				Checks to see if the current user the web server is running as is the owner of the current script.
				If they are not the same user, the file/directory is given a 777 permission so that the script owner can still manage the file.

			Parameters:
				location - The file or directory to set permissions on.
		*/

		public static function setPermissions($location) {
			if (!static::runningAsSU()) {
				@chmod($location, 0777);
			}
		}

		/*
			Function: tableCompare
				Returns a list of SQL commands required to turn one table into another.

			Parameters:
				table_a - The table that is being translated
				table_b - The table that the first table will become

			Returns:
				An array of SQL calls to perform to turn Table A into Table B.
		*/

		public static function tableCompare($table_a, $table_b) {
			// Get table A's description
			$table_a_description = BigTree::describeTable($table_a);
			$table_a_columns = $table_a_description["columns"];
			// Get table B's description
			$table_b_description = BigTree::describeTable($table_b);
			$table_b_columns = $table_b_description["columns"];

			// Setup up query array
			$queries = [];

			// Transition columns
			$last_key = "";
			foreach ($table_b_columns as $key => $column) {
				$mod = "";
				$action = "";
				// If this column doesn't exist in the Table A table, add it.
				if (!isset($table_a_columns[$key])) {
					$action = "ADD";
				} elseif ($table_a_columns[$key] !== $column) {
					$action = "MODIFY";
				}

				if ($action) {
					$mod = "ALTER TABLE `$table_a` $action COLUMN `$key` ".$column["type"];

					if (!empty($column["size"])) {
						$mod .= "(".$column["size"].")";
					}

					if (!empty($column["unsigned"])) {
						$mod .= " UNSIGNED";
					}

					if (!empty($column["charset"])) {
						$mod .= " CHARSET ".$column["charset"];
					}

					if (!empty($column["collate"])) {
						$mod .= " COLLATE ".$column["collate"];
					}

					if (empty($column["allow_null"])) {
						$mod .= " NOT NULL";
					} else {
						$mod .= " NULL";
					}

					if (isset($column["default"])) {
						$d = $column["default"];
						if ($d == "CURRENT_TIMESTAMP" || $d == "NULL") {
							$mod .= " DEFAULT $d";
						} else {
							$mod .= " DEFAULT '".sqlescape($d)."'";
						}
					}

					if ($last_key) {
						$mod .= " AFTER `$last_key`";
					} else {
						$mod .= " FIRST";
					}

					$queries[] = $mod;
				}

				$last_key = $key;
			}

			// Drop columns
			foreach ($table_a_columns as $key => $column) {
				// If this key no longer exists in the new table, we should delete it.
				if (!isset($table_b_columns[$key])) {
					$queries[] = "ALTER TABLE `$table_a` DROP COLUMN `$key`";
				}
			}

			// Add new indexes
			foreach ($table_b_description["indexes"] as $key => $index) {
				if (!isset($table_a_description["indexes"][$key]) || $table_a_description["indexes"][$key] != $index) {
					$pieces = [];
					foreach ($index["columns"] as $column) {
						if ($column["length"]) {
							$pieces[] = "`".$column["column"]."`(".$column["length"].")";
						} else {
							$pieces[] = "`".$column["column"]."`";
						}
					}
					$verb = isset($table_a_description["indexes"][$key]) ? "MODIFY" : "ADD";
					$queries[] = "ALTER TABLE `$table_a` $verb ".($index["unique"] ? "UNIQUE " : "")."KEY `$key` (".implode(", ", $pieces).")";
				}
			}

			// Drop old indexes
			foreach ($table_a_description["indexes"] as $key => $index) {
				if (!isset($table_b_description["indexes"][$key])) {
					$queries[] = "ALTER TABLE `$table_a` DROP KEY `$key`";
				}
			}

			// Drop old foreign keys -- we do this for all the existing foreign keys that don't directly match because we're going to regenrate key names
			foreach ($table_a_description["foreign_keys"] as $key => $definition) {
				$exists = false;
				foreach ($table_b_description["foreign_keys"] as $d) {
					if ($d == $definition) {
						$exists = true;
					}
				}
				if (!$exists) {
					$queries[] = "ALTER TABLE `$table_a` DROP FOREIGN KEY `$key`";
				}
			}

			// Import foreign keys
			foreach ($table_b_description["foreign_keys"] as $key => $definition) {
				$exists = false;
				foreach ($table_a_description["foreign_keys"] as $d) {
					if ($d == $definition) {
						$exists = true;
					}
				}
				if (!$exists) {
					$source = $destination = [];
					foreach ($definition["local_columns"] as $column) {
						$source[] = "`$column`";
					}
					foreach ($definition["other_columns"] as $column) {
						$destination[] = "`$column`";
					}
					$query = "ALTER TABLE `$table_a` ADD FOREIGN KEY (".implode(", ", $source).") REFERENCES `".$definition["other_table"]."`(".implode(", ", $destination).")";
					if ($definition["on_delete"]) {
						$query .= " ON DELETE ".$definition["on_delete"];
					}
					if ($definition["on_update"]) {
						$query .= " ON UPDATE ".$definition["on_update"];
					}
					$queries[] = $query;
				}
			}

			// Drop existing primary key if it's not the same
			if ($table_a_description["primary_key"] != $table_b_description["primary_key"]) {
				$pieces = [];
				foreach (array_filter((array) $table_b_description["primary_key"]) as $piece) {
					$pieces[] = "`$piece`";
				}
				$queries[] = "ALTER TABLE `$table_a` DROP PRIMARY KEY";
				$queries[] = "ALTER TABLE `$table_a` ADD PRIMARY KEY (".implode(",", $pieces).")";
			}

			// Switch engine if different
			if ($table_a_description["engine"] != $table_b_description["engine"]) {
				$queries[] = "ALTER TABLE `$table_a` ENGINE = ".$table_b_description["engine"];
			}

			// Switch character set if different
			if ($table_a_description["charset"] != $table_b_description["charset"]) {
				$queries[] = "ALTER TABLE `$table_a` CHARSET = ".$table_b_description["charset"];
			}

			// Switch auto increment if different
			if (isset($table_b_description["auto_increment"]) && ($table_a_description["auto_increment"] ?? "") != $table_b_description["auto_increment"]) {
				$queries[] = "ALTER TABLE `$table_a` AUTO_INCREMENT = ".$table_b_description["auto_increment"];
			}

			return $queries;
		}

		/*
			Function: tableContents
				Returns an array of INSERT statements for the rows of a given table.
				The INSERT statements will be binary safe with binary columns requested in hex.

			Parameters:
				table - Table to pull data from.

			Returns:
				An array.
		*/

		public static function tableContents($table) {
			$inserts = [];

			// Figure out which columns are binary and need to be pulled as hex
			$description = BigTree::describeTable($table);
			$column_query = [];
			$binary_columns = [];
			foreach ($description["columns"] as $key => $column) {
				if ($column["type"] == "tinyblob" || $column["type"] == "blob" || $column["type"] == "mediumblob" || $column["type"] == "longblob" || $column["type"] == "binary" || $column["type"] == "varbinary") {
					$column_query[] = "HEX(`$key`) AS `$key`";
					$binary_columns[] = $key;
				} else {
					$column_query[] = "`$key`";
				}
			}

			// Get the rows out of the table
			$qq = sqlquery("SELECT ".implode(", ", $column_query)." FROM `$table`");
			while ($ff = sqlfetch($qq)) {
				$keys = [];
				$vals = [];
				foreach ($ff as $key => $val) {
					$keys[] = "`$key`";
					if ($val === null) {
						$vals[] = "NULL";
					} else {
						if (in_array($key, $binary_columns)) {
							$vals[] = "X'".str_replace("\n", "\\n", sqlescape($val))."'";
						} else {
							$vals[] = "'".str_replace("\n", "\\n", sqlescape($val))."'";
						}
					}
				}
				$inserts[] = "INSERT INTO `$table` (".implode(",", $keys).") VALUES (".implode(",", $vals).")";
			}

			return $inserts;
		}

		/*
			Function: tableExists
				Determines whether a SQL table exists.

			Parameters:
				table - The table name.

			Returns:
				true if table exists, otherwise false.
		*/

		public static function tableExists($table) {
			$r = sqlrows(sqlquery("SHOW TABLES LIKE '".sqlescape($table)."'"));
			if ($r) {
				return true;
			}

			return false;
		}

		/*
			Function: touchFile
				touch()s a file even if the directory for it doesn't exist yet.

			Parameters:
				file - The file path to touch.
		*/

		public static function touchFile($file) {
			if (!static::isDirectoryWritable($file)) {
				return false;
			}

			$pathinfo = static::pathInfo($file);
			static::makeDirectory($pathinfo["dirname"]);

			touch($file);
			static::setPermissions($file);

			return true;
		}

		/*
			Function: translateArray
				Steps through an array and creates internal page links for all parts of it.

			Parameters:
				array - The array to process.

			Returns:
				An array with internal page links encoded.

			See Also:
				<untranslateArray>
		*/

		public static function translateArray($array) {
			foreach ($array as &$piece) {
				if (is_null($piece)) {
					continue;
				}

				if (is_array($piece)) {
					$piece = static::translateArray($piece);
				} else {
					$piece = BigTreeAdmin::autoIPL($piece);
				}
			}

			return $array;
		}

		/*
			Function: trimLength
				A smarter version of trim that works with HTML.

			Parameters:
				string - A string of text or HTML.
				length - The number of characters to trim to.

			Returns:
				A string trimmed to the proper number of characters.
		*/

		public static function trimLength($string, $length) {
			if (!is_string($string)) {
				return "";
			}

			$ns = "";
			$opentags = [];
			$string = trim($string);
			if (strlen(html_entity_decode(strip_tags($string))) < $length) {
				return $string;
			}
			if (strpos($string, " ") === false && strlen(html_entity_decode(strip_tags($string))) > $length) {
				return substr($string, 0, $length)."&hellip;";
			}
			$x = 0;
			$z = 0;
			while ($z < $length && $x <= strlen($string)) {
				$char = substr($string, $x, 1);
				$ns .= $char;        // Add the character to the new string.
				if ($char == "<") {
					// Get the full tag -- but compensate for bad html to prevent endless loops.
					$tag = "";
					while ($char != ">" && $char !== false) {
						$x++;
						$char = substr($string, $x, 1);
						$tag .= $char;
					}
					$ns .= $tag;

					$tagexp = explode(" ", trim($tag));
					$tagname = str_replace(">", "", $tagexp[0]);

					// Prevent warnings
					if (empty($tagexp[1])) {
						$tagexp[1] = "";
					}

					// If it's a self contained <br /> tag or similar, don't add it to open tags.
					if ($tagexp[1] != "/" && $tagexp[1] != "/>") {
						// See if we're opening or closing a tag.
						if (substr($tagname, 0, 1) == "/") {
							$tagname = str_replace("/", "", $tagname);
							// We're closing the tag. Kill the most recently opened aspect of the tag.
							$done = false;
							reset($opentags);
							while (current($opentags) && !$done) {
								if (current($opentags) == $tagname) {
									unset($opentags[key($opentags)]);
									$done = true;
								}
								next($opentags);
							}
						} else {
							// Open a new tag.
							$opentags[] = $tagname;
						}
					}
				} elseif ($char == "&") {
					$entity = "";
					while ($char != ";" && $char != " " && $char != "<") {
						$x++;
						$char = substr($string, $x, 1);
						$entity .= $char;
					}
					if ($char == ";") {
						$z++;
						$ns .= $entity;
					} elseif ($char == " ") {
						$z += strlen($entity);
						$ns .= $entity;
					} else {
						$z += strlen($entity);
						$ns .= substr($entity, 0, -1);
						$x -= 2;
					}
				} else {
					$z++;
				}
				$x++;
			}
			while ($x < strlen($string) && !in_array(substr($string, $x, 1), [" ", "!", ".", ",", "<", "&"])) {
				$ns .= substr($string, $x, 1);
				$x++;
			}
			if (strlen(strip_tags($ns)) < strlen(strip_tags($string))) {
				$ns .= "&hellip;";
			}
			$opentags = array_reverse($opentags);
			foreach ($opentags as $key => $val) {
				$ns .= "</".$val.">";
			}

			return $ns;
		}

		/*
			Function: unformatBytes
				Formats a string of kilobytes / megabytes / gigabytes back into bytes.

			Parameters:
				size - The string of (kilo/mega/giga)bytes.

			Returns:
				The number of bytes.
		*/

		public static function unformatBytes($size) {
			$type = substr($size, -1, 1);
			$num = substr($size, 0, -1);
			if ($type == "M") {
				return $num * 1048576;
			} elseif ($type == "K") {
				return $num * 1024;
			} elseif ($type == "G") {
				return ($num * 1024 * 1024 * 1024);
			}

			return 0;
		}

		/*
			Function: untranslateArray
				Steps through an array and creates hard links for all internal page links.

			Parameters:
				array - The array to process.

			Returns:
				An array with internal page links decoded.

			See Also:
				<translateArray>
		*/

		public static function untranslateArray($array) {
			if (!is_array($array)) {
				return [];
			}

			foreach ($array as &$piece) {
				if (!is_null($piece)) {
					if (is_array($piece)) {
						$piece = static::untranslateArray($piece);
					} elseif (is_string($piece)) {
						$piece = BigTreeCMS::replaceInternalPageLinks($piece);
					}
				}
			}

			return $array;
		}

		/*
			Function: unzip
				Unzips a file.

			Parameters:
				file - Location of the file to unzip
				destination - The full path to unzip the file's contents to.
		*/

		public static function unzip($file, $destination) {
			// If we can't write the output directory, we're not getting anywhere.
			if (!BigTree::isDirectoryWritable($destination)) {
				return false;
			}

			// Up the memory limit for the unzip.
			ini_set("memory_limit", "512M");

			$destination = rtrim($destination)."/";
			BigTree::makeDirectory($destination);

			// If we have the built in ZipArchive extension, use that.
			if (class_exists("ZipArchive")) {
				$z = new ZipArchive;

				if (!$z->open($file)) {
					// Bad zip file.
					return false;
				}

				for ($i = 0; $i < $z->numFiles; $i++) {
					if (!$info = $z->statIndex($i)) {
						// Unzipping the file failed for some reason.
						return false;
					}

					// If it's a directory, ignore it. We'll create them in putFile.
					if (substr($info["name"], -1) == "/") {
						continue;
					}

					// Ignore __MACOSX and all it's files.
					if (substr($info["name"], 0, 9) == "__MACOSX/") {
						continue;
					}

					$content = $z->getFromIndex($i);
					if ($content === false) {
						// File extraction failed.
						return false;
					}
					BigTree::putFile($destination.$file["name"], $content);
				}

				$z->close();

				return true;

				// Fall back on PclZip if we don't have the "native" version.
			} else {
				// WordPress claims this could be an issue, so we'll make sure multibyte encoding isn't overloaded.
				if (ini_get('mbstring.func_overload') && function_exists('mb_internal_encoding')) {
					$previous_encoding = mb_internal_encoding();
					mb_internal_encoding('ISO-8859-1');
				}

				$z = new PclZip($file);
				$archive = $z->extract(PCLZIP_OPT_EXTRACT_AS_STRING);

				// If we saved a previous encoding, reset it now.
				if (isset($previous_encoding)) {
					mb_internal_encoding($previous_encoding);
					unset($previous_encoding);
				}

				// If it's not an array, it's not a good zip. Also, if it's empty it's not a good zip.
				if (!is_array($archive) || !count($archive)) {
					return false;
				}

				foreach ($archive as $item) {
					// If it's a directory, ignore it. We'll create them in putFile.
					if ($item["folder"]) {
						continue;
					}

					// Ignore __MACOSX and all it's files.
					if (substr($item["filename"], 0, 9) == "__MACOSX/") {
						continue;
					}

					BigTree::putFile($destination.$item["filename"], $item["content"]);
				}

				return true;
			}
		}

		/*
			Function: uploadMaxFileSize
				Returns Apache's max file size value for use in forms.

			Returns:
				The integer value for setting a form's MAX_FILE_SIZE.
		*/

		public static function uploadMaxFileSize() {
			$upload_max_filesize = ini_get("upload_max_filesize");
			if (!is_integer($upload_max_filesize)) {
				$upload_max_filesize = static::unformatBytes($upload_max_filesize);
			}

			$post_max_size = static::postMaxSize();
			if ($post_max_size < $upload_max_filesize) {
				$upload_max_filesize = $post_max_size;
			}

			return $upload_max_filesize;
		}

		/*
			Function: urlExists
				Attempts to connect to a URL using cURL.

			Parameters:
				url - The URL to connect to.

			Returns:
				true if it can connect, false if connection failed.
		*/

		public static function urlExists($url) {
			// Handle // urls as http://
			if (substr($url,0,2) == "//") {
				$url = "http:".$url;
			}

			// Strip out any hash
			[$url] = explode("#", $url);

			$handle = curl_init($url);

			if ($handle === false) {
				return false;
			}

			// Ignore HTTPS rules when just checking if it exists
			curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

			// Limit the request to 5 seconds
			curl_setopt($handle, CURLOPT_TIMEOUT, 5);

			// We want just the header (NOBODY sets it to a HEAD request)
			curl_setopt($handle, CURLOPT_HEADER, true);
			curl_setopt($handle, CURLOPT_NOBODY, true);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

			// Fail on error should make it so response codes > 400 result in a fail
			curl_setopt($handle, CURLOPT_FAILONERROR, true);

			// Request as Firefox so that servers don't reject us for not having headers.
			curl_setopt($handle, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36"));

			// Execute the request and close the handle
			$success = curl_exec($handle) ? true : false;
			curl_close($handle);

			return $success;
		}

		// These functions remain in BigTree for backwards compatibility

		public static function centerCrop($file, $newfile, $cw, $ch, $retina = false, $grayscale = false) {
			$image = new BigTreeImage($file);

			return $image->centerCrop($newfile, $cw, $ch, $retina, $grayscale);
		}

		public static function createCrop($file, $new_file, $x, $y, $target_width, $target_height, $width, $height, $retina = false, $grayscale = false) {
			$image = new BigTreeImage($file);

			return $image->crop($new_file, $x, $y, $target_width, $target_height, $width, $height, $retina, $grayscale);
		}

		public static function createThumbnail($file, $new_file, $maxwidth, $maxheight, $retina = false, $grayscale = false, $upscale = false) {
			$image = new BigTreeImage($file);

			$image->thumbnail($new_file, $maxwidth, $maxheight, $retina, $grayscale, $upscale);
		}

		public static function createUpscaledImage($file, $new_file, $min_width, $min_height) {
			$image = new BigTreeImage($file);

			$image->upscale($new_file, $min_width, $min_height);
		}

		public static function getThumbnailSizes($file, $min_width, $min_height) {
			$image = new BigTreeImage($file);
			$sizes = $image->getThumbnailSize($min_width, $min_height);

			return [$image->Type, $image->Width, $image->Height, $sizes["width"], $sizes["height"]];
		}

		public static function getUpscaleSizes($file, $min_width, $min_height) {
			$image = new BigTreeImage($file);
			$sizes = $image->getUpscaleSize($min_width, $min_height);

			return [$image->Type, $image->Width, $image->Height, $sizes["width"], $sizes["height"]];
		}

		public static function imageManipulationMemoryAvailable($source, $width, $height) {
			$image = new BigTreeImage($source);

			return $image->checkMemory($width, $height);
		}

		public static function restoreMemoryLimit() {
			BigTreeImage::restoreMemoryLimit();
		}

		public static function setImageMemoryLimit() {
			BigTreeImage::setMemoryLimit();
		}

	}

	// For servers that don't have multibyte string extensions…
	if (!function_exists("mb_strlen")) {
		function mb_strlen($string) {
			return strlen($string);
		}
	}

	if (!function_exists("mb_strtolower")) {
		function mb_strtolower($string) {
			return strtolower($string);
		}
	}
