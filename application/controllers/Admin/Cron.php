<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron extends M_Default {

	public	$Required = ['Campaign','Contact','CampaignPool','ProductModel','CampaignPoolActivity','Brand','Invoice'],
			$LoginRequired = false;

	public function __construct(){

		parent::__construct();
	}

	public function SendInvoice(){

		$Time = strtotime('-1 month',strtotime(date('F Y')));
		$From = date('Y-m-01',$Time);
		$To = date('Y-m-t',$Time);
		$ToSave = [];
		$Brands = [];
		$this->Brand->UseRelation = ['Companies'];
		foreach($this->Brand->GetList([],0,0,'','BRD.*,COM.Config CompanyConfig,COM.Name CompanyName')->Result as $Brand){
			$this->Campaign->UseRelation = ['ProductModels'];
			$this->Campaign->UseJoins = ['ProductDetails','CampaignPool'];
			$this->Campaign->DB()->group_by('CMP.ProductModelID')->where(sprintf('CMPP.PaymentDate BETWEEN \'%s\' AND \'%s\'',$From,$To));
			$List = $this->Campaign->GetList(['PRD.BrandID'=>$Brand->ID,'CMPP.PaymentStatus'=>'Payment Processed','CMPP.OrderStatus !='=>'Refunded'],0,0,'','
				BRD.Name BrandName,COM.Name CompanyName,PRD.Name ProductName,PMDL.Name ProductModelName,
				SUM({P}CMPP.AmountPaid) TotalAmount,
				SUM({P}CMPP.ExtraAmountPaid) TotalExtraAmount,
				COUNT(DISTINCT {P}CMPP.ID) TotalGiveaways
			');
			if($List->Rows > 0){
				$Brands[$Brand->ID] = $Brand;
				$ToSave[] = [
					'StartDate'=>$From,
					'EndDate'=>$To,
					'BrandID'=>$Brand->ID,
					'ServicesCharges'=>0,
					'Tax'=>0,
					'Config'=>serialize([
						'List'=>$List,
						'DueDate'=>sprintf('Net %d Days',GetTime($From,$To,'Days',false))
					])
				];
			}
		}
		//Saving Data
		$Clouse = ['StartDate'=>$From,'EndDate'=>$To,'BrandID'=>array_keys($Brands)];
		$this->Invoice->Delete($Clouse,true);
		$this->Invoice->Insert($ToSave,true);
		//Generating PDF For Saved Data
		$Data['Headers'] = [
			'Item'=>'Marketing Services - {Row.BrandName} > {Row.ProductModelName}',
			'Period'=>sprintf('%s - %s',date('F d Y',strtotime($From)),date('F d Y',strtotime($To))),
			'Total Transactions'=>'{Row.TotalGiveaways}',
			'Subtotal'=>'{(Price(\'{Row.T otalAmount}\'))}',
			'Extra Amount'=>'{(Price(\'{Row.TotalExtraAmount}\'))}',
			'Total'=>'{(Price(_E(\'{Row.TotalAmount}\',true) + _E(\'{Row.TotalExtraAmount}\',true)))}'
		];
		foreach($this->Invoice->GetList($Clouse)->Result as $Invoice){
			$Data['Brand'] = $Brand = $Brands[$Invoice->BrandID];
			$InvoiceConfig = unserialize($Invoice->Config);
			Def($Brand,'Brand');
			Def($Invoice,'Invoice');
			$BrandConfig = unserialize($Brand->CompanyConfig);
			$Data['List'] = $InvoiceConfig['List'];
			$Data['Invoice'] = $Invoice;
			$Data['PDF'] = true;
			$Name = $this->Invoice->GeneratePDF($Data,$Invoice,false,$InvoiceNumber);

			if(isset($BrandConfig['InvoiceEmail']) && $BrandConfig['InvoiceEmail']){
				//Sending Email
				$this->load->library('email',[
					'mailtype'=>'html'
				]);
				$this->email->from('no-replay@tentwentyllc.com','TenTwenty LLC');
				$this->email->to($BrandConfig['InvoiceEmail']);
				if(isset($BrandConfig['InvoiceOtherEmailAddresses'])){
					$CC = explode("\n",$BrandConfig['InvoiceOtherEmailAddresses']);
					if(!empty($CC)){
						$CCSend = [];
						foreach($CC as $Index=>$CCAddress){
							$CCAddress  = trim($CCAddress);
							if($CCAddress)$CCSend[] = $CCAddress;
						}
						if(!empty($CCSend))$this->email->cc(implode(',',$CCSend));
					}
				}
				$this->email->subject(sprintf('Invoice (%s) From TenTwenty LLC - %s',$InvoiceNumber,date('F Y',strtotime($From))));
				$this->email->message(sprintf('
				<b>Hello,</b><br/><br/>
				Your invoice <b>#%s</b> (attached) from TenTwenty LLC is ready and needs to be paid within 30 days.<br/>
				Please make payment.<br/><br/>
				Thank you.
				',$InvoiceNumber));
				$this->email->attach($Name);
				$this->email->send(false);
				unset($this->email);
			}
		}
	}

	public function __destruct(){
		parent::__destruct();
	}
}
