<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
    // Images
    if(!empty($Product->Images)){
        $Product->Images = array_values(unserialize($Product->Images));
    }
    
    // Define Product
    def($Product,'Product');
    // Description
    $Descriptions = unserialize($Product->Descriptions);
    // Specification
    // $Specification = unserialize($Product->Specification);
    // $Specifications = array();

    // foreach($Specification as $Key=>$Value){
    //     $Specifications[] = sprintf('<li>%s: %s</li>',$Key,$Value);    
    // }
  
   
    $Preview = array();
    $Thumb = array();
    $I = 0;
    
    if($Product->Images){
         foreach($Product->Images as $Key=>$Image){
            $ActiveClass = $I === 0 ? 'active' : '';        
            // Preview
            $Preview[] = sprintf('<div class="carousel-item %s" data-type="%s">
                                     %s  
                                  </div>',
                                    $ActiveClass,$I,GetImage('{Product.EntryDate}',
                                    sprintf('{Product.Images.%s}',$Key),['class'=>'d-block'])
                                );
            $Thumb[] = sprintf('
                                <a href="javascript:;" data-bs-target="#carouselExampleIndicators"  data-bs-slide-to="%s" class="%s" aria-current="true" aria-label="Slide %1$s">%s</a>
                                ',
                                    $I,$ActiveClass,GetImage('{Product.EntryDate}',
                                    sprintf('{Product.Images.%s}',$Key))
                                );
            $I++;
        }
    }
   
?>
  
      
        
        <!-- Product Details Start -->
        <section class="product-details pt-30 rpt-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-details-content wow fadeInRight delay-0-2s">
                            <div class="section-title">
                                <h1><?php echo $Product->Name; ?></h1>
                            </div>
                            <div class="ratting-price mb-30">
                                <div class="ratting">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            
                            </div>
                            <hr class="mb-25">
                            <?php echo sprintf('<div class="scroller"> <p>%s</p></div>',$Descriptions['ShortDescription']); ?>
                            
                            <hr class="mt-30">
                            <ul class="category-tags pt-10 pb-15">
                                <li>
                                    <b>Category</b>
                                    <span>:</span>
                                    <?php 
                                        echo sprintf('%s <span>/</span> %s',_A(ucfirst($Product->CategoryName),URI(sprintf('category/%s',Slug($Product->CategoryName)))),_A(ucfirst($Product->Name),URI(sprintf('product/%s',Slug($Product->Name)))));
                                    ?>
                                    
                                    
                                </li>
                            </ul>
                            
                        </div>
                    </div>  
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 overflow-hidden">
                        <div class="row align-items-center align-items-lg-start">
                            
                            <div class="col-lg-12">
                                <div id="carouselExampleIndicators" class="main-carousel-container carousel slide" data-bs-ride="carousel">
                                    <div class="main-carousel-inner">
                                        <?php echo implode('',$Preview)?>
                                    </div>

                                    <div class="carousel-indicators SliderThumbnail">
                                         <?php echo implode('',$Thumb)?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 d-md-block d-sm-none d-none p-0">
                        <?php  
                            echo $ProductStaticBanner;
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="ProductQoute inner-box text-center">
                            <div class="title">
                                <h4>GET A QUOTE</h4>
                            </div>

                            <!--Appointment Form-->
                            <div class="appointment-form">
                                <form method="post" class="row" action="<?php echo URI(Slug('SubmitQuote')); ?>">
                                    <?php echo GenerateFields($this->QuickQuote->QuoteFields());?>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <div class="form-group">
                                        <button type="submit" class="theme-btn">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- What We Profide start -->
                <?php echo $WhatWeProvide; ?>
                <!-- What We Profide end -->
                <ul class="nav nav-tabs product-information-tab mt-80 mb-40 wow fadeInUp delay-0-2s">
                    <li><a href="#details" data-bs-toggle="tab" class="active show">Descrptions</a></li>
                    <li><a href="#Specifications" data-bs-toggle="tab">Specifications</a></li>
                    <li><a href="#FAQs" data-bs-toggle="tab">FAQs</a></li>
                    <li><a href="#review" data-bs-toggle="tab">Review (<?php echo $Reviews->Rows; ?>)</a></li>
                </ul>
                <div class="tab-content editortext pb-50 wow fadeInUp delay-0-2s">
                    <div class="tab-pane fade active show" id="details">
                        <?php echo $Descriptions['Description']; ?>
                    </div> 
                    <div class="tab-pane fade" id="Specifications">
                        <?php echo LocalImage('front/specification.png');?>
                    </div> 
                    <div class="tab-pane fade" id="FAQs">
                        <?php echo @$Descriptions['FAQs'] ?>
                    </div>   
                    <div class="tab-pane fade" id="review">
                        <h4><?php echo $Reviews->Rows; ?> Review</h4>
                        <ul class="list-unstyled">
                            <?php 
                                if($Reviews->Rows > 1){
                                    foreach($Reviews->Result as $Review){
                                        echo sprintf('
                                        <li style="border-bottom:1px solid #eee;">
                                            <h4>%s</h4>
                                            <div class="ratting-price mb-10">
                                                <div class="ratting">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                            %s
                                        </li>',
                                        $Review->Name,$Review->Comment);
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </section>
        <!-- Product Details End -->
        
        
        <!-- Review Form Start 
        <section class="review-form-area mb-120 wow fadeInUp delay-0-2s">
            <div class="container">
                <form id="review-form" class="review-form z-1 rel" name="review-form" action="submitreview" method="post">
                    <h3>Leave Your Reviews</h3>
                    <div class="ratting">
                        <h5>Your Rating</h5>
                        <div class="rate">
                            <input type="radio" id="star5" name="Stars" value="5" />
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="Stars" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="Stars" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="Stars" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="Stars" value="1" />
                            <label for="star1" title="text">1 star</label>
                        </div>
                    </div>
                    <div class="row mt-25">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" id="full-name" name="Name" class="form-control" value="" placeholder="Full Name" required="">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" id="phone" name="Email" class="form-control" value="" placeholder="Phone Number" required="">
                            </div>
                        </div>
                       
                        <input type="hidden"  name="Product" class="form-control" value="<?php //echo $Product->ID?>" >
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="Comment" id="message" class="form-control" rows="4" placeholder="Write Message" required=""></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="<?php// echo $this->security->get_csrf_token_name();?>" value="<?php //echo $this->security->get_csrf_hash();?>">
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <button type="submit" class="theme-btn">Send Reviews <i class="fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        -->
       
        
        <!-- Related Product Area start -->
        <section class="related-product-area mt-30 mb-30">
            <div class="container pb-55">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7">
                        <div class="section-title text-center mb-35 wow fadeInUp delay-0-2s">
                            <h2>Related Product</h2>
                        </div>
                    </div>
                </div>
                <div class="product-two-slider">
                    <?php echo implode('',$RelatedSlide); ?>
                </div>
            </div>
        </section>
        <!-- Related Product Area end -->
        
        <?php
            foreach($Widgets as $Widget){
                echo $Widget->Config;
            }

            // foreach($ProductWidgets as $ProductWidget){
            //     echo $ProductWidget->Config;
            // }
        ?>
        