<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo _E($Title)?></title>
        <?php echo $Bundels->Render('Common','CSS')?>
        <link rel="shortcut icon" href="<?php echo URI('favicon.ico')?>" />
    </head>
    <body>
        <div class="container-scroller">
            <?php
                $Messages = $this->Messages->GetMessages();
                if(!empty($Messages)):
            ?>
            <div class="row">
                <div class="col-4 mx-auto">
                    <?php echo implode('',$Messages);?>
                </div>
            </div>
            <?php endif;?>
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <?php echo $Page;?>
            </div>
        </div>
        <script type="text/javascript">
            var BaseLink = '<?php URI('')?>';
            var SystemTime = 0;
            var DisplayLoader = true;
        </script>
        <?php
            echo $Bundels->Render('Common','JS');
            echo $Bundels->Render('PostScripts','JS');
            
        ?>
    </body>
</html>
