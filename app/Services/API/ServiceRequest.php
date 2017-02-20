<?php

namespace Unified\Services\API;

/**
 * API service request.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
class ServiceRequest {
    /** @var Request type*/
    private $type = '';
    /** @var Request action*/
    private $action = '';
    /** @var array Request data */
    private $data = [ ];
    /** @var array User information*/
    private $user = [ ];
    /** @var array PAth to the requested service*/
    private $servicePath = "";
    
    /**
     * Service request constructor.
     *
     * @param unknown $type
     *            Request type
     * @param unknown $action
     *            Request action
     * @param unknown $data
     *            Request data
     * @param unknown $user
     *            User info
     */
    public function __construct($type, $action, $data, $user, $servicePath = 'Unified\\Services\\API\\') {
        $this->type = $type;
        $this->action = $action;
        $this->data = $data;
        $this->user = $user;
        $this->servicePath = $servicePath;
    }
    
    /**
     * Returns service type
     */
    final public function getType() {
        return $this->type;
    }
    
    /**
     * Returns service action
     */
    final public function getAction() {
        return $this->action;
    }
    
    /**
     * Returns user
     */
    final public function getUser() {
        return $this->user;
    }
    
    /**
     * Returns data
     */
    final public function getData() {
        return $this->data;
    }
    
    /**
     * Returns path to the service
     */
    final public function getServicePath() {
        return $this->servicePath;
    }
    
    /**
     * Sets data.
     * TODO This function is created as hack to allow to modify incoming getNodesId request data
     * to provide response with various node depth. REMOVE in RELEASE.
     */
    final public function setData($data) {
        $this->data = $data;
    }
    
    /**
     * Returns particular request data parameter
     * @param unknown $parameter Target parameter
     * @return mixed|NULL
     */
    final public function getDataParameter($parameter) {
        if (is_array($this->data) && array_key_exists($parameter, $this->data)) {
            return $this->data[$parameter];
        }
        return null;
    }
}
