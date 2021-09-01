<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . '../css/partnumberGenearorPage.css'?>">
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/jquery/2.2.2/jquery.min.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/jquery/popper/1.12.9/popper.min.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/jquery/bootstrap/4.3.0/bootstrap.min.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . '../css/bootstrap/4.3.0/bootstrap.min.css'?>">
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/jquery.clipboard.min.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/jquery.hotkeys-0.7.9.min.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/PNC2.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/ZeroClipboard.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/partnumberGenearorPage.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/Scripts/GeneratorViewModel.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/Scripts/Helper.js'?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '../js/clipboard.min.js'?>"></script>
<table>
    <tbody><tr>
        <td colspan="2">
            <table id="selectors" style="width: 680px;float:none;">
                <tbody id="selectorsBody">
                <tr>
                    <td colspan="2">
                        <h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;">SiT9120 Part Number Generator</h2>
                    </td>
                </tr>
                <tr>
                    <td class="caption">Frequency (MHz)</td>
                    <td class="content">
                        <div id="frequencycontainer" class="content_wrapper">
                            <div class="option" style="width: 200px; line-height: 26px; margin-right: 5px;">
                                <input name="frequency" type="text" id="frequency" autocomplete="off" maxlength="10" style="width: 160px; height: 26px; border: 1px solid #c2c2c2;">
                                <sup>
                                    <a href="" title="Supported frequencies: 25,50,74.175824,74.25,75,98.304,100,106.25,125,133,133.3,133.33,133.333,133.3333,133.33333,133.333333,148.351648,148.5,150,155.52,156.25,161.1328,166,166.6,166.66,166.666,166.6666,166.66666,166.666666,200,212.5">
                                        <img style="margin-bottom:0px" src="GeneratorScripts/images/help-16.png" alt="[i]">
                                    </a>
                                </sup>
                                <div class="infoicon" id="frequency_error">
                                    <a href="" class="selector_info" title="error!" onclick="return false;">
                                        <img alt="(i)" src="GeneratorScripts/images/exclamation-16.png">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="generatorRow">
                    <td class="caption">Signaling Type</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;"><input type="radio" name="signaling" value="1">LVPECL</div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="signaling" value="2">
                                    LVDS
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="generatorRow">
                    <td class="caption">Frequency Stability (PPM)</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="tolerance" value="F">
                                    ±10
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="tolerance" value="1">
                                    ±20
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="tolerance" value="2">
                                    ±25
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="tolerance" value="3">
                                    ±50
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                <ul id="freqList" style="display: none; width: 230px;">
                    <li style="width:70px;" class="rowElem" id="25">25</li>
                    <li style="width:70px;" class="rowElem" id="133.33">133.33</li>
                    <li style="width:70px;" class="rowElem" id="161.1328">161.1328</li>
                    <li style="width:70px;" class="rowElem" id="50">50</li>
                    <li style="width:70px;" class="rowElem" id="133.333">133.333</li>
                    <li style="width:70px;" class="rowElem" id="166">166</li>
                    <li style="width:70px;" class="rowElem" id="74.175824">74.175824</li>
                    <li style="width:70px;" class="rowElem" id="133.3333">133.3333</li>
                    <li style="width:70px;" class="rowElem" id="166.6">166.6</li>
                    <li style="width:70px;" class="rowElem" id="74.25">74.25</li>
                    <li style="width:70px;" class="rowElem" id="133.33333">133.33333</li>
                    <li style="width:70px;" class="rowElem" id="166.66">166.66</li>
                    <li style="width:70px;" class="rowElem" id="75">75</li>
                    <li style="width:70px;" class="rowElem" id="133.333333">133.333333</li>
                    <li style="width:70px;" class="rowElem" id="166.666">166.666</li>
                    <li style="width:70px;" class="rowElem" id="98.304">98.304</li>
                    <li style="width:70px;" class="rowElem" id="148.351648">148.351648</li>
                    <li style="width:70px;" class="rowElem" id="166.6666">166.6666</li>
                    <li style="width:70px;" class="rowElem" id="100">100</li>
                    <li style="width:70px;" class="rowElem" id="148.5">148.5</li>
                    <li style="width:70px;" class="rowElem" id="166.66666">166.66666</li>
                    <li style="width:70px;" class="rowElem" id="106.25">106.25</li>
                    <li style="width:70px;" class="rowElem" id="150">150</li>
                    <li style="width:70px;" class="rowElem" id="166.666666">166.666666</li>
                    <li style="width:70px;" class="rowElem" id="125">125</li>
                    <li style="width:70px;" class="rowElem" id="155.52">155.52</li>
                    <li style="width:70px;" class="rowElem" id="200">200</li>
                    <li style="width:70px;" class="rowElem" id="133">133</li>
                    <li style="width:70px;" class="rowElem" id="156.25">156.25</li>
                    <li style="width:70px;" class="rowElem" id="212.5">212.5</li>
                    <li style="width:70px;" class="rowElem" id="133.3">133.3</li>
                </ul>
                <tr class="generatorRow">
                    <td class="caption">Temperature Range (C)</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="temprange" value="C">
                                    -20 to 70
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="temprange" value="I">
                                    -40 to 85
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="generatorRow">
                    <td class="caption">Supply Voltage (V)</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="voltage" value="33">
                                    3.3
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="voltage" value="25">
                                    2.5
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 180px; border: 1px dotted transparent;">
                                <div class="option" style="width: 180px;"><input type="radio" name="voltage" value="XX">
                                    2.25V-3.63V(any voltage)
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="generatorRow">
                    <td class="caption">Package Size (mm)</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="package" value="B">
                                    3.2x2.5
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="package" value="C">
                                    5.0x3.2
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="package" value="D">
                                    7.0x5.0
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="generatorRow">
                    <td class="caption">Feature Pin</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;"><input type="radio" name="pin" value="S">ST</div></label><label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;"><div class="option" style="width: 100px;"><input type="radio" name="pin" value="E">OE</div></label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="pin" value="N">
                                    NC
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="generatorRow">
                    <td class="caption">Packaging</td>
                    <td class="content">
                        <div class="content_wrapper">
                            <label class="radio_wrapp" style="width: 150px; border: 1px dotted transparent;">
                                <div class="option" style="width: 150px;">
                                    <input type="radio" name="packaging" value="">
                                    Bulk for sampling only
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="packaging" value="X">
                                    250U
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="packaging" value="Y">
                                    1KU
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="packaging" value="T">
                                    3KU
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="packaging" value="G">
                                    250U 8 mm
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="packaging" value="E">
                                    1KU 8 mm
                                </div>
                            </label>
                            <label class="radio_wrapp" style="width: 100px; border: 1px dotted transparent;">
                                <div class="option" style="width: 100px;">
                                    <input type="radio" name="packaging" value="D">
                                    3KU 8 mm
                                </div>
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
                <tr id="footerRow">
                    <td colspan="3">
                        <div id="results">
                            <h3>SiTime Part Number:</h3>
                            <div class="input-group">
                                <input name="partnumber" id="partnumber" style="height: 32px; border: 1px solid #c2c2c2; display:inline-block; margin-left: 20%;" type="text" value="" onkeydown="trigger(event.keyCode)" readonly="readonly">
                                <div id="togglediv" class="custom-control custom-switch" style="margin-right:1%; margin-top:0.5%;">
                                    <input type="checkbox" class="custom-control-input" id="modeSwitcherBox" name="example">
                                    <label style="color: gray;" class="custom-control-label" for="modeSwitcherBox">
                                        Enable/Disable
                                        <br>
                                        Decoder mode
                                    </label>
                                </div>
                            </div>
                            <div id="btnsDiv">
                                <div id="resetdiv">
                                    <input type="submit" class="sitime-ga-btn" name="btnSubmit" id="reset" value="Reset" title="Reset all values" style="float:left;">
                                </div>
                                <div id="copydiv">
                                    <button class="copy-button sitime-ga-btn" data-clipboard-action="copy" data-clipboard-target="#partnumber" title="Copy the part number to clipboard">Copy</button>
                                </div>
                                <div id="requestdiv">
                                    <input type="submit" class="sitime-ga-btn" name="btnSubmit" id="request" value="Request Free Samples" title="Request Free Samples" style="float:left;"></div>
                                <div id="inventorydiv">
                                    <input type="submit" class="sitime-ga-btn" name="btnSubmit" id="inventory" value="Search Inventory" title="Search Inventory" style="float:left;"></div>
                                <div id="decodediv">
                                    <input type="submit" class="sitime-ga-btn" name="btnSubmit" id="decode" value="Decode" title="Decode part number" style="float: left; margin-left:10%; display:none">
                                </div>
                            </div>
                            <div id="DKDiv">
                                <button class="sitime-ga-btn" id="buy-on-DK" type="button" style="background-color:#f69e1d">
                                    <span class="" role="status" aria-hidden="true"></span>
                                    Buy on Digi-Key
                                </button>
                            </div>
                            <div id="EVB_results">
                                <h3>SiTime Eval Board Part Number:</h3>
                                <input name="EVB_partnumber" id="EVB_partnumber" style="height: 32px; border: 1px solid #c2c2c2;" type="text" value="" readonly="readonly">
                                <div id="copydivEVB">
                                    <button class="copy-button" data-clipboard-action="copy" data-clipboard-target="#EVB_partnumber" title="Copy the EVB part number to clipboard">
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>