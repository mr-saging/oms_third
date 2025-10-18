<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function food_menu()
	{
		$this->load->view('food_menu');
	}

    public function food_cart()
    {
        $this->load->view('food_cart');
    }

    public function food_checkout()
    {
        $this->load->view('food_checkout');
    }
}
