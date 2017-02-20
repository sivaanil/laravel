<?php
namespace Unified\Models;

class BaseFactory extends BaseSingleton
{
    use CacheTrait;
    use DatabaseTrait;
}