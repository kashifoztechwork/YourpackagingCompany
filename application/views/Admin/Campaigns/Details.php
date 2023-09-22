<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->Bundels->Snippet('PostScripts',_E('<script type="text/javascript">
        $(function(){
            $(\'body\').on(\'click\',\'[name=Save][type=button]\',function(){
                var E = $(this);
                var ID = E.attr(\'data-id\');
                var Object = {Save: \'true\'};
                Object[CFN] = CFH;
                Object[\'Item\'] = [ID];
                $(\'[data-type=DataForm] input[type!=checkbox][name*="[\'+(ID)+\']"],[data-type=DataForm] input[type=checkbox][name*="[\'+(ID)+\']"]:checked,[data-type=DataForm] select[name*="[\'+(ID)+\']"],[data-type=DataForm] textarea[name*="[\'+(ID)+\']"]\').each(function(){
                    var Item = $(this);
                    Object[Item.attr(\'name\')] = Item.val();
                });
                Request(\'{(CurrentURI().($_SERVER[\'QUERY_STRING\'] ? \'?\'.$_SERVER[\'QUERY_STRING\'] : \'\' ))}\',function(Data){
                    if(Data.Status){
                        SuccessMessage(\'Saved\',Data.Message);
                    }else{
                        ErrorMessage(\'Data Not Saved\',Data.Error);
                    }
                },Object);
            });
            $(\'.form-group[data-id=PaymentMethod]\').hide();
            $(\'body\').on(\'change\',\'[name^=PaymentStatus]\',function(){
                var E = $(this);
                var Fields = \'.form-group[data-id=PaymentMethod],.form-group[data-id=AmountToPay],.form-group[data-id=PaymentDate],.form-group[data-id=AmountPaid]\';
                if(E.val() == \'Payment Processed\'){
                    E.parents(\'.row\').first().find(Fields).show();
                }else if(E.val() == \'Pending Payment\'){
                    E.parents(\'.row\').first().find(\'.form-group[data-id=AmountToPay]\').show();
                }else{
                    E.parents(\'.row\').first().find(Fields).hide();
                    E.parents(\'.row\').first().find(\'.form-group[data-id=AmountToPay]\').hide();
                }
            });
            $(\'body [name^=PaymentStatus]\').change();

            $(\'body\').on(\'change\',\'[name^=ExtraAmountStatus]\',function(){
                var E = $(this);
                var PendingFields = \'.form-group[data-id=ExtraPaymentReason],.form-group[data-id=ExtraPayableAmount]\';
                var ProcessedFields = \'.form-group[data-id=ExtraPaymentReason],.form-group[data-id=ExtraPaymentDate],.form-group[data-id=ExtraAmountPaid],.form-group[data-id=ExtraPayableAmount]\';
                if(E.val() == \'Payment Processed\'){
                    E.parents(\'.row\').first().find(ProcessedFields).show();
                }else if(E.val() == \'Pending Payment\'){
                    E.parents(\'.row\').first().find(PendingFields).show();
                }else{
                    E.parents(\'.row\').first().find(ProcessedFields).hide();
                }
            });
            $(\'body [name^=ExtraAmountStatus]\').change();

            $(\'.form-group[data-id=PostStatus],.form-group[data-id=PostFL]\').hide();
            $(\'body\').on(\'change\',\'[name^=PostEmailDate]\',function(){
                var E = $(this);
                var Fields = \'.form-group[data-id=PostStatus],.form-group[data-id=PostFL]\';
                if(E.val() != \'\'){
                    E.parents(\'.row\').first().find(Fields).show();
                }else{
                    E.parents(\'.row\').first().find(Fields).hide();
                }
            });
            $(\'body [name^=PostEmailDate]\').change();

            $(\'.form-group[data-id=ReviewStatus],.form-group[data-id=ReviewFL]\').hide();
            $(\'body\').on(\'change\',\'[name^=ReviewRequestDate]\',function(){
                var E = $(this);
                var Fields = \'.form-group[data-id=ReviewStatus],.form-group[data-id=ReviewFL]\';
                if(E.val() != \'\'){
                    E.parents(\'.row\').first().find(Fields).show();
                }else{
                    E.parents(\'.row\').first().find(Fields).hide();
                }
            });
            $(\'body [name^=ReviewRequestDate]\').change();

        });
    </script>
    <style type="text/css">th{position:-webkit-sticky;position:sticky;top:0;z-index:2}th[scope=row]{position:-webkit-sticky;position:sticky;left:0;z-index:1}th[scope=row]{vertical-align:middle;color:inherit;background-color:inherit;background:linear-gradient(90deg,transparent 0,transparent calc(100% - .05em),#d6d6d6 calc(100% - .05em),#d6d6d6 100%)}table:nth-of-type(2) th:not([scope=row]):first-child{left:0;z-index:3;background:linear-gradient(90deg,#666 0,#666 calc(100% - .05em),#ccc calc(100% - .05em),#ccc 100%)}th[scope=row]+td{min-width:24em}th[scope=row]{min-width:20em}body{padding-bottom:90vh}</style>'));
    $DetailsA = [
        'Campaign Name'=>'{Row.Title}',
        'Company'=>'{Row.CompanyName}',
        'Brand'=>'{Row.BrandName}',
        'Product'=>'{Row.ProductName}',
    ];
    $DetailsB = [
        'Date Created'=>'{Row.EntryDate}',
        'Last Updated'=>'{Row.EntryUpdateDate}',
        'Total Items In Pool'=>$Pool->Total,
    ];
    $DetailsC = [
        'Total Email Sent'=>'{Row.TotalIntroEmails}',
        'Total Rebates'=>'{Row.TotalRebates} ({Row.RebatesPercent}%)',
        'Total Paypal Case Opened'=>'{Row.TotalPaypalCaseOpened} ({Row.PayPalCasePercent}%)',
        'Total Paypal Case Won'=>'{Row.TotalPaypalCaseWon} ({Row.PayPalCaseWonPercent}%)',
        'Total Refund'=>'{Row.TotalRefunded} ({Row.RefundPercent}%)',
        'Total Buys'=>'{Row.TotalPurchase} ({Row.PurchasePercent}%)',
        'Total Reviews'=>'{Row.TotalReviews} ({Row.ReviewsPercent}%)',
        'Total IG Posts'=>'{Row.TotalPosts} ({Row.PostsPercent}%)'
    ];
?>
<div class="card">
    <div class="card-header">Campaign Details</div>
    <div class="card-body p-2">
        <div class="row">
            <div class="col-4">
                <ul class="list-group">
                    <?php echo TemplateItems($DetailsA,'<li class="list-group-item active"><b>{Key}</b>: {Item}</li>');?>
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-group">
                    <?php echo TemplateItems($DetailsB,'<li class="list-group-item"><b>{Key}</b>: {Item}</li>');?>
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-group">
                    <li class="list-group-item active">Campaign Summary</li>
                    <?php echo TemplateItems($DetailsC,'<li class="list-group-item"><b>{Key}</b>: {Item}</li>');?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Quick Filters</div>
    <div class="card-body row">
        <?php
            $Actions = [
                [
                    'Button'=>'Do Today',
                    'ButtonTitle'=>'Today Actions',
                    'Actions'=>[
                        ['PayPalPayments'=>'Send Paypal Payment'],
                        ['SendPurchaseEmail'=>'Send Purchase Email'],
                        ['SendPurchaseFL2Email'=>'Send Purchase Frustration Level 2 Email'],
                        ['SendPurchaseFL3Email'=>'Send Purchase Frustration Level 3 Email'],
                        ['OpenPaypalCase'=>'Open Paypal Case'],
                        ['PostRequestEmail'=>'Send IG Post Request'],
                        ['SendPostFL2Email'=>'Send IG Post Frustration Level 2 Email'],
                        ['SendPostFL3Email'=>'Send IG Post Frustration Level 3 Email'],
                        ['ReviewRequestEmail'=>'Send Review Request Email'],
                        ['SendReviewFL2Email'=>'Send Review Frustration Level 2 Email'],
                        ['SendReviewFL3Email'=>'Send Review Frustration Level 3 Email']
                    ]
                ],
                [
                    'Button'=>'Overdue Items',
                    'ButtonTitle'=>'Overdue Actions',
                    'Actions'=>[
                        ['ODSendPurchaseFL1Email'=>'Send Purchase Frustration Level 2 Email'],
                        ['ODSendPurchaseFL3Email'=>'Send Purchase Frustration Level 3 Email'],
                        ['ODPostRequestEmail'=>'Send IG Post Request'],
                        ['ODSendPostFL2Email'=>'Send IG Post Frustration Level 2 Email'],
                        ['ODSendPostFL3Email'=>'Send IG Post Frustration Level 3 Email'],
                        ['ODReviewRequestEmail'=>'Send Review Request Email'],
                        ['ODSendReviewFL2Email'=>'Send Review Frustration Level 2 Email'],
                        ['ODSendReviewFL3Email'=>'Send Review Frustration Level 3 Email']
                    ]
                ]
            ];
            $Selected = '';
            foreach($Actions as $Button):?>
                <div class="dropdown col-6 text-center">
                    <?php echo _Button('Actions',$Button['Button'],['class'=>'btn btn-primary dropdown-toggle','data-toggle'=>'dropdown','style'=>'padding:0.56rem 1.375rem;'])?>
                    <div class="dropdown-menu">
                        <h6 class="dropdown-header"><?php echo $Button['ButtonTitle']?></h6>
            <?php
                foreach($Button['Actions'] as $Buttons){
                    foreach($Buttons as $Name=>$Title){
                        $Label = sprintf('(<b>%s</b>) %s',_E(sprintf('{QFD.%s}',$Name)),$Title);
                        if($Name == $QFAction)$Selected = $Label;
                        echo _A($Label,AURI(sprintf('Details/{Row.ID}?QFAction=%s',$Name)),['class'=>'dropdown-item']);
                    }
                }

            ?>
                    </div>
                </div>
            <?php
                endforeach;
            ?>
    </div>
    <?php
        if($Selected){
            echo _Tag('div',false,$Selected,['class'=>'card-footer text-center text-white bg-info']);
        }
    ?>
</div>
<form action="" method="get" class="accordion accordion-multiple-outline">
    <div class="card mt-1">
        <div class="card-header" role="tab">
            <h6 class="mb-0">
                <?php echo _A('<i class="card-icon mdi mdi-filter"></i> Filters','#Filters',['class'=>'collapsed','data-toggle'=>'collapse','aria-expanded'=>'false'])?>
            </h6>
        </div>
        <div class="card-body show" id="Filters">
            <div class="row">
                 <?php echo GenerateFields(FilterFields($Filters))?>
            </div>
        </div>
        <div class="card-footer text-right">
            <?php
                if(GS('F'))echo _A('Clear All Filters',AURI('Details/{Row.ID}'),['class'=>'btn btn-warning']).' ';
                echo _Button('Search','Filter',['class'=>'btn btn-info']);
            ?>
        </div>
    </div>
</form>
<form class="card mt-1" action="" method="post">
    <?php echo CSRF();?>
    <div class="card-header bg-info text-white">Manage Pool Data <?php echo sprintf('(Showing %s%s%d Out Of %d Record(s)) - This Page: <b>%d</b>',$Page > 1 ? ($Page - 1) * $PerPage : '',$Page > 1 ? ' - ' : '',$Pool->Rows >= $Page * $PerPage ? $Page * $PerPage : $Pool->Rows,$Pool->Total,$Pool->Rows)?></div>
    <div class="card-header"><?php echo _Button('Save','Save All',['class'=>'btn btn-success']); echo ' ';echo _Button('Download','Download Following Contacts',['class'=>'btn btn-primary'])?></div>
    <div class="card-body p-1">
        <table class="table table-bordered table-responsive" data-type="DataForm">
            <tbody>
                <?php
                    $Items = [];
                    foreach($Headers as $Header=>$Value){
                        $Items[$Header][] = _Tag('th',false,$Header,['scope'=>'row','class'=>'bg-white ml-0']);
                    }
                    foreach($Pool->Result as $Item){
                        Def($Item,'Item');
                        $Index = 0;
                        foreach($Headers as $Header=>$Value){
                            $Items[$Header][] = _Tag('td',false,_E($Value,true),$Index == 0 ? ['style'=>'font-weight: bold; min-width: 300px;'] : []);
                            $Index++;
                        }
                        //$Items[''][] = _Tag('td',false,_Button('Save','Save Data',['class'=>'btn btn-success']));
                    }
                    foreach($Items as $Header=>$Data){
                        echo _Tag('tr',false,implode('',$Data),[]);
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer text-right">
        <div class="row">
            <?php
                echo _Tag('div',false,_Button('Save','Save All',['class'=>'btn btn-success']),['class'=>'col-3']);
                if($Pages > 1){
            		//Limitize Pagination Page Numbers
                    $ListMethod = 'Details/{Row.ID}';
            		$PagesLimit = 3;
            		$End = $Page+$PagesLimit >= $Pages ? $Pages : $Page+$PagesLimit;
            		$Start = $Page >= $PagesLimit ? $Page-$PagesLimit : 1;
            		$Start = $Start == 0 ? 1 :  $Start;
            		//Getting Query Parameters
            		$QS = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ? sprintf('?%s',$_SERVER['QUERY_STRING']) : '';
            		$Links = array();
            		if($Page > 1){
            			$Links[] = sprintf('<li class="page-item">%s</li>',_A('<i class="mdi mdi-chevron-left"></i>',AURI(sprintf('%s/%s%s',$ListMethod,$Page-1,$QS)),['class'=>'page-link']));
            		}
            		for($I=$Start;$I<=$End;$I++){
            			if($I == $Page){
            				$Class = ' active';
            			}else{
            				$Class = '';
            			}
            			$Links[] = sprintf('<li class="page-item%s">%s</li>',$Class,_A($I,AURI(sprintf('%s/%s%s',$ListMethod,$I,$QS)),['class'=>'page-link']));
            		}
            		if($Page != $Pages){
            			$Links[] = sprintf('<li class="page-item">%s</li>',_A('<i class="mdi mdi-chevron-right"></i>',AURI(sprintf('%s/%s%s',$ListMethod,$Page+1,$QS)),['class'=>'page-link']));
            		}
            		echo sprintf('<nav class="col-8"><ul class="pagination d-flex justify-content-center pagination-success">%s</ul></nav>',implode('',$Links));
            	}
            ?>
        </div>
    </div>
</form>
