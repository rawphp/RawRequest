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
 * @package   RawPHP/RawRequest/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRequest;

use RawPHP\RawRequest\Request;

/**
 * The request tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawRequest/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * Setup done before test suite run.
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        touch( TEST_LOCK_FILE );
    }
    
    /**
     * Cleanup done after test suite run.
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        
        if ( file_exists( TEST_LOCK_FILE ) )
        {
            unlink( TEST_LOCK_FILE );
        }
    }
    
    /**
     * Setup done before each test.
     */
    protected function setUp()
    {
        $this->request = new Request();
    }
    
    /**
     * Test creating a relative url.
     */
    public function testCreateRelativeUrl()
    {
        $route    = 'home';
        $expected = 'home';
        $result = $this->request->createUrl( $route );
        $this->assertEquals( $expected, $result );
        
        $route    = 'home/index';
        $expected = 'home/index';
        $result = $this->request->createUrl( $route );
        $this->assertEquals( $expected, $result );
        
        $route    = 'home/index';
        $params   = array( 1 );
        $expected = 'home/index/1';
        $result = $this->request->createUrl( $route, $params );
        $this->assertEquals( $expected, $result );
        
        $route    = 'home/index';
        $params   = array( 1, 123456789 );
        $expected = 'home/index/1/123456789';
        $result = $this->request->createUrl( $route, $params );
        $this->assertEquals( $expected, $result );
    }
    
    /**
     * Test creating an absolute url.
     */
    public function testCreateAbsoluteUrl()
    {
        $route    = 'home';
        $expected = BASE_URL . 'home';
        $result = $this->request->createUrl( $route, NULL, TRUE );
        $this->assertEquals( $expected, $result );
        
        $route    = 'home/index';
        $expected = BASE_URL . 'home/index';
        $result = $this->request->createUrl( $route, NULL, TRUE );
        $this->assertEquals( $expected, $result );
        
        $route    = 'home/index';
        $params   = array( 1 );
        $expected = BASE_URL . 'home/index/1';
        $result = $this->request->createUrl( $route, $params, TRUE );
        $this->assertEquals( $expected, $result );
        
        $route    = 'home/index';
        $params   = array( 1, 123456789 );
        $expected = BASE_URL . 'home/index/1/123456789';
        $result = $this->request->createUrl( $route, $params, TRUE );
        $this->assertEquals( $expected, $result );
    }
}
