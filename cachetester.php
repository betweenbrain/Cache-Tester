<?php defined('JPATH_BASE') or die;

/**
 * File            includes.php
 * Created        11/26/13 4:58 PM
 * Author        Matt Thomas matt@betweenbrain.com
 * Copyright    Copyright (C) 2013 betweenbrain llc.
 */

/**
 * System plugin to properly include files without core hacks
 */
class PlgSystemCachetester extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->app    = JFactory::getApplication();
		$this->plugin =& JPluginHelper::getPlugin('system', 'cachetester');
		$this->params = new JParameter($this->plugin->params);

	}

	function onAfterInitialise()
	{
		if ($this->app->isAdmin())
		{

			$cacheAge  = $this->params->get('cacheage') * 60;
			$cacheDir  = JPATH_CACHE . '/cachetester/';
			$cacheDir  = preg_replace("/administrator\//", '', $cacheDir);
			$cacheFile = $cacheDir . 'objects.json';

			if (!is_dir($cacheDir))
			{
				mkdir($cacheDir);
			}

			if ($this->params->get('cache') == 1)
			{

				if (file_exists($cacheFile))
				{
					$age = filemtime($cacheFile);
					$now = time();

					if (($now - $age) > $cacheAge)
					{
						file_put_contents($cacheFile, 'Created: ' . date("Y-m-d H:i:s"));
					}

					return;
				}

				file_put_contents($cacheFile, 'Created: ' . date("Y-m-d H:i:s"));
			}
		}
	}
}
