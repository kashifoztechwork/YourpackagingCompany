<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php 
            if($Title || $Description || $Keywords || $SchemaTag){
                echo sprintf(
                    '<title>%s</title>
                    <meta name="description" content="%s" />
                    <meta name="keywords" content="%s"  />
                    %s',_E($Title),@$Description,@$Keywords,@$SchemaTag
                );
            } 
        ?>
        
        <?php echo @$IsIndex; ?>
        <?php
         
            echo $Bundels->Render('FrontCSS','CSS');
            echo $Bundels->Render('FontCSS','CSS');
        ?>
        <link rel="shortcut icon" href="<?php echo URI('resources/images/ypc.png')?>" />
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6446a4df4247f20fefed757c/1gupv8386';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QB7LQ5GYWN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QB7LQ5GYWN');
</script>
    </head>
    <body>
       

        <div class="page-wrapper">
                 <?php
                    $Messages = $this->Messages->GetMessages();
                    if(!empty($Messages)){
                        echo implode('',$Messages);
                        $Messages = '';
                    }
                    
                    echo sprintf('%s%s%s%s',$Header,$Sidebar,$Page,$Footer);
                ?>
        <!--End pagewrapper-->
        </div>
        
        <script type="text/javascript">
            var BaseLink = '<?php echo URI('')?>';
            var CFN = '<?php echo _CSRF()?>';
            var CFH = '<?php echo _CSRF_Hash()?>';
           
        </script>
        <?php
            echo $Bundels->Render('JSTrigger','JS');
            echo $Bundels->Render('PreScripts','JS');
            echo $Bundels->Render('FrontJS','JS');
            echo $Bundels->Render('PostScripts','JS');
        ?>
    </body>
</html>
