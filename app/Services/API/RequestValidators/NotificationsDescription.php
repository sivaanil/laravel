<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Notifications description validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NotificationsDescription {
    public function getNotificationsValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => NotificationsFields::getNotificationsParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getNotificationsByIdValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'sn.id' 
                ],
                RequestValidator::OPTIONAL => NotificationsFields::getNotificationsParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        return $validator;
    }
    public function modifyNotificationsValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'sn.id' 
                ],
                RequestValidator::OPTIONAL => 
                    NotificationsFields::modifyNotificationsParams ()
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
    public function addNotificationsValidator() {
        $description = [ 
                RequestValidator::MANDATORY => NotificationsFields::notificationsMandatoryParams (),
                RequestValidator::OPTIONAL => NotificationsFields::addNotificationsParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
    public function deleteNotificationsValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'sn.id' 
                ],
                RequestValidator::OPTIONAL => [
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
}
