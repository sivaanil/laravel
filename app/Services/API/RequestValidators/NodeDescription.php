<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Node description validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NodeDescription {
    public function getNodeClassesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'id' => 'id',
                        'name' => 'description' 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getNodeTypesValidator() {
        $snmpParams = [ 
                'snmpVersion' => 'ndt.defaultSNMPVer',
                'snmpRead' => 'css_decrypt(ndt.defaultSNMPRead)',
                'snmpWrite' => 'css_decrypt(ndt.defaultSNMPWrite)',
                'authType' => 'ndt.SNMPauthType',
                'username' => 'ndt.SNMPuserName',
                'authPassword' => 'css_decrypt(ndt.SNMPauthPassword)',
                'authEncryption' => 'ndt.SNMPauthEncryption',
                'privPassword' => 'css_decrypt(ndt.SNMPprivPassword)',
                'privEncryption' => 'ndt.SNMPprivEncryption' 
        ];
        
        $webUiParams = [ 
                'webUiLink' => 'ndt.defaultWebUi',
                'username' => 'ndt.defaultWebUiUser',
                'password' => 'css_decrypt(ndt.defaultWebUiPw)' 
        ];
        $portsDefParams = [ 
                'portDefId' => 'ndpd.id',
                'name' => 'ndpd.name',
                'port' => 'ndpd.default_port' 
        ];
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'id' => 'ndt.id',
                        'classId' => 'ndt.class_id',
                        'vendor' => 'ndt.vendor',
                        'model' => 'ndt.model',
                        'class' => 'ndc.description',
                        'autoBuild' => 'ndt.auto_build_enabled',
                        'canAddChildren' => 'ndt.can_add_children',
                        'mainDevice' => 'ndt.main_device',
                        'usesDefaultValue' => 'ndt.uses_default_value',
                        'usesSnmp' => 'ndt.uses_snmp',
                        'snmp' => $snmpParams,
                        'hasWebInterface' => 'ndt.has_web_interface',
                        'webUi' => $webUiParams,
                        'ports' => [$portsDefParams]
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getNodesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => NodeFields::nodeParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getNodeByIdValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => NodeFields::nodeParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        return $validator;
    }
    public function addNodeValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'typeId' => 'type_id',
                        'name' => 'dg.name',
                        'parent' => 'parent_node_id' 
                ],
                RequestValidator::OPTIONAL => [ 
                        'uuid' => 'ntm.uuid',
                        'ports' => [ 
                                NodeFields::setPortsParams () 
                        ],
                        'stats' => NodeFields::setStatsParams (),
                        'config' => NodeFields::setConfigParams (),
                        'info' => NodeFields::setInfoParams (),
                        'webEnabled' => 'd.web_enabled',
                        'isSiteportalDevice' => 'd.is_siteportal_device',
                        'webUi' => NodeFields::setWebUiParams (),
                        'visible' => 'ntm.visible',
                        'currentStatusId' => 'd.current_status_id',
                        'snmp' => NodeFields::setSnmpParams () 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        // Specify custom add node content validator function to be run during content validation
        $validator->validateContent ( array (
                $this,
                'addNodeContentValidator' 
        ));
        return $validator;
    }
    
    /**
     * Custom node validation function
     * @param unknown $contentArray
     */
    public function addNodeContentValidator($contentArray) {
        $results = [];
        // TODO Add node content verifications (snmp, etc.)
        // TODO test content validator
        return $results;
    }
    
    public function modifyNodeValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'id' 
                ],
                RequestValidator::OPTIONAL => [ 
                        'uuid' => 'ntm.uuid',
                        'name' => 'dg.name',
                        'parent' => 'parent_node_id',
                        'ports' => [ 
                                NodeFields::setPortsParams () 
                        ],
                        'stats' =>  NodeFields::setStatsParams (),
                        'config' => NodeFields::setConfigParams (),
                        'info' => NodeFields::setInfoParams (),
                        'webEnabled' => 'd.web_enabled',
                        'isSiteportalDevice' => 'd.is_siteportal_device',
                        'webUi' => NodeFields::setWebUiParams (),
                        'visible' => 'ntm.visible',
                        'currentStatusId' => 'd.current_status_id',
                        'snmp' => NodeFields::setSnmpParams () 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
    public function deleteNodeValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'id' 
                ],
                RequestValidator::OPTIONAL => [
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
}
