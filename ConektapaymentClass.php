<?php 
require_once("conektaphp/lib/Conekta.php");
Class ConektaPaymentGetWay
{
	function __construct($apikey)
	{
		Conekta::setApiKey($apikey);
	}
//creating customer
	public	function CreateCustomer($name,$email)
	{
		try {
			 $customer = Conekta_Customer::create(
			  array(
			  	'name'  =>$name,
			    'email' => $email,
			    //'phone' => "55-5555-5555",
			   // 'cards' => array("tok_8kZwafM8IcN23Nd9"),
			    //'plan'  => "gold-plan"
			  )
			);
			return array("type"=>1,"data"=>$customer);
		} catch (Conekta_Error $e) {
			return array("type"=>0,"data"=>$e->getMessage());
			 
		}
	}

	//find customer 
	public function FindCustomer($cid){
		try {
			$customer = Conekta_Customer::find($cid);
			 return array("type"=>1,"data"=>$customer);
			
		} catch (Conekta_Error $e) {
			  return array("type"=>0,"data"=>$e->getMessage());
		}
	}

	//creating card 
	public function CreateCard($cid,$token)
	{
			$customer = $this->FindCustomer($cid);
		
			if($customer['type']==1) {
				
						
				try {
					$card = $customer['data']->createCard(array('token' =>$token));
					return array("type"=>1,"data"=>$card);
				}
				catch (Conekta_Error $e) {
			  		return array("type"=>0,"data"=>$e->getMessage());
				}
			}
			else {
					return array("type"=>0,"data"=>$customer['data']);
			}
	}
	
	//geting user cards 
	public function GetUserCard($cid)
	{
			$customer = $this->FindCustomer($cid);
			if($customer['type']==1) {
				
				return array("type"=>1,"data"=>$customer['data']);
			}
			else {
				return array("type"=>0,"data"=>$customer['data']);
			}
	}

	//update card
	public function UpdateDefaultCard($cid,$card)
	{
			$customer = $this->FindCustomer($cid);
			
			if($customer['type']==1) {				
				try {
					 $customer = $customer['data']->update(
					  array(
					  
						'default_card_id'=>$card
					  )
					);
					
					return array("type"=>1,"data"=>$customer);
				} catch (Conekta_Error $e) {
					return array("type"=>0,"data"=>$e->getMessage());
				}
			}
			else {
				return array("type"=>0,"data"=>$customer['data']);
			}
	}
	public function DeleteCard($cid,$index)
	{
		
		$customer = $this->FindCustomer($cid);
		//print_r($customer['data']); exit;
		if($customer['type']==1) {				
				try {
					$card = $customer['data']->cards[$index]->delete();
					
					return array("type"=>1,"data"=>$card);
				}
				catch (Conekta_Error $e) {
					return array("type"=>0,"data"=>$e->getMessage());
				}
				
		}
		else{
			return array("type"=>0,"data"=>$customer['data']);
		}
	}
	
	//create payment 
	public function CreatePayment($paymentData)
	{
		try {
			$charge = Conekta_Charge::create(array(
			  'description'=> 'Trip',
			  'reference_id'=> $paymentData['reference_id'],
			  'amount'=> $paymentData['amount'],
			  'currency'=>'MXN',
			  'card'=> $paymentData['card'], //we can send card id or customer id both 
			  'details'=> array(
				'name'=> $paymentData['name'],
				'phone'=> $paymentData['phone'],
				'email'=> $paymentData['email'],
				
				'line_items'=> array(
				  array(
					'name'=> 'Trip',
					'description'=> $paymentData['descp'],
					'unit_price'=> $paymentData['amount'],
					'quantity'=> 1
				  )
				),
				
			  )
			));
			return array("type"=>1,"data"=>$charge);
		} catch (Conekta_Error $e) {
			return array("type"=>0,"data"=>$e->getMessage());
		}
	}

	
	
}
$con = new ConektaPaymentGetWay("key_xZEfGF2FohhhLHH3rxgtwQ");
print_r($con->CreateCustomer("wakeel","wakeel@test.com")); exit;
$cid = "cus_DTq95NrdhzLdEevGG";
//$cus = $con->FindCustomer($cid);
$cus = $con->createCASH();
print_r($cus); exit; 




?>