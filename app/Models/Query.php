<?php
namespace Unified\Models;

/**
 * Query
 *
 * Designed to be an object-oriented based wrapper around the heavily
 * statically designed QueryTrait.
 *
 * This will allow for the query-based functionally to be tied to the query
 * itself and not the models, while also allowing for mocking during
 * automated unit testing.
 */
class Query
{
    use QueryTrait;

    private $query = null;
    
    /**
     * construct
     *
     * @param \Illuminate\Database\Query\Builder $dbQuery
     */
    public function __construct($query)
    {
        $this->query = $query;;
    }

    /**
     * setQueryFields
     *
     * @param type $fields
     * @param type $isCount
     * @return \Unified\Models\Query
     */
    public function setQueryFields($fields, $isCount)
    {
        self::setFields($this->query, $fields, $isCount);

        return $this;
    }

    /**
     * setQueryFilters
     *
     * @param type $filters
     * @return \Unified\Models\Query
     */
    public function filter($filters)
    {
        self::setFilters($this->query, $filters);

        return $this;
    }

    /**
     * setQuerySortby
     *
     * @param type $sortby
     * @return \Unified\Models\Query
     */
    public function sortBy($sortby)
    {
        self::setSortby($this->query, $sortby);

        return $this;
    }

    /**
     * setQueryPagination
     *
     * @param type $offset
     * @param type $limit
     * @return \Unified\Models\Query
     */
    public function paginate($offset, $limit)
    {
        self::setPagination($this->query, $offset, $limit);

        return $this;
    }

    /**
     * getQueryResults
     */
    public function getQueryResults($isCount, $contentName)
    {
        return self::getResults($this->query, $isCount, $contentName);
    }
}