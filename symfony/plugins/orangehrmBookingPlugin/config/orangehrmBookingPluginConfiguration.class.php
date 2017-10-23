<?php

/**
 * Description of orangehrmBookingPluginConfiguration
 *
 * @author amora
 */
class orangehrmBookingPluginConfiguration extends sfPluginConfiguration {

	public function initialize() {
		$enabledModules = sfConfig::get('sf_enabled_modules');
		if (is_array($enabledModules)) {
			sfConfig::set('sf_enabled_modules', array_merge(sfConfig::get('sf_enabled_modules'), array('booking')));
		}
	}

}
