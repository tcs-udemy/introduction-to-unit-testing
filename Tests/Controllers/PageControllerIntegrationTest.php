<?php
namespace Acme\Tests;

use Acme\Controllers\PageController;

/**
 * Class PageControllerIntegrationTest
 * @package Acme\Tests
 */
class PageControllerIntegrationTest extends AcmeBaseIntegrationTest {

    public function testGetHomePage()
    {
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        $resp->method('render')
            ->willReturn(true);

        $controller = new PageController($this->request, $resp,
            $this->session, $this->signer, $this->blade);

        $controller->getShowHomePage();

        // should have view of home
        $expected = "home";
        $actual = \PHPUnit_Framework_Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);
    }


    public function testGetShowPage()
    {
        // create a mock of Response and make render method a stub
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        // override render method to return true
        $resp->method('render')
            ->willReturn(true);

        // mock the controller and make getUri a stub
        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $resp, $this->session,
                $this->signer, $this->blade])
            ->setMethods(['getUri'])
            ->getMock();

        // orverride getUri to return just the slug from the uri
        $controller->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('about-acme'));

        // call the method we want to test
        $controller->getShowPage();

        // we expect to get the $page object with browser_title set to "About Acme"
        $expected = "About Acme";
        $actual = $controller->page->browser_title;

        // run assesrtion for browser title/page title
        $this->assertEquals($expected, $actual);

        // should have view of generic-page
        $expected = "generic-page";
        $actual = \PHPUnit_Framework_Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);

        // should have page_id of 1
        $expected = 1;
        $actual = $controller->page->id;
        $this->assertEquals($expected, $actual);
    }

}
