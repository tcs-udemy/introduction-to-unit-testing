<?php
namespace Acme\Controllers;

use Acme\Models\Page;

/**
 * Class PageController
 * @package Acme\Controllers
 */
class PageController extends BaseControllerWithDI {

    /**
     * Show the home page
     * @return html
     */
    public function getShowHomePage()
    {
        return $this->response
            ->withView('home')
            ->render();
    }


    /**
     * Show a generic page from db
     * @return html
     */
    public function getShowPage()
    {
        // extract page name from the url
        $uri = explode("/", $this->request->server['REQUEST_URI']);
        $target = $uri[1];

        // find matching page in the db
        $page = Page::where('slug', '=', $target)->first();

        // look up page content
        if ($page) {
            $browser_title = $page->browser_title;
            $page_content = $page->page_content;
            $page_id = $page->id;
        }

        if (!isset($browser_title)) {
            $this->getShow404();
            exit();
        }

        return $this->response
            ->with('browser_title', $browser_title)
            ->with('page_content', $page_content)
            ->with('page_id', $page_id)
            ->withView('generic-page')
            ->render();
    }


    /**
     * show 404 page
     */
    public function getShow404()
    {
        return $this->response
            ->withView('page-not-found')
            ->withError("Page not found!")
            ->withResponseCode(404)
            ->render();
    }

}
