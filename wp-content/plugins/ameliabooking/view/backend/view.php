<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<!--suppress JSUnusedLocalSymbols -->
<script>
  var wpAmeliaUploadsAmeliaURL = '<?php echo UPLOADS_AMELIA_FILES_URL; ?>';
  var wpAmeliaUseUploadsAmeliaPath = '<?php echo UPLOADS_AMELIA_FILES_PATH_USE; ?>';
  var wpAmeliaPluginURL = '<?php echo AMELIA_URL; ?>';
  var wpAmeliaPluginAjaxURL = '<?php echo AMELIA_ACTION_URL; ?>';
  var wpAmeliaPluginStoreURL = '<?php echo AMELIA_STORE_API_URL; ?>';
  var wpAmeliaSiteURL = '<?php echo AMELIA_SITE_URL; ?>';
  var menuPage = '<?php echo isset($page) ? (string)$page : ''; ?>';
  var wpAmeliaSMSVendorId = '<?php echo AMELIA_SMS_VENDOR_ID; ?>';
  var wpAmeliaSMSProductId10 = '<?php echo AMELIA_SMS_PRODUCT_ID_10; ?>';
  var wpAmeliaSMSProductId20 = '<?php echo AMELIA_SMS_PRODUCT_ID_20; ?>';
  var wpAmeliaSMSProductId50 = '<?php echo AMELIA_SMS_PRODUCT_ID_50; ?>';
  var wpAmeliaSMSProductId100 = '<?php echo AMELIA_SMS_PRODUCT_ID_100; ?>';
  var wpAmeliaSMSProductId200 = '<?php echo AMELIA_SMS_PRODUCT_ID_200; ?>';
  var wpAmeliaSMSProductId500 = '<?php echo AMELIA_SMS_PRODUCT_ID_500; ?>';
  window.wpAmeliaPluginURL = location.protocol === 'https:' ? window.wpAmeliaPluginURL.replace('http:', 'https:') : window.wpAmeliaPluginURL;
  window.wpAmeliaPluginAjaxURL = location.protocol === 'https:' ? window.wpAmeliaPluginAjaxURL.replace('http:', 'https:') : window.wpAmeliaPluginAjaxURL;
</script>
<div id="amelia-app-backend" class="amelia-booking">
  <transition name="fade">
    <router-view></router-view>
  </transition>
</div>
