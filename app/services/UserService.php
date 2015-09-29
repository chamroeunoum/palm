<?php
/**
 * User: chamroeunoum
 * Date: 14/3/15
 * Time: 15:39
 */

class UserService extends Service {
    public function login(){
        /* Call any components or plugins as needed */

        $contact = $this->loadComponent("Contact");
        $contact->index();

        $contactModel = $this->loadModel('Contact');
        $contactRecord = $contactModel->getContact();

        print_r( $contactRecord );

        //$this->template("default");

    }

}