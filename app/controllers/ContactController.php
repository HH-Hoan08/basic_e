<?php
class ContactController extends Controller {
    public function index() {
        $this->view('contact', ['pageTitle' => 'Basic Shop - Liên Hệ']);
    }
}