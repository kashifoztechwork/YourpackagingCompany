<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo _E($Title)?></title>
        <?php echo $Bundels->Render('Common','CSS','SummerNoteCSS'); ?>
        <link rel="shortcut icon" href="<?php echo URI('favicon.ico');?>" />
    </head>
    <body>
        <div class="container-scroller">
            <?php echo $Header?>
            <div class="container-fluid page-body-wrapper">
                <?php echo $Sidebar?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php
                            $Messages = $this->Messages->GetMessages();
                            if(!empty($Messages)){
                                echo implode('',$Messages);
                            }
                            echo $Page
                        ?>
                    </div>
                    <?php echo $Footer?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var BaseLink = '<?php echo URI('')?>';
            var SystemTime = 0;
            var DisplayLoader = true;
            var CFN = '<?php echo _CSRF()?>';
            var CFH = '<?php echo _CSRF_Hash()?>';
            var Currencies = <?php echo json_encode(CURRENCIES)?>
        </script>
        <?php
            echo $Bundels->Render('PreScripts','JS');
            echo $Bundels->Render('Common','JS');
            echo $Bundels->Render('TinyMCE','JS');
            echo $Bundels->Render('SummerNoteJS','JS');
            echo $Bundels->Render('PostScripts','JS');
            
        ?>
        <script>
            var maxLength = 150;
            $('textarea.description').keyup(function() {
                var textlen = maxLength - $(this).val().length;
                $('#rchars').text(textlen);
            });

            var TitleMaxLength = 60;
            $('input.title').keyup(function() {
                var TextLength = TitleMaxLength - $(this).val().length;
                $('#fortitle').text(TextLength);
            });
        </script>
    </body>
</html>
