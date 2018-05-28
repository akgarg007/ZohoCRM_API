<?php defined('BASEPATH') OR exit('No direct script access allowed');
class welcome extends MY_Controller {

// cURL and PHP 5.6 or above required to use Zoho API on May 2018

var $client_id;
var $client_secret;
var $auth_token;

    public function __construct() {
        parent::__construct();
        $this->load->model('global_model'); 

		// Enter all these values below.
        $this->client_id = '';    
        $this->client_secret = ''; 
        $this->auth_token = ''; 
        
    }



    public function index()
    {
        $this->load->view('add_subscriber');
    }

	// This is ZohoCRM API Version 1.0
    public function leads_get()
    {
		// This is ZohoCRM API Version 1.0
		//   header("Content-type: application/xml");

		// Enter token here
		$token="";
		$xml = '<Leads>
		<row no="1">
		<FL val="Lead Source">Web Download</FL>
		<FL val="Company">Your Company</FL>
		<FL val="First Name">Hannah</FL>
		<FL val="Last Name">Smith</FL>
		<FL val="Email">testing@testing.com</FL>
		<FL val="Title">Manager</FL>
		<FL val="Phone">1234567890</FL>
		<FL val="Home Phone">0987654321</FL>
		<FL val="Other Phone">1212211212</FL>
		<FL val="Fax">02927272626</FL>
		<FL val="Mobile">292827622</FL>
		</row>
		</Leads>';
		$url = "https://crm.zoho.com/crm/private/xml/Leads/insertRecords";
		$param= "authtoken=".$token."&scope=crmapi&xmlData=".$xml;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$response = curl_exec($ch);
		curl_close($ch);
		echo $response;
		echo "<br/>";
		echo "<a href='". base_url('welcome/leads_get')."'>Refresh</a>" ;   

    }

    public function insertRecords()
    {
		// This is version 2.0 of ZohoCRM API
		$url = 'https://www.zohoapis.com/crm/v2/Leads';  

		$data =
				'{
					"data": [
						{
							"Last_Name":"Bindu",
							"Company":"ECLABS"
						}
					],
					
					“triggger”:[“workflow”,”approval”,”blueprint”]
				}';
		
        // send a HTTP POST request with curl
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
												'Content-Type: application/json',
												'Authorization: 394ca2c14ec78baf4a6ad834b1f41a7d'
											));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        $response = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // print_r($httpCode);
        echo "<pre>";
        
        $response = json_decode($response);
		
		print_r($response);

    }

	public function getLeads()
	{
		$url = 'https://www.zohoapis.com/crm/v2/Leads';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json','X:GET','Authorization: 394ca2c14ec78baf4a6ad834b1f41a7d']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);

        $response = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // print_r($httpCode);
        echo "<pre>";
        
        $response = json_decode($response);
		
		// print_r($response->data);

		foreach($response->data as $lead)
		{
			echo "Lead Company:". $lead->Company."<br/>";
			echo "Lead Email:". $lead->Email."<br/>";
			echo "Lead Creation Time:". $lead->Created_Time."<br/>";
			echo "Lead Created By:";print_r($lead->Created_By);echo "<br/>";
			echo "Lead ID:". $lead->id."<br/>";
			echo "<a href='".base_url('welcome/get_lead_by_id?id=').$lead->id."'>Get complete information of this Lead</a>";
			echo "<br><br>";
		}

	}
 
	public function get_lead_by_id()
	{
		$lead_id = $this->input->get('id');
		$url = 'https://www.zohoapis.com/crm/v2/Leads/'.$lead_id;
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:Application/json','X:GET','Authorization: 394ca2c14ec78baf4a6ad834b1f41a7d']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        print_r($httpCode);
        echo "<pre>";
        
        $response = json_decode($response);
		print_r($response);
	}
    

}
