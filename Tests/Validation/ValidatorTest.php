<?php
namespace Acme\Tests;

use Acme\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {

    protected $request;
    protected $response;
    protected $session;
    protected $blade;


    protected function setUp()
    {
        include(__DIR__ . "/../../bootstrap/functions.php");

        $signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['abc134'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $this->session = $this->getMockBuilder('Acme\Http\Session')
            ->getMock();

        $this->blade = $this->getMockBuilder('duncan3dc\Laravel\BladeInstance')
            ->setConstructorArgs(['abc', 'abc'])
            ->getMock();

        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $signer, $this->blade, $this->session])
            ->getMock();
    }

    function getReq($input = "")
    {
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        if (strlen($input) > 0) {
            $req->expects($this->once())
                ->method('input')
                ->will($this->returnValue($input));
        }

        return $req;
    }


    public function testGetIsValidReturnsTrue()
    {
        // just to show that we can, we'll instantiate a real object here, and not a mocked object.
        $validator = new Validator($this->request, $this->response, $this->session);
        $validator->setIsValid(true);
        $this->assertTrue($validator->getIsValid());
    }


    public function testGetIsValidReturnsFalse()
    {
        // just to show that we can, we'll instantiate a real object here, and not a mocked object.
        $validator = new Validator($this->request, $this->response, $this->session);
        $validator->setIsValid(false);
        $this->assertFalse($validator->getIsValid());
    }


    public function testCheckForMinStringLengthWithValidData()
    {
        // our $request from the constructor does not have any request parameters, so
        // we'll make a different one by calling getReq
        $req = $this->getReq('yellow');

        $validator = new Validator($req, $this->response, $this->session);
        $errors = $validator->check(['mintype' => 'min:3']);

        $this->assertCount(0, $errors);
    }


    public function testCheckForMinStringLengthWithInvalidData()
    {
        // our $request from the constructor does not have any request parameters, so
        // we'll make a different one by calling getReq
        $req = $this->getReq('x');

        $validator = new Validator($req, $this->response, $this->session);
        $errors = $validator->check(['mintype' => 'min:3']);

        $error_msg = $errors[0];
        $this->assertTrue(strContains($error_msg, "must be at least"));
    }


    public function testCheckForEmailWithValidData()
    {
        // our $request from the constructor does not have any request parameters, so
        // we'll make a different one by calling getReq
        $req = $this->getReq('john@here.com');

        $validator = new Validator($req, $this->response, $this->session);
        $errors = $validator->check(['mintype' => 'email']);
        $this->assertCount(0, $errors);
    }


    public function testCheckForEmailWithInvalidData()
    {
        // our $request from the constructor does not have any request parameters, so
        // we'll make a different one by calling getReq
        $req = $this->getReq('email');

        $validator = new Validator($req, $this->response, $this->session);
        $errors = $validator->check(['mintype' => 'email']);
        $error_msg = $errors[0];
        $this->assertTrue(strContains($error_msg, "must be a valid email"));
    }


    public function testCheckForEqualToWithInvalidData()
    {
        // we do not specify setMethods, so all methods are stubs, all return null,
        // and all are easy to override
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->at(0))
            ->method('input')
            ->will($this->returnValue('jack'));

        $req->expects($this->at(1))
            ->method('input')
            ->will($this->returnValue('jill'));

        $validator = new Validator($req, $this->response, $this->session);
        $errors = $validator->check(['my_field' => 'equalTo:another_field']);

        $error_msg = $errors[0];
        $this->assertStringStartsWith("Value does not match", $error_msg);
    }


    public function testCheckForEqualToWithValidData()
    {
        // we did not specify setMethods, so all methods are stubs, all return null,
        // and all are easy to override
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->at(0))
            ->method('input')
            ->will($this->returnValue('jack'));

        $req->expects($this->at(1))
            ->method('input')
            ->will($this->returnValue('jack'));

        $validator = new Validator($req, $this->response, $this->session);
        $errors = $validator->check(['my_field' => 'equalTo:another_field']);
        $this->assertCount(0, $errors);
    }


    public function testCheckForUniqueWithValidData()
    {
        // This time we'll mock the entire Validator class and test that instead of an actual
        // instance of the class.

        // We specify setMethods, so ONLY specified methods are stubbed. All others are mocked,
        // and will run the code behind them. Stubbed can be overridden, and return null by default.
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['getRows'])
            ->getMock();

        $validator->method('getRows')
            ->willReturn([]);

        $errors = $validator->check(['my_field' => 'unique:User']);
        $this->assertCount(0, $errors);
    }


    public function testCheckForUniqueWithInvalidData()
    {
        // This time we'll mock the entire Validator class and test that instead of an actual
        // instance of the class.

        // we specify setMethods, so ONLY specified methods are stubbed. All others are mocked,
        // and will run the code behind them. Stubbed can be overridden, and return null by default
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['getRows'])
            ->getMock();

        $validator->method('getRows')
            ->willReturn(['a']);

        $errors = $validator->check(['my_field' => 'unique:User']);

        $error_msg = $errors[0];
        $this->assertTrue(strContains($error_msg, "already exists in this system"));

    }


    public function testValidateWithValidData()
    {
        // This time we'll mock the entire Validator class and test that instead of an actual
        // instance of the class.

        // we specify setMethods, so ONLY specified methods are stubbed. All others are mocked,
        // and will run the code behind them. Stubbed can be overridden, and return null by default
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['check'])
            ->getMock();

        $isValid = $validator->validate(['foo' => 'min:1'], '/bar');
        $this->assertTrue($isValid);
    }


    public function testValidateWithInvalidData()
    {
        // This time we'll mock the entire Validator class and test that instead of an actual
        // instance of the class.

        // we specify setMethods, so ONLY specified methods are stubbed. All others are mocked,
        // and will run the code behind them. Stubbed can be overridden, and return null by default
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['check', 'redirectToPage'])
            ->getMock();

        $validator->expects($this->once())
            ->method('check')
            ->will($this->returnValue(['some error happened']));

        $validator->expects($this->once())
            ->method('redirectToPage');

        // We can't really use an asssertion here -- this is just a smoke test.
        // This will be tested properly in integration testing.
        // Right now, the fact that it didn't blow up is enough for us!
        $validator->validate(['foo' => 'min:1'], '/bar');
    }

}
