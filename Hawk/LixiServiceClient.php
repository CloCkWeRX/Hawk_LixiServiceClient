<?php
require 'HTTP/Request2.php';
require 'Hawk/Exception.php';

class Hawk_LixiServiceClient {
	
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

	protected function prepare_request($request, $name, $payload) {
		$request->setBody($payload);
		$request->setURL($this->endpoint . $name);

		return $request;
	}

	protected function assess_response($response) {
		if ($response->getStatus() == 200) {
			return true;
		}

		throw new Hawk_Exception("Received HTTP Response " . $response->getStatus());
	}
}