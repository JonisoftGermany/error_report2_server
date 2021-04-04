<?php

namespace ErrorReport2;


/**
 * ErrorReport2 Config Trait
 *
 * @package ErrorReport2
 * @version 2.0.0
 */
trait ErrorReport2ServerConfigTrait
{
	private ?ErrorReport2ServerConfig $er2_config;

	final public function getER2Config() : ?ErrorReport2ServerConfig
	{
		return $this->er2_config;
	}

	final public function setER2Config(ErrorReport2ServerConfig $er2_config) : void
	{
		$this->er2_config = $er2_config;
	}
}


?>