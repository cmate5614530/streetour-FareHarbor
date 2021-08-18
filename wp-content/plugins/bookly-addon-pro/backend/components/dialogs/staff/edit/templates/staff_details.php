<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib as BooklyLib;
/**
 * @var array $categories
 * @var BooklyLib\Entities\Staff $staff
 */
?>
<div class="form-group">
    <label for="bookly-category"><?php esc_html_e( 'Category', 'bookly' ) ?></label>
    <select name="category_id" class="form-control custom-select" id="bookly-category">
        <option value="0"><?php esc_html_e( 'Uncategorized', 'bookly' ) ?></option>
        <?php foreach ( $categories as $category ) : ?>
            <option value="<?php echo $category['id'] ?>" <?php selected( $category['id'], $staff->getCategoryId() ) ?>><?php echo $category['name'] ?></option>
        <?php endforeach ?>
    </select>
</div>