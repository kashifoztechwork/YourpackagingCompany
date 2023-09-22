<?php defined('BASEPATH') OR exit('No direct script access allowed');

// $WhatWeProvide = [
//         [
//             'Icon'=>LocalImage('/front/icons/10-12 Business Days To Dispatch.png'),
//             'Name'=>'10-12 Business Days To Dispatch'
//         ],
//         [
//             'Icon'=>LocalImage('/front/icons/Competitive Pricing.png'),
//             'Name'=>'Competitive Pricing'
//         ],
//         [
//             'Icon'=>LocalImage('/front/icons/Custom Size & Style.png'),
//             'Name'=>'Custom Size & Style'
//         ],
//         [
//             'Icon'=>LocalImage('/front/icons/Free Design Support.png'),
//             'Name'=>'Free Design Support'
//         ],
//         [
//             'Icon'=>LocalImage('/front/icons/Free Shipping.png'),
//             'Name'=>'Free Shipping'
//         ],
//         [
//             'Icon'=>LocalImage('/front/icons/No Die Cut Or Plate Charges.png'),
//             'Name'=>'No Die Cut Or Plate Charges'
//         ]
//     ];

//     $Providing = array();
//     foreach($WhatWeProvide as $Provide){
//         $Providing[] = sprintf('
//                         <div class="col">
//                             <div class="what-we-provide-item wow fadeInUp delay-0-3s text-center" style=" min-height:180px;">
//                                 %s
//                                 <h6 class="pt-2" style="font-size:14px;">%s</h6>
//                             </div>
//                         </div>',
//                         $Provide['Icon'],$Provide['Name']);
//     }
?>
   <!-- sidebar mobile menu - start
            ================================================== -->
            
            <div id="carouselExampleControls" class="carousel slide hero-two-area text-center rel z-1" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php echo implode('',$Slides); ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <!-- sidebar mobile menu - end
            ================================================== -->
            <!-- What We Profide start -->
           
            <!-- What We Profide end -->
            <!-- Shop Area start -->

            <!-- banner_section - start
            ================================================== -->
            <section class="funfact_section sec_ptb_130 clearfix" data-background="assets/images/backgrounds/bg_01.jpg">
                <div class="container">
                    <div class="funfact_wrap">

                         <?php echo $WhatWeProvide; ?>

                    </div>
                </div>
            </section>
            <!-- banner_section - end
            ================================================== -->


            <!-- feature_section - start
            ================================================== -->
            <section class="feature_section sec_ptb_130 clearfix">
                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-lg-7 col-md-7 col-sm-9 col-xs-12">
                            <div class="section_title text-center mb_30 wow fadeInUp2" data-wow-delay=".1s">
                                <h4 class="small_title">Our Core Features</h4>
                                <h2 class="big_title mb-0">
                                    Experience Allows Us To Printing Things
                                </h2>
                                <span class="biggest_title">Features</span>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-lg-between justify-content-md-center justify-content-sm-center">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="feature_image_1 wow fadeInUp2" data-wow-delay=".2s">
                                <img src="<?php echo base_url()?>resources/assets/images/feature/img_01.jpg" alt="icon_not_found">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 order-lg-first">
                            <div class="feature_primary wow fadeInUp2 clearfix" data-wow-delay=".4s">
                                <div class="item_icon bg_default_yellow">
                                    <i class="fal fa-print"></i>
                                </div>
                                <div class="item_content">
                                    <h3 class="item_title">Printing & Press</h3>
                                    <p>
                                        Sed ut perspiciat unde omnis
                                        iste natus error sit voluptatem 
                                        accusantium system
                                    </p>
                                    <a class="text_btn" target="_blank" href="service_details.html"><span>Read More</span> <i class="far fa-arrow-right"></i></a>
                                </div>
                            </div>

                            <div class="feature_primary wow fadeInUp2 clearfix" data-wow-delay=".6s">
                                <div class="item_icon bg_default_orange">
                                    <i class="fal fa-laptop-code"></i>
                                </div>
                                <div class="item_content">
                                    <h3 class="item_title">Digital Printing</h3>
                                    <p>
                                        Sed ut perspiciat unde omnis
                                        iste natus error sit voluptatem 
                                        accusantium system
                                    </p>
                                    <a class="text_btn" target="_blank" href="service_details.html"><span>Read More</span> <i class="far fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="feature_primary wow fadeInUp2 clearfix" data-wow-delay=".4s">
                                <div class="item_icon bg_default_blue">
                                    <i class="fal fa-cube"></i>
                                </div>
                                <div class="item_content">
                                    <h3 class="item_title">3D Printing</h3>
                                    <p>
                                        Sed ut perspiciat unde omnis
                                        iste natus error sit voluptatem 
                                        accusantium system
                                    </p>
                                    <a class="text_btn" target="_blank" href="service_details.html"><span>Read More</span> <i class="far fa-arrow-right"></i></a>
                                </div>
                            </div>

                            <div class="feature_primary wow fadeInUp2 clearfix" data-wow-delay=".6s">
                                <div class="item_icon bg_default_pink">
                                    <i class="fal fa-cog"></i>
                                </div>
                                <div class="item_content">
                                    <h3 class="item_title">Offest Printing</h3>
                                    <p>
                                        Sed ut perspiciat unde omnis
                                        iste natus error sit voluptatem 
                                        accusantium system
                                    </p>
                                    <a class="text_btn" target="_blank" href="service_details.html"><span>Read More</span> <i class="far fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            <!-- feature_section - end
            ================================================== -->


            <!-- about_section - start
            ================================================== -->
            <section class="about_section sec_ptb_130 bg_gray clearfix">
                <div class="container">
                    <div class="section_title text-center mb_80 wow fadeInUp2" data-wow-delay=".1s">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-7 col-sm-9 col-xs-12">
                                <h4 class="small_title">About Our Company</h4>
                                <h2 class="big_title mb-0">
                                    Printing Your Dream Works With Printem
                                </h2>
                                <span class="biggest_title">AboutUs</span>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-lg-between justify-content-md-center justify-content-sm-center">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="about_image_2 wow fadeInUp2" data-wow-delay=".3s">
                                <img src="<?php echo base_url()?>resources/assets/images/about/img_02.jpg" alt="icon_not_found">
                                <a class="play_btn popup_video" href="http://www.youtube.com/watch?v=0O2aH4XLbto">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="about_content">
                                <p class="wow fadeInUp2" data-wow-delay=".5s">
                                    <span class="experience_image">
                                        <img src="<?php echo base_url()?>resources/assets/images/about/img_03.png" alt="image_not_found">
                                    </span>
                                    <span>
                                        Sed ut perspiciatis unde omnis iste 
                                        natus error sit voluptatem accusantiu
                                        dolorem laudantium, totam rem aper
                                        iam eaque ipsa quae ab illo inventore 
                                        veritatis et quasi architecto beataese 
                                        vitae dicta sunt explicabo. Nemo enim
                                        ipsam voluptatem quia volupta
                                    </span>
                                </p>
                                <p class="wow fadeInUp2" data-wow-delay=".6s">
                                    Sed ut perspicias unde omnis iste natus error sit voluptatem accusantium dolor
                                    emqu laudantium totam rem aperiam, eaque ipsa quae ab illo inventore veritatis 
                                    et quasi arch beatae vitae dicta sunt explicabo enim ipsam voluptatem
                                </p>

                                <div class="avatar_wrap wow fadeInUp2" data-wow-delay=".7s">
                                    <div class="avatar_image">
                                        <img src="<?php echo base_url()?>resources/assets/images/meta/img_01.png" alt="image_not_found">
                                    </div>
                                    <div class="avatar_content">
                                        <h4 class="avatar_name">Kristofer C. Bello</h4>
                                        <span class="avatar_title">CEO & Founder</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cricle_border">
                    <img src="<?php echo base_url()?>resources/assets/images/about/circle_border.png" alt="icon_not_found">
                </div>
            </section>
            <!-- about_section - end
            ================================================== -->


            <!-- service_section - start
            ================================================== -->
            <section class="service_section sec_ptb_130 clearfix">
                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
                            <div class="section_title text-center mb_50 wow fadeInUp2" data-wow-delay=".1s">
                                <h4 class="small_title">What We Offers</h4>
                                <h2 class="big_title mb-0">
                                    We Provide Lot’s Of Printing & Branding Service
                                </h2>
                                <span class="biggest_title">Services</span>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-lg-between justify-content-md-center justify-content-sm-center">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="service_grid_2 wow fadeInUp2" data-wow-delay=".3s">
                                <a class="item_image" target="_blank" href="service_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/services/img_04.jpg" alt="image_not_found">
                                </a>
                                <div class="item_content_wrap">
                                    <div class="item_icon bg_default_orange">
                                        <img src="<?php echo base_url()?>resources/assets/images/services/icon_01.png" alt="icon_not_found">
                                    </div>
                                    <div class="item_content">
                                        <h3 class="item_title">Design & Branding</h3>
                                        <p>
                                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumse totam rem aperiam eaque ipsa quae abillo
                                        </p>
                                        <a class="text_btn" target="_blank" href="service_details.html">
                                            <span>Read More</span>
                                            <i class="far fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 order-lg-first">
                            <div class="service_boxed bg_default_yellow text-center wow fadeInUp2" data-wow-delay=".5s">
                                <div class="item_icon">
                                    <img src="<?php echo base_url()?>resources/assets/images/services/icon_02.png" alt="icon_not_found">
                                </div>
                                <h3 class="item_title">Color Printing</h3>
                                <p>Sed ut perspiciatis unde omnis iste natus error voluptate</p>
                                <a class="text_btn" target="_blank" href="service_details.html">
                                    <span>Read More</span>
                                    <i class="far fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="service_boxed bg_default_blue text-center text-white wow fadeInUp2" data-wow-delay=".7s">
                                <div class="item_icon">
                                    <img src="<?php echo base_url()?>resources/assets/images/services/icon_03.png" alt="icon_not_found">
                                </div>
                                <h3 class="item_title">3D Printing</h3>
                                <p>Sed ut perspiciatis unde omnis iste natus error voluptate</p>
                                <a class="text_btn" target="_blank" href="service_details.html">
                                    <span>Read More</span>
                                    <i class="far fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="service_boxed bg_default_orange text-center text-white wow fadeInUp2" data-wow-delay=".5s">
                                <div class="item_icon">
                                    <img src="<?php echo base_url()?>resources/assets/images/services/icon_04.png" alt="icon_not_found">
                                </div>
                                <h3 class="item_title">Digital Printing</h3>
                                <p>Sed ut perspiciatis unde omnis iste natus error voluptate</p>
                                <a class="text_btn" target="_blank" href="service_details.html">
                                    <span>Read More</span>
                                    <i class="far fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="service_boxed bg_default_lightblue text-center text-white wow fadeInUp2" data-wow-delay=".7s">
                                <div class="item_icon">
                                    <img src="<?php echo base_url()?>resources/assets/images/services/icon_05.png" alt="icon_not_found">
                                </div>
                                <h3 class="item_title">Logos Printing</h3>
                                <p>Sed ut perspiciatis unde omnis iste natus error voluptate</p>
                                <a class="text_btn" target="_blank" href="service_details.html">
                                    <span>Read More</span>
                                    <i class="far fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            <!-- service_section - end
            ================================================== -->


            <!-- cta_section - start
            ================================================== -->
            
            <!-- cta_section - end
            ================================================== -->


            <!-- whatwedo_section - start
            ================================================== -->
            
            <!-- whatwedo_section - end
            ================================================== -->


            <!-- portfolio_section - start
            ================================================== -->
            <section class="portfolio_section sec_ptb_130 clearfix">
                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
                            <div class="section_title text-center mb_80 wow fadeInUp2" data-wow-delay=".1s">
                                <h4 class="small_title">Our Recent Works</h4>
                                <h2 class="big_title mb-0">
                                    Let’s See Our Latest Project That Recently Done
                                </h2>
                                <span class="biggest_title">Works</span>
                            </div>
                        </div>
                    </div>

                    <div class="metro_portfolio_grid grid wow fadeInUp2" data-wow-delay=".3s">
                        <div class="grid-sizer"></div>
                        <div class="grid-item w_50">
                            <div class="portfolio_fullimage">
                                <a class="plus_effect" target="_blank" href="portfolio_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/portfolio/metro/img_01.jpg" alt="image_not_found">
                                </a>
                            </div>
                        </div>
                        <div class="grid-item w_50">
                            <div class="portfolio_fullimage">
                                <a class="plus_effect" target="_blank" href="portfolio_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/portfolio/metro/img_02.jpg" alt="image_not_found">
                                </a>
                            </div>
                        </div>
                        <div class="grid-item">
                            <div class="portfolio_fullimage">
                                <a class="plus_effect" target="_blank" href="portfolio_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/portfolio/metro/img_03.jpg" alt="image_not_found">
                                </a>
                            </div>
                        </div>
                        <div class="grid-item">
                            <div class="portfolio_fullimage">
                                <a class="plus_effect" target="_blank" href="portfolio_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/portfolio/metro/img_04.jpg" alt="image_not_found">
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            <!-- portfolio_section - end
            ================================================== -->


            <!-- testimonial_section - start
            ================================================== -->
            
            <!-- testimonial_section - end
            ================================================== -->


            <!-- blog_section - start
            ================================================== -->
            <section class="blog_section sec_ptb_130 clearfix">
                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="section_title text-center mb_50 wow fadeInUp2" data-wow-delay=".1s">
                                <h4 class="small_title">Latest News & Blog</h4>
                                <h2 class="big_title mb-0">
                                    Get More Update For News & Articles
                                </h2>
                                <span class="biggest_title">Blogs</span>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 wow fadeInUp2" data-wow-delay=".3s">
                            <div class="blog_grid_1">
                                <a class="item_image" target="_blank" href="blog_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/blog/grid/img_01.jpg" alt="image_not_found">
                                </a>
                                <div class="item_content_wrap">
                                    <div class="post_admin">
                                        <div class="admin_image">
                                            <img src="<?php echo base_url()?>resources/assets/images/meta/img_01.png" alt="image_not_found">
                                        </div>
                                        <div class="admin_content">
                                            <h4 class="abmin_name">David Warner</h4>
                                            <span class="post_date"><i class="far fa-calendar-alt"></i> 25 Nov 2020</span>
                                        </div>
                                        <a class="icon_btn" target="_blank" href="blog_details.html">
                                            <i class="far fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="item_content">
                                        <h3 class="item_title">
                                            <a target="_blank" href="blog_details.html">
                                                Solving Common Cross Plat Form Issues When Working With Flutter Ways
                                            </a>
                                        </h3>
                                        <ul class="post_meta ul_li clearfix">
                                            <li><a href="#!"><i class="far fa-comments"></i> Comments (05)</a></li>
                                            <li><a href="#!"><i class="far fa-heart"></i> View (1k)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 wow fadeInUp2" data-wow-delay=".5s">
                            <div class="blog_grid_1">
                                <a class="item_image" target="_blank" href="blog_details.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/blog/grid/img_02.jpg" alt="image_not_found">
                                </a>
                                <div class="item_content_wrap">
                                    <div class="post_admin">
                                        <div class="admin_image">
                                            <img src="<?php echo base_url()?>resources/assets/images/meta/img_02.png" alt="image_not_found">
                                        </div>
                                        <div class="admin_content">
                                            <h4 class="abmin_name">Somalia Fizza</h4>
                                            <span class="post_date"><i class="far fa-calendar-alt"></i> 25 Nov 2020</span>
                                        </div>
                                        <a class="icon_btn" target="_blank" href="blog_details.html">
                                            <i class="far fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="item_content">
                                        <h3 class="item_title">
                                            <a target="_blank" href="blog_details.html">
                                                Introduction To SWR React Hooks For Remote Data Fetc Monthly Update Things
                                            </a>
                                        </h3>
                                        <ul class="post_meta ul_li clearfix">
                                            <li><a href="#!"><i class="far fa-comments"></i> Comments (05)</a></li>
                                            <li><a href="#!"><i class="far fa-heart"></i> View (1k)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 wow fadeInUp2" data-wow-delay=".7s">
                            <div class="blog_fullimage text-white">
                                <img src="<?php echo base_url()?>resources/assets/images/blog/fullimage/img_01.jpg" alt="image_not_found">
                                <div class="item_content">
                                    <div class="post_admin">
                                        <h4 class="abmin_name">Somalia Fizza</h4>
                                        <span class="post_date"><i class="far fa-calendar-alt"></i> 25 Nov 2020</span>
                                        <a class="icon_btn" target="_blank" href="blog_details.html">
                                            <i class="far fa-arrow-right"></i>
                                        </a>
                                    </div>

                                    <h3 class="item_title">
                                        <a target="_blank" href="blog_details.html">
                                            Solving Common Cross Plat Form Issues When Working With Flutter Ways
                                        </a>
                                    </h3>
                                    <ul class="post_meta ul_li clearfix">
                                        <li><a href="#!"><i class="far fa-comments"></i> Comments (05)</a></li>
                                        <li><a href="#!"><i class="far fa-heart"></i> View (1k)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            <!-- blog_section - end
            ================================================== -->


            <!-- brand_section - start
            ================================================== -->
            <div class="brand_section primary_brand_section bg_gray d-flex align-items-center clearfix">
                <div class="container text-center">
                    <div class="row align-items-center justify-content-lg-between">

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="brand_logo bg_default_yellow">
                                <a href="index_1.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/logo/logo_03_1x.png" srcset="<?php echo base_url()?>resources/assets/images/logo/logo_03_2x.png 2x" alt="logo_not_found">
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                            <div class="logo_image">
                                <a href="#!">
                                    <img src="<?php echo base_url()?>resources/assets/images/brands/img_01.png" alt="logo_not_found">
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                            <div class="logo_image">
                                <a href="#!">
                                    <img src="<?php echo base_url()?>resources/assets/images/brands/img_02.png" alt="logo_not_found">
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                            <div class="logo_image">
                                <a href="#!">
                                    <img src="<?php echo base_url()?>resources/assets/images/brands/img_03.png" alt="logo_not_found">
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                            <div class="logo_image">
                                <a href="#!">
                                    <img src="<?php echo base_url()?>resources/assets/images/brands/img_04.png" alt="logo_not_found">
                                </a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- brand_section - end
            ================================================== -->


        </main>