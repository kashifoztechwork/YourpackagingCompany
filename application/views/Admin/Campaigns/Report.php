<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    echo '<tbody>';
    $Out = [];
    $Totals = [];
    $Weights = [];
    foreach($List->Result as $Row){
        Def($Row,'Row');
        $Cells = [];
        foreach($Headers as $Label=>$Header){
            $Cells[] = _Tag('td',false,_E($Header,true));
        }
        $Out[] = _Tag('tr',false,implode('',$Cells));
    }
    echo implode('',$Out);
    echo '</tbody>';
