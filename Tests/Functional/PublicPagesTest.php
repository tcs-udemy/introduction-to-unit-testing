<?php
namespace Acme\Tests;

/**
 * Class PublicPagesTest
 * @package Acme\Tests
 */
class PublicPagesTest extends AcmeBaseIntegrationTest {

    /**
     * Test public pages
     * @dataProvider provideUrls
     */
    function testPages($urlToTest)
    {
        $response_code = $this->crawl('http://localhost' . $urlToTest);
        $this->assertEquals(200, $response_code);
    }


    /**
     * Test page not found
     */
    function testPageNotFound()
    {
        $response_code = $this->crawl('http://localhost/asdf');
        $this->assertEquals(404, $response_code);
    }



    /**
     * Test showing login page
     */
    function testLoginPage()
    {
        $response_code = $this->crawl('http://localhost/login');
        $this->assertEquals(200, $response_code);
    }


    /**
     * return response code when crawling a given url
     * @param $url
     * @return mixed
     */
    function crawl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $response_code;
    }


    /**
     * @return array
     */
    public function provideUrls()
    {
        return [
            ['/'],
            ['/about-acme'],
            ['/account-activated'],
            ['/success'],
        ];
    }
}
