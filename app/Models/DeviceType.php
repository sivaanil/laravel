<?php namespace Unified\Models;

use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

require_once ENV('CSWAPI_ROOT') . '/common/class/cssEncryption.php';

class DeviceType extends Eloquent
{
    use QueryTrait;

    // Enumeration for specific device types
    const CONTACT_CLOSURE = 1620;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_type';
    public $timestamps = false;

    protected $encrypted = ['defaultWebUiPw', 'defaultSNMPRead', 'defaultSNMPWrite', 'SNMPauthPassword', 'SNMPprivPassword'];

    public function device(){
        return $this->belongsTo('Device','type_id');
    }

    public function getAttribute($key)
    {
        if (array_key_exists($key, array_flip($this->encrypted)) && ctype_xdigit($returnArray[$key]))
        {
            return \cssEncryption::getInstance()->Decrypt(parent::getAttribute($key));
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (array_key_exists($key, array_flip($this->encrypted)))
        {
            parent::setAttribute($key, \cssEncryption::getInstance()->Encrypt($value));
            return;
        }

        parent::setAttribute($key, $value);
    }

    public function toArray(){
        $returnArray = $this->attributesToArray();
        foreach ($this->encrypted as $key) {
            if (array_key_exists($key, $returnArray) && ctype_xdigit($returnArray[$key]))
            {
                $returnArray[$key] = \cssEncryption::getInstance()->Decrypt($returnArray[$key]);
            }
        }
        return $returnArray;
    }
    
    public static function getById($nodeTypeId) {
        return DB::table ( 'css_networking_device_type' )->select ( "*" )-> where('id','=',$nodeTypeId)->get();
    }
    
    public static function getDeviceTypes(QueryParameters $config) {
        
        if (class_exists('\cssEncryption')) {
            DB::statement('SET @css_encryption_key = :key', ['key' => \cssEncryption::getInstance()->getKey()]);
        }
        
        $fields = $config->getFields();
        // Remove port related fields from field's list.
        // We will add port related information later if necessary
        $portFields = QueryParameters::popElementsStartingWith ( $fields, "ndpd." );
    
        if (! empty ( $portFields )) {
            // Add ID to be latter used to search for port information
            // Field __id__ will be removed during port search
            $fields [] = "ndt.id as __id__";
        }
        
        $filters = $config->getFilters();
        $sortby = $config->getSortby();
        
        // Check if port definition related parameters are present in filters.
        $portDefJoinIsrequired = QueryParameters::isPresentInFilters( $filters, 'ndpd.') ||
                                 QueryParameters::isPresentInFilters($sortby, 'ndpd.');
        
        // Start query construction
        $query = DB::table ( 'css_networking_device_type as ndt' );
        $query = self::setFields($query, $fields, $config->isCount());
        $query->leftjoin ( "css_networking_device_class as ndc", "ndt.class_id", "=", "ndc.id" );

        if ($portDefJoinIsrequired) {
            $query->leftjoin ( "css_networking_device_port_def as ndpd", "ndpd.device_type_id", "=", "ndt.id" );
            $query = $query->groupBy('ndt.id');
        }
        
        // Apply filters
        $query = self::setFilters ( $query, $filters);
        
        // Apply sortby
        $query = self::setSortby ( $query, $sortby );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );
        
        // Execute query
        $retVal = self::getResults ( $query, $config->isCount (), 'nodeTypes' );
        
        // add port information to results if necessary
        if (! empty ( $portFields )) {
            foreach ( $retVal [ 'nodeTypes' ] as &$dt ) {
                $ports = DB::table ( 'css_networking_device_port_def as ndpd' )->select ( $portFields )->where (
                        "ndpd.device_type_id",
                        $dt->__id__ )->get ();
                        // Add port info to deviceType object
                        $dt->ports = $ports;
                        // Remove index used to get port information
                        unset ( $dt->__id__ );
            }
        }
        
        return $retVal;
    }

    public static function getDeviceTypeList($className)
    {
        return DB::table('css_networking_device_type AS dt')
                        ->select('dt.id', 'dt.vendor', 'dt.model', 'dc.id AS classId')
                        ->join('css_networking_device_class AS dc ', 'dc.id', '=', 'dt.class_id')
                        ->where('dt.auto_build_enabled', '=', DB::raw('?'))
                        ->whereIn('dc.id', function ($query) {
                            $query->select('id')
                            ->from('css_networking_device_class')
                            ->where('description', '=', DB::raw('?'));
                        })
                        ->setBindings(['1', $className])
                        ->get();
    }

    public static function verifyTypeAgainstClass($class)
    {
        $types = DB::Table('css_networking_device_type AS dt')
                ->select('dt.id AS id')
                ->whereIn('dt.class_id', function ($query) {
                    $query->select('id')
                    ->from('css_networking_device_class')
                    ->where('description', '=', DB::raw('?'));
                })
                ->setBindings([$class])
                ->get();

        $validTypes = [];
        foreach ($types as $typeId) {
            array_push($validTypes, $typeId->id);
        }
        return implode(", ", $validTypes);
    }

}
