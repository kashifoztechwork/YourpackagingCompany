<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <style type="text/css">
            body{padding: 10px;}
            *{font-family: calibri;margin:0px;padding: 0px;}
            h3,h4{margin: 0px;}
            h3{font-size: 22px;color: #35b0ff !important; font-weight: normal;}
            h4{font-size: 18px;}
            p.info{color: #999;}
            .ftheader{border: dotted 1px #ddd;padding: 10px;}
            .subinfo{padding:5px 20px;}
            table.invoicedetails thead th{background:#EEE;padding: 10px 2px;text-align: center;border-bottom: solid 1px #CCC;border-top: solid 1px #CCC;}
            table.invoicedetails thead th:first-child,table.invoicedetails tbody td:first-child{text-align: left;}
            table.invoicedetails tbody td{padding: 8px 2px;text-align: center;border-bottom: solid 1px #CCC;}
            .PrintButton{padding: 5px;position: fixed; right: 10px;}
        </style>
        <style media="print">
            .PrintButton{display: none;}
        </style>
        <?php if(PS('Print')):?>
            <script type="text/javascript">
                window.print();
            </script>
        <?php endif;?>
        <title>TenTwenty LLC - <?php echo _E('{Brand.CompanyName}')?></title>
    </head>
    <body>
        <form action="" method="post">
            <?php
                echo LocalImage('logoinvoice.jpg','200,auto',['style'=>'margin-bottom: 10px;margin-right: 10px;']);
                if(!$PDF){
                    echo _Button('Print','Print',['class'=>'PrintButton']);
                    echo _Button('Save','Save',['class'=>'PrintButton','style'=>'right: 60px;']);
                    echo _Button('PDF','Export PDF',['class'=>'PrintButton','style'=>'right: 110px;']);
                }
            ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td valign="top" class="ftheader">
                            <h3>From:</h3>
                            <h4>TenTwenty LLC</h4>
                            <p class="info">80 SW 8th St Suite 3303<br />Miami, FL 33130<br />United States</p>
                        </td>
                        <td width="20"></td>
                        <td valign="top" class="ftheader">
                            <h3>To:</h3>
                            <?php
                                echo _E('<h4>{Brand.CompanyName}</h4><p class="info">{Brand.CompanyConfig.Address}<br />{Brand.CompanyConfig.City}, {Brand.CompanyConfig.State} {Brand.CompanyConfig.Zip}<br />{Brand.CompanyConfig.Country}</p>')
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="3" height="30px"></td></tr>
                    <tr>
                        <td colspan="3" class="subinfo"><b>Invoice Number : </b><?php echo str_pad($Invoice->ID,6,301200,STR_PAD_LEFT)?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="subinfo"><b>Invoice Date : </b><?php echo date('F Y')?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="subinfo"><b>Date Due: </b><?php echo PS('Print') || $PDF ? _E('{Invoice.Config.DueDate}',true) : _Input('DueDate',_E('{Invoice.Config.DueDate}',true) ? _E('{Invoice.Config.DueDate}',true) : 'Net 90 Days','text',['style'=>'padding: 5px;']);?></td>
                    </tr>
                    <tr><td colspan="3" height="30px"></td></tr>
                </tbody>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" class="invoicedetails">
                <thead>
                    <?php
                        $Out = [];
                        foreach($Headers as $Key=>$Value){
                            $Out[$Key] = _Tag('th',false,$Key);
                        }
                        echo _Tag('tr',false,implode('',$Out));
                    ?>
                </thead>
                <tbody>
                    <?php
                        $Totals = [];
                        foreach($List->Result as $Row){
                            Def($Row,'Row');
                            foreach($Headers as $Key=>$Value){
                                $Out[$Key] = _Tag('td',false,_E($Value,true));
                            }
                            echo _Tag('tr',false,implode('',$Out));
                            $Totals[] = $Row->TotalAmount;
                            $Totals[] = $Row->TotalExtraAmount;
                        }
                    ?>
                </tbody>
            </table>
            <table cellpadding="0" cellspacing="0" style="margin-top:50px;" width="100%">
                <tbody>
                    <tr>
                        <td width="60%"></td>
                        <td width="40%">
                            <table width="100%" cellpadding="0" cellspacing="0" class="invoicedetails">
                                <thead>
                                    <tr>
                                        <th colspan="2" style="text-align: center;">Invoice Summary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: left;padding-left: 50px;"><b>Subtotal</b></td>
                                        <td style="text-align: right;padding-right: 50px;"><?php echo Price(Sum($Totals))?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;padding-left: 50px;"><b>Service Charges</b></td>
                                        <td style="text-align: right;padding-right: 50px;"><?php echo PS('Print') || $PDF ? Price($Invoice->ServicesCharges) : _Input('ServiceCharges',$Invoice->ServicesCharges,'Number',['style'=>'padding: 5px;','step'=>0.01]);?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;padding-left: 50px;"><b>Taxes</b></td>
                                        <td style="text-align: right;padding-right: 50px;"><?php echo PS('Print') || $PDF ? Price($Invoice->Tax) : _Input('Tax',$Invoice->Tax,'Number',['style'=>'padding: 5px;','step'=>0.01]);?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;padding-left: 50px;"><b>Grand Total</b></td>
                                        <td style="text-align: right;padding-right: 50px;"><?php echo Price(Sum($Totals) + $Invoice->ServicesCharges + $Invoice->Tax)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php echo CSRF();?>
        </form>
    </body>
</html>
