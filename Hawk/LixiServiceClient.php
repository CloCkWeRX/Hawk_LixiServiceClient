<?php
require 'HTTP/Request2.php';
require 'Hawk/Exception.php';

class Hawk_LixiServiceClient {
	
	protected $last_response;
	protected $request;

	public function __construct(HTTP_Request2 $request, $endpoint) {
		$this->request = $request;
		$this->endpoint = $endpoint;

		$this->request->setMethod(HTTP_Request2::METHOD_POST);
		$this->request->setHeader('Content-type: text/xml; charset=utf-8');
	}

	public function order($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'order', $xml)->send()
		);
	}

	public function cancel($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'cancel', $xml)->send()
		);
	}

	public function quote($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'quote', $xml)->send()
		);
	}

	public function update($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'update', $xml)->send()
		);
	}


	public function clarity_provided($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'clarity_provided', $xml)->send()
		);
	}

	public function added_to_compliance($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'added_to_compliance', $xml)->send()
		);
	}

	public function bounced_from_compliance($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'bounced_from_compliance', $xml)->send()
		);
	}

	public function milestone_update($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'milestone_update', $xml)->send()
		);
	}


	public function reject_action_update($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'reject_action_update', $xml)->send()
		);
	}

	protected function prepare_request($request, $name, $payload) {
		$request->setBody($payload);
		$request->setURL($this->endpoint . $name);

		return $request;
	}

	protected function assess_response($response) {
		$this->last_response = $response;

		if ($response->getStatus() == 200) {
			return true;
		}

		throw new Hawk_Exception($response->getBody(), $response->getStatus());
	}

	public function __getLastRequest() {
		return $this->request->getBody();
	}

	public function __getLastResponse() {
		return $this->last_response->getBody();
	}

	public function __getLastRequestHeaders() {
		return print_r($this->request->getHeaders(), true);
	}

	public function __getLastResponseHeaders() {
		return print_r($this->last_response->getHeader(), true);
	}
}