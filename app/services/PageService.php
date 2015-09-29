<?php
/**
 * User: chamroeunoum
 * Date: 11/4/15
 * Time: 13:43
 */

class PageService extends Service {
    public function index() {

        // load component
        // $cmpPage = $this->loadComponent("Page");

        // set data to component to be used by component
        // $cmpPage->set(array("s_var"=>"love you rosa"));

        // set data to the view of Service (Template or block)
        // $this->set(array("template_var"=>"I'm vairable of template") );

        // call method of component
        // $cmpPage->index();

        // load model of component
        // $pageModel = $this->loadModel("Page");

        // call method of component model
        // $pageModel->getPage();

        // call method of block to render a small view to client
        // $this->block( "default" );

        // call method of template to render view to client
         $this->template("default");

    }
}