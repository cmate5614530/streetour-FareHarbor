<?php

require 'fh-header.php';
$type = $_REQUEST['type'] ?? 'list';
$book_item = $_REQUEST['item'] ?? '';

if (!empty($book_item)) {

    require_once("../../../../wp-load.php");
    include("./httpful.phar");

    $api_app = get_option('streetours-fareharbor-app-key') ?? "646cb627-293d-41dc-9f69-3d5cb25ec2fc";
    $api_user = get_option('streetours-fareharbor-user-key') ?? "c7ebe150-95e2-4dba-bb0b-64c3022ec99b";
    $company = get_option('streetours_fareharbor_company') ?? 'bodyglove';

    $root_url = 'https://demo.fareharbor.com/api/external/v1';
    $company = 'bodyglove';
    $availabilities_url = "$root_url/companies/$company/items/";

    $response = \Httpful\Request::get($availabilities_url)
        ->addHeader("X-FareHarbor-API-App", $api_app)
        ->addHeader("X-FareHarbor-API-User", $api_user)
        ->send();

    $fhItems = $response->body->items;

    foreach ($fhItems as $fhItem):
//       if ($fhItem->pk == $book_item):
        ?>
        <div class="container">
            <?php echo $fhItem->booking_notes_safe_html; ?>
        </div>
    <?php
//        endif;
    endforeach;
}
else{?>
    <a href="https://fareharbor.com/embeds/book/companyname/" onclick="return !(window.FH && FH.open({ shortname: 'bodyglove',flow:409,view: {item: 409} }));">Book now!</a>

    <script type="text/javascript">
        document.getElementById('my-button')
            .addEventListener('click', function(){
                FH.open({
                    shortname: 'bodyglove',
                    items:[409],
                    view: {item: 409}
                });
            });
    </script>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card-deck st-fare" >
                    <div class="card st-item" style="width: 100% !important;">
                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?item=183&company=bodyglove"
                           class="st-item-dolphin st-f-1">
                            <div class="card-body st-p-0">
                                <img src="https://d1a2dkr8rai8e2.cloudfront.net/api/file/goyMvVpnQkWAAGm9vLbH">
                            </div>
                        </a>
                        <div class="card-body">
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?item=183&company=bodyglove"
                               class="st-item-dolphin st-f-1"><b>Snorkel
                                    & Dolphin Adventure</b></a>
                            <p>
                                Step aboard our 65′ Catamaran for a fun-filled day of adventure! This 4.5 hour cruise
                                offers breakfast, lunch, plenty of shade, cushioned seating, 20′ waterslide, and
                                more!
                            </p>
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?item=183&company=bodyglove" class="st-a-btn">
                                <span id="btn-1" style="margin-top: 1rem!important;" class="btn st-learn-more">
                                    <span class="inner btn-1">
                                        <span class="label">Learn More</span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

}
?>

<?php require 'fh-footer.php' ?>