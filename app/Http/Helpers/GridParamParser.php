<?php

namespace Unified\Http\Helpers;

use \StdClass;

class GridParamParser {

    protected $data       = null;
    protected $filters    = [];
    protected $pagination = null;
    protected $sort       = null;

    public function __construct(Array $data = []) {
        if (!empty($data)) {
            $this->data = $data;
        }
    }

    public function getFilters() {
        return $this->filters;
    }

    public function getSort() {
        return $this->sort;
    }

    public function getPagination() {
        return $this->pagination;
    }

    public function parse() {

        // Process filters
        if (array_key_exists('filterscount', $this->data)) {
            $filtersCount = $this->data['filterscount'];

            for ($i = 0; $i < $filtersCount; ++$i) {
                $this->filters[] = (object)[
                    'value'     => $this->data['filtervalue'.$i],
                    'condition' => $this->data['filtercondition'.$i],
                    'operator'  => $this->data['filteroperator'.$i],
                    'datafield' => $this->data['filterdatafield'.$i],
                ];
            }
        }

        // Process sorting
        if (array_key_exists('sortdatafield', $this->data)) {
            $this->sort = (object)[
                'datafield' => $this->data['sortdatafield'],
                'order'     => $this->data['sortorder'],
            ];
        }

        // Process pagination data
        $this->pagination = (object)[
            'page'              => !empty($this->data['pagenum']) ? $this->data['pagenum'] : '1',
            'pagesize'          => !empty($this->data['pagesize']) ? $this->data['pagesize'] : '',
            'recordStartIndex'  => !empty($this->data['recordstartindex']) ? $this->data['recordstartindex'] : '',
            'recordEndIndex'    => !empty($this->data['recordendindex']) ? $this->data['recordendindex'] : '',
        ];

    }

    /**
     * Returns the MySQL condition corresponding to a jqxGrid filter condition
     * @param StdClass $filter The filter to turn into Eloquent params
     * @return
     */
    public function getWhereClause($filter) {
        $fld            = $filter->datafield;
        $condition      = $filter->condition;
        $filtervalue    = $filter->value;

        $value = "";
        switch ($condition) {
            case "CONTAINS":
                $condition = "LIKE";
                $value = "%{$filtervalue}%";
                break;
            case "DOES_NOT_CONTAIN":
                $condition = "NOT LIKE";
                $value = "%{$filtervalue}%";
                break;
            case "EQUAL":
                $condition = "=";
                $value = $filtervalue;
                break;
            case "NOT_EQUAL":
                $condition = "<>";
                $value = $filtervalue;
                break;
            case "GREATER_THAN":
                $condition = ">";
                $value = $filtervalue;
                break;
            case "LESS_THAN":
                $condition = "<";
                $value = $filtervalue;
                break;
            case "GREATER_THAN_OR_EQUAL":
                $condition = ">=";
                $value = $filtervalue;
                break;
            case "LESS_THAN_OR_EQUAL":
                $condition = "<=";
                $value = $filtervalue;
                break;
            case "STARTS_WITH":
                $condition = "LIKE";
                $value = "{$filtervalue}%";
                break;
            case "ENDS_WITH":
                $condition = "LIKE";
                $value = "%{$filtervalue}";
                break;
            case "NULL":
                $condition = "IS NULL";
                $value = "";
                break;
            case "NOT_NULL":
                $condition = "IS NOT NULL";
                $value = "";
                break;
        }

        return (object)[
            'field'     => $fld,
            'condition' => $condition,
            'value'     => $value,
        ];
    }

}
