<?php
namespace Acme\Http;

class Request
{
    public $request;
    public $get;
    public $post;
    public $server;

    public function __construct(Array $request, Array $get = [], Array $post = [])
    {
        $this->request = $request;
        $this->get = $get;
        $this->post = $post;
        $this->server = $_SERVER;
    }

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function input($value)
    {
        $req = $this->getRequest();
        return $req[$value];
    }

}
