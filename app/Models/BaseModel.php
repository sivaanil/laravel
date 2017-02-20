<?php
namespace Unified\Models;

use \Config;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use QueryTrait;
    use DatabaseTrait;
    use CacheTrait;

    protected static $unguarded = true;

    /**
     * construct
     *
     * Set the model attributes and the database connection
     */
    public function __construct($attributes = array(), $connection = null)
    {
        // If a database connection is passed in,
        // use that as the connection for the model.
        if (!is_null($connection)) {
            $this->setDb($connection);
        }

        // Pass the attributes up to the superclass's construct
        parent::__construct($attributes);
    }

    /**
     * generateError
     *
     * Generate Error structure
     *
     * @param string $errorMessage Error Message
     */
    protected function generateError($errorMessage)
    {
        return QueryTrait::error($errorMessage);
    }
}