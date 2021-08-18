<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Inputs;
use Bookly\Lib as BooklyLib;

$attrs = array( 'id' => 'bookly-show-address' );
if ( BooklyLib\Config::invoicesActive() ) {
    $attrs['disabled'] = 'disabled';
}
?>
<div class="col-md-3 my-2">
    <div data-toggle="bookly-popover" data-trigger="hover" data-placement="auto"<?php if ( BooklyLib\Config::invoicesActive() ) : ?> class="bookly-js-simple-popover"<?php endif ?> data-content="<?php esc_attr_e( 'Address information is needed for Invoices add-on. To disable, deactivate Invoices add-on first.', 'bookly' ) ?>">
        <?php Inputs::renderCheckBox( __( 'Show address fields', 'bookly' ), null, BooklyLib\Config::invoicesActive() || get_option( 'bookly_app_show_address' ), $attrs ) ?>
    </div>
</div>