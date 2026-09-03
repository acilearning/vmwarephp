<?php
namespace Vmwarephp;

class SoapClient extends \SoapClient {

	/**
	 * Temporarily populated by Service::makeSoapCall() around each SOAP request.
	 *
	 * Declared so PHP 8.2+ does not emit "Creation of dynamic property" deprecations.
	 *
	 * @var array|null
	 */
	public $_classmap;

	function __doRequest($request, $location, $action, $version, $one_way = 0) {
		$request = $this->appendXsiTypeForExtendedDatastructures($request);
		$action = "urn:vim25/6.7"; // Labs: support vSphere 6.7 calls
		$result = parent::__doRequest($request, $location, $action, $version, $one_way);
		if (isset($this->__soap_fault) && $this->__soap_fault) {
			throw $this->__soap_fault;
		}
		return $result;
	}

	/* PHP does not provide inheritance information for wsdl types so we have to specify that its and xsi:type
	 * php bug #45404
	 * */
	private function appendXsiTypeForExtendedDatastructures($request) {
		return $request = str_replace(array("xsi:type=\"ns1:TraversalSpec\"", '<ns1:selectSet />'), array("xsi:type=\"ns1:TraversalSpec\"", ''), $request);
	}
}
