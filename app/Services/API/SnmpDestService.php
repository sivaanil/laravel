<?php

namespace Unified\Services\API;

use Unified\Models\SNMPDestination;
use Unified\Services\API\Handlers\SnmpDestHandler;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;

/**
 * Handles SnmpDest related functions for the API.
 */
class SnmpDestService extends APIService {
	/**
	 * SnmpDest Service constructor.
	 *
	 * @param ServiceRequest $request
	 *        	Service request
	 */
	public function __construct(ServiceRequest $request) {
		parent::__construct ( $request, RequestValidator::getValidator ( $request->getType (), $request->getAction () ) );
	}
	
	/**
	 * Return list of SnmpDest
	 *
	 * @return Service response object with the following status codes:
	 */
	public function getSnmpDest() {
		$snmpDest = SNMPDestination::getSnmpDest ( $this->getQueryParameters () );
		// Structurize result object
		RequestValidator::structurizeObject ( $snmpDest );
		return new ServiceResponse ( ServiceResponse::SUCCESS, $snmpDest );
	}
	
	/**
	 * Return SnmpDest with specified ID
	 *
	 * @return Service response object with the following status codes:
	 */
	public function getSnmpDestById() {
		// Utilize SNMPDestination::getSnmpDest call. Incoming filters should already have filter by id.
		$snmpDest = SNMPDestination::getSnmpDest ( $this->getQueryParameters () );
		// Structurize result object
		RequestValidator::structurizeObject ( $snmpDest );
		if (count ( $snmpDest ['snmpDest'] ) == 0) {
			return new ServiceResponse ( ServiceResponse::NOT_FOUND );
		} else {
			return new ServiceResponse ( ServiceResponse::SUCCESS, $snmpDest );
		}
	}
	
	/**
	 * Modify SnmpDest
	 *
	 * @return Service response object with the following status codes:
	 */
	public function modifySnmpDest() {
		$retVal = SNMPDestination::modifySnmpDest ( $this->getContent () );
		
		if (isset ( $retVal ['status'] ) && $retVal ['status']) {
			return new ServiceResponse ( ServiceResponse::SUCCESS, [ ] );
		} else {
			return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
					'error' => $retVal ['error'] 
			) );
		}
	}
	
	/**
	 * Add SnmpDest Dest
	 *
	 * @return Service response object with the following status codes:
	 */
	public function addSnmpDest() {
		$retVal = SNMPDestination::addSnmpDest ( $this->getContent () );
		
		if (isset ( $retVal ['status'] ) && $retVal ['status']) {
			return new ServiceResponse ( ServiceResponse::SUCCESS, [ 
					'id' => $retVal ['id'] 
			] );
		} else {
			return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
					'error' => $retVal ['error'] 
			) );
		}
	}
	/**
	 * Delete SnmpDest Dest
	 *
	 * @return Service response object with the following status codes:
	 */
	public function deleteSnmpDest() {
		$retVal = SNMPDestination::deleteSnmpDest ( $this->getContent () );
		
		if (isset ( $retVal ['status'] ) && $retVal ['status']) {
			return new ServiceResponse ( ServiceResponse::SUCCESS, [ ] );
		} else {
			return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
					'error' => $retVal ['error'] 
			) );
		}
	}
}
