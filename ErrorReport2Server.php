<?php

namespace ErrorReport2;

use SPFW\system\config\Config;
use SPFW\system\Controller;
use SPFW\system\JsonOutput;
use SPFW\system\routing\PostRequest;
use SPFW\system\routing\Request;


/**
 * ErrorReport2 Server
 *
 * @package ErrorReport2
 * @version 2.0.2
 */
final class ErrorReport2Server extends Controller
{
	private const ER2_VERSION = '2.0.2';

	private const ERROR_RESPONSE_CODE = 400;
	private const SUCCESS_RESPONSE_CODE = 201;

	private const REQUIRED_NODES = [
			'authentication',
			'general',
			'environment',
			'request',
			'database',
			'cookies',
			'get',
			'post',
			'session'
	];


	private ErrorReport2ServerConfig $config;


	public function __construct(string $method_name, Request $request)
	{
		parent::__construct( $method_name, $request);

		$global_config = Config::get();
		if ($global_config === null) {
			throw new \RuntimeException('Unknown global configuration');
		}

		$config_traits = class_uses($global_config);
		if (!\in_array(ErrorReport2ServerConfigTrait::class, $config_traits, true)) {
			throw new \RuntimeException('Unknown global configuration');
		}

		/** @noinspection PhpUndefinedMethodInspection */
		$er2_server_config = $global_config->getER2Config();

		if ($er2_server_config === null) {
			throw new \RuntimeException('Undefined ER2 configuration');
		}

		$this->config = $er2_server_config;
	}

	private function checkHttpMethod(Request $request) : void
	{
		if (!($request instanceof PostRequest)) {
			throw new \RuntimeException('Invalid http method');
		}
	}

	private function checkToken(array $json_structure) : void
	{
		$token_string = $json_structure['authentication']['token'];

		if (!$this->config->authenticateToken($token_string)) {
			throw new \InvalidArgumentException('Invalid token');
		}
	}

	private function returnError() : JsonOutput
	{
		$error_json = [
				'logging_accepted'	=> false
		];

		return new JsonOutput($error_json, self::ERROR_RESPONSE_CODE);
	}

	private function returnSuccess() : JsonOutput
	{
		$error_json = [
				'logging_accepted'	=> true
		];

		return new JsonOutput($error_json, self::SUCCESS_RESPONSE_CODE);
	}

	public function listener(Request $request) : JsonOutput
	{
		try {
			$this->checkHttpMethod($request);
			$raw_post_payload = $this->rawPostData();
			$json_structure = $this->unpackPayload($raw_post_payload);
			$this->validateJson($json_structure);
			$this->checkToken($json_structure);
			$this->save($json_structure);
		} catch (\Throwable $e) {
			return $this->returnError();
		}

		return $this->returnSuccess();
	}

	private function prepareJsonInsertion(?array $json_structure) : ?string
	{
		if ($json_structure === null || \count($json_structure) === 0) {
			return null;
		}

		try {
			return json_encode($json_structure, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {
			return 'JSON ERROR';
		}
	}

	private function rawPostData() : string
	{
		return file_get_contents('php://input');
	}

	private function save(array $json_structure) : ErrorReport2
	{
		$er2_data = [
				'service_id'			=> $json_structure['authentication']['service_id'],
				'er2_client_version'	=> $json_structure['authentication']['er2_version'],
				'er2_server_version'	=> self::ER2_VERSION,
				'session_id'			=> $json_structure['general']['er2_session_id'],
				'client_timestamp'		=> \DateTime::createFromFormat('Y-m-d\TH:i:s', $json_structure['general']['timestamp']),
				'host_name'				=> $json_structure['general']['host_name'],
				'host_os'				=> $json_structure['general']['host_os'],
				'host_os_release'		=> $json_structure['general']['host_os_release'],
				'host_os_version'		=> $json_structure['general']['host_os_version'],
				'php_version'			=> $json_structure['general']['php_version'],
				'php_mode'				=> $json_structure['general']['php_mode'],
				'php_mem_usage'			=> $json_structure['general']['php_mem_usage'],
				'debug_mode'			=> $json_structure['general']['debug_mode'],
				'request_method'		=> $json_structure['request']['method'],
				'request_domain'		=> $json_structure['request']['domain'],
				'request_subdomain'		=> $json_structure['request']['subdomain'],
				'request_tcp_port'		=> $json_structure['request']['tcp_port'],
				'request_path'			=> $json_structure['request']['path'],
				'request_cli'			=> $json_structure['request']['cli'],
				'request_secure_connection'		=> $json_structure['request']['secure_connection'],
				'environment'			=> $this->prepareJsonInsertion($json_structure['environment']),
				'database'				=> $this->prepareJsonInsertion($json_structure['database']),
				'cookies'				=> $this->prepareJsonInsertion($json_structure['cookies']),
				'get'					=> $this->prepareJsonInsertion($json_structure['get']),
				'post'					=> $this->prepareJsonInsertion($json_structure['post']),
				'session'				=> $this->prepareJsonInsertion($json_structure['session']),
				'errors'				=> $this->prepareJsonInsertion($json_structure['errors'] ?? null),
				'throwable'				=> $this->prepareJsonInsertion($json_structure['throwable'] ?? null),
		];

		return ErrorReport2::create($er2_data);
	}

	/**
	 * @param string $raw_payload
	 *
	 * @return array Decoded json-string
	 * @throws \JsonException
	 */
	private function unpackPayload(string $raw_payload) : array
	{
		return json_decode($raw_payload, true, 20, JSON_THROW_ON_ERROR);
	}

	private function validateJson(array $json_structure) : void
	{
		foreach (self::REQUIRED_NODES as $node_name) {
			if (!\array_key_exists($node_name, $json_structure)) {
				throw new \InvalidArgumentException('Missing node "' . $node_name . '" in json-structure');
			}
		}
	}
}


?>