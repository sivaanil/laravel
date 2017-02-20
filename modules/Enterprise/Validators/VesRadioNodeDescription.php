<?php

namespace Modules\Enterprise\Validators;

use Unified\Services\API\RequestValidators\RequestValidator;

/**
 * VES Radio Node validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class VesRadioNodeDescription {
    public function getNodesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'serviceNodeSN' => 'GetMainDevicePropertyByNodeMap(nntm.node_map,"Serial Number")' 
                ] + NodeFields::radioNodeParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
}
