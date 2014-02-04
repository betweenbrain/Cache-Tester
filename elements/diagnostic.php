<?php defined('_JEXEC') or die;

/**
 * File       diagnostic.php
 * Created    2/12/13 11:59 AM
 * Author     Matt Thomas
 * Website    http://betweenbrain.com
 * Email      matt@betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2012 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */
class JElementDiagnostic extends JElement
{

	function fetchElement()
	{

		// Fetch parameters via database query
		$db  = JFactory::getDBO();
		$sql = 'SELECT params
          FROM #__plugins
          WHERE element = "cachetester"';
		$db->setQuery($sql);
		$params = $db->loadResult();

		$params   = parse_ini_string($params);
		$cacheage = $params['cacheage'];
		$cacheDir = JPATH_CACHE . '/cachetester/';
		$cacheDir = preg_replace("/administrator\//", '', $cacheDir);

		// Initialize variables
		$result   = null;
		$messages = null;
		$errors   = null;

		if ($params['showdiagnostic'] == 1)
		{

			// Check cache stuff
			if ($params['cache'] != 1)
			{
				$messages[] = "Caching is disabled.";
			}

			if ($params['cache'] == 1)
			{

				$messages[] = "Caching is enabled.";

				$cacheFile = $cacheDir . 'objects.json';

				if (file_exists($cacheFile))
				{
					$cacheAge   = date("F d Y H:i:s", filemtime($cacheFile));
					$messages[] = "Cache file at $cacheFile exists.";
					$messages[] = "Cache file was created $cacheAge.";
				}
				else
				{
					$errors[] = "Cache file at $cacheFile does not exist!";
				}

				$messages[] = "Cache lifetime is $cacheage minute(s).<br/>";

				if (is_dir($cacheDir))
				{
					$messages[] = "Cache directory at $cacheDir exists.";
					if (is_writable($cacheDir))
					{
						$messages[] = "Cache directory at $cacheDir is writable.";
					}
				}
				else
				{
					$errors[] = "Cache directory at $cacheDir does not exist!";
					if (!is_writable($cacheDir))
					{
						$errors[] = "Cache directory at $cacheDir is not writable!";
					}
				}
			}

			if ($messages[0])
			{
				$result .= '<dl id="system-message"><dt>Information</dt><dd class="message fade"><ul>';
				foreach ($messages as $message)
				{
					$result .= '<li>' . $message . '</li>';
				}
				$result .= '</ul></dd></dl>';
			}

			if ($errors[0])
			{
				$result .= '<dl id="system-message"><dt>Errors</dt><dd class="error message fade"><ul>';
				foreach ($errors as $error)
				{
					$result .= '<li>' . $error . '</li>';
				}
				$result .= '</ul></dd></dl>';
			}

			if ($result)
			{
				return print_r($result, false);
			}

			return false;
		}

		return false;
	}
}