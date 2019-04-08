<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Config extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('config');

		$this->load->library('barcode_lib');
		$this->load->library('sale_lib');
	}

	/*
	* This function loads all the licenses starting with the first one being OSPOS one
	*/
	private function _licenses()
	{
		$i = 0;
		$bower = FALSE;
		$composer = FALSE;
		$license = array();

		$license[$i]['title'] = 'Open Source Point Of Sale ' . $this->config->item('application_version');

		if(file_exists('license/LICENSE'))
		{
			$license[$i]['text'] = $this->xss_clean(file_get_contents('license/LICENSE', NULL, NULL, 0, 2000));
		}
		else
		{
			$license[$i]['text'] = 'LICENSE file must be in OSPOS license directory. You are not allowed to use OSPOS application until the distribution copy of LICENSE file is present.';
		}

		// read all the files in the dir license
		$dir = new DirectoryIterator('license');

		foreach($dir as $fileinfo)
		{
			// license files must be in couples: .version (name & version) & .license (license text)
			if($fileinfo->isFile())
			{
				if($fileinfo->getExtension() == 'version')
				{
					++$i;

					$basename = 'license/' . $fileinfo->getBasename('.version');

					$license[$i]['title'] = $this->xss_clean(file_get_contents($basename . '.version', NULL, NULL, 0, 100));

					$license_text_file = $basename . '.license';

					if(file_exists($license_text_file))
					{
						$license[$i]['text'] = $this->xss_clean(file_get_contents($license_text_file , NULL, NULL, 0, 2000));
					}
					else
					{
						$license[$i]['text'] = $license_text_file . ' file is missing';
					}
				}
				elseif($fileinfo->getBasename() == 'bower.LICENSES')
				{
					// set a flag to indicate that the JS Plugin bower.LICENSES file is available and needs to be attached at the end
					$bower = TRUE;
				}
				elseif($fileinfo->getBasename() == 'composer.LICENSES')
				{
					// set a flag to indicate that the composer.LICENSES file is available and needs to be attached at the end
					$composer = TRUE;
				}
			}
		}

		// attach the licenses from the LICENSES file generated by bower
		if($composer)
		{
			++$i;
			$license[$i]['title'] = 'Composer Libraries';
			$license[$i]['text'] = '';

			$file = file_get_contents('license/composer.LICENSES');
			$array = json_decode($file, TRUE);

			foreach($array as $key => $val)
			{
				if(is_array($val) && $key == 'dependencies')
				{
					foreach($val as $key1 => $val1)
					{
						if(is_array($val1))
						{
							$license[$i]['text'] .= 'component: ' . $key1 . "\n";

							foreach($val1 as $key2 => $val2)
							{
								if(is_array($val2))
								{
									$license[$i]['text'] .= $key2 . ': ';

									foreach($val2 as $key3 => $val3)
									{
										$license[$i]['text'] .= $val3 . ' ';
									}

									$license[$i]['text'] .= "\n";
								}
								else
								{
									$license[$i]['text'] .= $key2 . ': ' . $val2 . "\n";
								}
							}

							$license[$i]['text'] .= "\n";
						}
						else
						{
							$license[$i]['text'] .= $key1 . ': ' . $val1 . "\n";
						}
					}
				}
			}

			$license[$i]['text'] = $this->xss_clean($license[$i]['text']);
		}

		// attach the licenses from the LICENSES file generated by bower
		if($bower)
		{
			++$i;
			$license[$i]['title'] = 'JS Plugins';
			$license[$i]['text'] = '';

			$file = file_get_contents('license/bower.LICENSES');
			$array = json_decode($file, TRUE);

			foreach($array as $key => $val)
			{
				if(is_array($val))
				{
					$license[$i]['text'] .= 'component: ' . $key . "\n";

					foreach($val as $key1 => $val1)
					{
						if(is_array($val1))
						{
							$license[$i]['text'] .= $key1 . ': ';

							foreach($val1 as $key2 => $val2)
							{
								$license[$i]['text'] .= $val2 . ' ';
							}

							$license[$i]['text'] .= "\n";
						}
						else
						{
							$license[$i]['text'] .= $key1 . ': ' . $val1 . "\n";
						}
					}

					$license[$i]['text'] .= "\n";
				}
			}

			$license[$i]['text'] = $this->xss_clean($license[$i]['text']);
		}

		return $license;
	}

	/*
	* This function loads all the available themes in the dist/bootswatch directory
	*/
	private function _themes()
	{
		$themes = array();

		// read all themes in the dist folder
		$dir = new DirectoryIterator('dist/bootswatch');

		foreach($dir as $dirinfo)
		{
			if($dirinfo->isDir() && !$dirinfo->isDot() && $dirinfo->getFileName() != 'fonts')
			{
				$file = $this->xss_clean($dirinfo->getFileName());
				$themes[$file] = $file;
			}
		}

		asort($themes);

		return $themes;
	}

	public function get_operating_shops()
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');

		$shop_types = array('dbf', 'shop', 'hub');
		$this->db->where_in('login_type', $shop_types);
		$this->db->where('deleted !=', 1);
		$query = $this->db->get();

		$shops = array('' => $this->lang->line('items_none'));
		foreach($query->result_array() as $row)
			{
				$shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
			}

		return $shops;	
	}

	public function index()
	{
		$data['stock_locations'] = $this->Stock_location->get_all()->result_array();
		$data['dinner_tables'] = $this->Dinner_table->get_all()->result_array();
		$data['tax_categories'] = $this->Tax->get_all_tax_categories()->result_array();
		$data['customer_rewards'] = $this->Customer_rewards->get_all()->result_array();
		$data['support_barcode'] = $this->barcode_lib->get_list_barcodes();
		$data['logo_exists'] = $this->config->item('company_logo') != '';
		$data['line_sequence_options'] = $this->sale_lib->get_line_sequence_options();
		$data['register_mode_options'] = $this->sale_lib->get_register_mode_options();
		$data['rounding_options'] = Rounding_mode::get_rounding_options();
		$data['tax_codes'] = $this->get_tax_code_options();
		$data['show_office_group'] = $this->Module->get_show_office_group();
		$data['operating_shops'] = $this->get_operating_shops();

		$data = $this->xss_clean($data);

		// load all the license statements, they are already XSS cleaned in the private function
		$data['licenses'] = $this->_licenses();
		// load all the themes, already XSS cleaned in the private function
		$data['themes'] = $this->_themes();

		$data['mailchimp'] = array();
		if($this->_check_encryption())
		{
			$data['mailchimp']['api_key'] = $this->encryption->decrypt($this->config->item('mailchimp_api_key'));
			$data['mailchimp']['list_id'] = $this->encryption->decrypt($this->config->item('mailchimp_list_id'));
		}
		else
		{
			$data['mailchimp']['api_key'] = '';
			$data['mailchimp']['list_id'] = '';
		}

		// load mailchimp lists associated to the given api key, already XSS cleaned in the private function
		$data['mailchimp']['lists'] = $this->_mailchimp();

		$this->load->view("configs/manage", $data);
	}
	
	public function get_tax_code_options()
	{
		$tax_codes = $this->Tax->get_all_tax_codes()->result_array();
		$tax_code_options = array();
		foreach($tax_codes as $tax_code)
		{
			$a = $tax_code['tax_code'];
			$b = $tax_code['tax_code_name'];
			$tax_code_options[$a] = $b;
		}
		return $tax_code_options;
	}

	public function save_info()
	{
		$upload_success = $this->_handle_logo_upload();
		$upload_data = $this->upload->data();

		$batch_save_data = array(
			'company' => $this->input->post('company'),
			'address' => $this->input->post('address'),
			'phone' => $this->input->post('phone'),
			'email' => $this->input->post('email'),
			'fax' => $this->input->post('fax'),
			'website' => $this->input->post('website'),
			'return_policy' => $this->input->post('return_policy')
		);

		if(!empty($upload_data['orig_name']))
		{
			// XSS file image sanity check
			if($this->xss_clean($upload_data['raw_name'], TRUE) === TRUE)
			{
				$batch_save_data['company_logo'] = $upload_data['raw_name'] . $upload_data['file_ext'];
			}
		}

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $upload_success && $result ? TRUE : FALSE;
		$message = $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully');
		$message = $upload_success ? $message : strip_tags($this->upload->display_errors());

		echo json_encode(array(
			'success' => $success,
			'message' => $message
		));
	}

	public function save_general()
	{
		$batch_save_data = array(
			'theme' => $this->input->post('theme'),
			'default_sales_discount' => $this->input->post('default_sales_discount'),
			'receiving_calculate_average_price' => $this->input->post('receiving_calculate_average_price') != NULL,
			'lines_per_page' => $this->input->post('lines_per_page'),
			'notify_horizontal_position' => $this->input->post('notify_horizontal_position'),
			'notify_vertical_position' => $this->input->post('notify_vertical_position'),
			'gcaptcha_enable' => $this->input->post('gcaptcha_enable') != NULL,
			'gcaptcha_secret_key' => $this->input->post('gcaptcha_secret_key'),
			'gcaptcha_site_key' => $this->input->post('gcaptcha_site_key'),
			'suggestions_first_column' => $this->input->post('suggestions_first_column'),
			'suggestions_second_column' => $this->input->post('suggestions_second_column'),
			'suggestions_third_column' => $this->input->post('suggestions_third_column'),
			'giftcard_number' => $this->input->post('giftcard_number'),
			'derive_sale_quantity' => $this->input->post('derive_sale_quantity') != NULL,
			'column1_name' => $this->input->post('column1_name'),
			'column2_name' => $this->input->post('column2_name'),
			'column3_name' => $this->input->post('column3_name'),
			'column4_name' => $this->input->post('column4_name'),
			'column5_name' => $this->input->post('column5_name'),
			'column6_name' => $this->input->post('column6_name'),
			'column7_name' => $this->input->post('column7_name'),
			'column8_name' => $this->input->post('column8_name'),
			'column9_name' => $this->input->post('column9_name'),
			'column10_name' => $this->input->post('column10_name'),
			'custom1_name' => $this->input->post('custom1_name'),
			'custom2_name' => $this->input->post('custom2_name'),
			'custom3_name' => $this->input->post('custom3_name'),
			'custom4_name' => $this->input->post('custom4_name'),
			'custom5_name' => $this->input->post('custom5_name'),
			'custom6_name' => $this->input->post('custom6_name'),
			'custom7_name' => $this->input->post('custom7_name'),
			'custom8_name' => $this->input->post('custom8_name'),
			'custom9_name' => $this->input->post('custom9_name'),
			'custom10_name' => $this->input->post('custom10_name')
		);

		$this->Module->set_show_office_group($this->input->post('show_office_group') != NULL);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function ajax_check_number_locale()
	{
		$number_locale = $this->input->post('number_locale');
		$fmt = new \NumberFormatter($number_locale, \NumberFormatter::CURRENCY);
		$currency_symbol = empty($this->input->post('currency_symbol')) ? $fmt->getSymbol(\NumberFormatter::CURRENCY_SYMBOL) : $this->input->post('currency_symbol');
		if($this->input->post('thousands_separator') == 'false')
		{
			$fmt->setAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
		}
		$fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $currency_symbol);
		$number_local_example = $fmt->format(1234567890.12300);
		echo json_encode(array(
			'success' => $number_local_example != FALSE,
			'number_locale_example' => $number_local_example,
			'currency_symbol' => $currency_symbol,
			'thousands_separator' => $fmt->getAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL) != ''
		));
	}

	public function save_locale()
	{
		$exploded = explode(":", $this->input->post('language'));
		$batch_save_data = array(
			'currency_symbol' => $this->input->post('currency_symbol'),
			'language_code' => $exploded[0],
			'language' => $exploded[1],
			'timezone' => $this->input->post('timezone'),
			'dateformat' => $this->input->post('dateformat'),
			'timeformat' => $this->input->post('timeformat'),
			'thousands_separator' => $this->input->post('thousands_separator'),
			'number_locale' => $this->input->post('number_locale'),
			'currency_decimals' => $this->input->post('currency_decimals'),
			'tax_decimals' => $this->input->post('tax_decimals'),
			'quantity_decimals' => $this->input->post('quantity_decimals'),
			'country_codes' => $this->input->post('country_codes'),
			'payment_options_order' => $this->input->post('payment_options_order'),
			'date_or_time_format' => $this->input->post('date_or_time_format'),
			'cash_decimals' => $this->input->post('cash_decimals'),
			'cash_rounding_code' => $this->input->post('cash_rounding_code'),
			'financial_year' => $this->input->post('financial_year')
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_email()
	{
		$password = '';

		if($this->_check_encryption())
		{
			$password = $this->encryption->encrypt($this->input->post('smtp_pass'));
		}

		$batch_save_data = array(
			'protocol' => $this->input->post('protocol'),
			'mailpath' => $this->input->post('mailpath'),
			'smtp_host' => $this->input->post('smtp_host'),
			'smtp_user' => $this->input->post('smtp_user'),
			'smtp_pass' => $password,
			'smtp_port' => $this->input->post('smtp_port'),
			'smtp_timeout' => $this->input->post('smtp_timeout'),
			'smtp_crypto' => $this->input->post('smtp_crypto')
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_message()
	{
		$password = '';

		if($this->_check_encryption())
		{
			$password = $this->encryption->encrypt($this->input->post('msg_pwd'));
		}

		$batch_save_data = array(
			'msg_msg' => $this->input->post('msg_msg'),
			'msg_uid' => $this->input->post('msg_uid'),
			'msg_pwd' => $password,
			'msg_src' => $this->input->post('msg_src')
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	/*
	* This function fetches all the available lists from Mailchimp for the given API key
	*/
	private function _mailchimp($api_key = '')
	{
		$this->load->library('mailchimp_lib', array('api_key' => $api_key));

		$result = array();

		if(($lists = $this->mailchimp_lib->getLists()) !== FALSE)
		{
			if(is_array($lists) && !empty($lists['lists']) && is_array($lists['lists']))
			{
				foreach($lists['lists'] as $list)
				{
					$list = $this->xss_clean($list);
					$result[$list['id']] = $list['name'] . ' [' . $list['stats']['member_count'] . ']';
				}
			}
		}

		return $result;
	}

	/*
	AJAX call from mailchimp config form to fetch the Mailchimp lists when a valid API key is inserted
	*/
	public function ajax_check_mailchimp_api_key()
	{
		// load mailchimp lists associated to the given api key, already XSS cleaned in the private function
		$lists = $this->_mailchimp($this->input->post('mailchimp_api_key'));
		$success = count($lists) > 0 ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_mailchimp_key_' . ($success ? '' : 'un') . 'successfully'),
			'mailchimp_lists' => $lists
		));
	}

	public function save_mailchimp()
	{
		$api_key = '';
		$list_id = '';

		if($this->_check_encryption())
		{
			$api_key = $this->encryption->encrypt($this->input->post('mailchimp_api_key'));
			$list_id = $this->encryption->encrypt($this->input->post('mailchimp_list_id'));
		}

		$batch_save_data = array(
			'mailchimp_api_key' => $api_key,
			'mailchimp_list_id' => $list_id
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function ajax_stock_locations()
	{
		$stock_locations = $this->Stock_location->get_all()->result_array();

		$stock_locations = $this->xss_clean($stock_locations);

		$this->load->view('partial/stock_locations', array('stock_locations' => $stock_locations));
	}

	public function ajax_dinner_tables()
	{
		$dinner_tables = $this->Dinner_table->get_all()->result_array();

		$dinner_tables = $this->xss_clean($dinner_tables);

		$this->load->view('partial/dinner_tables', array('dinner_tables' => $dinner_tables));
	}

	public function ajax_tax_categories()
	{
		$tax_categories = $this->Tax->get_all_tax_categories()->result_array();

		$tax_categories = $this->xss_clean($tax_categories);

		$this->load->view('partial/tax_categories', array('tax_categories' => $tax_categories));
	}

	public function ajax_customer_rewards()
	{
		$customer_rewards = $this->Customer_rewards->get_all()->result_array();

		$customer_rewards = $this->xss_clean($customer_rewards);

		$this->load->view('partial/customer_rewards', array('customer_rewards' => $customer_rewards));
	}

	private function _clear_session_state()
	{
		$this->sale_lib->clear_sale_location();
		$this->sale_lib->clear_table();
		$this->sale_lib->clear_all();
		$this->load->library('receiving_lib');
		$this->receiving_lib->clear_stock_source();
		$this->receiving_lib->clear_stock_destination();
		$this->receiving_lib->clear_all();
	}

	public function save_locations()
	{
		$this->db->trans_start();

		$not_to_delete = array();
		foreach($this->input->post() as $key => $value)
		{
			if(strstr($key, 'stock_location'))
			{
				$location_id = preg_replace("/.*?_(\d+)$/", "$1", $key);

				// save or update
				$location_data = array('location_name' => $value);
				if($this->Stock_location->save($location_data, $location_id))
				{
					$location_id = $this->Stock_location->get_location_id($value);
					$not_to_delete[] = $location_id;
					$this->_clear_session_state();
				}
			}
		}

		// all locations not available in post will be deleted now
		$deleted_locations = $this->Stock_location->get_all()->result_array();

		foreach($deleted_locations as $location => $location_data)
		{
			if(!in_array($location_data['location_id'], $not_to_delete))
			{
				$this->Stock_location->delete($location_data['location_id']);
			}
		}

		$this->db->trans_complete();

		$success = $this->db->trans_status();

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_tables()
	{
		$this->db->trans_start();

		$dinner_table_enable = $this->input->post('dinner_table_enable') != NULL;

		$this->Appconfig->save('dinner_table_enable', $dinner_table_enable);

		if($dinner_table_enable)
		{
			$not_to_delete = array();
			foreach($this->input->post() as $key => $value)
			{
				if(strstr($key, 'dinner_table') && $key != 'dinner_table_enable')
				{
					$dinner_table_id = preg_replace("/.*?_(\d+)$/", "$1", $key);
					$not_to_delete[] = $dinner_table_id;
					// save or update
					$table_data = array('name' => $value);
					if($this->Dinner_table->save($table_data, $dinner_table_id))
					{
						$this->_clear_session_state();
					}
				}
			}

			// all tables not available in post will be deleted now
			$deleted_tables = $this->Dinner_table->get_all()->result_array();

			foreach($deleted_tables as $dinner_tables => $table)
			{
				if(!in_array($table['dinner_table_id'], $not_to_delete))
				{
					$this->Dinner_table->delete($table['dinner_table_id']);
				}
			}
		}

		$this->db->trans_complete();

		$success = $this->db->trans_status();

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_tax()
	{
		$this->db->trans_start();

		$customer_sales_tax_support = $this->input->post('customer_sales_tax_support') != NULL;

		$batch_save_data = array(
			'default_tax_1_rate' => parse_decimals($this->input->post('default_tax_1_rate')),
			'default_tax_1_name' => $this->input->post('default_tax_1_name'),
			'default_tax_2_rate' => parse_decimals($this->input->post('default_tax_2_rate')),
			'default_tax_2_name' => $this->input->post('default_tax_2_name'),
			'tax_included' => $this->input->post('tax_included') != NULL,
			'customer_sales_tax_support' => $customer_sales_tax_support,
			'default_origin_tax_code' => $this->input->post('default_origin_tax_code')
		);

		$success = $this->Appconfig->batch_save($batch_save_data) ? TRUE : FALSE;
		$delete_rejected = FALSE;

		if($customer_sales_tax_support)
		{
			$array_save = array();
			foreach($this->input->post() as $key => $value)
			{
				if(strstr($key, 'tax_category'))
				{
					$tax_category_id = preg_replace("/.*?_(\d+)$/", "$1", $key);
					$array_save[$tax_category_id]['tax_category'] = $value;
				}
				elseif(strstr($key, 'tax_group_sequence'))
				{
					$tax_category_id = preg_replace("/.*?_(\d+)$/", "$1", $key);
					$array_save[$tax_category_id]['tax_group_sequence'] = $value;
				}
			}

			$not_to_delete = array();
			if(!empty($array_save))
			{
				foreach($array_save as $key => $value)
				{
					// save or update
					$category_data = array('tax_category' => $value['tax_category'], 'tax_group_sequence' => $value['tax_group_sequence']);
					$this->Tax->save_tax_category($category_data, $key);
					$not_to_delete[] = $key;
				}
			}

			// all categories not available in post will be deleted now
			$deleted_categories = $this->Tax->get_all_tax_categories()->result_array();

			foreach($deleted_categories as $tax_category => $category)
			{
				if(!in_array($category['tax_category_id'], $not_to_delete))
				{
					$usg1 = $this->Tax->get_tax_category_usage($category['tax_category_id']);
					$usg2 = $this->Item->get_tax_category_usage($category['tax_category_id']);
					if(($usg1 + $usg2) == 0)
					{
						$this->Tax->delete_tax_category($category['tax_category_id']);
					}
					else
					{
						$delete_rejected = TRUE;
					}
				}
			}
		}

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		$message = '';
		if($success && $delete_rejected)
		{
			$message = $this->lang->line('config_tax_category_used');
			$success = FALSE;
		}
		else
		{
			$message = $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully');
		}

		echo json_encode(array(
			'success' => $success,
			'message' => $message
		));
	}

	public function save_rewards()
	{
		$this->db->trans_start();

		$customer_reward_enable = $this->input->post('customer_reward_enable') != NULL;

		$this->Appconfig->save('customer_reward_enable', $customer_reward_enable);

		if($customer_reward_enable)
		{
			$not_to_delete = array();
			$array_save = array();
			foreach($this->input->post() as $key => $value)
			{
				if(strstr($key, 'customer_reward') && $key != 'customer_reward_enable')
				{
					$customer_reward_id = preg_replace("/.*?_(\d+)$/", "$1", $key);
					$not_to_delete[] = $customer_reward_id;
					$array_save[$customer_reward_id]['package_name'] = $value;
				}
				elseif(strstr($key, 'reward_points'))
				{
					$customer_reward_id = preg_replace("/.*?_(\d+)$/", "$1", $key);
					$array_save[$customer_reward_id]['points_percent'] = $value;
				}
			}

			if(!empty($array_save))
			{
				foreach($array_save as $key => $value)
				{
					// save or update
					$package_data = array('package_name' => $value['package_name'], 'points_percent' => $value['points_percent']);
					$this->Customer_rewards->save($package_data, $key);
				}
			}

			// all packages not available in post will be deleted now
			$deleted_packages = $this->Customer_rewards->get_all()->result_array();

			foreach($deleted_packages as $customer_rewards => $reward_category)
			{
				if(!in_array($reward_category['package_id'], $not_to_delete))
				{
					$this->Customer_rewards->delete($reward_category['package_id']);
				}
			}
		}

		$this->db->trans_complete();

		$success = $this->db->trans_status();

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_barcode()
	{
		$batch_save_data = array(
			'barcode_type' => $this->input->post('barcode_type'),
			'barcode_width' => $this->input->post('barcode_width'),
			'barcode_height' => $this->input->post('barcode_height'),
			'barcode_font' => $this->input->post('barcode_font'),
			'barcode_font_size' => $this->input->post('barcode_font_size'),
			'barcode_first_row' => $this->input->post('barcode_first_row'),
			'barcode_second_row' => $this->input->post('barcode_second_row'),
			'barcode_third_row' => $this->input->post('barcode_third_row'),
			'barcode_num_in_row' => $this->input->post('barcode_num_in_row'),
			'barcode_page_width' => $this->input->post('barcode_page_width'),
			'barcode_page_cellspacing' => $this->input->post('barcode_page_cellspacing'),
			'barcode_generate_if_empty' => $this->input->post('barcode_generate_if_empty') != NULL,
			'allow_duplicate_barcodes' => $this->input->post('allow_duplicate_barcodes') != NULL,
			'barcode_content' => $this->input->post('barcode_content'),
			'barcode_formats' => json_encode($this->input->post('barcode_formats'))
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_receipt()
	{
		$batch_save_data = array (
			'receipt_template' => $this->input->post('receipt_template'),
			'receipt_font_size' => $this->input->post('receipt_font_size'),
			'email_receipt_check_behaviour' => $this->input->post('email_receipt_check_behaviour'),
			'print_receipt_check_behaviour' => $this->input->post('print_receipt_check_behaviour'),
			'receipt_show_company_name' => $this->input->post('receipt_show_company_name') != NULL,
			'receipt_show_taxes' => $this->input->post('receipt_show_taxes') != NULL,
			'receipt_show_total_discount' => $this->input->post('receipt_show_total_discount') != NULL,
			'receipt_show_description' => $this->input->post('receipt_show_description') != NULL,
			'receipt_show_serialnumber' => $this->input->post('receipt_show_serialnumber') != NULL,
			'print_silently' => $this->input->post('print_silently') != NULL,
			'print_header' => $this->input->post('print_header') != NULL,
			'print_footer' => $this->input->post('print_footer') != NULL,
			'print_top_margin' => $this->input->post('print_top_margin'),
			'print_left_margin' => $this->input->post('print_left_margin'),
			'print_bottom_margin' => $this->input->post('print_bottom_margin'),
			'print_right_margin' => $this->input->post('print_right_margin')
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function save_invoice()
	{
		$batch_save_data = array (
			'invoice_enable' => $this->input->post('invoice_enable') != NULL,
			'default_register_mode' => $this->input->post('default_register_mode'),
			'sales_invoice_format' => $this->input->post('sales_invoice_format'),
			'sales_quote_format' => $this->input->post('sales_quote_format'),
			'recv_invoice_format' => $this->input->post('recv_invoice_format'),
			'invoice_default_comments' => $this->input->post('invoice_default_comments'),
			'invoice_email_message' => $this->input->post('invoice_email_message'),
			'line_sequence' => $this->input->post('line_sequence'),
			'last_used_invoice_number' =>$this->input->post('last_used_invoice_number'),
			'last_used_quote_number' =>$this->input->post('last_used_quote_number'),
			'quote_default_comments' => $this->input->post('quote_default_comments'),
			'work_order_enable' => $this->input->post('work_order_enable') != NULL,
			'work_order_format' => $this->input->post('work_order_format'),
			'last_used_work_order_number' =>$this->input->post('last_used_work_order_number')
		);

		$result = $this->Appconfig->batch_save($batch_save_data);
		$success = $result ? TRUE : FALSE;

		// Update the register mode with the latest change so that if the user
		// switches immediately back to the register the mode reflects the change
		if($success == TRUE)
		{
			if($this->config->item('invoice_enable') == '1')
			{
				$this->sale_lib->set_mode($batch_save_data['default_register_mode']);
			}
			else
			{
				$this->sale_lib->set_mode('sale');
			}
		}

		echo json_encode(array(
			'success' => $success,
			'message' => $this->lang->line('config_saved_' . ($success ? '' : 'un') . 'successfully')
		));
	}

	public function remove_logo()
	{
		$result = $this->Appconfig->batch_save(array('company_logo' => ''));

		echo json_encode(array('success' => $result));
	}

	private function _handle_logo_upload()
	{
		$this->load->helper('directory');

		// load upload library
		$config = array('upload_path' => './uploads/',
				'allowed_types' => 'gif|jpg|png',
				'max_size' => '1024',
				'max_width' => '800',
				'max_height' => '680',
				'file_name' => 'company_logo');
		$this->load->library('upload', $config);
		$this->upload->do_upload('company_logo');

		return strlen($this->upload->display_errors()) == 0 || !strcmp($this->upload->display_errors(), '<p>'.$this->lang->line('upload_no_file_selected').'</p>');
	}

	private function _check_encryption()
	{
		$encryption_key = $this->config->item('encryption_key');

		// check if the encryption_key config item is the default one
		if($encryption_key == '' || $encryption_key == 'YOUR KEY')
		{
			// Config path
			$config_path = APPPATH . 'config/config.php';

			// Open the file
			$config = file_get_contents($config_path);

			// $key will be assigned a 32-byte (256-bit) hex-encoded random key
			$key = bin2hex($this->encryption->create_key(32));

			// set the encryption key in the config item
			$this->config->set_item('encryption_key', $key);

			// replace the empty placeholder with a real randomly generated encryption key
			$config = preg_replace("/(.*encryption_key.*)('');/", "$1'$key';", $config);

			$result = FALSE;

			// Chmod the file
			@chmod($config_path, 0777);

			// Verify file permissions
			if(is_writable($config_path))
			{
				// Write the new config.php file
				$handle = @fopen($config_path, 'w+');
				// Write the file
				$result = (fwrite($handle, $config) === FALSE) ? FALSE : TRUE;

				fclose($handle);
			}

			// Chmod the file
			@chmod($config_path, 0444);

			return $result;
		}

		return TRUE;
	}

	public function backup_db()
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		if($this->Employee->has_module_grant('config', $employee_id))
		{
			$this->load->dbutil();

			$prefs = array(
				'format' => 'zip',
				'filename' => 'ospos.sql'
			);

			$backup = $this->dbutil->backup($prefs);

			$file_name = 'ospos-' . date("Y-m-d-H-i-s") .'.zip';
			$save = 'uploads/' . $file_name;
			$this->load->helper('download');
			while(ob_get_level())
			{
				ob_end_clean();
			}

			force_download($file_name, $backup);
		}
		else
		{
			redirect('no_access/config');
		}
	}
}
?>
