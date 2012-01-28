<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trade extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('auctions_model');
		$this->load->model('items_model');
		$this->load->model('static_model');
		$this->load->model('players_model');
		$this->load->model('market_model');
		$this->load->model('enchantments_model');
		$this->load->model('player_items_model');
		
	}

	function index()
	{
		redirect('/');
	}
	function buy_static()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);	
			$static_id = $this->input->post('ID');
			$quant = $this->input->post('Quantity');
			
			$static_info = $this->static_model->get_static($static_id);
			
			if ($quant > 0){
				if (is_numeric($quant)){
					if ($user_data->money >= ($static_info[0]->buy * $quant)){
						$player_item = $this->player_items_model->get_item_match($static_info[0]->item_id, $username);
						if (empty($player_item))
						{
							$this->player_items_model->new_player_item($static_info[0]->item_id, $username, $quant);	
						}else{
							$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity + $quant);
						}
						$this->players_model->set_money($username, $user_data->money - ($static_info[0]->buy * $quant));	
						$this->players_model->set_purchases($username, $user_data->bought + $quant, $user_data->spent + ($static_info[0]->buy * $quant));
						echo "done";
					}else{
						echo "not enough money";	
					}
				}else{
					echo "not a number";	
				}
			}else{
				echo "negative number";
			}
		}else{
			echo "not logged in";
		}
	}
	function sell_static()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);	
			$static_id = $this->input->post('ID');
			$quant = $this->input->post('Quantity');
			
			$static_info = $this->static_model->get_static($static_id);
			
			if ($quant > 0){
				if (is_numeric($quant)){
					$player_item = $this->player_items_model->get_item_match($static_info[0]->item_id, $username);
					if (!empty($player_item))
					{
						if ($player_item[0]->quantity >= $quant)
						{
							$this->players_model->set_money($username, $user_data->money + ($static_info[0]->sell * $quant));	
							$this->players_model->set_sales($username, $user_data->sold + $quant, $user_data->earnt + ($static_info[0]->sell * $quant));
							$newQuant = $player_item[0]->quantity - $quant;
							if ($newQuant > 0){
								$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity - $quant);
							}else{
								$this->player_items_model->delete_item($player_item[0]->id);	
							}
							echo "done";
						}else{
							echo "not enough items";	
						}
					}else{
						echo "no items";
					}	
				}else{
					echo "not a number";	
				}
			}else{
				echo "negative number";	
			}			
		}else{
			echo "not logged in";	
		}
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
				if (is_numeric($quant)){
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
							
								$this->players_model->set_purchases($username, $user_data->bought + $quant, $user_data->spent + ($auction_info[0]->price * $quant));
								$this->players_model->set_sales($seller_data->username, $seller_data->sold + $quant, $seller_data->earnt + ($auction_info[0]->price * $quant));
							
								$market_price = $this->market_model->get_market_price($auction_info[0]->item_id);
								if (empty($market_price))
								{
									$this->market_model->new_market_price($auction_info[0]->item_id, $quant, $auction_info[0]->price);
								}else{
									$current_count = $market_price[0]->quantity;
									$current_price = $market_price[0]->price;
									$total = $current_count * $current_price + ($quant *  $auction_info[0]->price);
									$new_market_price = $total / ($current_count + $quant);
									$this->market_model->set_price($market_price[0]->id, $current_count + $quant, $new_market_price);
								}
								$this->session->set_msg("Success! Items bought.");
								redirect('/auctions');	
							}else{
								$this->session->set_error("Sorry but you are not allowed to buy your own items. Forever alone.");
								redirect('/auctions'); //can't buy own items		
							}
						}else{
							$this->session->set_error("You do not appear to have enough money to buy those items.");
							redirect('/auctions'); //not enough money
						}
					}else{
						$this->session->set_error("That doesn't work, you are trying to buy more items than are available.");
						redirect('/auctions'); //quantity too big	
					}
				}else{
					$this->session->set_error("What was with those letters/symbols? That makes no sense, just a number please.");
					redirect('/auctions'); //quantity negative	
				}
			}else{
				$this->session->set_error("What was with those letters/symbols? That makes no sense, just a number please.");
				redirect('/auctions'); //quantity negative	
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
									if ($newQuant > 0){
										$this->player_items_model->set_quant($item_id, $newQuant);
									}else{
										$this->player_items_model->delete_item($item_info[0]->id);	
									}
									$this->session->set_msg("Success! Auction created.");
									redirect('/myauctions');							
								}else{
									$this->session->set_error("Please enter a valid number for the quantity.");
									redirect('/myauctions'); //quant not a number	
								}
							}else{
								$this->session->set_error("Please enter a valid number for the price.");
								redirect('/myauctions'); //price not a number	
							}
						}else{
							$this->session->set_error("You cannot sell more items than you own, they don't grow on trees you know.");
							redirect('/myauctions'); //not enough items	
						}
					}else{
						$this->session->set_error("Huh? You don't even own that item, how would you like it if I tried to sell your house?");
						redirect('/myauctions');	//don't own item
					}
				}else{
					$this->session->set_error("Please do not try and sell a negative number of items, We do not want to give you free items.");
					redirect('/myauctions');  //negative quant
				}
			}else{
				$this->session->set_error("I don't get what you are trying to do here. Positive numbers for the price only please.");
				redirect('/myauctions');  //negative price
			}
		}else{
			$this->session->set_error("Please log in to be able to create auctions.");
			redirect('/myauctions');  //not logged in
		}
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */