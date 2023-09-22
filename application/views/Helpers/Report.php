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
            if(is_array($Header)){
                $Value = _E($Header[0],true);
                $Totals[$Label][] = $Value;
                if(isset($Header[1]) && $Header[1]){
                    $Value = ($Header[1])($Value);
                }
                if(isset($Header[3]) && $Header[3]){
                    $Weights[$Label][] = _E($Header[3],true);
                }
            }else{
                $Value = _E($Header,true);
            }
            $Cells[] = _Tag('td',false,$Value);
        }
        $Out[] = _Tag('tr',false,implode('',$Cells));
    }
    if(!empty($Totals)){
        $TotalCells = [];
        foreach($Headers as $Label=>$Header){
            if(is_array($Header) && isset($Totals[$Label])){
                $Value = $Totals[$Label];
                if(isset($Header[2]) && $Header[2]){
                    $Value = ($Header[2])($Value);
                }else{
                    $Value = Sum($Value);
                }
                if(isset($Header[1]) && $Header[1]){
                    $Value = ($Header[1])($Value);
                }
                $TotalCells[] = _Tag('th',false,$Value);
            }else{
                $TotalCells[] = _Tag('th',false,'-');
            }
        }
        echo _Tag('tr',false,implode('',$TotalCells));
    }
    echo implode('',$Out);
    echo '</tbody>';
