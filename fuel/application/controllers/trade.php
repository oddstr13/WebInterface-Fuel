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
		$this->load->model('mail_model');
		$this->load->model('enchantments_model');
		$this->load->model('player_items_model');
		
	}

	function index()
	{
		redirect('/');
	}
	function mail_item()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username');
			$user_data = $this->users->get_user_by_username($username);	
			$item_id = $this->input->post('ID');
			
			$item_info = $this->player_items_model->get_item($item_id);
			if ($item_info[0]->player == $user_data->id){
				$this->mail_model->new_mail($item_info[0]->item_id, $user_data->id, $item_info[0]->quantity);
				$this->player_items_model->delete_item($item_info[0]->id);
				$this->session->set_msg("Success! Items send to in-game mail box.");
				redirect('/items');
			}else{
				$this->session->set_error("That is not your item, so I will not mail it to you.");	
				redirect('/items');	
			}
		}else{
			$this->session->set_error("Please log in before trying to do that.");	
			redirect('/login');	
		}
	}
	function buy_static()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username');
			$buy_static = $this->session->userdata('can_buy_static');
			$user_data = $this->users->get_user_by_username($username);	
			$static_id = $this->input->post('ID');
			$quant = round($this->input->post('Quantity'),0);
			
			$static_info = $this->static_model->get_static($static_id);
			if ($buy_static == 1){
				if (isset($static_info[0]->buy)){
					if ($quant > 0){
						if (is_numeric($quant)){
							if ($user_data->money >= ($static_info[0]->buy * $quant)){
								$player_item = $this->player_items_model->get_item_match($static_info[0]->item_id, $user_data->id);
								if (empty($player_item))
								{
									$this->player_items_model->new_player_item($static_info[0]->item_id, $user_data->id, $quant);	
								}else{
									$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity + $quant);
								}
								$this->players_model->set_money($user_data->id, $user_data->money - ($static_info[0]->buy * $quant));	
								$this->players_model->set_purchases($user_data->id, $user_data->bought + $quant, $user_data->spent + ($static_info[0]->buy * $quant));
								
								$market_price = $this->market_model->get_market_price($static_info[0]->item_id);
								if (empty($market_price))
								{
									$this->market_model->new_market_price($static_info[0]->item_id, $quant, $static_info[0]->buy);
								}else{
									$current_count = $market_price[0]->quantity;
									$current_price = $market_price[0]->price;
									$total = $current_count * $current_price + ($quant *  $static_info[0]->buy);
									$new_market_price = $total / ($current_count + $quant);
									$this->market_model->set_price($market_price[0]->id, $current_count + $quant, $new_market_price);
								}
								
								
								$this->session->set_msg("Success! Items bought.");
								redirect('/static');	
							}else{
								$this->session->set_error("That quantity didn't look like a proper number to me.");	
								redirect('/static');
							}
						}else{
							$this->session->set_error("That quantity didn't look like a proper number to me.");	
							redirect('/static');
						}
					}else{
						$this->session->set_error("That quantity didn't look like a proper number to me.");	
						redirect('/static');
					}
				}else{
					$this->session->set_error("Sorry we are not selling that item.");	
					redirect('/static');
				}
			}else{
				$this->session->set_error("You do not have permission to buy static items");
				redirect('/static');
			}
		}else{
			$this->session->set_error("Please log in before trying to do that.");	
			redirect('/static');
		}
	}
	function sell_static()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);	
			$sell_static = $this->session->userdata('can_sell_static');
			$static_id = $this->input->post('ID');
			$quant = round($this->input->post('Quantity'), 0);
			
			$static_info = $this->static_model->get_static($static_id);
			if ($sell_static == 1){
				if (isset($static_info[0]->sell)){
					if ($quant > 0){
						if (is_numeric($quant)){
							$player_item = $this->player_items_model->get_item_match($static_info[0]->item_id, $user_data->id);
							if (!empty($player_item))
							{
								if ($player_item[0]->quantity >= $quant)
								{
									$this->players_model->set_money($user_data->id, $user_data->money + ($static_info[0]->sell * $quant));	
									$this->players_model->set_sales($user_data->id, $user_data->sold + $quant, $user_data->earnt + ($static_info[0]->sell * $quant));
									$newQuant = $player_item[0]->quantity - $quant;
									if ($newQuant > 0){
										$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity - $quant);
									}else{
										$this->player_items_model->delete_item($player_item[0]->id);	
									}
									
									$market_price = $this->market_model->get_market_price($static_info[0]->item_id);
									if (empty($market_price))
									{
										$this->market_model->new_market_price($static_info[0]->item_id, $quant, $static_info[0]->sell);
									}else{
										$current_count = $market_price[0]->quantity;
										$current_price = $market_price[0]->price;
										$total = $current_count * $current_price + ($quant *  $static_info[0]->sell);
										$new_market_price = $total / ($current_count + $quant);
										$this->market_model->set_price($market_price[0]->id, $current_count + $quant, $new_market_price);
									}
									
									$this->session->set_msg("Success! Items sold.");
									redirect('/static');	
								}else{
									$this->session->set_error("You do not have that many items to sell.");
									redirect('/static');	
								}
							}else{
								$this->session->set_error("You do not have any of that item to sell.");
								redirect('/static');
							}	
						}else{
							$this->session->set_error("That quantity didn't look like a proper number to me.");
							redirect('/static');
						}
					}else{
						$this->session->set_error("That quantity didn't look like a proper number to me.");	
						redirect('/static');
					}
				}else{
					$this->session->set_error("Sorry you are not allowed to sell that item.");	
					redirect('/static');	
				}
			}else{
				$this->session->set_error("You do not have permission to sell items to the server.");
				redirect('/static');	
			}
		}else{
			$this->session->set_error("Please log in before doing that.");	
			redirect('/static');
		}
	}
	function buy_item()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);	
			$buy_auction = $this->session->userdata('can_buy_auction');
			$auction_id = $this->input->post('ID');
			$quant = $this->input->post('Quantity');
			$quant = round($quant, 0);
		
			
			$auction_info = $this->auctions_model->get_auction($auction_id);
			$seller_data = $this->users->get_user_by_username($auction_info[0]->seller);
			if ($buy_auction == 1){
				if ($quant > 0){
					if (is_numeric($quant)){
						if ($quant <= $auction_info[0]->quantity){
							if ($user_data->money >= ($auction_info[0]->price * $quant)){
								if ($username != $auction_info[0]->seller){
									$player_item = $this->player_items_model->get_item_match($auction_info[0]->item_id, $user_data->id);
									if (empty($player_item))
									{
										$this->player_items_model->new_player_item($auction_info[0]->item_id, $user_data->id, $quant);	
									}else{
										$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity + $quant);
									}
									$number_left = $auction_info[0]->quantity - $quant;
									if ($number_left <= 0){
										$this->auctions_model->delete_auction($auction_info[0]->id);	
									}else{
										$this->auctions_model->set_quantity($auction_info[0]->id, $auction_info[0]->quantity - $quant);
									}
									$this->players_model->set_money($user_data->id, $user_data->money - ($auction_info[0]->price * $quant));
									$this->players_model->set_money($seller_data->id, $seller_data->money + ($auction_info[0]->price * $quant));
								
									$this->players_model->set_purchases($user_data->id, $user_data->bought + $quant, $user_data->spent + ($auction_info[0]->price * $quant));
									$this->players_model->set_sales($seller_data->id, $seller_data->sold + $quant, $seller_data->earnt + ($auction_info[0]->price * $quant));
								
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
				$this->session->set_error("You do not have permission to buy items from player auctions.");	
				redirect('/auctions');
			}
		}else{
			redirect('/login');	
		}
	}
	function set_buy_static()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username');
			$user_data = $this->users->get_user_by_username($username);
			$is_admin = $this->session->userdata('is_admin');
			$item_id = $this->input->post('ID');
			$buy = $this->input->post('Value');
			
			$static = $this->static_model->get_static_by_item($item_id);
			if ($is_admin){
				if (!empty($static))
				{
					$this->static_model->update_buy($static[0]->id, $buy);	
				}else{
					$this->static_model->new_static_buy($item_id, $buy);	
				}	
				redirect('/static_edit');
			}else{
				redirect('/static');	
			}
		}else{
			redirect('/static');	
		}
	}
	function set_sell_static()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username');
			$user_data = $this->users->get_user_by_username($username);
			$is_admin = $this->session->userdata('is_admin');
			$item_id = $this->input->post('ID');
			$sell = $this->input->post('Value');
			
			$static = $this->static_model->get_static_by_item($item_id);
			if ($is_admin){
				if (!empty($static))
				{
					$this->static_model->update_sell($static[0]->id, $sell);	
				}else{
					$this->static_model->new_static_sell($item_id, $sell);	
				}	
				redirect('/static_edit');
			}else{
				redirect('/static');	
			}
		}else{
			redirect('/static');	
		}
	}
	function new_static()
	{
 
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username');
			$user_data = $this->users->get_user_by_username($username);
			$is_admin = $this->session->userdata('is_admin');
			$item_id = $this->input->post('ID');
			$buy = $this->input->post('Sell');
			$sell = $this->input->post('Buy');
			
			if ($is_admin == 1){
				if ($price > 0){
					if ($quant > 0){
						if ($item_info[0]->player == $user_data->id){
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
				$this->session->set_error("You are not an admin, so you are not allowed to create infinite sale prices.");
				redirect('/static');
			}
		}else{
			$this->session->set_error("Please log in to be able to create auctions.");
			redirect('/myauctions');  //not logged in
		}
	}
	function cancel_auction()
	{
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);
			$is_admin = $this->session->userdata('is_admin');
			$auction_id = $this->input->post('ID');
		
			$auction_info = $this->auctions_model->get_auction($auction_id);
			
			if ($username == $auction_info[0]->seller)
			{
				$player_item = $this->player_items_model->get_item_match($auction_info[0]->item_id, $user_data->id);
				if (empty($player_item))
				{
					$this->player_items_model->new_player_item($auction_info[0]->item_id, $user_data->id, $auction_info[0]->quantity);	
				}else{
					$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity + $auction_info[0]->quantity);
				}
				$this->auctions_model->delete_auction($auction_info[0]->id);
				$this->session->set_msg("Success! Auction removed.");
				redirect('/myauctions');
			}
			else if ($is_admin)
			{
				$seller_info = $this->users->get_user_by_username($auction_info[0]->seller);
				$player_item = $this->player_items_model->get_item_match($auction_info[0]->item_id, $seller_info->id);
				if (empty($player_item))
				{
					$this->player_items_model->new_player_item($auction_info[0]->item_id, $seller_info->id, $auction_info[0]->quantity);	
				}else{
					$this->player_items_model->set_quantity($player_item[0]->id, $player_item[0]->quantity + $auction_info[0]->quantity);
				}
				$this->auctions_model->delete_auction($auction_info[0]->id);
				$this->session->set_msg("Success! Auction removed.");
				redirect('/auctions');
			}
			else
			{
				$this->session->set_error("You do not have permission to cancel this auction.");
				redirect('/myauctions'); //quant not a number	
			}
		}
	}
	function new_auction()
	{
 
		if ($this->tank_auth->is_logged_in()){ 
			$username = $this->session->userdata('username'); 
			$user_data = $this->users->get_user_by_username($username);
			$sell_auction = $this->session->userdata('can_sell_auction');
			$item_id = $this->input->post('Item');
			$quant = round($this->input->post('Quantity'), 0);
			$price = $this->input->post('Price');

			$item_info = $this->player_items_model->get_item($item_id);
			if ($sell_auction == 1){
				if ($price > 0){
					if ($quant > 0){
						if ($item_info[0]->player == $user_data->username){
							if ($item_info[0]->quantity >= $quant){
								if (is_numeric($price)){
									if (is_numeric($quant)){
										$newQuant = $item_info[0]->quantity - $quant;
										$this->auctions_model->new_auction($item_info[0]->item_id, $price, $quant, $user_data->id);
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
				$this->session->set_error("You do not have permission to create new auctions.");
				redirect('/myauctions');	
			}
		}else{
			$this->session->set_error("Please log in to be able to create auctions.");
			redirect('/myauctions');  //not logged in
		}
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */