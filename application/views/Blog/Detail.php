<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

    def($Post,'Post');

?>
        
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
          <!-- Blog Page Area start -->
          <section class="blog-details-area py-130">
            <div class="container">
                <div class="row justify-content-between">
                   
                    <div class="col-lg-12">
                        <div class="blog-details-content">
                            <div class="image wow fadeInUp delay-0-2s">

                                <?php echo GetImage('{Post.EntryDate}','{Post.Image}');?>
                            </div>
                            <div class="content editortext">
                                <ul class="blog-meta">
                                    <li><i class="fa fa-calendar-alt"></i> <?php echo _E('{Post.EntryDate}'); ?></li>
        
                                </ul>
                                <h4 class="blog-title"><?php echo _E('{Post.Name}'); ?></h4>
                                <?php echo unserialize($Post->Content);?>
                            </div>
                        </div>
                        
                       
                     
                    </div>
                </div>
            </div>
        </section>
        <!-- Blog Page Area end -->
      
         <!-- Blog Area start -->
         <section class="blog-area pt-30 pb-30">
            <div class="container">
                <div class="row justify-content-between align-items-end pb-15">
                    <div class="col-lg-9">
                        <div class="section-title mb-25 wow fadeInUp delay-0-2s">
                            <span class="sub-title mb-10">Related Post</span>
                            <h2>Get Every Single Update</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <?php echo _A('View More <i class="fa fa-long-arrow-right"></i>',URI('blog'),['class'=>'theme-btn mb-25 wow fadeInUp delay-0-4s']);?>
                    </div>
                </div>
                <div class="blog-slider">
                    <?php echo implode(' ',$RelatedPost);?>
                </div>
            </div>
        </section>
        <!-- Blog Area end -->
     
      

        
        


        