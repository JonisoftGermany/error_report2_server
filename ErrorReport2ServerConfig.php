<?php

namespace ErrorReport2;


/**
 * ErrorReport2 Server Configuration
 *
 * @package ErrorReport2
 * @version 2.1.0
 */
final class ErrorReport2ServerConfig
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

	public function checkConfig() : bool
	{
		return true;
	}
}


?>