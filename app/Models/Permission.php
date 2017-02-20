<?php
namespace Unified\Models;

use \DateTime;

class Permission extends BaseModel
{
    private $data;
    
    /**
     * convertToUnixtime
     */
    protected function convertToUnixtime($value)
    { 
        if (is_object($value)) {
            return $value->getTimestamp();
        } elseif (is_string($value)) {
            return (int) date('U', strtotime($value));
        } elseif (is_int($value)) {
            return $value;
        } else {
            return null;
        }
    }
    
    /**
     * getCreatedAt
     */
    public function getCreatedAt()
    {
        return $this->data->created_at;
    }
    
    /**
     * getUpdatedAt
     */
    public function getUpdatedAt()
    {
        return $this->data->updated_at;
    }
    
    /**
     * construct
     */
    public function __construct($attributes)
    {
        if (property_exists($attributes, 'created_at')) {
            $attributes->created_at = $this->convertToUnixtime($attributes->created_at);
        }
        if (property_exists($attributes, 'updated_at')) {
            $attributes->updated_at = $this->convertToUnixtime($attributes->updated_at);
        }
        
        $this->data = $attributes;
    }

    /**
     * toArray
     */
    public function toArray()
    {
        $results = [
            'id' => $this->data->id,
            'slug' => $this->data->slug,
            'updatedAt' => $this->data->updated_at,
            'deleted' => $this->data->deleted
        ];
        
        // only include this data if the permission was not deleted
        if (!$this->data->deleted) {
            $results['title'] = $this->data->title;
            $results['description'] = $this->data->description;
        }
        
        return $results;
    }
}
