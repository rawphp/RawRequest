<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 * 
 * Copyright (c) 2014 RawPHP.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * PHP version 5.4
 * 
 * @category  PHP
 * @package   RawPHP/RawRequest
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRequest;

use RawPHP\RawRequest\IRequest;
use RawPHP\RawBase\Component;

/**
 * This class represents a HTTP request.
 * 
 * This class has two required defines:
 *  1. BASE_URL       -> this is the base url of your applicaton
 *  2. TEST_LOCK_FILE -> this is a file flag to indicate that we are testing
 *                       this define is only required for testing purposes
 * 
 * @category  PHP
 * @package   RawPHP/Core
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Request extends Component implements IRequest
{
    public $userAgent;
    public $scheme          = 'https';
    public $server;
    public $script;
    public $port;
    public $method;
    public $query;
    public $requestUri;
    
    public $route;
    public $params;
    
    /**
     * Initialises the request.
     * 
     * @param array $config optional configuration array
     * 
     * @action ON_REQUEST_INIT_ACTION
     */
    public function init( $config = array( ) )
    {
        if ( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) )
        {
            $this->userAgent   = $_SERVER[ 'HTTP_USER_AGENT' ];
        }
        if ( isset( $_SERVER[ 'SERVER_NAME' ] ) )
        {
            $this->server       = $_SERVER[ 'SERVER_NAME' ];
        }
        if ( isset( $_SERVER[ 'SERVER_PORT' ] ) )
        {
            $this->port         = $_SERVER[ 'SERVER_PORT' ];
        }
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) )
        {
            $this->method       = $_SERVER[ 'REQUEST_METHOD' ];
        }
        if ( isset( $_SERVER[ 'QUERY_STRING' ] ) )
        {
            $this->query        = $_SERVER[ 'QUERY_STRING' ];
        }
        if ( isset( $_SERVER[ 'SCRIPT_NAME' ] ) )
        {
            $this->script       = $_SERVER[ 'SCRIPT_NAME' ];
        }
        if ( isset( $_SERVER[ 'REQUEST_URI' ] ) )
        {
            $this->requestUri   = $_SERVER[ 'REQUEST_URI' ];
        }
        if ( isset( $_SERVER[ 'REQUEST_SCHEME' ] ) )
        {
            $this->scheme       = $_SERVER[ 'REQUEST_SCHEME' ];
        }
        
        $request = str_replace( $_SERVER[ 'SCRIPT_NAME' ], '', $this->requestUri );
        
        $elements = explode( '/', $request );
        
        if ( '' === $elements[ 0 ] )
        {
            array_shift( $elements );
        }
        
        if ( 2 <= count( $elements ) )
        {
            $this->route = $elements[ 0 ] . '/' . $elements[ 1 ];
            
            array_shift( $elements );
            array_shift( $elements );
            
            if ( 0 < count( $elements ) )
            {
                $this->params = $elements;
            }
        }
        else if ( 1 === count( $elements ) )
        {
            $this->route = $elements[ 0 ];
            
            array_shift( $elements );
        }
        
        $this->doAction( self::ON_REQUEST_INIT_ACTION );
    }
    
    /**
     * Creates a http url.
     * 
     * This method has a dependency on two defines
     *  1. TEST_LOCK_FILE - test lock file, define it as '/path/to/site/root/test.lock'
     *  2. BASE_URL - this is the base url of your application, e.g, http://rawphp.org
     * 
     * @param string $route    the route
     * @param array  $params   list of parameters (in the correct order)
     * @param bool   $absolute whether the url should be absolute
     * 
     * @filter ON_ROUTER_CREATE_URL_FILTER
     * 
     * @return string the url
     */
    public function createUrl( $route, $params = array(), $absolute = FALSE )
    {
        $url = $this->script . '/' . $route;
        
        if ( file_exists( TEST_LOCK_FILE ) )
        {
            $url = $route;
        }
        
        if ( !empty( $params ) )
        {
            foreach( $params as $value )
            {
                $url .= "/$value";
            }
        }
        
        if ( $absolute )
        {
            if ( !file_exists( TEST_LOCK_FILE ) )
            {
                $url = $this->scheme . '://' . $this->server . $url;
            }
            else
            {
                $url = BASE_URL . $url;
            }
        }
        
        return $this->filter( self::ON_ROUTER_CREATE_URL_FILTER, $url, $route, $params, $absolute );
    }
    
    const ON_REQUEST_INIT_ACTION       = 'on_init_action';
    
    const ON_ROUTER_CREATE_URL_FILTER   = 'on_create_url_filter';
    
    public static $httpCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    );
}
