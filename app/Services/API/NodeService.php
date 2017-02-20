<?php
namespace Unified\Services\API;

use Unified\Http\Helpers\QueryParameters;
use Unified\Models\DeviceClass;
use Unified\Models\DeviceType;
use Unified\Models\Node;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;

/**
 * Handles Node-related functions for the API.
 */
class NodeService extends APIService {
    /**
     * Node Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request) {
        parent::__construct ( $request, RequestValidator::getValidator ( $request->getType (), $request->getAction () ) );
    }
    
    /**
     * Return list of node classes
     *
     * @return Service response object with the following status codes:
     */
    public function getNodeClasses() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, DeviceClass::getDeviceClasses ( $this->getQueryParameters() ) );
    }
    
    /**
     * Return list of node types
     *
     * @return Service response object with the following status codes:
     */
    public function getNodeTypes() {
        $nodeTypes = DeviceType::getDeviceTypes ( $this->getQueryParameters() );
        // Structurize result object
        RequestValidator::structurizeObject ( $nodeTypes );
        return new ServiceResponse ( ServiceResponse::SUCCESS, $nodeTypes );
    }
    
    /**
     * Return list of nodes
     *
     * @return Service response object with the following status codes:
     */
    public function getNodes() {
        $nodes = Node::getNodes ( $this->getQueryParameters() );
        // Structurize result object
        RequestValidator::structurizeObject ( $nodes );
        return new ServiceResponse ( ServiceResponse::SUCCESS, $nodes );
    }
    /**
     * Return node with specified ID
     *
     * @return Service response object with the following status codes:
     */
    public function getNodeById() {
        $params = $this->getQueryParameters();
        $depth = $params->getControlParam ( 'depth' );
        
        // Utilize getNodes call. Incoming filters should already have filter by node id.
        $serverResponse = self::getNodes ( $params );
        $nodes = $serverResponse->getContent ();
        // add child info if requested by depth parameter
        if ($depth > 0) {
            self::addChildInfo ( $nodes ['nodes'], $params, $depth );
        }
        
        // Structurize result object
        RequestValidator::structurizeObject ( $nodes );
        if (count ( $nodes ['nodes'] ) == 0) {
            return new ServiceResponse ( ServiceResponse::NOT_FOUND );
        } else {
            return new ServiceResponse ( ServiceResponse::SUCCESS, $nodes );
        }
    }
    
    /**
     * Add node
     *
     * @return Service response object with the following status codes:
     */
    public function addNode() {
        $content = $this->getContent();
        // Convert UTC to local time
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastAlarmsScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastPropertiesScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastFailedScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastFailedAlarmsScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastFailedPropertiesScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastHeartbeat' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.stopScanUntil' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.stopAlarmUntil' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.stopPropertyUntil' );
        
        $retVal = Node::addNode($content);
        
        if (isset ( $retVal ['status'] ) && $retVal ['status']) {
            return new ServiceResponse ( ServiceResponse::SUCCESS, [ 'nodeId' => $retVal['nodeId'] ] );
        } else {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY,
                    array ('error' => $retVal ['error']) );
        }
    }
    
    /**
     * Modify node
     *
     * @return Service response object with the following status codes:
     */
    public function modifyNode() {
        $content = $this->getContent();
        // Convert UTC to local time
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastAlarmsScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastPropertiesScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastFailedScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastFailedAlarmsScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastFailedPropertiesScan' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.lastHeartbeat' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.stopScanUntil' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.stopAlarmUntil' );
        QueryParameters::convertUtcToLocalTime ( $content, 'd.stopPropertyUntil' );
        
        $retVal = Node::modifyNode($content);
        
        if (isset ( $retVal ['status'] ) && $retVal ['status']) {
            return new ServiceResponse ( ServiceResponse::SUCCESS, [] );
        } else {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY,
                    array ('error' => $retVal ['error']) );
        }
    }
    
    /**
     * Delete node
     *
     * @return Service response object with the following status codes:
     */
    public function deleteNode() {
        $retVal = Node::deleteNode($this->getContent());
        
        if (isset ( $retVal ['status'] ) && $retVal ['status']) {
            return new ServiceResponse ( ServiceResponse::SUCCESS, [] );
        } else {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY,
                    array ('error' => $retVal ['error']) );
        }
    }
    
    private static function addChildInfo(&$result, $config, $depth) {
        if ($depth == 0) {
            return;
        }
        
        // Loop through nodes and add children data if possible
        foreach ( $result as &$node ) {
            if (! empty ( $node->children )) {
                foreach ( $node->children as &$child ) {
                    // Modify requested ID by replacing filter for 'nntm.node_id'
                    // FIXME: This is not right way to do it. We should not modify original request
                    $config->modifyFilter ( 'nntm.node_id', '=', $child->id );
                    // Request node data
                    $serverResponse = self::getNodes ( $config );
                    $res = $serverResponse->getContent ();
                    
                    $child = $res ['nodes'];
                    // Check child childrens
                    self::addChildInfo ( $child, $config, $depth - 1 );
                }
            }
        }
    }
    }
