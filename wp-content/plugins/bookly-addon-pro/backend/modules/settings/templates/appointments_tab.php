<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Backend\Components\Controls\Inputs as ControlsInputs;
use Bookly\Backend\Components\Settings\Selects;
?>
<div class="tab-pane" id="bookly_settings_appointments">
    <form method="post" action="<?php echo esc_url( add_query_arg( 'tab', 'appointments' ) ) ?>">
        <div class="card-body">
            <?php Selects::renderSingle( 'bookly_appointments_time_delimiter', __( 'Time delimiter', 'bookly' ), null, $slot_lengths ) ?>
        </div>

        <div class="card-footer bg-transparent d-flex justify-content-end">
            <?php ControlsInputs::renderCsrf() ?>
            <?php Buttons::renderSubmit() ?>
            <?php Buttons::renderReset( null, 'ml-2' ) ?>
        </div>
    </form>
</div>