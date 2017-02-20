<?php

namespace Unified\Models;

use Unified\Http\Helpers\GridParamParser;

interface GridModel {

    public function getForGrid(GridParamParser $parser, $param = null);

}
