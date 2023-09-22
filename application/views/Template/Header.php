<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php 
    // Get Logo
    $SiteInfo = $this->Setting->Row(['ID'=>1]);
    $SiteInfo->Config = unserialize($SiteInfo->Config);
    $SiteInfo->Logo = unserialize($SiteInfo->Logo);
    def($SiteInfo,'SiteInfo');

    $Logo = sprintf('<li class="logo">%s</li>',_A(GetImage('{SiteInfo.EntryDate}','{SiteInfo.Logo}',['height'=>'60px','width'=>'']),URI(''),[],true,true));

    // Boxes List
    $Boxes = $this->Category->GetList(['Type'=>'Boxes','Featured'=>1],0,0,'','{P}CAT.Name');
    $Boxes = $Boxes->Result;
    $BoxList = [];

    foreach($Boxes as $Box){
        $BoxList[$Box->Name] = sprintf('<li>%s</li>',_A($Box->Name,URI(sprintf('category/%s',Slug($Box->Name))),[],true,true));
    }
    $BoxList['View All'] = sprintf('<li>%s</li>',_A('View All',URI(sprintf('category/%s',Slug('Custom Boxes'))),[],true,true));

    // Template List
    $Templates = $this->Category->GetList(['Type'=>'Template','Featured'=>1],0,0,'','{P}CAT.Name');
    $Templates = $Templates->Result;
    $TempList = [];

    foreach($Templates as $Template){
        $TempList[$Template->Name] = sprintf('<li>%s</li>',_A($Template->Name,URI(sprintf('category/%s',Slug($Template->Name))),[],true,true));
    }
    $TempList['View All'] = sprintf('<li>%s</li>',_A('View All',URI(sprintf('category/%s',Slug('Templates'))),[],true,true)); 

    $Navigations = array(
        'Home'=>sprintf('<li>%s</li>',_A('Home',URI(''))),
        'Custom Boxes'=>sprintf('<li class="has_child">%s <ul class="submenu"> %s</ul></li>',_A('Custom Boxes',URI(sprintf('category/%s',Slug('Custom Boxes'))),[],true,true),implode('',$BoxList)),
        'Templates'=>sprintf('<li class="has_child">%s <ul class="submenu"> %s</ul></li>',_A('Templates',URI(sprintf('category/%s',Slug('Templates'))),[],true,true),implode('',$TempList)),
        'Materials'=>sprintf('<li>%s</li>',_A('Materials',URI('category/materials'),[],true,true)),
        'Blog'=>sprintf('<li>%s</li>',_A('Blog',URI('blog'),[],true,true)),
       
    );

    // Arrange Header
    //array_splice($Navigations,Round(Count($Navigations)/2),0,$Logo);
    
    echo $this->Widget->Row(['WGT.Name'=>'Whatsapp'])->Config;
?>
<header class="header_section sticky_header primary_header clearfix">
            <div class="primary_header_content clearfix">
                <div class="brand_logo">
                   
                        <?php //echo _A(GetImage('{SiteInfo.EntryDate}','{SiteInfo.Logo}',['height'=>'60px','width'=>'']),URI(''));?>
                    
                    <ul class="mobilemenu_btns_group ul_li_right clearfix">
                        <li>
                            <button type="button" class="search_btn" data-bs-toggle="collapse" data-bs-target="#search_body_collapse" aria-expanded="false" aria-controls="search_body_collapse">
                                <i class="fa fa-search"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="mobile_menu_btn"><i class="fa fa-bars"></i></button>
                        </li>
                    </ul>
                </div>

                <nav class="main_menu clearfix">
                    <ul class="ul_li_center clearfix">
                        <?php echo implode('',$Navigations);?>
                    </ul>
                    
                </nav>

                <div class="header_hotline bg_gray clearfix">
                    <div class="item_icon">
                        <img src="<?php echo base_url()?>resources/assets/images/icons/hot_line.png" alt="icon_not_found">
                    </div>
                    <div class="item_content">
                        <h4>contact us</h4>
                        <span><?php echo _A($SiteInfo->Config['Phone'],URI(sprintf('calto:%s',$SiteInfo->Config['Phone']))); ?></span>
                    </div>
                </div>
            </div>

            <div id="search_body_collapse" class="search_body_collapse collapse">
                <div class="search_body">
                    <div class="container-fluid">
                        <form action="#">
                            <div class="form_item m-0">
                                <input type="search" name="search" placeholder="Type here...">
                                <button type="submit"><i class="fal fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>