<?php
namespace Unified\Services\API\RequestValidators;

class PermissionDescription
{
    public function __call($method, $args)
    {
        return new RequestValidator([]);
    }
}