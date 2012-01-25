<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trade extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('auctions_model');
		$this->load->model('items_model');
		$this->load->model('enchantments_model');
		
	}

	function index()
	{
		redirect('/');
	}
	function new_auction()
	{
 
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);
			$item_id = $this->input->post('Item');
			$quant = $this->input->post('Quantity');
			$price = $this->input->post('Price');

			$item_info = $this->items_model->get_item($item_id);
			if ($price > 0){
				if ($quant > 0){
					if ($item_info[0]->owner == $username){
						if ($item_info[0]->quantity >= $quant){
							if (is_numeric($price)){
								if (is_numeric($quant)){
									$newQuant = $item_info[0]->quantity - $quant;
									$this->auctions_model->new_auction($item_id, $price, $quant);
									$this->items_model->set_quant($item_id, $newQuant);
									redirect('/myauctions?success=1');							
								}else{
									redirect('/myauctions?error=1'); //quant not a number	
								}
							}else{
								redirect('/myauctions?error=2'); //price not a number	
							}
						}else{
							redirect('/myauctions?error=3'); //not enough items	
						}
					}else{
						redirect('/myauctions?error=4');	//don't own item
					}
				}else{
					redirect('/myauctions?error=5');  //not logged in
				}
			}else{
				redirect('/myauctions?error=6');  //not logged in
			}
		}else{
			redirect('/myauctions?error=7');  //not logged in
		}
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */