<?php
namespace Acme\Tests;

use Acme\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {

    protected $request;
    protected $response;
    protected $testdata;


    protected function setUp()
    {
        $signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['abc134'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')->getMock();
        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $signer])
            ->getMock();
    }


    public function testGetIsValidReturnsTrue()
    {
        $validator = new Validator($this->request, $this->response);
        $validator->setIsValid(true);
        $this->assertTrue($validator->getIsValid());
    }


    public function testGetIsValidReturnsFalse()
    {
        $validator = new Validator($this->request, $this->response);
        $validator->setIsValid(false);
        $this->assertFalse($validator->getIsValid());
    }


    public function testCheckForMinStringLengthWithValidData()
    {
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this->returnValue('yellow'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }



    public function testCheckForMinStringLengthWithInvalidData()
    {
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this->returnValue('x'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }


    public function testCheckForEmailWithValidData()
    {
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this->returnValue('john@here.com'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(0, $errors);
    }


    public function testCheckForEmailWithInvalidData()
    {
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this->returnValue('email'));

        $validator = new Validator($req, $this->response);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(1, $errors);
    }

    /*
    public function testValidateWithValidData()
    {
        $this->testdata = ['check_field' => 'john@here.ca'];
        $this->setUpRequestResponse();
        $this->assertTrue($this->validator->validate(['check_field' => 'email'], '/error'));
    }

    public function testValidateWithInvalidData()
    {
        $this->testdata = ['check_field' => 'x'];
        $this->setUpRequestResponse();
        $this->validator->validate(['check_field' => 'email'], '/register');
    }
    */

}
