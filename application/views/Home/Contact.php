<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
    def($SiteInfo,'SiteInfo');
?>


         
          
        <!-- Contact Info Area start -->
        <section class="contact-info-area mb-130">
            <div class="container">
                <div class="row no-gap">
                    <div class="col-lg-6">
                        <div class="contact-info-content wow fadeInLeft delay-0-2s">
                            <div class="section-title mb-25">
                                <span class="sub-title mb-15">Contact Us</span>
                                <h2>We’re Ready to Helps! Feel Free to Contact With Us</h2>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="contact-info-wrap wow fadeInRight delay-0-2s">
                            <div class="contact-info-item">
                                <div class="icon"><i class="fa fa-map-marker"></i></div>
                                <div class="content">
                                    <h4>Loaction</h4>
                                    <p><?php echo _E('{SiteInfo.Config.Address}')?></p>
                                </div>
                            </div>
                            <div class="contact-info-item">
                                <div class="icon"><i class="fa fa-envelope-open"></i></div>
                                <div class="content">
                                    <h4>Email Us</h4>
                                    <?php echo _A(_E('{SiteInfo.Config.Email}'),sprintf('mailto:%s',_E('{SiteInfo.Config.Email}')));?>
                                </div>
                            </div>
                            <div class="contact-info-item">
                                <div class="icon"><i class="fa fa-phone"></i></div>
                                <div class="content">
                                    <h4>Phone No</h4>
                                    <?php echo _A(_E('{SiteInfo.Config.Phone}'),sprintf('callto:%s',_E('{SiteInfo.Config.Phone}')));?>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Info Area end -->
        
        
        <!-- Location Map Area Start -->
        <div class="contact-page-map wow fadeInUp delay-0-2s">
            <div class="our-location">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d136834.1519573059!2d-74.0154445224086!3d40.7260256534837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1639991650837!5m2!1sen!2sbd" style="border:0; width: 100%;"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <!-- Location Map Area End -->
        
        
         <!-- Contact Form Start -->
         <section class="contact-form-area mt-110 mb-130 wow fadeInUp delay-0-2s">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form id="contactForm" class="contact-form z-1 rel" action="<?= URI('contact/submit'); ?>" name="contactForm" method="post">
                            <div class="section-title text-center mb-40">
                               <span class="sub-title">Get In Touch</span>
                               <h2>Send Us Message</h2>
                           </div>
                            <div class="row mt-25">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="Name"><i class="fa fa-user"></i></label>
                                        <input type="text" id="name" name="Name" class="form-control" value="" placeholder="Full Name" required data-error="Please enter your name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="Email"><i class="fa fa-envelope"></i></label>
                                        <input type="email" id="email" name="Email" class="form-control" value="" placeholder="Email Address" required data-error="Please enter your name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="Phone"><i class="fa fa-phone"></i></label>
                                        <input type="text" id="phone" name="Phone" class="form-control" value="" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="Subject"><i class="fa fa-send"></i></label>
                                        <input type="text" id="subject" name="Subject" class="form-control" value="" placeholder="Subject" required data-error="Please enter your subject">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="Message"><i class="fa fa-highlighter"></i></label>
                                        <textarea name="Message" id="message" class="form-control" rows="4" placeholder="Write your Message" required data-error="Please enter your Message"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                <div class="col-sm-12">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="theme-btn">Send Message <i class="fa fa-arrow-right"></i></button>
                                        <div id="msgSubmit" class="hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Form End -->
        