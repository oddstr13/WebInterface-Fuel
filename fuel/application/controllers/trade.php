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
		$this->load->model('players_model');
		$this->load->model('enchantments_model');
		$this->load->model('player_items_model');
		
	}

	function index()
	{
		redirect('/');
	}
	function buy_item()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);	
			$auction_id = $this->input->post('ID');
			$quant = $this->input->post('Quantity');
			
			//echo "Auction ID: ".$auction_id;
			//echo "<br/> With Quantity: ".$quant;
			//echo "By user: ".$user_data->money;
			
			$auction_info = $this->auctions_model->get_auction($auction_id);
			$seller_data = $this->users->get_user_by_username($auction_info[0]->seller);
			//echo "<pre>";
			//print_r($auction_info);
			//echo "</pre>";
			if ($quant > 0){
				if ($quant <= $auction_info[0]->quantity){
					if ($user_data->money >= ($auction_info[0]->price * $quant)){
						if ($username != $auction_info[0]->seller){
							$player_item = $this->player_items_model->get_item_match($auction_info[0]->item_id, $username);
							if (empty($player_item))
							{
								$this->player_items_model->new_player_item($auction_info[0]->item_id, $username, $quant);	
							}else{
								$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity + $quant);
							}
							$number_left = $auction_info[0]->quantity - $quant;
							if ($number_left <= 0){
								$this->auctions_model->delete_auction($auction_info[0]->id);	
							}else{
								$this->auctions_model->set_quantity($auction_info[0]->id, $auction_info[0]->quantity - $quant);
							}
							$this->players_model->set_money($username, $user_data->money - ($auction_info[0]->price * $quant));
							$this->players_model->set_money($seller_data->username, $seller_data->money + ($auction_info[0]->price * $quant));
							redirect('/auctions?success=1');	
						}else{
							redirect('/auctions?error=1'); //can't buy own items		
						}
					}else{
						redirect('/auctions?error=2'); //not enough money
					}
				}else{
					redirect('/auctions?error=3'); //quantity too big	
				}
			}else{
				redirect('/auctions?error=4'); //quantity negative	
			}
		}else{
			redirect('/login');	
		}
	}
	function new_auction()
	{
 
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);
			$item_id = $this->input->post('Item');
			$quant = $this->input->post('Quantity');
			$price = $this->input->post('Price');

			$item_info = $this->player_items_model->get_item($item_id);
			//echo "<pre>";
			//print_r($item_info);
			//echo "</pre>";
			if ($price > 0){
				if ($quant > 0){
					if ($item_info[0]->player == $username){
						if ($item_info[0]->quantity >= $quant){
							if (is_numeric($price)){
								if (is_numeric($quant)){
									$newQuant = $item_info[0]->quantity - $quant;
									$this->auctions_model->new_auction($item_info[0]->item_id, $price, $quant, $item_info[0]->player);
									$this->player_items_model->set_quant($item_id, $newQuant);
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