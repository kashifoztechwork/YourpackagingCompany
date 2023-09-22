<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="content-wrapper auth p-0 theme-two">
    <div class="row d-flex align-items-stretch">
        <div class="col-md-4 banner-section d-none d-md-flex align-items-stretch justify-content-center">
            <div class="slide-content bg-1"></div>
        </div>
        <div class="col-12 col-md-8 h-100 bg-white">
            <div class="auto-form-wrapper d-flex align-items-center justify-content-center flex-column">
                <form action="<?php echo URI('LoginProcess',true)?>" method="post" data-validation="true">
                    <?php //echo  LocalImage('logo.png','250,auto',['class'=>'pb-3']) ?>
                    <h3 class="mr-auto display-3">Welcome!<br/><span class="text-primary">Your Packaging Company</span></h3>
                    <p class="mb-5 mr-auto">Please enter your credentials below.</p>
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'Email',
                                'Label'=>'Email Address',
                                'Type'=>'Text',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL12
                                ]
                            ],[
                                'Name'=>'Password',
                                'Label'=>'Your Password',
                                'Type'=>'Password',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control','AA'=>['required']],
                                    'Template'=>COL12
                                ]
                            ]
                        ]);
                    ?>
                    <div class="form-group">
                        <?php
                            echo CSRF();
                            echo _Button('Login','Login',['class'=>'btn btn-primary submit-btn'])
                        ?>
                    </div>
                    <div class="wrapper mt-5 text-gray">
             
                           
                                <p class="footer-text">Developed By <b class="text-primary">IT</b> <small>Hamza Nisar</small></p>
                          
                      
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
