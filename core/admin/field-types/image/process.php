<?php
	if (empty($field["file_input"]["error"])) {
		$field["file_input"]["error"] = 0;
	}

	// If a file upload error occurred, return the old data and set errors
	if ($field["file_input"]["error"] == 1 || $field["file_input"]["error"] == 2) {
		$bigtree["errors"][] = [
			"field" => $field["title"],
			"error" => "The image you uploaded (".$field["file_input"]["name"].") was too large &mdash; <strong>Max file size: ".ini_get("upload_max_filesize")."</strong>",
		];
		$field["output"] = $field["input"];
	} elseif ($field["file_input"]["error"] == 3) {
		$bigtree["errors"][] = [
			"field" => $field["title"],
			"error" => "The image upload failed (".$field["file_input"]["name"].").",
		];
		$field["output"] = $field["input"];
	} else {
		// We uploaded a new image.
		if (!empty($field["file_input"]["tmp_name"]) && is_uploaded_file($field["file_input"]["tmp_name"])) {
			$file = $admin->processImageUpload($field);
			$field["output"] = $file ? $file : $field["input"];
		// Using an existing image or one from the Image Browser
		} else {
			$field["output"] = $field["input"] ?: "";

			// We're trying to use an image from the Image Browser.
			if (substr($field["output"],0,11) == "resource://") {
				// It's technically a new file now, but we pulled it from resources so we might need to crop it.
				$resource = $admin->getResourceByFile(substr($field["output"],11));
				$resource_location = str_replace(array("{wwwroot}",WWW_ROOT,"{staticroot}",STATIC_ROOT),SITE_ROOT,$resource["file"]);

				// See if the file was actually stored in the cloud
				if (!file_exists($resource_location)) {
					$resource_location = $resource["file"];
				}
				$pinfo = BigTree::pathInfo($resource_location);

				// Emulate a newly uploaded file
				$field["file_input"] = array("name" => $pinfo["basename"],"tmp_name" => SITE_ROOT."files/".uniqid("temp-").".img","error" => false);
				BigTree::copyFile($resource_location,$field["file_input"]["tmp_name"]);

				$file = $admin->processImageUpload($field);
				$field["output"] = $file ? $file : $field["input"];
			} elseif (!empty($bigtree["post_data"]["__".$field["key"]."_recrop__"]) || !empty($field["recrop"])) {
				// User has asked for a re-crop
				$image = new BigTreeImage(str_replace(STATIC_ROOT, SITE_ROOT, $field["input"]), $field["settings"], true);
				$image_copy = $image->copy();
				$image_copy->StoredName = pathinfo($field["input"], PATHINFO_BASENAME);
				$image_copy->filterGeneratableCrops();
				$crops = $image_copy->processCrops();

				if (!count($crops)) {
					$image_copy->destroy();
				}

				$bigtree["crops"] = array_merge($bigtree["crops"], $crops);
			}
		}
	}
