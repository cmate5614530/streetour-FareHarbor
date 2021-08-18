<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib as BooklyLib;
use Bookly\Backend\Components\Dialogs\Staff\Edit\Proxy;
use Bookly\Backend\Components\Controls\Buttons;
/**
 * @var BooklyLib\Entities\Staff $staff
 * @var array $tpl_data
 */
?>
<form>
    <div class="form-group">
        <label for="bookly-working-time-limit"><?php esc_html_e( 'Limit working hours per day', 'bookly' ) ?></label>
        <select name="working_time_limit" class="form-control custom-select" id="bookly-working-time-limit">
            <option value=""><?php esc_html_e( 'Unlimited', 'bookly' ) ?></option>
            <?php for ( $i = 1; $i < 24; $i ++ ) : ?>
                <option value="<?php echo $i * 3600 ?>" <?php selected( $i * 3600, $staff->getWorkingTimeLimit() ) ?>><?php echo BooklyLib\Utils\DateTime::secondsToInterval( $i * 3600 ) ?></option>
            <?php endfor ?>
        </select>
        <small class="form-text text-muted"><?php esc_html_e( 'This setting allows limiting the total time occupied by bookings per day for staff member. Padding time is not included.', 'bookly' ) ?></small>
    </div>
    <?php if ( $for_backend || ! $zoom['credentials_required'] ) : ?>
        <?php self::renderTemplate( '_zoom_settings', compact( 'staff', 'zoom' ) ) ?>
    <?php endif ?>
    <?php if ( $for_backend ) : ?>
        <?php self::renderTemplate( '_gc_settings', $tpl_data['gc'] ) ?>
        <?php Proxy\OutlookCalendar::renderCalendarSettings( $tpl_data ) ?>
        <div class="bookly-js-modal-footer">
            <?php Buttons::renderSubmit( 'bookly-advanced-save' ) ?>
        </div>
    <?php endif ?>
    <div class="form-group">
        <label for="bookly-timezone"><?php esc_html_e( 'Timezone', 'bookly' ) ?></label>
        <select name="time_zone" class="form-control custom-select" id="bookly-timezone">
            <option value=""><?php esc_html_e( 'Default', 'bookly' ) ?></option>
            <?php echo wp_timezone_choice( $staff->getTimeZone( false ) ?: 'default' ) ?>
        </select>
        <small class="form-text text-muted"><?php esc_html_e( 'The staff member\'s schedule will be considered to be in the selected time zone. This time zone will also be used for the dates and times in notifications sent to the staff member', 'bookly' ) ?></small>
    </div>
</form>