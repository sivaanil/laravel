<?php

namespace Unified\Services\API;

use Unified\Models\SNMPNotification;
use Unified\Services\API\Handlers\NotificationsHandler;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;

/**
 * Handles Notifications related functions for the API.
 */
class NotificationsService extends APIService {
	/**
	 * Notifications Service constructor.
	 *
	 * @param ServiceRequest $request
	 *        	Service request
	 */
	public function __construct(ServiceRequest $request) {
		parent::__construct ( $request, RequestValidator::getValidator ( $request->getType (), $request->getAction () ) );
	}
	
	/**
	 * Return list of Notifications
	 *
	 * @return Service response object with the following status codes:
	 */
	public function getNotifications() {
		$notifications = SNMPNotification::getSnmpNotification ( $this->getQueryParameters () );
		// Structurize result object
		RequestValidator::structurizeObject ( $notifications );
		return new ServiceResponse ( ServiceResponse::SUCCESS, $notifications );
	}
	
	/**
	 * Return Notifications with specified ID
	 *
	 * @return Service response object with the following status codes:
	 */
	public function getNotificationsById() {
		// Utilize SNMPNotification::getSnmpNotification call. Incoming filters should already have filter by id.
		$notification = SNMPNotification::getSnmpNotification ( $this->getQueryParameters () );
		// Structurize result object
		RequestValidator::structurizeObject ( $notification );
		if (count ( $notification ['notifications'] ) == 0) {
			return new ServiceResponse ( ServiceResponse::NOT_FOUND );
		} else {
			return new ServiceResponse ( ServiceResponse::SUCCESS, $notification );
		}
	}
	
	/**
	 * Modify Notifications
	 *
	 * @return Service response object with the following status codes:
	 */
	public function modifyNotifications() {
		$retVal = SNMPNotification::modifySnmpNotifications ( $this->getContent () );
		if (isset ( $retVal ['status'] ) && $retVal ['status']) {
			return new ServiceResponse ( ServiceResponse::SUCCESS, [ ] );
		}
		
		return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
				'error' => 'error' 
		) );
	}
	
	/**
	 * Add Notifications Dest
	 *
	 * @return Service response object with the following status codes:
	 */
	public function addNotifications() {
		$retVal = SNMPNotification::addSnmpNotification ( $this->getContent () );
		if (isset ( $retVal ['status'] ) && $retVal ['status']) {
			return new ServiceResponse ( ServiceResponse::SUCCESS, [ 
					'id' => $retVal ['id'] 
			] );
		}
		
		return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
				'error' => $retVal ['error'] 
		) );
	}
	/**
	 * Delete Notifications Dest
	 *
	 * @return Service response object with the following status codes:
	 */
	public function deleteNotifications() {
		$retVal = SNMPNotification::deleteSnmpNotification ( $this->getContent () );
		
		if (isset ( $retVal ['status'] ) && $retVal ['status']) {
			return new ServiceResponse ( ServiceResponse::SUCCESS, [ ] );
		}
		return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
				'error' => $retVal ['error'] 
		) );
	}
}
