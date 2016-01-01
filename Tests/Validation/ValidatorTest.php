<?php
namespace Acme\Tests;

use Acme\Http\Response;
use Acme\Http\Request;
use Acme\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {

    public function testGetIsValidReturnsTrue()
    {
        $request = new Request([]);
        $response = new Response($request);
        $validator = new Validator($request, $response);

        $validator->setIsValid(true);
        $this->assertTrue($validator->getIsValid());

    }

    public function testGetIsValidReturnsFalse()
    {
        $request = new Request([]);
        $response = new Response($request);
        $validator = new Validator($request, $response);

        $validator->setIsValid(false);
        $this->assertFalse($validator->getIsValid());

    }

    public function testCheckForMinStringLengthWithValidData()
    {
        $request = new Request(['mintype' => 'yellow']);
        $response = new Response($request);
        $validator = new Validator($request, $response);

        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLengthWithInvalidData()
    {
        $request = new Request(['mintype' => 'x']);
        $response = new Response($request);
        $validator = new Validator($request, $response);

        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailWithValidData()
    {
        $request = new Request(['mintype' => 'john@here.com']);
        $response = new Response($request);
        $validator = new Validator($request, $response);

        $errors = $validator->check(['mintype' => 'email']);

        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailWithInalidData()
    {
        $request = new Request(['mintype' => 'whatever']);
        $response = new Response($request);
        $validator = new Validator($request, $response);

        $errors = $validator->check(['mintype' => 'email']);

        $this->assertCount(1, $errors);
    }
}
