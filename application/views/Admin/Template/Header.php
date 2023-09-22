<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="index.html"></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <!--
        <ul class="navbar-nav navbar-nav-left header-links d-none d-md-flex">
            <li class="nav-item active">
                <span class="nav-link">
                    @Html.ActionLink("Make Me Available", "Available", "Home",null, new { @class = "btn btn-warning btn-md" })
                </span>
            </li>
            //OR
            <li class="nav-item active">
                <span class="nav-link">
                    <a href="javascript:;" class="btn btn-success btn-sm">Available @Helper.Ago(Available.CheckIn.Value)</a>
                </span>
            </li>
        </ul>-->
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
                <div class="badge badge-info">
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="badge badge-light">
                            <i class="mdi mdi-clock text-dark icon-lg"></i>
                        </span>
                        <div class="wrapper">
                            <div class="fluid-container">
                                <h3 class="mb-0 text-white pl-2 pr-2" data-type="Clock">00:00:00 AM</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <!--<li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-message-text-outline"></i>
                    <span class="count bg-warning">2</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <div class="dropdown-item py-3">
                        <p class="mb-0 font-weight-medium float-left">
                            You have 7 unread mails
                        </p>
                        <span class="badge badge-inverse-info badge-pill float-right">View all</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="images/faces/face4.jpg" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1">
                                David Grey
                                <span class="float-right font-weight-light small-text text-gray">1 Minutes ago</span>
                            </h6>
                            <p class="font-weight-light small-text mb-0">
                                The meeting is cancelled
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="images/faces/face2.jpg" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1">
                                Tim Cook
                                <span class="float-right font-weight-light small-text text-gray">15 Minutes ago</span>
                            </h6>
                            <p class="font-weight-light small-text mb-0">
                                New product launch
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="images/faces/face3.jpg" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1">
                                Johnson
                                <span class="float-right font-weight-light small-text text-gray">18 Minutes ago</span>
                            </h6>
                            <p class="font-weight-light small-text mb-0">
                                Upcoming board meeting
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count bg-success">4</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <a class="dropdown-item py-3">
                        <p class="mb-0 font-weight-medium float-left">
                            You have 4 new notifications
                        </p>
                        <span class="badge badge-pill badge-inverse-info float-right">View all</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-inverse-success">
                                <i class="mdi mdi-alert-circle-outline mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                            <p class="font-weight-light small-text mb-0">
                                Just now
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-inverse-warning">
                                <i class="mdi mdi-comment-text-outline mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                            <p class="font-weight-light small-text mb-0">
                                Private message
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-inverse-info">
                                <i class="mdi mdi-email-outline mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                            <p class="font-weight-light small-text mb-0">
                                2 days ago
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item d-none d-lg-block color-setting">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-tune"></i>
                </a>
            </li>-->
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                    <?php echo _E('{Profile.FirstName} {Profile.LastName}')?>
                    <span class="mr-3"></span>
                    <!--<img class="img-xs rounded-circle" src="images/faces/face1.jpg" alt="Profile image">-->
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                    <div class="d-flex border-bottom">
                        <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-user"></i> <?php echo _E('{Profile.RoleName}')?>
                        </div>
                    </div>
                    <?php
                        echo _A('My Account',URI('Users/MyAccount',true),['class'=>'dropdown-item']);
                        echo _A('Change Password',URI('Users/ChangePassword',true),['class'=>'dropdown-item']);
                        echo _A('Logout',URI('Logout',true),['class'=>'dropdown-item']);
                    ?>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
