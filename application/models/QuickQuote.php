<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class QuickQuote extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_QUICKQUOTE,
			$DBInstance = 'QQ',
			$EnableLogs = false;

	public function __construct(){

		$this->ResetRelations();
		parent::__construct();
	}

	// QouteFields
	public function QuoteFields(){
		return [
			[
				'Name'=>'Name',
				'Label'=>'Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				]
			],
			[
				'Name'=>'Email',
				'Label'=>'Email',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				]
			],
			[
				'Name'=>'Phone',
				'Label'=>'Phone',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				]
			],
			[
				'Name'=>'Quantity',
				'Label'=>'Quantity',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				]
			],
			[
				'Name'=>'Colors',
				'Label'=>'Colors',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12,
					'Data'=>['1 Color','2 Color','3 Color','4 Color','4/1 Color','4/2 Color','4/3 Color','4/4 Color'],
					'UV'=>false
				]
			],
			[
				'Name'=>'Config[Length]',
				'Label'=>'Length',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				]
			],
			[
				'Name'=>'Config[Width]',
				'Label'=>'Width',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				]
			],
			[
				'Name'=>'Config[Depth]',
				'Label'=>'Depth',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				]
			],
			[
				'Name'=>'Config[Unit]',
				'Label'=>'Unit',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3,
					'Data'=>['CM','MM','INCH'],
					'UV'=>false
				]
			],
			[
				'Name'=>'Config[Message]',
				'Label'=>'Message',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				]
		
			],
			[
				'Name'=>'Image',
				'Label'=>'Image',
				'Type'=>'File',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				]
		
			]
		];
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Name',
						'Length'=>77,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Email',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Phone',
						'Length'=>20,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Quantity',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Product',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Colors',
						'Length'=>40,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Config',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Image',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>false
					]
				]
			]);
		}
	}
}
