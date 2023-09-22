<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  
        <!-- Page Banner Start -->
        <section class="page-banner bgs-cover text-white pt-65 pb-75" style="background-image: url(assets/images/banner.jpg);">
            <div class="container">
                <div class="banner-inner">
                    <h1 class="page-title wow fadeInUp delay-0-2s"><?php echo $Title; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb wow fadeInUp delay-0-4s">
                            <li class="breadcrumb-item"><?php echo _A('Home',URI(''));?></li>
                            <li class="breadcrumb-item active"><?php echo $Title; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>
        <!-- Page Banner End -->
         <!-- What We Profide start -->
         <?php echo $WhatWeProvide; ?>
        <!-- What We Profide end -->
      
        
        <!-- Product Area start -->
        <section class="blog-standard-area py-130">
            <div class="container">
                <div class="row">
                <div class="col-lg-12">
                        <div class="blog-standard-wrap row">
                            <?php echo implode('',$List);?>
                           
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-12">
                       
                        <?php 
                            $Counter =  1;
                            $End = ($Total / 12) + 1;
                            $Links = array();
                            for($I=$Counter;$I<=$End;$I++){
                                
                                if($I == $CurrentPage){
                                    $Class = 'active';
                                } else {
                                    $Class = '';
                                }
                                $Links[] = sprintf('<li class="page-item %s">%s</li>',$Class,_A($I,sprintf('?p=%s',$I),['class'=>'page-link']));
                              
                            }
                       
                        ?>
                        
                        <ul class="pagination flex-wrap pt-20">
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fa fa-chevron-left"></i></span>
                            </li>
                            <?php echo implode('',$Links);?>
                            <li class="page-item">
                                <a class="page-link" href="#"><i class="fa fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
            </div>
        </section>
        <div class="editortext">
        <?php
            foreach($Widgets as $Widget){
                echo $Widget->Config;
            }
        ?>
       
        </div>
        <!-- Product Area end -->
        

        
        


        