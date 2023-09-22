<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $Bundels->Snippet('PostScripts',_E('
        <style type="text/css">
            .wizard > .steps > ul > li {width: 16.65% !important;}
            .wizard > .content > .body ul > li {display: block !important;padding: 0.75rem 1.25rem !important;}
        </style>
        <script type="text/javascript">
            var Rules = {
            };
            var LastAForm = 0;
            var LastBForm = 0;
            $(function(){
                //Inventment
                $(\'body\').on(\'change\',\'[data-type=Investment]\',function(){
                    var FinalInvestment = 0;
                    var EL = {};
                    var Index = 0;
                    $(\'[data-type=Investment]\').each(function(E){
                        EL[Index] = $(this).val() ? parseFloat($(this).val()) : 0;
                        Index++
                    });
                    FinalInvestment = ((EL[0] + EL[1]) * (EL[2] + EL[3]) * EL[4] * 1.3) + ((5 + EL[5] + EL[6]) * EL[7]) + ((EL[5] + EL[6]) * EL[3] * 0.66);
                    $(\'[data-type=FinalInvestment]\').val(FinalInvestment.toFixed(2));
                });
                //Order
                $(\'body\').on(\'change\',\'[data-type=Order]\',function(){
                    var FinalOrder = 0;
                    var OR = {};
                    var Index = 0;
                    $(\'[data-type=Order]\').each(function(E){
                        OR[Index] = $(this).val() ? parseInt($(this).val()) : 0;
                        Index++
                    });
                    FinalOrder = OR[0] * (OR[1] + OR[2] + OR[3] + OR[4]);
                    $(\'[data-type=FinalOrder]\').val(FinalOrder);
                });
                $(\'body\').on(\'change\',\'[name=Criteria]\',function(){
                    var E = $(this);
                    var Criteria = E.val();
                    /*if(Criteria == 1){
                        var Elements = {
                            \'StepB[ROI]\' : \'{OC.1.ROI.Value}\',
                            \'StepC[ReviewRequired]\' : \'{OC.1.Review_B.Value}\'
                        };
                    }
                    for(var I in Elements){
                        var Value = Elements[I];
                        $(\'[name="\'+(I)+\'"]\').val(Value).attr(\'readonly\',true);
                    }*/
                    if(Criteria == 3){
                        RemoveSection(\'CriteriaFormA\',LastBForm);
                        RemoveSection(\'CriteriaFormB\',LastAForm);
                        LastBForm = DuplicateSection(\'CriteriaFormB\');
                    }else{
                        RemoveSection(\'CriteriaFormA\',LastBForm);
                        RemoveSection(\'CriteriaFormB\',LastAForm);
                        LastAForm = DuplicateSection(\'CriteriaFormA\');
                    }
                });
                $(\'[name=Criteria]:checked\').change();
            });
        </script>
    '));
    $Bundels->Snippet('PostScripts',$Bundels->Render('Wizard','JS'));
?>
<div class="card">
    <div class="card-header">
        <?php echo _E('{Current.Title}')?>
    </div>
    <div class="card-body">
        <form action="<?php echo AURI('Action/Add')?>" method="post" data-type="Wizard">
            <div data-type="Steps">
                <h3>Product Criteria</h3>
                <section class="row" style="width: 100%;">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'Criteria',
                                'Label'=>'Criteria',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>_E('<div class="col-12">
                                        <div class="card">
                                            <div class="card-header p-2 bg-info text-white">{(_Input(\'Criteria\',1,\'radio\',[\'class\'=>\'d-inline\',\'AA\'=>[\'checked\']]))} Risky, Fast Growth {(_A(\'Edit\',AURI(\'Criteria\'),[\'class\'=>\'btn btn-danger btn-sm\',\'target\'=>\'_blank\']))}</div>
                                            <div class="card-body ">
                                                ROI > {OC.1.ROI.Value}%<br />
                                                Profit Margin > {OC.1.ProfitMargin.Value}%<br />
                                                At Least {OC.1.ASIN_A.Value} ASINs with < {OC.1.Review_A.Value} reviews making > ${OC.1.Revenue_A.Value} revenue<br />
                                                At Least {OC.1.ASIN_B.Value} ASINs making > ${OC.1.Revenue_B.Value} revenue<br />
                                                At Least {OC.1.ASIN_C.Value} ASINs making > ${OC.1.Revenue_C.Value} revenue<br />
                                                At Least {OC.1.Keywords_A.Value} relevent keywords with SRF < {OC.1.SRF_A.Value}<br />
                                                At Least {OC.1.Keywords_B.Value} keywords with SRF < {OC.1.SRF_B.Value} with one of the top {OC.1.ASIN_D.Value} ASINs having less then {OC.1.Review_B.Value} reviews<br />
                                            </div>
                                        </div>
                                        <div class="card mt-3">
                                            <div class="card-header p-2 bg-info text-white">{(_Input(\'Criteria\',2,\'radio\',[\'class\'=>\'d-inline\']))} Steady, Safe Growth {(_A(\'Edit\',AURI(\'Criteria\'),[\'class\'=>\'btn btn-danger btn-sm\',\'target\'=>\'_blank\']))}</div>
                                            <div class="card-body">
                                                ROI > {OC.2.ROI.Value}%<br />
                                                Profit Margin > {OC.2.ProfitMargin.Value}%<br />
                                                At Least {OC.2.ASIN_A.Value} ASINs with < {OC.2.Review_A.Value} reviews making > ${OC.2.Revenue_A.Value} revenue<br />
                                                At Least {OC.2.ASIN_B.Value} ASINs with < {OC.2.Review_B.Value} reviews making > ${OC.2.Revenue_B.Value} revenue<br />
                                                Not more then {OC.2.ASIN_C.Value} ASINs with more then {OC.2.Review_C.Value} reviews making > ${OC.2.Review_C.Value} reviews<br />
                                                At Least {OC.2.Keywords_A.Value} relevent keywords with SFR {OC.2.SFR_A.Value}
                                            </div>
                                        </div>
                                        <div class="card mt-3">
                                            <div class="card-header p-2 bg-info text-white">{(_Input(\'Criteria\',3,\'radio\',[\'class\'=>\'d-inline\']))} Mirroring</div>
                                            <!--<div class="card-body" style="line-height: 50px;">
                                                Start from Budget Calculator and Submit your criteria  here:<br />
                                                - Product Price {(_Input(\'Static[ProductPrice]\',\'\',\'text\',[\'class\'=>\'form-control w-auto d-inline\']))}<br />
                                                - Profit Margin {(_Input(\'Static[ProfitMargin]\',\'\',\'text\',[\'class\'=>\'form-control w-auto d-inline\']))}<br />
                                                - Monthly Profit Goal {(_Input(\'Static[ProfitGoal]\',\'\',\'text\',[\'class\'=>\'form-control w-auto d-inline\']))}<br />
                                                - Min # of Reviews {(_Input(\'Static[Reviews]\',\'\',\'text\',[\'class\'=>\'form-control w-auto d-inline\']))}<br />
                                            </div>-->
                                        </div>
                                    </div>')
                                ],
                                'Default'=>'{Row.Criteria}'
                            ]
                        ]);
                    ?>
                </section>
                <h3>Basic</h3>
                <section style="width: 100%;">
                    <div class="col-12" data-type="Duplicate" data-section="CriteriaFormA" data-index="{Index}">
                        <div class="row m-0 p-0">
                            <?php
                                echo GenerateFields([
                                    [
                                        'Name'=>'Keyword',
                                        'Label'=>'Keyword',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                            'Template'=>COL9
                                        ],
                                        'Default'=>'{Row.Keyword}'
                                    ],[
                                        'Name'=>'ASIN',
                                        'Label'=>'Product ASIN',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL3
                                        ],
                                        'Default'=>'{Row.ASIN}'
                                    ],[
                                        'Name'=>'CompanyID',
                                        'Label'=>'Select Company',
                                        'Type'=>'DropDown',
                                        'Config'=>[
                                            'Model'=>'Company',
                                            'Fields'=>['ID','Name','ProfileName'],
                                            'Display'=>'{Name} ({ProfileName})',
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL3
                                        ],
                                        'Default'=>'{Row.CompanyID}'
                                    ],[
                                        'Name'=>'Link',
                                        'Label'=>'Product Link',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL9
                                        ],
                                        'Default'=>'{Row.Link}'
                                    ],[
                                        'Name'=>'StepA[NumberOfSellers]',
                                        'Type'=>'Number',
                                        'Label'=>'Number Of Sellers Making More Than $30k/m Revenue',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL6
                                        ],
                                        'Default'=>'{Row.StepA.NumberOfSellers}'
                                    ],[
                                        'Name'=>'StepA[NicheAge]',
                                        'Type'=>'Number',
                                        'Label'=>'Avg. Niche Age',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL3
                                        ],
                                        'Default'=>'{Row.StepA.NicheAge}'
                                    ],[
                                        'Name'=>'StepA[Price]',
                                        'Type'=>'Number',
                                        'Label'=>'Avg. Price',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL3
                                        ],
                                        'Default'=>'{Row.StepA.Price}'
                                    ],[
                                        'Name'=>'StepA[Revenue]',
                                        'Label'=>'Avg. Revenue',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL6
                                        ],
                                        'Default'=>'{Row.StepA.Revenue}'
                                    ],[
                                        'Name'=>'StepA[Review]',
                                        'Type'=>'Number',
                                        'Label'=>'Avg Review',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control'],
                                            'Template'=>COL6
                                        ],
                                        'Default'=>'{Row.StepA.Review}'
                                    ],[
                                        'Name'=>'StepA[Review]',
                                        'Type'=>'TextArea',
                                        'Label'=>'Notes',
                                        'Config'=>[
                                            'Attributes'=>['class'=>'form-control','rows'=>5],
                                            'Template'=>COL12
                                        ],
                                        'Default'=>'{Row.StepA.Review}'
                                    ]
                                ]);
                            ?>
                        </div>
                    </div>
                    <div class="row" data-type="SectionContainer" data-id="CriteriaFormA"></div>
                    <div class="col-12" data-type="Duplicate" data-section="CriteriaFormB" data-index="{Index}">
                        <div class="row m-0 p-0">
                        <?php
                            echo GenerateFields([
                                [
                                    'Name'=>'Keyword',
                                    'Label'=>'Keyword',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                        'Template'=>COL12
                                    ],
                                    'Default'=>'{Row.Keyword}'
                                ],[
                                    'Name'=>'',
                                    'Config'=>[
                                        'Template'=>'<h6 class="col-12">Representative Details</h5><hr class="col-12 mt-0 border-top-1 mb-2" style="margin-left: -12px;" />'
                                    ]
                                ],[
                                    'Name'=>'ASIN',
                                    'Label'=>'Rep ASIN',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.ASIN}'
                                ],[
                                    'Name'=>'Rep ASIN Link',
                                    'Label'=>'Product Link',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL9
                                    ],
                                    'Default'=>'{Row.Link}'
                                ],[
                                    'Name'=>'StepA[Price]',
                                    'Type'=>'Number',
                                    'Label'=>'Rep ASIN Price',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.Price}'
                                ],[
                                    'Name'=>'StepA[Review]',
                                    'Type'=>'Number',
                                    'Label'=>'Rep ASIN Reviews',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.Review}'
                                ],[
                                    'Name'=>'StepA[ReviewRating]',
                                    'Type'=>'Number',
                                    'Label'=>'Rep ASIN Review Rating',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.ReviewRating}'
                                ],[
                                    'Name'=>'StepA[Revenue]',
                                    'Label'=>'Rep ASIN Revenue',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.Revenue}'
                                ],[
                                    'Name'=>'StepA[RunningAds]',
                                    'Label'=>'Rep ASIN Running Ads',
                                    'Type'=>'Radio',
                                    'Config'=>[
                                        'Data'=>['Yes'=>'Yes','No'=>'No'],
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL4
                                    ],
                                    'Default'=>'{Row.StepA.RunningAds}'
                                ],[
                                    'Name'=>'StepA[ImprovedImages]',
                                    'Label'=>'Rep ASIN Images can be Improved',
                                    'Type'=>'Radio',
                                    'Config'=>[
                                        'Data'=>['Yes'=>'Yes','No'=>'No'],
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL4
                                    ],
                                    'Default'=>'{Row.StepA.ImprovedImages}'
                                ],[
                                    'Name'=>'StepA[EBC]',
                                    'Label'=>'Rep ASIN EBC',
                                    'Type'=>'Radio',
                                    'Config'=>[
                                        'Data'=>['Yes'=>'Yes','No'=>'No'],
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL4
                                    ],
                                    'Default'=>'{Row.StepA.EBC}'
                                ],[
                                    'Name'=>'',
                                    'Config'=>[
                                        'Template'=>'<h6 class="col-12">Top Seller Details</h5><hr class="col-12 mt-0 border-top-1 mb-2" style="margin-left: -12px;" />'
                                    ]
                                ],[
                                    'Name'=>'StepA[TopSellerRevenue]',
                                    'Label'=>'Top Seller Avg. Revenue',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.TopSellerRevenue}'
                                ],[
                                    'Name'=>'StepA[TopSellerReviews]',
                                    'Label'=>'Top Seller Avg. Reviews',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.TopSellerReviews}'
                                ],[
                                    'Name'=>'StepA[TopSellerPrice]',
                                    'Type'=>'Number',
                                    'Label'=>'Top Seller Avg. Price',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control'],
                                        'Template'=>COL3
                                    ],
                                    'Default'=>'{Row.StepA.TopSellerPrice}'
                                ],[
                                    'Name'=>'',
                                    'Config'=>[
                                        'Template'=>'<hr class="col-12 border-top-1 mb-2" style="margin-left: -12px;" />'
                                    ]
                                ],[
                                    'Name'=>'StepA[Notes]',
                                    'Type'=>'TextArea',
                                    'Label'=>'Notes',
                                    'Config'=>[
                                        'Attributes'=>['class'=>'form-control','rows'=>5],
                                        'Template'=>COL12
                                    ],
                                    'Default'=>'{Row.StepA.Notes}'
                                ],[
                                    'Name'=>'',
                                    'Config'=>[
                                        'Template'=>'<hr class="col-12 border-top-1 mb-2" style="margin-left: -12px;" />'
                                    ]
                                ]
                            ]);
                        ?>
                    </div>
                    </div>
                    <div class="row" data-type="SectionContainer" data-id="CriteriaFormB"></div>
                </section>
                <h3>Estimation</h3>
                <section class="row" style="width: 100%;">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'StepB[Cost]',
                                'Label'=>'Estimated Product Cost',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL4
                                ],
                                'Default'=>'{Row.StepB.Cost}'
                            ],[
                                'Name'=>'StepB[ROI]',
                                'Label'=>'Estimated ROI',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL4
                                ],
                                'Default'=>'{Row.StepB.ROI}'
                            ],[
                                'Name'=>'StepB[PM]',
                                'Label'=>'Estimated PM',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control'],
                                    'Template'=>COL4
                                ],
                                'Default'=>'{Row.StepB.PM}'
                            ]
                        ]);
                    ?>
                </section>
                <h3>Parameters</h3>
                <section class="row" style="width: 100%;">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'X',
                                'Label'=>'Optimal Maximum Dimensions (Width)',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL4
                                ],
                                'Default'=>'{Row.X}'
                            ],[
                                'Name'=>'X',
                                'Label'=>'Height',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.Y}'
                            ],[
                                'Name'=>'Z',
                                'Label'=>'Depth',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.Z}'
                            ],[
                                'Name'=>'DUnit',
                                'Label'=>'Unit',
                                'Type'=>'DropDown',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'UV'=>false,
                                    'Data'=>DIMENTION_UNITS,
                                    'Template'=>COL2
                                ],
                                'Default'=>'{Row.Z}'
                            ],[
                                'Name'=>'Weight',
                                'Label'=>'Optimal Maximum Weight',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL4
                                ],
                                'Default'=>'{Row.X}'
                            ],[
                                'Name'=>'WUnit',
                                'Label'=>'Unit',
                                'Type'=>'DropDown',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Data'=>WEIGHT_UNITS,
                                    'UV'=>false,
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.WUnit}'
                            ],[
                                'Name'=>'Config[Size]',
                                'Label'=>'Optimal Size Tier',
                                'Type'=>'DropDown',
                                'Config'=>[
                                    'Data'=>['Small Standard-size','Large Standard-size','Small oversize','Medium oversize','Large oversize','Special oversize'],
                                    'UV'=>false,
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL5
                                ],
                                'Default'=>'{Row.Config.Size}'
                            ]
                        ]);
                    ?>
                    <div class="col-12">
                        <h4 class="display-4">Product size tiers</h4>
                        <p>Fulfillment fees are based on the weight and dimensions of the packaged item (the product size tier).</p>
                        <div class="alert alert-warning"><b>Important:</b> For multi-channel fulfillment orders, see Fulfillment fees for multi-channel fulfillment orders.</div>
                        <h4 class="display-4">Standard-size versus oversize</h4>
                        <p>A standard-size item is one that, when fully packaged, weighs 20 lb or less and does not exceed:</p>
                        <ul class="list-group">
                            <li class="list-group-item">18 inches on its longest side</li>
                            <li class="list-group-item">14 inches on its median side</li>
                            <li class="list-group-item">8 inches on its shortest side</li>
                        </ul>
                        <br />
                        <p>Any item exceeding these dimensions is considered oversize.</p>
                        <div class="alert alert-warning"><b>Important:</b> If a product is sold as a set, the weight and dimensions are the combined total of all of the items in the set packaged together.</div>
                        <h4 class="display-4">Determine the product size tier for your item</h4>
                        <p>To determine the correct tier for your item, find the row in the table below with measurements that do not exceed the weight or the dimensions of your item.</p>
                        <p>For standard-size products that weigh 1 lb or less, and for special oversize products, use the unit weight. For all other products, use the larger of either the single unit weight or the dimensional weight. The dimensional weight is equal to the unit volume (length x width x height) divided by 139. The dimensional weight for oversize items assumes a minimum width and height of 2 inches.</p>
                        <p>To calculate the length plus girth:</p>
                        <ol>
                            <li>Measure the length, height, and width of the packaged unit.</li>
                            <li>Calculate the girth by adding the shortest and median sides and multiplying by 2.</li>
                            <li>Add the longest side and girth.</li>
                        </ol>
                        <table class="table table-bordered">
                            <head>
                                <tr>
                                    <th colspan="5" class="bg-info text-white">Maximum weights and dimensions for packaged items</th>
                                </tr>
                                <tr>
                                    <th class="bg-info text-white">Product size tier</th>
                                    <th class="bg-info text-white">Weight</th>
                                    <th class="bg-info text-white">Longest side</th>
                                    <th class="bg-info text-white">Median side</th>
                                    <th class="bg-info text-white">Shortest side</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Small standard-size</td><td>12 oz</td><td>15 inches</td><td>12 inches</td><td>0.75 inch</tr>
                                <tr><td>Large standard-size</td><td>20 lb</td><td>18 inches</td><td>14 inches</td><td>8 inches</tr>
                                <tr><td>Small oversize</td><td>70 lb</td><td>60 inches</td><td>30 inches</td><td>n/a</tr>
                                <tr><td>Medium oversize</td><td>150 lb</td><td>108 inches</td><td>n/a</td><td>n/a</tr>
                                <tr><td>Large oversize</td><td>150 lb</td><td>108 inches</td><td>n/a</td><td>n/a</tr>
                                <tr><td>Special oversize*</td><td>Over 150 lb</td><td>Over 108 inches</td><td>n/a</td><td>n/a</tr>
                            </tbody>
                        </table>
                        <div class="alert alert-warning"><b>Important:</b> Amazon may verify the weight and dimensions of a product using representative samples. Amazon’s information about a product’s weight and dimensions will be used to calculate fees if there is a difference between Amazon’s information and a seller’s information. Amazon may change its information about a product’s weight and dimensions from time to time to reflect updated measurements. Fees based on the weight and dimensions of a product are calculated using Amazon’s information about the weight and dimensions of that product at the time the fee is calculated.</div>
                        <h4 class="display-4">*Special oversize</h4>
                        <p>The special oversize tier applies to products that must be delivered using special delivery options due to their size, weight, special handling requirements, or other restrictions. Products are classified and charged as special oversize if any of the following is true:</p>
                        <ul class="list-group">
                            <li class="list-group-item">Dimensions are greater than 108 inches on the longest side</li>
                            <li class="list-group-item">Unit weight or dimensional weight is greater than 150 lb</li>
                            <li class="list-group-item">Length + girth is greater than 165 inches</li>
                            <li class="list-group-item">Amazon has determined that the product requires special handling to ensure a good customer experience</li>
                        </ul>
                        <h4 class="display-4">Change a product's weight or dimensions</h4>
                        <p>When you are the only seller of an ASIN and you need to change your product's weight or dimensions, contact Seller Support and explain that you need to update the measurements. If you currently have inventory for the product, you may need to sell through it before the new weight and dimensions show.</p>
                        <div class="alert alert-info"><b>Note:</b> Amazon stores weight and dimensions at the ASIN level. We cannot target a specific unit for measurement. When multiple sellers sell the same ASIN, the weight and dimensions information may not represent your specific units.</div>
                    </div>
                </section>
                <h3>Inventment</h3>
                <section class="row" style="width: 100%;">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'StepC[ManufacturingTime]',
                                'Label'=>'Manufacturing Time Required',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.ManufacturingTime}'
                            ],[
                                'Name'=>'StepC[ShippingTime]',
                                'Label'=>'Shipping Time',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.ShippingTime}'
                            ],[
                                'Name'=>'StepC[CostPerUnit]',
                                'Label'=>'Cost Per Unit',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.CostPerUnit}'
                            ],[
                                'Name'=>'StepC[ShippingCostPerUnit]',
                                'Label'=>'Shipping Cost Per Unit',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.ShippingCostPerUnit}'
                            ],[
                                'Name'=>'StepC[TargetSales]',
                                'Label'=>'Target Sales Per Month',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.TargetSales}'
                            ],[
                                'Name'=>'StepC[FBAFees]',
                                'Label'=>'FBA Fees',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.FBAFees}'
                            ],[
                                'Name'=>'StepC[ReferralFee]',
                                'Label'=>'Amazon Referral Fee',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.ReferralFee}'
                            ],[
                                'Name'=>'StepC[ReviewRequired]',
                                'Label'=>'Number of Reviews Needed Before Ranking Campaign',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required'],'data-type'=>'Investment'],
                                    'Template'=>COL3
                                ],
                                'Default'=>'{Row.StepC.ReviewRequired}'
                            ],[
                                'Name'=>'StepC[InventmentRequired]',
                                'Label'=>'Required Initial Investment',
                                'Type'=>'Decimal',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['readonly'],'data-type'=>'FinalInvestment'],
                                    'Template'=>COL6
                                ],
                                'Default'=>'{Row.StepC.InventmentRequired}'
                            ]
                        ]);
                    ?>
                </section>
                <h3>Order</h3>
                <section class="row" style="width: 100%;">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'StepD[Sales]',
                                'Label'=>'Expected Sales/day',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','data-type'=>'Order']
                                ],
                                'Default'=>'{Row.StepD.Sales}'
                            ],[
                                'Name'=>'StepD[ManufactureDays]',
                                'Label'=>'Estimated Days to Manufacture (22 Optimal)',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','data-type'=>'Order']
                                ],
                                'Default'=>'{Row.StepD.ManufactureDays}'
                            ],[
                                'Name'=>'StepD[ShippingDays]',
                                'Label'=>'Estimated Days for Shipping (60 Optimal If Using 3PL)',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','data-type'=>'Order'],
                                    'Template'=>COL12
                                ],
                                'Default'=>'{Row.StepD.ShippingDays}'
                            ],[
                                'Name'=>'StepD[CampaginDays]',
                                'Label'=>'Number of Days Estimated For Reviews Campagin (Around 45 Optimal)',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','data-type'=>'Order'],
                                    'Template'=>COL12
                                ],
                                'Default'=>'{Row.StepD.CampaginDays}'
                            ],[
                                'Name'=>'StepD[RankingDays]',
                                'Label'=>'Days Required For Ranking Campaign (30 Optimal)',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','data-type'=>'Order']
                                ],
                                'Default'=>'{Row.StepD.RankingDays}'
                            ],[
                                'Name'=>'StepD[OrderUnits]',
                                'Label'=>'1st Order Units Requirement',
                                'Type'=>'Number',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['readonly'],'data-type'=>'FinalOrder']
                                ],
                                'Default'=>'{Row.StepD.OrderUnits}'
                            ]
                        ]);
                    ?>
                </section>
            </div>
            <?php echo CSRF()?>
        </form>
    </div>
</div>
