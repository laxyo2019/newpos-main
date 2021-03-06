<?php

class MailSender extends CI_Controller 
{

  public function __construct()
	{
		parent::__construct('mailsender');
		
		$this->load->library('email_lib');
	}

  public function send_daily_sales()
	{
		$search = '';
		$limit = 100;
		$offset = 0;
		$sort = '';
		$order = 'asc';
		$filtr = array();

		$filters = array(
			'sale_type' => 'all',
			'start_date' => date('Y-m-d'),
			'end_date' => date('Y-m-d'),
			'only_cash' => FALSE,
			'only_due' => FALSE,
			'only_check' => FALSE,
			'only_invoices' => $this->config->item('invoice_enable') && $this->input->get('only_invoices'),
			'is_valid_receipt' => $this->Sale->is_valid_receipt($search),
		);

		$reporting_locations = array(
			'IP' => 6,
			'BK' => 7,
			'AP' => 8,
			'MH' => 11
		);

		foreach($reporting_locations as $key1=>$value1)
		{
			$filters['location_id'] = $value1;
			// check if any filter is set in the multiselect dropdown
			$filledup = array_fill_keys($filtr, TRUE);
			$filters = array_merge($filters, $filledup);

			$sales = $this->Sale->search($search, $filters, $limit, $offset, $sort, $order);
      $payments = $this->Sale->get_payments_summary($search, $filters);
      $table = '<div id="report_summary">';

      foreach($payments as $key=>$payment)
      {
        $amount = $payment['payment_amount'];
    
        // WARNING: the strong assumption here is that if a change is due it was a cash transaction always
        // therefore we remove from the total cash amount any change due
        if( $payment['payment_type'] == 'Cash' )
        {
          foreach($sales->result_array() as $key=>$sale)
          {
            $amount -= $sale['change_due'];
          }
        }
        $table .= '<div class="summary_row">' . $payment['payment_type'] . ': ' . to_currency($amount) . '</div>';
      }
      $table .= '</div>';
			$payment_summary[$key1] = $table;
		}

		$data['payment_summary'] = $payment_summary;
		$to = 'alikhar@laxyosolutionsoft.com';
		$subject = "Today's Sales: (".date('d-m-Y').")";
		$message = $this->load->view('messages/mail/daily_sales', $data, TRUE);
		$result = $this->email_lib->sendEmail($to, $subject, $message);
		// echo $result;	
  }

}
?>