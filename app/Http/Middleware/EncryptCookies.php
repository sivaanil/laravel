<?php

namespace Unified\Http\Middleware;

/**
 * Description of EncryptCookies
 *
 * @author ross.keatinge
 */
class EncryptCookies extends \Illuminate\Cookie\Middleware\EncryptCookies
{

    protected $except = ['guacuser', 'guacpwd', 'guaccid', 'GUAC_AUTH'];

}
