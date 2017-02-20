<?php

namespace Modules\Enterprise\Validators;

use Unified\Services\API\RequestValidators\RequestValidator;

/**
 * VES Service Node validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class VesServiceNodeDescription {
    public function getNodesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => NodeFields::nodeParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
}
