<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Json extends M_Default {

	public	$LoginRequired = false,
			$NoProfiler = true;

	public function __construct(){

		parent::__construct();
	}

	public function Index(){
		if(Ajax()){
			$Fetch = PS('Type');
			$By = PS('FetchBy');
			$Key = PS('PK');
			$Other = PS('Other');

			if($Fetch && $By && $Key){

				$Fetch = sprintf('%s_%s',$Fetch,$By);
				$Items = ['Status'=>'Success'];
				//Check What To Fetch
				switch($Fetch){
					case 'ShipmentTo_Form':
						$this->load->model(['Supplier','Warehouse']);
						switch($Key){
							case 'Supplier':
								$Items['Items'] = $this->Supplier->Options([],'Name ASC',['ID','Name'],'{Name}','',false);
							break;
							case 'Warehouse':
								$Items['Items'] = $this->Warehouse->Options(['Type'=>'Private'],'Name ASC',['ID','Name'],'{Name}','',false);
							break;
							case 'Amazon Warehouse':
								$Items['Items'] = $this->Warehouse->Options(['Type'=>'Amazon'],'Name ASC',['ID','Name'],'{Name}','',false);
							break;
							case '3rd Party Service':
								$Items['Items'] = $this->Warehouse->Options(['Type'=>'3rd Party'],'Name ASC',['ID','Name'],'{Name}','',false);
							break;
						}
					break;
					case 'Products_Brand':
						$this->load->model(['Product']);
						$Items['Items'] = $this->Product->Options(['BrandID'=>intval($Key)],'Name ASC',['ID','Name'],'{Name}');
					break;
					case 'Models_Product':
						$this->load->model(['ProductModel']);
						$Items['Items'] = $this->ProductModel->Options(['ProductID'=>intval($Key)],'Name ASC',['ID','Name'],'{Name}');
					break;
					case 'Items_Model':
						$this->load->model(['ProductItem']);
						$Items['Items'] = $this->ProductItem->Options(['ProductModelID'=>intval($Key)],'Name ASC',['ID','Name'],'{Name}');
					break;
					case 'Suppliers_ItemType':
						$this->load->model(['ProductItem','Supplier']);
						$Item = $this->ProductItem->Row($Key);
						$this->Supplier->DB()->where(sprintf('FIND_IN_SET(\'%s\',{P}SUP.Types) ',$Item->ProductItemTypeID));
						$Items['Items'] = $this->Supplier->Options([],'Name ASC',['ID','Name'],'{Name}');
					break;
				}
				echo JsonOut($Items,200);
			}else{
				echo JsonOut([],403,'Invalid Data');
			}
		}else{
			InvalidData();
		}
	}
}
