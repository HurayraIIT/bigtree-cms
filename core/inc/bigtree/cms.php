<?php
	/*
		Class: BigTreeCMS
			The primary interface to BigTree that is used by the front end of the site for pulling settings, navigation, and page content.
	*/

	class BigTreeCMSBase {

		public $AutoSaveSettings = []; // Deprecated
		public $ModuleClassList = [];

		public static $BreadcrumbTrunk;
		public static $IRLCache = [];
		public static $IPLCache = [];
		public static $MySQLTime = false;
		public static $ReplaceableRoots = [];
		public static $ReplaceableRootTokens = [];
		public static $Secure;
		public static $SiteRoots = [];

		protected static $HeadContext;

		/*
			Constructor:
				Builds a flat file module class list so that module classes can be autoloaded instead of always in memory.
		*/

		public function __construct() {
			global $bigtree;

			// If the cache exists, just use it.
			if (file_exists(SERVER_ROOT."cache/bigtree-module-class-list.json")) {
				$items = json_decode(file_get_contents(SERVER_ROOT."cache/bigtree-module-class-list.json"),true);
			} else {
				// Get the Module Class List
				$modules = BigTreeJSONDB::getAll("modules");
				$items = [];

				foreach ($modules as $module) {
					$items[$module["class"]] = $module["route"];
				}

				// Cache it so we don't hit the database.
				BigTree::putFile(SERVER_ROOT."cache/bigtree-module-class-list.json",BigTree::json($items));
			}

			$this->ModuleClassList = $items;

			// Find root paths for all sites to include in URLs if we're in a multi-site environment
			if (defined("BIGTREE_SITE_KEY") || count($bigtree["config"]["sites"])) {
				$cache_location = SERVER_ROOT."cache/bigtree-multi-site-cache.json";

				if (!file_exists($cache_location)) {
					foreach ($bigtree["config"]["sites"] as $site_key => $site_data) {
						$page = sqlfetch(sqlquery("SELECT path FROM bigtree_pages WHERE id = '".intval($site_data["trunk"])."'"));
						$site_data["key"] = $site_key;
						static::$SiteRoots[$page["path"]] = $site_data;
					}

					// We want the primary domain (at root 0) last as well as longer routes first
					ksort(static::$SiteRoots);
					static::$SiteRoots = array_reverse(static::$SiteRoots);

					file_put_contents($cache_location, BigTree::json(static::$SiteRoots));
				} else {
					static::$SiteRoots = json_decode(file_get_contents($cache_location), true);
				}

				foreach (static::$SiteRoots as $site_path => $site_data) {
					if ($site_data["trunk"] == BIGTREE_SITE_TRUNK) {
						define("BIGTREE_SITE_PATH", $site_path);
					}
				}
			}
		}

		/*
			Destructor:
				Saves settings back to the database that were instantiated through the autoSaveSetting method.
				The autoSaveSetting feature is deprecated.
		*/

		public function __destruct() {
			foreach ($this->AutoSaveSettings as $id => $obj) {
				if (is_object($obj)) {
					BigTreeAdmin::updateInternalSettingValue($id, get_object_vars($obj), true);
				} else {
					BigTreeAdmin::updateInternalSettingValue($id, $obj, true);
				}
			}
		}

		// Deprecated
		public function &autoSaveSetting($id,$return_object = true) {
			$id = static::extensionSettingCheck($id);

			// Only want one usage to exist
			if (!isset($this->AutoSaveSettings[$id])) {
				$data = $this->getSetting($id);

				// Create a setting if it doesn't exist yet
				if ($data === false) {
					$data = [];
				}

				// Asking for an object? Return it as an object
				if ($return_object) {
					$obj = new stdClass;
					if (is_array($data)) {
						foreach ($data as $key => $val) {
							$obj->$key = $val;
						}
					}
					$this->AutoSaveSettings[$id] = $obj;
				// Otherwise return an array
				} else {
					$this->AutoSaveSettings[$id] = $data;
				}
			}

			// Already exists, return it
			return $this->AutoSaveSettings[$id];
		}

		/*
			Function: cacheDelete
				Deletes data from BigTree's cache table.

			Parameters:
				identifier - Uniquid identifier for your data type (i.e. org.bigtreecms.geocoding)
				key - The key for your data (if no key is passed, deletes all data for a given identifier)
		*/

		public static function cacheDelete($identifier,$key = false) {
			$identifier = sqlescape($identifier);

			if ($key === false) {
				sqlquery("DELETE FROM bigtree_caches WHERE `identifier` = '$identifier'");
			} else {
				sqlquery("DELETE FROM bigtree_caches WHERE `identifier` = '$identifier' AND `key` = '".sqlescape($key)."'");
			}
		}

		/*
			Function: cacheGet
				Retrieves data from BigTree's cache table.

			Parameters:
				identifier - Uniquid identifier for your data type (i.e. org.bigtreecms.geocoding)
				key - The key for your data.
				max_age - The maximum age (in seconds) for the data, defaults to any age.
				decode - Decode JSON (defaults to true, specify false to return JSON)

			Returns:
				Data from the table (json decoded, objects convert to keyed arrays) if it exists or false.
		*/

		public static function cacheGet($identifier,$key,$max_age = false,$decode = true) {
			$identifier = sqlescape($identifier);
			$key = sqlescape($key);

			if ($max_age) {
				// We need to get MySQL's idea of what time it is so that if PHP's differs we don't screw up caches.
				if (!static::$MySQLTime) {
					$t = sqlfetch(sqlquery("SELECT NOW() as `time`"));
					static::$MySQLTime = $t["time"];
				}
				$max_age = date("Y-m-d H:i:s",strtotime(static::$MySQLTime) - $max_age);

				$f = sqlfetch(sqlquery("SELECT * FROM bigtree_caches WHERE `identifier` = '$identifier' AND `key` = '$key' AND timestamp >= '$max_age'"));
			} else {
				$f = sqlfetch(sqlquery("SELECT * FROM bigtree_caches WHERE `identifier` = '$identifier' AND `key` = '$key'"));
			}

			if (!$f) {
				return false;
			}

			if ($decode) {
				return json_decode($f["value"],true);
			} else {
				return $f["value"];
			}
		}

		/*
			Function: cachePut
				Puts data into BigTree's cache table.

			Parameters:
				identifier - Uniquid identifier for your data type (i.e. org.bigtreecms.geocoding)
				key - The key for your data.
				value - The data to store.
				replace - Whether to replace an existing value (defaults to true).

			Returns:
				True if successful, false if the indentifier/key combination already exists and replace was set to false.
		*/

		public static function cachePut($identifier,$key,$value,$replace = true) {
			$identifier = sqlescape($identifier);
			$key = sqlescape($key);
			$f = sqlfetch(sqlquery("SELECT `key` FROM bigtree_caches WHERE `identifier` = '$identifier' AND `key` = '$key'"));
			if ($f && !$replace) {
				return false;
			}

			$value = BigTree::json($value,true);

			if ($f) {
				sqlquery("UPDATE bigtree_caches SET `value` = '$value', `timestamp` = NOW() WHERE `identifier` = '$identifier' AND `key` = '$key'");
			} else {
				sqlquery("INSERT INTO bigtree_caches (`identifier`,`key`,`value`) VALUES ('$identifier','$key','$value')");
			}
			return true;
		}

		/*
			Function: cacheUnique
				Puts data into BigTree's cache table with a random unqiue key and returns the key.

			Parameters:
				identifier - Uniquid identifier for your data type (i.e. org.bigtreecms.geocoding)
				value - The data to store

			Returns:
				They unique cache key.
		*/

		public static function cacheUnique($identifier,$value) {
			$success = false;
			while (!$success) {
				$key = uniqid("",true);
				$success = static::cachePut($identifier,$key,$value,false);
			}
			return $key;
		}

		/*
			Function: catch404
				Manually catch and display the 404 page from a routed template; logs missing page with handle404
		*/

		public static function catch404() {
			global $admin,$bigtree,$cms;

			static::checkOldRoutes($bigtree["path"]);

			ob_clean();

			if (static::handle404($_GET["bigtree_htaccess_url"] ?? "")) {
				$bigtree["layout"] = "default";
				ob_start();
				include SERVER_ROOT."templates/basic/_404.php";
				$bigtree["content"] = ob_get_clean();
				ob_start();
				include "../templates/layouts/".$bigtree["layout"].".php";
				die();
			}
		}

		/*
			Function: checkOldRoutes
				Checks the old route table, redirects if the page is found.

			Parameters:
				path - An array of routes
		*/

		public static function checkOldRoutes($path) {
			global $bigtree;

			// Add multi-site path
			if (defined("BIGTREE_SITE_PATH")) {
				$path = array_filter(array_merge(explode("/", BIGTREE_SITE_PATH), $path));
			}

			$route = false;
			$additional_commands = "";
			$x = count($path);

			while ($x) {
				$route = SQL::fetchSingle("SELECT new_route FROM bigtree_route_history WHERE old_route = ?", implode("/", array_slice($path, 0, $x)));

				if ($route) {
					if ($x < count($path)) {
						$additional_commands = implode("/", array_slice($path, $x));
					}

					break;
				}

				$x--;
			}

			// If it's in the old routing table, send them to the new page.
			if ($route) {
				$page_data = SQL::fetch("SELECT id, template FROM bigtree_pages WHERE path = ?", $route);

				// If this page was moved multiple times, it could have more than one entry in the route history
				while ($route && !$page_data) {
					$route = SQL::fetchSingle("SELECT new_route FROM bigtree_route_history WHERE old_route = ?", $route);

					if ($route) {
						$page_data = SQL::fetch("SELECT id, template FROM bigtree_pages WHERE path = ?", $route);
					}
				}

				if ($page_data) {
					$redirect_url = static::getLink($page_data["id"]);

					if ($additional_commands) {
						$template = BigTreeJSONDB::get("templates", $page_data["template"]);

						// Additional commands to a non-routed template is a 404, we shouldn't try to append them
						if (empty($template["routed"])) {
							return;
						}

						$redirect_url = rtrim($redirect_url, "/")."/".$additional_commands;

						if (empty($bigtree["config"]["trailing_slash_behavior"]) || $bigtree["config"]["trailing_slash_behavior"] != "remove") {
							$redirect_url .= "/";
						}
					}

					BigTree::redirect($redirect_url, "301");
				}
			}
		}

		/*
			Function: decodeResources
				Turns the JSON resources data into a PHP array of resources with links being translated into front-end readable links.
				This function is called by BigTree's router and is generally not a function needed to end users.

			Parameters:
				data - JSON encoded callout data.

			Returns:
				An array of resources.
		*/

		public static function decodeResources($data) {
			if (!is_array($data)) {
				$data = json_decode($data,true);
			}
			if (is_array($data)) {
				foreach ($data as $key => $val) {
					if (is_array($val)) {
						// If this value is an array, untranslate it so that {wwwroot} and ipls get fixed.
						$val = BigTree::untranslateArray($val);
					} elseif (is_array(json_decode($val,true))) {
						// If this value is an array, untranslate it so that {wwwroot} and ipls get fixed.
						$val = BigTree::untranslateArray(json_decode($val,true));
					} else {
						// Otherwise it's a string, just replace the {wwwroot} and ipls.
						$val = static::replaceInternalPageLinks($val);
					}
					$data[$key] = $val;
				}
			}
			return $data;
		}

		/*
			Function: drawHeadTags
				Draws the <title>, meta description, and open graph tags for the given context.
				The context defaults to the current page and can be changed via BigTreeCMS::setHeadContext

			Parameters:
				site_title - A site title that draws after the page title if entered, also used for og:site_name
				divider - The divider between the page title and site title, defaults to |
		*/

		public static function drawHeadTags($site_title = "", $divider = "|") {
			global $bigtree;

			$context = static::$HeadContext;
			$image_width = 0;
			$image_height = 0;

			if (empty($context)) {
				$og = $bigtree["page"]["open_graph"] ?? [];
				$title = $bigtree["page"]["title"] ?? "Page Not Found";
				$og_title = !empty($og["title"]) ? $og["title"] : $bigtree["page"]["title"] ?? "";
				$description = !empty($bigtree["page"]["meta_description"]) ? $bigtree["page"]["meta_description"] : $og["description"] ?? "";
				$og_description = !empty($og["description"]) ? $og["description"] : $bigtree["page"]["meta_description"] ?? "";
				$image = $og["image"] ?? "";
				$image_width = $og["image_width"] ?? null;
				$image_height = $og["image_height"] ?? null;
				$type = !empty($og["type"]) ? $og["type"] : "website";
			} else {
				$og = static::getOpenGraph($context["table"], $context["entry"]) ?: $bigtree["page"]["open_graph"];

				if (!empty($og["title"])) {
					$title = $og["title"];
				} elseif (!empty($context["title"])) {
					$title = $context["title"];
				} else {
					$title = $bigtree["page"]["title"];
				}

				$og_title = $title;

				if (!empty($og["description"])) {
					$description = $og["description"];
				} elseif (!empty($context["description"])) {
					$description = $context["description"];
				} else {
					$description = $bigtree["page"]["meta_description"];
				}

				$og_description = $description;

				if (!empty($og["type"])) {
					$type = $og["type"];
				} elseif (!empty($context["type"])) {
					$type = $context["type"];
				} else {
					$type = "website";
				}

				if (!empty($og["image"])) {
					$image = $og["image"];
					$image_width = $og["image_width"] ?? "";
					$image_height = $og["image_height"] ?? "";
				} elseif (!empty($context["image"])) {
					$image = $context["image"];
				} else {
					$image = $bigtree["page"]["open_graph"]["image"] ?? "";
					$image_width = $bigtree["page"]["open_graph"]["image_width"] ?? "";
					$image_height = $bigtree["page"]["open_graph"]["image_height"] ?? "";
				}
			}

			if (empty($title) && defined("BIGTREE_URL_IS_404")) {
				$title = "404";
			}

			if ($site_title && (defined("BIGTREE_URL_IS_404") || !empty($bigtree["page"]["id"]))) {
				$title .= BigTree::safeEncode(" $divider $site_title");
			}

			echo "<title>$title</title>\n";
			echo '		<meta name="description" content="'.$description.'" />'."\n";
			echo '		<meta property="og:title" content="'.$og_title.'" />'."\n";
			echo '		<meta property="og:description" content="'.$og_description.'" />'."\n";
			echo '		<meta property="og:type" content="'.$type.'" />'."\n";

			if ($site_title) {
				echo '		<meta property="og:site_name" content="'.BigTree::safeEncode($site_title).'" />'."\n";
			}

			if ($image) {
				if (substr($image, 0, 2) == "//") {
					$image = "https:".$image;
				}

				echo '		<meta property="og:image" content="'.$image.'" />'."\n";

				// Only get image dimensions if the image is local
				if (!$image_width && strpos($image, WWW_ROOT) === 0) {
					[$image_width, $image_height] = getimagesize(str_replace(WWW_ROOT, SITE_ROOT, $image));
				}

				if ($image_width && $image_height) {
					echo '		<meta property="og:image:width" content="'.intval($image_width).'" />'."\n";
					echo '		<meta property="og:image:height" content="'.intval($image_height).'" />'."\n";
				}
			}
		}

		/*
			Function: drawXMLSitemap
				Outputs an XML sitemap.
		*/

		public static function drawXMLSitemap() {
			include BigTree::path("inc/bigtree/sitemap.php");

			header("Content-type: text/xml");

			// Send file content if sitemap.xml is already generated and saved.
			$filename = SERVER_ROOT.BigTreeSitemapGenerator::FILE_PATH;

			if (file_exists($filename) && filemtime($filename) > time() - 24 * 60 * 60) {
				$fp = fopen($filename, 'rb');
				fpassthru($fp);

				die();
			}

			// As fallback, we should generate sitemap on the fly.
			$sitemap = new BigTreeSitemapGenerator;
			$xml = $sitemap->generateSitemap();
			$sitemap->saveFile($xml);
			echo $xml;

			die();
		}

		/*
			Function: extensionSettingCheck
				Checks to see if we're in an extension and if we're requesting a setting attached to it.
				For example, if "test-setting" is requested and "com.test.extension*test-setting" exists it will be used.

			Parameters:
				id - Setting id

			Returns:
				An extension setting ID if one is found.
		*/

		public static function extensionSettingCheck($id) {
			global $bigtree;

			// See if we're in an extension
			if (!empty($bigtree["extension_context"])) {
				$extension = $bigtree["extension_context"];

				// If we're already asking for it by it's namespaced name, don't append again.
				if (substr($id, 0, strlen($extension)) == $extension) {
					return $id;
				}

				// See if namespaced version exists
				$exists = BigTreeJSONDB::exists("settings", $extension."*".$id) || SQL::exists("bigtree_settings", $extension."*".$id);

				if ($exists) {
					return "$extension*$id";
				}
			}

			return $id;
		}

		/*
			Function: generateReplaceableRoots
				Caches a list of tokens and the values that are related to them.
		*/

		public static function generateReplaceableRoots() {
			global $bigtree;

			$valid_root = function($root) {
				return (substr($root, 0, 7) == "http://" || substr($root, 0, 8) == "https://" || substr($root, 0, 2) == "//");
			};

			// Figure out what roots we can replace
			if (!count(static::$ReplaceableRootTokens)) {
				if ($valid_root(ADMIN_ROOT)) {
					static::$ReplaceableRootTokens["{adminroot}"] = ADMIN_ROOT;
					static::$ReplaceableRoots[ADMIN_ROOT] = "{adminroot}";
				}

				foreach ($bigtree["config"]["sites"] as $site_key => $site_configuration) {
					if ($valid_root($site_configuration["static_root"])) {
						// For backwards compatibility we add this in as decodeable even if it matches www_root
						static::$ReplaceableRootTokens["{staticroot:$site_key}"] = $site_configuration["static_root"];

						if ($site_configuration["static_root"] != $site_configuration["www_root"]) {
							static::$ReplaceableRoots[$site_configuration["static_root"]] = "{staticroot:$site_key}";
						}
					}

					if ($valid_root($site_configuration["www_root"])) {
						static::$ReplaceableRootTokens["{wwwroot:$site_key}"] = $site_configuration["www_root"];
						static::$ReplaceableRoots[$site_configuration["www_root"]] = "{wwwroot:$site_key}";
					}
				}

				if ($valid_root(STATIC_ROOT)) {
					static::$ReplaceableRootTokens["{staticroot}"] = STATIC_ROOT;

					if (!isset(static::$ReplaceableRoots[STATIC_ROOT])) {
						static::$ReplaceableRoots[STATIC_ROOT] = "{staticroot}";
					}
				}

				if ($valid_root(WWW_ROOT)) {
					static::$ReplaceableRootTokens["{wwwroot}"] = WWW_ROOT;

					if (!isset(static::$ReplaceableRoots[WWW_ROOT])) {
						static::$ReplaceableRoots[WWW_ROOT] = "{wwwroot}";
					}
				}
			}
		}

		/*
			Function: getBreadcrumb
				Returns an array of titles, links, and ids for pages above and including the current page.

			Parameters:
				ignore_trunk - Ignores trunk settings when returning the breadcrumb

			Returns:
				An array of arrays with "title", "link", and "id" of each of the pages above the current (or passed in) page.

			See Also:
				<getBreadcrumbByPage>
		*/

		public static function getBreadcrumb($ignore_trunk = false) {
			global $bigtree;
			return static::getBreadcrumbByPage($bigtree["page"],$ignore_trunk);
		}

		/*
			Function: getBreadcrumbByPage
				Returns an array of titles, links, and ids for the pages above and including the given page.

			Parameters:
				page - A page array (containing at least the "path" from the database) *(optional)*
				ignore_trunk - Ignores trunk settings when returning the breadcrumb

			Returns:
				An array of arrays with "title", "link", and "id" of each of the pages above the current (or passed in) page.
				If a trunk is hit, BigTreeCMS::$BreadcrumbTrunk is set to the trunk.

			See Also:
				<getBreadcrumb>
		*/

		public static function getBreadcrumbByPage($page,$ignore_trunk = false) {
			if (empty($page) || !isset($page["path"])) {
				return [];
			}

			$bc = [];

			if (!empty($page["changes_applied"])) {
				$parent = $page["parent"];

				while ($parent > 0) {
					$parent_page = SQL::fetch("SELECT id, nav_title, path, parent FROM bigtree_pages WHERE id = ?", $parent);

					if ($parent_page) {
						$bc[] = [
							"title" => $parent_page["nav_title"],
							"link" => static::linkForPath($parent_page["path"]),
							"id" => $parent_page["id"]
						];
					} else {
						break;
					}

					$parent = $parent_page["parent"];
				}

				$bc = array_reverse($bc);
				$bc[] = [
					"title" => $page["nav_title"],
					"link" => "",
					"id" => $page["id"]
				];

				return $bc;
			} else {
				// Break up the pieces so we can get each piece of the path individually and pull all the pages above this one.
				$pieces = explode("/", $page["path"]);
				$paths = [];
				$path = "";

				foreach ($pieces as $piece) {
					$path = $path.$piece."/";
					$paths[] = "path = '".sqlescape(trim($path, "/"))."'";
				}

				// Get all the ancestors, ordered by the page length so we get the latest first and can count backwards to the trunk.
				$q = sqlquery("SELECT id,nav_title,path,trunk FROM bigtree_pages WHERE (".implode(" OR ", $paths).") ORDER BY LENGTH(path) DESC");
			}

			$trunk_hit = false;

			while ($f = sqlfetch($q)) {
				// In case we want to know what the trunk is.
				if ($f["trunk"] || $f["id"] == BIGTREE_SITE_TRUNK) {
					$trunk_hit = true;
					static::$BreadcrumbTrunk = $f;
				}

				if (!$trunk_hit || $ignore_trunk) {
					$bc[] = array(
						"title" => stripslashes($f["nav_title"]),
						"link" => static::linkForPath($f["path"]),
						"id" => $f["id"]
					);
				}
			}

			$bc = array_reverse($bc);

			// Check for module breadcrumbs
			$template = BigTreeJSONDB::get("templates", $page["template"]);

			if (!empty($template["module"])) {
				$module = BigTreeJSONDB::get("modules", $template["module"]);

				if (!empty($module["class"])) {
					if (class_exists($module["class"])) {
						$moduleClass = new $module["class"];

						if (method_exists($moduleClass, "getBreadcrumb")) {
							$bc = array_merge($bc, $moduleClass->getBreadcrumb($page));
						}
					}
				}
			}

			return $bc;
		}

		/*
			Function: getFeed
				Gets a feed's information from the database.

			Parameters:
				id - A feed ID

			Returns:
				An array of feed information.

			See Also:
				<getFeedByRoute>
		*/

		public static function getFeed($id) {
			if (is_array($id)) {
				$id = $id["id"];
			}

			$feed = BigTreeJSONDB::get("feeds", $id);
			$feed["options"] = &$feed["settings"]; // Backwards compatibility

			return $feed;
		}

		/*
			Function: getFeedByRoute
				Gets a feed's information from the database

			Parameters:
				route - The route of the feed to pull.

			Returns:
				An array of feed information with settings and fields decoded from JSON.

			See Also:
				<getFeed>
		*/

		public static function getFeedByRoute($route) {
			$feed = BigTreeJSONDB::get("feeds", $route, "route");
			$feed["options"] = &$feed["settings"]; // Backwards compatibility

			return $feed;
		}

		/*
			Function: getHiddenNavByParent
				Returns an alphabetical list of pages that are not visible in navigation.

			Parameters:
				parent - The parent ID for which to pull child pages.

			Returns:
				An array of page entries from the database (without resources or callouts).

			See Also:
				<getNavByParent>
		*/

		public static function getHiddenNavByParent($parent = 0) {
			return static::getNavByParent($parent,1,false,true);
		}

		/*
			Function: getInternalPageLink
				Returns a hard link to the page's publicly accessible URL from its encoded soft link URL.

			Parameters:
				ipl - Internal Page Link (ipl://, irl://, {wwwroot}, or regular URL encoding)

			Returns:
				Public facing URL.
		*/

		public static function getInternalPageLink($ipl) {
			global $bigtree;

			// Regular links
			if (substr($ipl,0,6) != "ipl://" && substr($ipl,0,6) != "irl://") {
				return static::replaceRelativeRoots($ipl);
			}

			$ipl = explode("//",$ipl);
			$navid = $ipl[1];

			// Resource Links
			if ($ipl[0] == "irl:") {
				// See if it's in the cache.
				if (isset(static::$IRLCache[$navid])) {
					if (!empty($ipl[2])) {
						return BigTree::prefixFile(static::$IRLCache[$navid],$ipl[2]);
					} else {
						return static::$IRLCache[$navid];
					}
				} else {
					$r = sqlfetch(sqlquery("SELECT * FROM bigtree_resources WHERE id = '".sqlescape($navid)."'"));
					$file = $r ? static::replaceRelativeRoots($r["file"]) : false;
					static::$IRLCache[$navid] = $file;

					if (!empty($ipl[2])) {
						return BigTree::prefixFile($file,$ipl[2]);
					} else {
						return $file;
					}
				}
			}

			// New IPLs are encoded in JSON
			$command_parts = json_decode(base64_decode($ipl[2] ?? ""));
			$get_vars = base64_decode($ipl[3] ?? "");
			$hash = base64_decode($ipl[4] ?? "");

			// If it can't be rectified, we still don't want a warning.
			if (is_array($command_parts) && count($command_parts)) {
				$last = end($command_parts);
				$commands = implode("/", $command_parts);

				// If the URL's last piece has a GET (?), hash (#), or appears to be a file (.) don't add a trailing slash
				if ((empty($bigtree["config"]["trailing_slash_behavior"]) || $bigtree["config"]["trailing_slash_behavior"] != "remove") &&
					strpos($last,"#") === false && strpos($last,"?") === false && strpos($last,".") === false
				) {
					$commands .= "/";
				}
			} else {
				$commands = "";
			}

			// See if it's in the cache.
			if (!isset(static::$IPLCache[$navid])) {
				// Get the page's path
				$f = sqlfetch(sqlquery("SELECT path FROM bigtree_pages WHERE id = '".sqlescape($navid)."'"));

				if (is_null($f)) {
					return null;
				}

				// Set the cache
				static::$IPLCache[$navid] = rtrim(static::linkForPath($f["path"]), "/");
			}

			if (empty($bigtree["config"]["trailing_slash_behavior"]) ||
				$bigtree["config"]["trailing_slash_behavior"] != "remove" ||
				$commands != ""
			) {
				$url = static::$IPLCache[$navid]."/".$commands;
			} else {
				$url = static::$IPLCache[$navid];
			}

			if ($get_vars) {
				$url .= "?".$get_vars;
			}

			if ($hash) {
				$url .= "#".$hash;
			}

			return $url;
		}

		/*
			Function: getLink
				Returns the public link to a page in the database.

			Parameters:
				id - The ID of the page.

			Returns:
				Public facing URL.
		*/

		public static function getLink($id) {
			global $bigtree;

			// Homepage, just return the web root.
			if ($id == BIGTREE_SITE_TRUNK) {
				return WWW_ROOT;
			}

			// If someone is requesting the link of the page they're already on we don't need to request it from the database.
			if (!empty($bigtree["page"]["id"]) && $bigtree["page"]["id"] == $id) {
				return static::linkForPath($bigtree["page"]["path"]);
			} else {
				// Otherwise we'll grab the page path from the db.
				$page = sqlfetch(sqlquery("SELECT path, template, external FROM bigtree_pages WHERE id = '".sqlescape($id)."' AND archived != 'on'"));

				if ($page) {
					if ($page["external"] !== "" && $page["template"] === "") {
						if (substr($page["external"], 0, 6) == "ipl://" || substr($page["external"], 0, 6) == "irl://") {
							$page["external"] = static::getInternalPageLink($page["external"]);
						}

						return $page["external"];
					}

					return static::linkForPath($page["path"]);
				}
			}

			return false;
		}

		/*
			Function: getNavByParent
				Returns a multi-level navigation array of pages visible in navigation
				(or hidden, if $only_hidden is set to true)

			Parameters:
				parent - Either a single page ID or an array of page IDs -- the latter is used internally
				levels - The number of levels of navigation depth to recurse
				follow_module - Whether to pull module navigation or not
				only_hidden - Whether to pull visible (false) or hidden (true) pages
				explicit_zero - In a multi-site environment you must pass true for this parameter if you want root level children rather than the site-root level

			Returns:
				A multi-level navigation array containing "id", "parent", "title", "route", "link", "new_window", and "children"
		*/

		public static function getNavByParent($parent = 0, $levels = 1, $follow_module = true, $only_hidden = false, $explicit_zero = false) {
			global $bigtree;
			static $module_nav_count = 0;

			$nav = [];
			$find_children = [];

			// If we're asking for root (0) and in multi-site, use that site's root instead of the top-level root
			if (!$explicit_zero && $parent === 0 && BIGTREE_SITE_TRUNK !== 0) {
				$parent = BIGTREE_SITE_TRUNK;
			}

			// If the parent is an array, this is actually a recursed call.
			// We're finding all the children of all the parents at once -- then we'll assign them back to the proper parent instead of doing separate calls for each.
			if (is_array($parent)) {
				$where_parent = [];
				foreach ($parent as $p) {
					$where_parent[] = "parent = '".sqlescape($p)."'";
				}
				$where_parent = "(".implode(" OR ",$where_parent).")";
			// If it's an integer, let's just pull the children for the provided parent.
			} else {
				$parent = sqlescape($parent);
				$where_parent = "parent = '$parent'";
			}

			$in_nav = $only_hidden ? "" : "on";
			$sort = $only_hidden ? "nav_title ASC" : "position DESC, id ASC";

			$q = sqlquery("SELECT id,nav_title,parent,external,new_window,template,route,path
						   FROM bigtree_pages
						   WHERE $where_parent
							 AND in_nav = '$in_nav'
							 AND archived != 'on'
							 AND (publish_at <= NOW() OR publish_at IS NULL)
							 AND (expire_at >= NOW() OR expire_at IS NULL)
						   ORDER BY $sort");

			// Wrangle up some kids
			while ($f = sqlfetch($q)) {
				$link = static::linkForPath($f["path"]);
				$new_window = false;

				// If we're REALLY an external link we won't have a template, so let's get the real link and not the encoded version.  Then we'll see if we should open this thing in a new window.
				if ($f["external"] && $f["template"] == "") {
					$link = static::getInternalPageLink($f["external"]);
					if ($f["new_window"] == "Yes") {
						$new_window = true;
					}
				}

				// Add it to the nav array
				$nav[$f["id"]] = array("id" => $f["id"], "parent" => $f["parent"], "title" => $f["nav_title"], "route" => $f["route"], "link" => $link, "new_window" => $new_window, "children" => []);

				// If we're going any deeper, mark down that we're looking for kids of this kid.
				if ($levels > 1) {
					$find_children[] = $f["id"];
				}
			}

			// If we're looking for children, send them all back into getNavByParent, decrease the depth we're looking for by one.
			if (count($find_children)) {
				$subnav = static::getNavByParent($find_children,$levels - 1,$follow_module);
				foreach ($subnav as $item) {
					// Reassign these new children back to their parent node.
					$nav[$item["parent"]]["children"][$item["id"]] = $item;
				}
			}

			// If we're pulling in module navigation...
			if ($follow_module) {
				// This is a recursed iteration.
				if (is_array($parent)) {
					$where_parent = [];

					foreach ($parent as $p) {
						$where_parent[] = "id = '".sqlescape($p)."'";
					}

					$q = sqlquery("SELECT id, path, template FROM bigtree_pages WHERE (".implode(" OR ",$where_parent).")");

					while ($f = sqlfetch($q)) {
						$template = BigTreeJSONDB::get("templates", $f["template"]);

						if (!empty($template["module"])) {
							$module = BigTreeJSONDB::get("modules", $template["module"]);

							if (!empty($module["class"]) && class_exists($module["class"])) {
								$instance = new $module["class"];

								if (method_exists($instance, "getNav")) {
									$modNav = $instance->getNav($f);
									// Give the parent back to each of the items it returned so they can be reassigned to the proper parent.
									$module_nav = [];

									foreach ($modNav as $item) {
										$item["parent"] = $f["id"];
										$item["id"] = "module_nav_".$module_nav_count;
										$module_nav[] = $item;
										$module_nav_count++;
									}

									$nav_position = "bottom";

									if (property_exists($instance, "NavPosition")) {
										$nav_position = $instance->NavPosition ?? $module["class"]::$NavPosition;
									}

									if ($nav_position == "top") {
										$nav = array_merge($module_nav, $nav);
									} else {
										$nav = array_merge($nav, $module_nav);
									}
								}
							}
						}
					}
				// This is the first iteration.
				} else {
					$f = sqlfetch(sqlquery("SELECT id, path, template FROM bigtree_pages WHERE id = '$parent'"));

					if ($f) {
						$template = BigTreeJSONDB::get("templates", $f["template"]);

						if (!empty($template["module"])) {
							$module = BigTreeJSONDB::get("modules", $template["module"]);

							if (!empty($module["class"]) && class_exists($module["class"])) {
								$instance = new $module["class"];

								if (method_exists($instance, "getNav")) {
									$nav_position = "bottom";

									if (property_exists($instance, "NavPosition")) {
										$nav_position = $instance->NavPosition;
									} else if (property_exists($module["class"], "NavPosition")) {
										$nav_position = $module["class"]::$NavPosition;
									}

									if ($nav_position == "top") {
										$nav = array_merge($instance->getNav($f), $nav);
									} else {
										$nav = array_merge($nav, $instance->getNav($f));
									}
								}
							}
						}
					}
				}
			}

			return $nav;
		}

		/*
			Function: getNavId
				Provides the page ID for a given path array.
				This is a method used by the router and the admin and can generally be ignored.

			Parameters:
				path - An array of path elements from a URL
				previewing - Whether we are previewing or not.

			Returns:
				An array containing the page ID and any additional commands.
		*/

		public static function getNavId($path, $previewing = false) {
			$commands = [];

			// Add multi-site path
			if (defined("BIGTREE_SITE_PATH")) {
				$path = array_filter(array_merge(explode("/", BIGTREE_SITE_PATH), $path));
			}

			// Reset indexes
			$path = array_values($path);

			if (!$previewing) {
				$publish_at = "AND (publish_at <= NOW() OR publish_at IS NULL) AND (expire_at >= NOW() OR expire_at IS NULL)";
			} else {
				$publish_at = "";
			}

			// See if we have a straight up perfect match to the path.
			$page = SQL::fetch("SELECT id, template FROM bigtree_pages WHERE path = ? AND archived = '' $publish_at", implode("/", $path));

			if ($page) {
				$template = BigTreeJSONDB::get("templates", $page["template"]);

				return array($page["id"], $commands, $template["routed"] ?? false);
			}

			// Guess we don't, let's chop off commands until we find a page.
			$x = 0;
			while ($x < count($path)) {
				$x++;
				$commands[] = $path[count($path)-$x];

				// We have additional commands, so we're now making sure the template is also routed, otherwise it's a 404.
				$page = SQL::fetch("SELECT id, template FROM bigtree_pages WHERE path = ? AND archived = '' $publish_at", implode("/", array_slice($path, 0, -1 * $x)));

				if ($page) {
					$template = BigTreeJSONDB::get("templates", $page["template"]);

					if (!empty($template["routed"])) {
						return array($page["id"], array_reverse($commands), "on");
					}
				}
			}

			return array(false,false,false);
		}

		/*
		 	Function: getOpenGraph
		 		Returns Open Graph data for the specified table/id combination.

			Paremeters:
				table - The table for the entry
				id - The ID of the entry

			Returns:
				An array of Open Graph data.
		*/

		public static function getOpenGraph($table, $id) {
			try {
				$og = SQL::fetch("SELECT * FROM bigtree_open_graph WHERE `table` = ? AND `entry` = ?", $table, $id);
			} catch (Exception $e) {
				$og = null;
			}

			if (!$og) {
				return [
					"title" => "",
					"description" => "",
					"type" => "website",
					"image" => ""
				];
			}

			return BigTree::untranslateArray($og);
		}

		/*
			Function: getPage
				Returns a page along with its resources and callouts decoded.

			Parameters:
				id - The ID of the page.
				decode - Whether to decode resources and callouts or not and retrieve open graph info (setting to false saves processing time)

			Returns:
				A page array from the database.
		*/

		public static function getPage($id, $decode = true) {
			$page = SQL::fetch("SELECT * FROM bigtree_pages WHERE id = ?", $id);

			if (!$page) {
				return false;
			}

			if ($page["external"] && $page["template"] == "") {
				$page["external"] = static::getInternalPageLink($page["external"]);
			}

			if ($decode) {
				$page["open_graph"] = static::getOpenGraph("bigtree_pages", $id);
				$page["resources"] = static::decodeResources($page["resources"]);

				// Backwards compatibility with 4.0 callout system
				if (isset($page["resources"]["4.0-callouts"])) {
					$page["callouts"] = $page["resources"]["4.0-callouts"];
				} elseif (isset($f["resources"]["callouts"])) {
					$page["callouts"] = $page["resources"]["callouts"];
				} else {
					$page["callouts"] = [];
				}
			}

			return $page;
		}

		/*
			Function: getPendingPage
				Returns a page along with pending changes applied.

			Parameters:
				id - The ID of the page.
				decode - Whether to decode resources and callouts or not (setting to false saves processing time, defaults true).
				return_tags - Whether to return tags for the page (defaults false).

			Returns:
				A page array from the database.
		*/

		public static function getPendingPage($id, $decode = true, $return_tags = false) {
			// Numeric id means the page is live.
			if (is_numeric($id)) {
				$page = static::getPage($id);

				if (!$page) {
					return false;
				}

				// If we're looking for tags, apply them to the page.
				if ($return_tags) {
					$page["tags"] = static::getTagsForPage($id);
				}

				// Get pending changes for this page.
				$f = sqlfetch(sqlquery("SELECT * FROM bigtree_pending_changes WHERE `table` = 'bigtree_pages' AND item_id = '".$page["id"]."'"));

			// If it's prefixed with a "p" then it's a pending entry.
			} else {
				// Set the page to empty, we're going to loop through the change later and apply the fields.
				$page = [];

				// Get the changes.
				$f = sqlfetch(sqlquery("SELECT * FROM bigtree_pending_changes WHERE `id` = '".sqlescape(substr($id,1))."'"));

				if ($f) {
					$f["id"] = $id;
				} else {
					return false;
				}
			}

			// If we have changes, apply them.
			if ($f) {
				$page["changes_applied"] = true;
				$page["updated_at"] = $f["date"];
				$changes = json_decode($f["changes"],true);

				foreach ($changes as $key => $val) {
					if ($key == "external") {
						$val = static::getInternalPageLink($val);
					}

					$page[$key] = $val;
				}

				if ($return_tags) {
					// Decode the tag changes, apply them back.
					$tags = [];
					$tags_changes = json_decode($f["tags_changes"],true);

					if (is_array($tags_changes)) {
						foreach ($tags_changes as $tag_id) {
							$tag = sqlfetch(sqlquery("SELECT * FROM bigtree_tags WHERE id = '".intval($tag_id)."'"));

							if ($tag) {
								$tags[] = $tag;
							}
						}
					}

					$page["tags"] = $tags;
				}

				$page["open_graph"] = BigTree::untranslateArray(json_decode($f["open_graph_changes"], true));
			}

			// Turn resource entities into arrays that have been IPL decoded.
			if ($decode) {
				if (isset($page["resources"]) && is_array($page["resources"])) {
					$page["resources"] = static::decodeResources($page["resources"]);
				}

				// Backwards compatibility with 4.0 callout system
				if (isset($page["resources"]["4.0-callouts"])) {
					$page["callouts"] = $page["resources"]["4.0-callouts"];
				} elseif (isset($page["resources"]["callouts"])) {
					$page["callouts"] = $page["resources"]["callouts"];
				} else {
					$page["callouts"] = [];
				}
			}

			// Make sure the pending page gets it's ID
			if (!is_numeric($id)) {
				$page["id"] = $id;
			}

			return $page;
		}

		/*
			Function: getPreviewLink
				Returns a URL to where this page can be previewed.

			Parameters:
				id - The ID of the page (or pending page)

			Returns:
				A URL.
		*/

		public static function getPreviewLink($id) {
			global $bigtree;

			if (substr($id,0,1) == "p") {
				return WWW_ROOT."_preview-pending/$id/";
			} elseif ($id == 0) {
				return WWW_ROOT."_preview/";
			} else {
				$link = static::getLink($id);

				if (defined("BIGTREE_SITE_KEY") || count($bigtree["config"]["sites"])) {
					foreach (static::$SiteRoots as $site_path => $site_data) {
						if (strpos($link, $site_data["www_root"]) === 0) {
							return str_replace($site_data["www_root"], $site_data["www_root"]."_preview/", $link);
						}
					}
				}

				return str_replace(WWW_ROOT, WWW_ROOT."_preview/", $link);
			}
		}

		/*
			Function: getRelatedPagesByTags
				Returns pages related to the given set of tags.

			Parameters:
				tags - An array of tags to search for.

			Returns:
				An array of related pages sorted by relevance (how many tags get matched).
		*/

		public static function getRelatedPagesByTags($tags = []) {
			$results = [];
			$relevance = [];
			foreach ($tags as $tag) {
				if (is_array($tag)) {
					$tag = $tag["tag"];
				}
				$tdat = sqlfetch(sqlquery("SELECT * FROM bigtree_tags WHERE tag = '".sqlescape($tag)."'"));
				if ($tdat) {
					$q = sqlquery("SELECT * FROM bigtree_tags_rel WHERE tag = '".$tdat["id"]."' AND `table` = 'bigtree_pages'");
					while ($f = sqlfetch($q)) {
						$id = $f["entry"];
						if (in_array($id,$results)) {
							$relevance[$id]++;
						} else {
							$results[] = $id;
							$relevance[$id] = 1;
						}
					}
				}
			}
			array_multisort($relevance,SORT_DESC,$results);
			$items = [];
			foreach ($results as $result) {
				$items[] = static::getPage($result);
			}
			return $items;
		}

		/*
			Function: getResource
				Returns a resource.

			Parameters:
				id - The id of the resource.

			Returns:
				A resource entry.
		*/

		public static function getResource($id) {
			return BigTreeAdmin::getResource($id);
		}

		/*
			Function: getSetting
				Gets the value of a setting.

			Parameters:
				id - The ID of the setting.

			Returns:
				A string or array of the setting's value.
		*/

		public static function getSetting($id) {
			global $bigtree;

			$id = static::extensionSettingCheck($id);
			$setting = SQL::fetch("SELECT *, AES_DECRYPT(value, ?) AS `decrypted_value` FROM bigtree_settings WHERE id = ?", $bigtree["config"]["settings_key"], $id);

			if (!$setting) {
				return false;
			}

			$value_to_decode = $setting["encrypted"] ? $setting["decrypted_value"] : $setting["value"];

			if (is_null($value_to_decode)) {
				return null;
			}

			$value = json_decode($value_to_decode, true);

			if (is_null($value)) {
				return $value;
			} elseif (is_array($value)) {
				return BigTree::untranslateArray($value);
			} else {
				return static::replaceInternalPageLinks($value);
			}
		}

		/*
			Function: getSettings
				Gets the value of multiple settings.

			Parameters:
				id - Array containing the ID of the settings.

			Returns:
				Array containing the string or array of each setting's value.
		*/

		public static function getSettings($ids) {
			global $bigtree;

			$settings = [];

			foreach ($ids as $setting_id) {
				$value = static::getSetting($setting_id);

				if ($value !== false) {
					$settings[$setting_id] = $value;
				}
			}

			return $settings;
		}

		/*
			Function: getTag
				Returns a tag for a given tag id.

			Parameters:
				id - The id of the tag to retrieve.

			Returns:
				A tag entry from bigtree_tags.
		*/

		public static function getTag($id) {
			return SQL::fetch("SELECT * FROM bigtree_tags WHERE id = ?", $id);
		}

		/*
			Function: getTagByRoute
				Returns a tag for a given tag route.

			Parameters:
				route - The route of the tag to retrieve.

			Returns:
				A tag entry from bigtree_tags.
		*/

		public static function getTagByRoute($route) {
			return SQL::fetch("SELECT * FROM bigtree_tags WHERE route = ?", $route);
		}

		/*
			Function: getTagsForPage
				Returns a list of tags the page was tagged with.

			Parameters:
				page - Either a page array (containing at least the page's ID) or a page ID.
				full - Whether to return a full tag array or just the tag string (defaults to full tag array)

			Returns:
				An array of tags.
		*/

		public static function getTagsForPage($page, $full = true) {
			if (!is_numeric($page)) {
				$page = $page["id"];
			}

			if ($full) {
				return SQL::fetchAll("SELECT bigtree_tags.* FROM bigtree_tags JOIN bigtree_tags_rel
									  ON bigtree_tags.id = bigtree_tags_rel.tag
									  WHERE bigtree_tags_rel.`table` = 'bigtree_pages'
										AND bigtree_tags_rel.`entry` = ?
									  ORDER BY bigtree_tags.`tag` ASC", $page);
			}

			return SQL::fetchAllSingle("SELECT bigtree_tags.tag FROM bigtree_tags JOIN bigtree_tags_rel
										ON bigtree_tags.id = bigtree_tags_rel.tag
										WHERE bigtree_tags_rel.`table` = 'bigtree_pages'
										  AND bigtree_tags_rel.`entry` = ?
										ORDER BY bigtree_tags.`tag` ASC", $page);
		}

		/*
			Function: getTemplate
				Returns a template from the database with resources decoded.

			Parameters:
				id - The ID of the template.

			Returns:
				The template row from the database with resources decoded.
		*/

		public static function getTemplate($id) {
			return BigTreeJSONDB::get("templates", $id);
		}

		/*
			Function: getToplevelNavigationId
				Returns the highest level ancestor for the current page.

			Parameters:
				trunk_as_toplevel - Treat a trunk as top level navigation instead of a new "site" (will return the trunk instead of the first nav item below the trunk if encountered) - defaults to false

			Returns:
				The ID of the highest ancestor of the current page.

			See Also:
				<getToplevelNavigationIdForPage>

		*/

		public static function getTopLevelNavigationId($trunk_as_toplevel = false) {
			global $bigtree;
			return static::getTopLevelNavigationIdForPage($bigtree["page"],$trunk_as_toplevel);
		}

		/*
			Function: getToplevelNavigationIdForPage
				Returns the highest level ancestor for a given page.

			Parameters:
				page - A page array (containing at least the page's "path").
				trunk_as_toplevel - Treat a trunk as top level navigation instead of a new "site" (will return the trunk instead of the first nav item below the trunk if encountered) - defaults to false

			Returns:
				The ID of the highest ancestor of the given page.

			See Also:
				<getToplevelNavigationId>

		*/

		public static function getTopLevelNavigationIdForPage($page,$trunk_as_toplevel = false) {
			$paths = [];
			$path = "";
			$parts = explode("/",$page["path"]);

			foreach ($parts as $part) {
				$path .= "/".$part;
				$path = ltrim($path,"/");
				$paths[] = "path = '".sqlescape($path)."'";
			}

			// Get either the trunk or the top level nav id.
			$f = sqlfetch(sqlquery("SELECT id,trunk,path FROM bigtree_pages WHERE (".implode(" OR ",$paths).") AND (trunk = 'on' OR parent = '".BIGTREE_SITE_TRUNK."') ORDER BY LENGTH(path) DESC LIMIT 1"));

			if ($f["trunk"] && !$trunk_as_toplevel) {
				// Get the next item in the path.
				$g = sqlfetch(sqlquery("SELECT id FROM bigtree_pages WHERE (".implode(" OR ",$paths).") AND LENGTH(path) < ".strlen($f["path"])." ORDER BY LENGTH(path) ASC LIMIT 1"));
				if ($g) {
					$f = $g;
				}
			}

			return $f["id"];
		}

		/*
			Function: handle404
				Handles a 404.

			Parameters:
				url - The URL you hit that's a 404.
		*/

		public static function handle404($url) {
			$url = sqlescape(htmlspecialchars(strip_tags(rtrim($url, "/"))));
			$existing = null;

			if (!$url) {
				return true;
			}

			// See if there's any GET requests
			$get = $_GET;
			unset($get["bigtree_htaccess_url"]);

			if (count($get)) {
				$query_pieces = [];

				foreach ($get as $key => $value) {
					$query_pieces[] = $key."=".$value;
				}

				$get = sqlescape(htmlspecialchars(implode("&", $query_pieces)));

				if (defined("BIGTREE_SITE_KEY")) {
					$existing = sqlfetch(sqlquery("SELECT * FROM bigtree_404s WHERE broken_url = '$url' AND get_vars = '$get' AND site_key = '".sqlescape(BIGTREE_SITE_KEY)."'"));
				} else {
					$existing = sqlfetch(sqlquery("SELECT * FROM bigtree_404s WHERE broken_url = '$url' AND get_vars = '$get'"));
				}

				// Look for a 404 that has a redirect but no get vars
				if (empty($existing["redirect_url"])) {
					if (defined("BIGTREE_SITE_KEY")) {
						$non_get_existing = sqlfetch(sqlquery("SELECT * FROM bigtree_404s WHERE broken_url = '$url' AND redirect_url != '' AND get_vars = '' AND site_key = '".sqlescape(BIGTREE_SITE_KEY)."'"));
					} else {
						$non_get_existing = sqlfetch(sqlquery("SELECT * FROM bigtree_404s WHERE broken_url = '$url' AND redirect_url != '' AND get_vars = ''"));
					}

					if ($non_get_existing) {
						$existing = $non_get_existing;
					}
				}
			} else {
				$get = "";

				if (defined("BIGTREE_SITE_KEY")) {
					$existing = sqlfetch(sqlquery("SELECT * FROM bigtree_404s WHERE broken_url = '$url' AND get_vars = '' AND site_key = '".sqlescape(BIGTREE_SITE_KEY)."'"));
				} else {
					$existing = sqlfetch(sqlquery("SELECT * FROM bigtree_404s WHERE broken_url = '$url' AND get_vars = ''"));
				}
			}

			if (!empty($existing["redirect_url"])) {
				$existing["redirect_url"] = static::getInternalPageLink($existing["redirect_url"]);

				if ($existing["redirect_url"] == "/") {
					$existing["redirect_url"] = "";
				}

				if (substr($existing["redirect_url"],0,7) == "http://" || substr($existing["redirect_url"],0,8) == "https://") {
					$redirect = $existing["redirect_url"];
				} else {
					$redirect = WWW_ROOT.str_replace(WWW_ROOT, "", $existing["redirect_url"]);
				}

				sqlquery("UPDATE bigtree_404s SET requests = (requests + 1) WHERE id = '".$existing["id"]."'");
				BigTree::redirect(htmlspecialchars_decode($redirect), "301");

				return false;
			} else {
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
				
				if (!defined("BIGTREE_DO_NOT_CACHE")) {
					define("BIGTREE_DO_NOT_CACHE", true);
				}
				
				if (!defined("BIGTREE_URL_IS_404")) {
					define("BIGTREE_URL_IS_404", true);
				}

				if ($existing && $existing["get_vars"] == $get) {
					sqlquery("UPDATE bigtree_404s SET requests = (requests + 1) WHERE id = '".$existing["id"]."'");
				} elseif (defined("BIGTREE_SITE_KEY")) {
					sqlquery("INSERT INTO bigtree_404s (`broken_url`, `get_vars`, `requests`, `site_key`) VALUES ('$url', '$get', '1', '".sqlescape(BIGTREE_SITE_KEY)."')");
				} else {
					sqlquery("INSERT INTO bigtree_404s (`broken_url`, `get_vars`, `requests`) VALUES ('$url', '$get', '1')");
				}

				return true;
			}
		}

		/*
			Function: linkForPath
				Returns a correct link for a page's path for the current site in a multi-domain setup.

			Parameters:
				path - A page path

			Returns:
				A fully qualified URL
		*/

		public static function linkForPath($path) {
			global $bigtree;

			// Remove the site root from the path for multi-site
			if (defined("BIGTREE_SITE_KEY") || (is_array($bigtree["config"]["sites"]) && count($bigtree["config"]["sites"]))) {
				foreach (static::$SiteRoots as $site_path => $site_data) {
					if ($path === "" && $site_path === "") {
						return $site_data["www_root"];
					}

					if ($site_path == "" || strpos($path, $site_path) === 0) {
						if ($site_path) {
							$path = substr($path, strlen($site_path) + 1);
						}

						if (!empty($bigtree["config"]["trailing_slash_behavior"]) &&
							$bigtree["config"]["trailing_slash_behavior"] == "remove"
						) {
							return rtrim($site_data["www_root"].$path, "/");
						}

						return rtrim($site_data["www_root"].$path, "/")."/";
					}
				}
			}

			if ($path === "") {
				return WWW_ROOT;
			}

			if (!empty($bigtree["config"]["trailing_slash_behavior"]) &&
				$bigtree["config"]["trailing_slash_behavior"] == "remove"
			) {
				return WWW_ROOT.$path;
			}

			return WWW_ROOT.$path."/";
		}

		/*
			Function: makeSecure
				Forces the site into Secure mode.
				When Secure mode is enabled, BigTree will enforce the user being at HTTPS and will rewrite all insecure resources (like CSS, JavaScript, and images) to use HTTPS.
		*/

		public static function makeSecure() {
			if (!BigTree::getIsSSL()) {
				BigTree::redirect("https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"],"301");
			}

			static::$Secure = true;
		}

		/*
			Function: replaceHardRoots
				Replaces all hard roots in a URL with relative ones (i.e. {wwwroot}).

			Parameters:
				string - A string with hard roots.

			Returns:
				A string with relative roots.
		*/

		public static function replaceHardRoots($string) {
			static::generateReplaceableRoots();

			return strtr($string, static::$ReplaceableRoots);
		}

		/*
			Function: replaceInternalPageLinks
				Replaces the internal page links in an HTML block with hard links.

			Parameters:
				html - An HTML block

			Returns:
				An HTML block with links hard-linked.
		*/

		public static function replaceInternalPageLinks($html) {
			// Save time if there's no content
			if (is_null($html) || trim($html) === "") {
				return "";
			}

			if (substr($html,0,6) == "ipl://" || substr($html,0,6) == "irl://") {
				$html = static::getInternalPageLink($html);
			} else {
				$html = static::replaceRelativeRoots($html);
				$html = preg_replace_callback('^="(ipl:\/\/[a-zA-Z0-9\_\:\/\.\?\=\-]*)"^',array("BigTreeCMS","replaceInternalPageLinksHook"),$html);
				$html = preg_replace_callback('^="(irl:\/\/[a-zA-Z0-9\_\:\/\.\?\=\-]*)"^',array("BigTreeCMS","replaceInternalPageLinksHook"),$html);
			}

			return $html;
		}
		public static function replaceInternalPageLinksHook($matches) {
			return '="'.static::getInternalPageLink($matches[1]).'"';
		}

		/*
			Function: replaceRelativeRoots
				Replaces all relative roots in a URL (i.e. {wwwroot}) with hard links.

			Parameters:
				string - A string with relative roots.

			Returns:
				A string with hard links.
		*/

		public static function replaceRelativeRoots($string) {
			static::generateReplaceableRoots();

			return strtr($string, static::$ReplaceableRootTokens);
		}

		/*
			Function: setHeadContext
				Sets the context for the drawHeadTags method.

			Parameters:
				table - A data table to pull open graph information from
				entry - The ID of the entry to pull open graph information for
				title - A page title to use (optional, will use Open Graph information if not entered)
				description - A meta description to use (optional, will use Open Graph information if not entered)
				image - An image to use for Open Graph (if OG data is empty)
				type - An Open Graph type to default to (if left empty and OG data is empty, will use "website")
		*/

		public static function setHeadContext($table, $entry, $title = null, $description = null, $image = null, $type = null) {
			static::$HeadContext = [
				"table" => $table,
				"entry" => $entry,
				"title" => $title,
				"description" => $description,
				"image" => $image,
				"type" => $type
			];
		}

		/*
			Function: urlify
				Turns a string into one suited for URL routes.

			Parameters:
				title - A short string.

			Returns:
				A string suited for a URL route.
		*/

		public static function urlify($title) {
			global $bigtree;

			if (class_exists("Locale") && version_compare(PHP_VERSION, "7.0.0") >= 0) {
				require_once(SERVER_ROOT."core/inc/lib/slug-generator/src/SlugOptions.php");
				require_once(SERVER_ROOT."core/inc/lib/slug-generator/src/SlugGenerator.php");

				$options = new Ausi\SlugGenerator\SlugOptions;
				$options->setLocale($bigtree["config"]["locale"] ?? "en_US");

				$generator = new Ausi\SlugGenerator\SlugGenerator($options);

				return $generator->generate($title ?? "");
			} else {
				$accent_match = array('Â', 'Ã', 'Ä', 'À', 'Á', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
				$accent_replace = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'B', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

				$title = str_replace($accent_match, $accent_replace, $title);
				$title = htmlspecialchars_decode($title);
				$title = str_replace("/","-",$title);
				$title = strtolower(preg_replace('/\s/', '-',preg_replace('/[^a-zA-Z0-9\s\-\_]+/', '',trim($title))));
				$title = str_replace("--","-",$title);

				return $title;
			}
		}
	}
