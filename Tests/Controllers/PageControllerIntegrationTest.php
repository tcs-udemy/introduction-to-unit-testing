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
}
