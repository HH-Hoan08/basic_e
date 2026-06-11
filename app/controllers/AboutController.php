<?php
class AboutController extends Controller {
    public function index() {
        $this->view('about', ['pageTitle' => 'Basic Shop - Về Chúng Tôi']);
    }
}