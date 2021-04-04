<?php

namespace ErrorReport2;

use SPFW\system\config\IConfig;


/**
 * ErrorReport2 Server Configuration
 *
 * @package ErrorReport2
 * @version 2.0.0
 */
final class ErrorReport2ServerConfig implements IConfig
{
	/** @var string[] $allowed_token */
	private array $allowed_token = [];


	public function addToken(string $token) : self
	{
		$this->allowed_token[] = $token;
		return $this;
	}

	public function authenticateToken(string $token) : bool
	{
		return \in_array($token, $this->allowed_token, true);
	}

	public function checkConfig(bool $strict = false) : bool
	{
		return true;
	}
}


?>