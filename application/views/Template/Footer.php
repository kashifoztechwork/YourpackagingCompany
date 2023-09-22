<?php defined('BASEPATH') OR exit('No direct script access allowed');

$FooterLink = [
    sprintf('<li>%s</li>',_A('Home',URI(''))),
    sprintf('<li>%s</li>',_A('Custom Boxes',URI('category/custom-boxes'),[],true,true)),
    sprintf('<li>%s</li>',_A('Templates',URI('category/templates'),[],true,true)),
    sprintf('<li>%s</li>',_A('Blog',URI('blog'),[],true,true)),
    sprintf('<li>%s</li>',_A('Need a Support?',URI('contact'),[],true,true))
];


if($TopProducts = $this->Product->GetList(['{P}PRD.Status'=>true,'{P}PRD.Featured'=>true],6,0,'','PRD.Name')){
        foreach($TopProducts->Result as $TopProduct){
            $TopList[] = sprintf('<li>%s</li>',_A($TopProduct->Name,URI(sprintf('product/%s',Slug($TopProduct->Name))),[],true,true));
        }
}

echo $this->Widget->Row(['WGT.Name'=>'FooterTop'])->Config;

?>
        <footer class="footer_section secondary_footer bg_black text-white clearfix">
            <div class="container">
                <div class="row justify-content-lg-between justify-content-md-center justify-content-sm-center">

                    <div class="col-lg-4 col-md-8">
                        <div class="widget footer_about bg_black">
                            <div class="brand_logo bg_default_yellow">
                                <a href="index_1.html">
                                    <img src="<?php echo base_url()?>resources/assets/images/logo/logo_03_1x.png" srcset="<?php echo base_url()?>resources/assets/images/logo/logo_03_2x.png 2x" alt="logo_not_found">
                                </a>
                            </div>
                            <p class="mb_30">
                                Sed ut perspiciatis unde omnis iste natus 
                                error sit voluptatem accusantium dolorem
                                que laudantium, totam rem aperiam eaque ipsa quae abillo inventore veritatis quasi 
                                architecto beatae vitae dicta
                            </p>
                            <ul class="circle_social_links ul_li mb_80 clearfix">
                                <li>
                                    <a class="bg_facebook" href="#!">
                                        <i class="fa fa-facebook-f"></i>
                                        <i class="fa fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="bg_twitter" href="#!">
                                        <i class="fa fa-twitter"></i>
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="bg_youtube" href="#!">
                                        <i class="fa fa-youtube"></i>
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="bg_linkedin" href="#!">
                                        <i class="fa fa-linkedin"></i>
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="copyright_text bg_black">
                                <p class="mb-0">© Copyright 2023 All Right Reserved Design By <a class="author_links" target="_blank" href="https://themeforest.net/user/bdevs">BDevs</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-8">
                        <div class="footer_widget_area">
                            <div class="row justify-content-lg-between">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="widget footer_useful_links">
                                        <h3 class="footer_widget_title">Quick Links</h3>
                                        <ul class="ul_li_block clearfix">
                                            <li><a href="#!">Design & Branding</a></li>
                                            <li><a href="#!">3D Design & Printing</a></li>
                                            <li><a href="#!">Offset Printing</a></li>
                                            <li><a href="#!">Business Printing</a></li>
                                            <li><a href="#!">Corporate Printing</a></li>
                                            <li><a href="#!">T-Shart Printing</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="widget recent_works_list">
                                        <h3 class="footer_widget_title">Recent News</h3>
                                        <ul class="ul_li_block clearfix">
                                            <li>
                                                <div class="small_blog clearfix">
                                                    <div class="item_content">
                                                        <h3 class="item_title">
                                                            <a target="_blank" href="blog_details.html">
                                                                Web Designers Can Help Restaura Move Into
                                                            </a>
                                                        </h3>
                                                        <span class="post_date"><i class="far fa-calendar-alt"></i> 25 Nov 2020</span>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="small_blog clearfix">
                                                    <div class="item_content">
                                                        <h3 class="item_title">
                                                            <a target="_blank" href="blog_details.html">
                                                                Building Facial Recognits Web Application React
                                                            </a>
                                                        </h3>
                                                        <span class="post_date"><i class="far fa-calendar-alt"></i> 25 Nov 2020</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <div class="widget footer_contact_info clearfix">
                                        <h3 class="footer_widget_title">Conatct Us</h3>
                                        <ul class="ul_li_block clearfix">
                                            <li>
                                                <div class="item_icon bg_default_orange">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                                <div class="item_content">
                                                    <h4>phone number</h4>
                                                    <p>+012 (345) 678 99</p>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="item_icon bg_default_yellow">
                                                    <i class="fa fa-envelope-open"></i>
                                                </div>
                                                <div class="item_content">
                                                    <h4>Email addres</h4>
                                                    <p>support@gmail.com</p>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="item_icon bg_default_lightblue">
                                                    <i class="fa fa-map-marker-alt"></i>
                                                </div>
                                                <div class="item_content">
                                                    <h4>Locations</h4>
                                                    <p>20 Main Street, USA</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="footer_newsletter_boxed">
                                <div class="row justify-content-lg-between">
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                        <h3 class="newsletter_title">Get More Update Join Our Newsletters</h3>
                                    </div>
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                        <form action="#">
                                            <ul class="form_list ul_li_right clearfix">
                                                <li>
                                                    <input type="email" name="email" placeholder="Enter Your Email">
                                                </li>
                                                <li><button type="submit" class="icon_btn"><i class="fa fa-arrow-right"></i></button></li>
                                            </ul>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="border_shapes" data-background="<?php echo base_url()?>resources/assets/images/shapes/borders_shape.png"></div>
        </footer>