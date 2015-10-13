<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 10/9/15
 * Time: 6:20 PM
 */
class Error {

    public $message;

    public function __construct($message="") {
        $this->message = $message;
    }

}