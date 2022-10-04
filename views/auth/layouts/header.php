<?php
  use Erp360\Core\Helpers\SiteHelper;
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>ERP360 - <?php echo $data['page_title'];  ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SiteHelper::assets('img/favicon/favicon.ico'); ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com'); ?>" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?php echo SiteHelper::assets('vendor/fonts/boxicons.css'); ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo SiteHelper::assets('vendor/css/core.css" class="template-customizer-core-css'); ?>" />
    <link rel="stylesheet" href="<?php echo SiteHelper::assets('vendor/css/theme-default.css" class="template-customizer-theme-css'); ?>" />
    <link rel="stylesheet" href="<?php echo SiteHelper::assets('css/demo.css'); ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo SiteHelper::assets('vendor/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?php echo SiteHelper::assets('vendor/css/pages/page-auth.css'); ?>" />
    <!-- Helpers -->
    <script src="<?php echo SiteHelper::assets('vendor/js/helpers.js'); ?>"></script>
    <script src="<?php echo SiteHelper::assets('js/config.js'); ?>"></script>
    <script src="<?php echo SiteHelper::assets('vendor/libs/jquery/jquery.js'); ?>"></script>
    <script src="<?php echo SiteHelper::assets('vendor/libs/jquery-validation/dist/jquery.validate.min.js'); ?>"></script>
    <script src="<?php echo SiteHelper::assets('vendor/libs/jquery-validation/dist/additional-methods.min.js'); ?>"></script>


    <script>

    jQuery.validator.setDefaults({
        ignore: [],
        onfocusout: function (e) {
            this.element(e);
        },
        onkeyup: false,
    
        highlight: function (element) {
            jQuery(element).closest('.form-control').addClass('invalid');
        },
        unhighlight: function (element) {
            jQuery(element).closest('.form-control').removeClass('invalid');
            jQuery(element).closest('.form-control').addClass('valid');
        },
    
        errorElement: 'span',
        errorClass: 'invalid-feedback',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                // $(element).siblings(".invalid-feedback").append(error);
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    jQuery.each(jQuery.validator.methods, function (key, value) {
        jQuery.validator.methods[key] = function () {       
            if(arguments.length > 0) {
                arguments[0] = jQuery.trim(arguments[0]);
            }

            return value.apply(this, arguments);
        };
    });

    </script>

  </head>

  <body>

    <!-- Content -->

    <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">