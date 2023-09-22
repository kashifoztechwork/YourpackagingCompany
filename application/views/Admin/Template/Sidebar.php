<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $Controllers = $this->Module->GetNestedModules();

?>
<!--
<div id="right-sidebar" class="settings-panel">
    <i class="settings-close mdi mdi-close"></i>
    <div class="d-flex align-items-center justify-content-between border-bottom">
        <p class="settings-heading font-weight-bold border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
    </div>
    <ul class="chat-list">
        <li class="list active">
            <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
                <p></p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">19 min</small>
        </li>
        <li class="list">
            <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
            <div class="info">
                <div class="wrapper d-flex">
                    <p>Catherine</p>
                </div>
                <p>Away</p>
            </div>
            <div class="badge badge-success badge-pill my-auto mx-2">4</div>
            <small class="text-muted my-auto">23 min</small>
        </li>
        <li class="list">
            <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
                <p>Daniel Russell</p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">14 min</small>
        </li>
        <li class="list">
            <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
            <div class="info">
                <p>James Richardson</p>
                <p>Away</p>
            </div>
            <small class="text-muted my-auto">2 min</small>
        </li>
        <li class="list">
            <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
                <p>Madeline Kennedy</p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">5 min</small>
        </li>
        <li class="list">
            <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
            <div class="info">
                <p>Sarah Graves</p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">47 min</small>
        </li>
    </ul>
</div>-->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="profile-image">
                    <span class="online-status online"></span>
                </div>
                <div class="profile-name">
                    <p class="name"></p>
                    <p class="designation"></p>
                </div>
                <div class="notification-panel mt-4">
                    <span><i class="mdi mdi-settings"></i></span>
                    <span class="count-wrapper">
                        <i class="mdi mdi-bell"></i><span class="count top-right bg-warning">4</span>
                    </span>
                    <span><i class="mdi mdi-email"></i></span>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <?php echo _A('<i class="mdi mdi-monitor text-success icon-md"></i>&nbsp;<span class="menu-title">Dashboard</span>',URI('',true),['class'=>'nav-link']);?>
        </li>
        <?php
            foreach($Controllers[NULL]  as $Controller):
                $Config = unserialize($Controller->Config);
                $ChildControllers = isset($Controllers[$Controller->ID]) ? $Controllers[$Controller->ID] : [];
                if(!empty($ChildControllers)):                    
        ?>
        <li class="nav-item<?php echo $Controller->ID == _E('{Current.ModuleID}') ? ' active' : ''?>">
            <?php
                echo _A(sprintf('<i class="%s"></i>&nbsp;<span class="menu-title">%s</span><i class="menu-arrow"></i>',$Config['Icon'],$Controller->Title),sprintf('#%d',$Controller->ID),['class'=>'nav-link','data-toggle'=>'collapse','aria-expanded'=>'false','aria-controls'=>$Controller->ID]);
                if(!empty($ChildControllers)):
            ?>
                <div class="collapse" id="<?php echo $Controller->ID?>">
                    <ul class="nav flex-column sub-menu">
                        <?php
                            foreach($ChildControllers as $ChildController){
                                echo sprintf('<li class="nav-item%s">%s</li>',$ChildController->ID == _E('{Current.ID}') ? ' active' : '',_A($ChildController->Title,URI($ChildController->Slug,true),['class'=>'nav-link']));
                            }
                        ?>
                    </ul>
                </div>
            <?php endif;endif;?>
        </li>
        <?php endforeach?>
    </ul>
</nav>
