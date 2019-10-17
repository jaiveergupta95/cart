<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->model('product_model');

	}

	public function index(){
        $data['data']=$this->product_model->get_all_product();

        //echo "<pre>"; print_r($data['data']->result()); die();
		$this->load->view('product_view',$data);

	}
    /*fav  start*/
	public function property(){
       
		$this->load->view('property');

	}	

	public function fav(){
       
		$this->load->view('fav');

	}

	function add_to_cart_fav(){ 
       
        $dataarray=$this->fav_count();
       
		$found = current(array_filter($dataarray, function($item) {
		    return isset($item['name']) && $this->input->post('propertycode') == $item['name'];
		}));

		if($found && !empty($found)){
			//print_r($found['rowid']); die();
	        $dataup = array(
	            'rowid' => $found['rowid'], 
	            'qty' => 0, 
	        );
        	$this->cart->update($dataup);
			echo json_encode(array('status' => 'sub','cartid' => $found['id'],'count' => $this->fav_count_return()));
		}else{

	        $data = array(
	            'id' => $this->input->post('propertyid'), 
	            'name' => $this->input->post('propertycode'), 
	            'price' => $this->input->post('propertyprice'), 
	            'qty' => $this->input->post('propertyqty'), 
	        );	
	        $this->cart->insert($data);	
	        
	        echo json_encode(array('status' => 'add','cartid' => $this->input->post('propertyid'),'count' => $this->fav_count_return()));	
		}

        
    }


    function fav_count(){
    	$count=array();
    	foreach($this->cart->contents() as $items){

    		$count[]= $items;
    	}
    	return $count;
  
    }

    function fav_count_return(){
    	$count=array();
    	foreach($this->cart->contents() as $items){

    		$count[]= $items;
    	}
    	return count($count);
  
    }    

    function delete_cart_fav(){ 
        $data = array(
            'rowid' => $this->input->post('row_id'), 
            'qty' => 0, 
        );
        $this->cart->update($data);
        echo $this->show_cart();
    }	    	
	/*fav end*/		

	function add_to_cart(){ 
        $data = array(
            'id' => $this->input->post('product_id'), 
            'name' => $this->input->post('product_name'), 
            'price' => $this->input->post('product_price'), 
            'qty' => $this->input->post('quantity'), 
        );
        $this->cart->insert($data);
        echo $this->show_cart(); 
    }
 
    function show_cart(){ 
        $output = '';
        $no = 0;
        foreach ($this->cart->contents() as $items) {
            $no++;
            $output .='
                <tr>
                    <td>'.$items['name'].'</td>
                    <td>'.number_format($items['price']).'</td>
                    <td>'.$items['qty'].'</td>
                    <td>'.number_format($items['subtotal']).'</td>
                    <td><button type="button" id="'.$items['rowid'].'" class="romove_cart btn btn-danger btn-sm">Cancel</button></td>
                </tr>
            ';
        }
        $output .= '
            <tr>
                <th colspan="3">Total</th>
                <th colspan="2">'.'Rp '.number_format($this->cart->total()).'</th>
            </tr>
        ';
        return $output;
    }
 
    function load_cart(){ 
        echo $this->show_cart();
    }
 
    function delete_cart(){ 
        $data = array(
            'rowid' => $this->input->post('row_id'), 
            'qty' => 0, 
        );
        $this->cart->update($data);
        echo $this->show_cart();
    }	
    
    
}


