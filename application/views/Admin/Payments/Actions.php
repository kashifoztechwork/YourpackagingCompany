<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $ActionContainer = [];
    foreach($Actions as $Access=>$Action){
        if($Access == 'ORDERPAYMENT' && $Row->TotalPaid >= $Row->TotalCost){
            $Action = NULL;
        }
        if($Action && $this->Access->HaveAccess($Access)){
            $Attributes = isset($Action['Attributes']) ? $Action['Attributes'] : [];
            $Attributes['Class'] = 'dropdown-item';
            $ActionContainer[] = _A($Action['Title'],_E($Action['Link']),$Attributes);
        }
    }
    echo implode('',$ActionContainer);
