<?php

class ControllerCommonFooter extends PT_Controller
{
    public function index()
    {
        return $this->load->view('common/footer');
    }
}
