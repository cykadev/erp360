<?php

use Erp360\Core\Helpers\SiteHelper;

$data = [];
$data['page_title'] = 'Page Not Found!';

SiteHelper::renderView('auth/layouts/header', $data);

?>
    <!-- Content -->

    <!-- Error -->
    <div class="container-xxl container-p-y">
      <div class="misc-wrapper">
        <h2 class="mb-2 mx-2">Page Not Found :(</h2>
        <p class="mb-4 mx-2">Oops! 😖 The requested URL was not found on this server.</p>
        <a href="<?php echo SiteHelper::navigator(''); ?>" class="btn btn-primary">Back to home</a>
        <div class="mt-3">
          <img
            src="<?php echo SiteHelper::assets('img/illustrations/page-misc-error-light.png'); ?>"
            alt="page-misc-error-light"
            width="500"
            class="img-fluid"
            data-app-dark-img="illustrations/page-misc-error-dark.png"
            data-app-light-img="illustrations/page-misc-error-light.png"
          />
        </div>
      </div>
    </div>
    <!-- /Error -->

    <!-- / Content -->

<?php SiteHelper::renderView('auth/layouts/footer'); ?>
