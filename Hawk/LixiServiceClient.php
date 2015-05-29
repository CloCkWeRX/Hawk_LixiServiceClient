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

	public function updateAddress($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'update_address', $xml)->send()
		);
	}

	public function updateFee($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'update_fee', $xml)->send()
		);
	}

	public function clarity_provided($job_id, $comments) {
		$this->request->setHeader('Content-type: application/x-www-form-urlencoded');

		// This has an empty payload
		$this->request->setURL($this->endpoint . 'clarity_provided');
		$this->request->addPostParameter(
			array('job_id' => $job_id, 'comments' => $comments)
		);

		return $this->assess_response($this->request->send());
	}

	public function added_to_compliance($job_id) {
		$this->request->setHeader('Content-type: application/x-www-form-urlencoded');

		// This has an empty payload
		$this->request->setURL($this->endpoint . 'added_to_compliance');
		$this->request->addPostParameter(
			array('job_id' => $job_id)
		);

		return $this->assess_response(
			$this->prepare_request($this->request->send())
		);
	}

	public function bounced_from_compliance($job_id, $comment) {
		$this->request->setHeader('Content-type: application/x-www-form-urlencoded');

		// This has an empty payload
		$this->request->setURL($this->endpoint . 'bounced_from_compliance');
		$this->request->addPostParameter(
			array('job_id' => $job_id, 'comment' => $comment)
		);

		return $this->assess_response(
			$this->prepare_request($this->request->send())
		);
	}

	public function milestone_update($job_id, $milestone_code, $time)) {
		$this->request->setHeader('Content-type: application/x-www-form-urlencoded');

		// This has an empty payload
		$this->request->setURL($this->endpoint . 'milestone_update');
		$this->request->addPostParameter(
			array(
				'job_id' => $job_id, 
				'time' => $time,
				'milestone_code' => $milestone_code
			)
		);

		return $this->assess_response($this->request->send());
	}

	public function escalate($xml, $service_id, $reason) {

	}

	public function reject_action_update($job_id, $comments) {
		$this->request->setHeader('Content-type: application/x-www-form-urlencoded');

		// This has an empty payload
		$this->request->setURL($this->endpoint . 'reject_action_update');
		$this->request->addPostParameter(
			array('job_id' => $job_id, 'comments' => $comments)
		);

		return $this->assess_response($this->request->send());
	}

	public function query($xml) {
		return $this->assess_response(
			$this->prepare_request($this->request, 'query', $xml)->send()
		);
	}

	protected function prepare_request(HTTP_Request2 $request, $name, $payload) {
		$request->setBody($payload);
		$request->setURL($this->endpoint . $name);

		return $request;
	}

	protected function assess_response(HTTP_Request2_Response $response) {
		$this->last_response = $response;

		if ($response->getStatus() == 200) {
			return true;
		}

		throw new Hawk_Exception($response->getBody(), $response->getStatus());
	}

	public function __getLastRequest() {
		return !empty($this->request) ? $this->request->getBody() : null;
	}

	public function __getLastResponse() {
		return !empty($this->last_response) ? $this->last_response->getBody() : null;
	}

	public function __getLastRequestHeaders() {
		return !empty($this->request) ? print_r($this->request->getHeaders(), true) : null;
	}

	public function __getLastResponseHeaders() {
		return !empty($this->last_response) ? print_r($this->last_response->getHeader(), true) : null;
	}
}