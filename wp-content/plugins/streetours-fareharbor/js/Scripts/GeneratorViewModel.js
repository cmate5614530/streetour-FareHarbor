/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var inheriting = {};
var PartSeparate = new Array();
PartSeparate.key = "-";
PartSeparate.value = true;
var Headers = {
    "Family": "Family",
    "FrequencyStability": "Frequency Stability (PPM)",
    "TemperatureRange": "Temperature Range (C)",
    "SupplyVoltage": "Supply Voltage (V)",
    "PackageSize": "Package Size (mm)",
    "ControlPin": "Feature Pin",
    "Packaging": "Packaging",
    "Description": "Description",
    "OutputDriverStrength": "Output Drive Strength",
    "SignallingType": "Signaling Type",
    "Swing": "Swing",
    "FrequencySelect": "Frequency select option",
    "VCMO": "Pull Range (PPM)",
    "OutputWaveform": "Output Waveform",
    "SpreadSpectrum": "Spread Option (%)",
    "SpreadType": "Spread Type and Profile",
    "DeviceAddress": "Device Address",
    "WaveForm": "Output Waveform",
    "ACDCCoupling": "AC- or DC-Coupled Receiver",
    "OutputVoh": "DC-Coupled Output VOH",
    "OutputVol": "DC-Coupled Output VOL or AC Swing",
    "ACSwing": "AC Swing",
    "SerialMode": "Serial IF",
    "I2CAddress": "I2C Address",
    "SpecialFeatures": "Special Features",
	"SignallingGroup": "Signaling Group",
	"Reserved": "Reserved"
};

var Tips = {
    "FrequencyStability20Tip": "Available only on -20 to 70(C)",
    "PackagingTip": "250U, 1KU and 3KU Tape and Reel are available for minimum quantities of 250U, 1KU and 3KU respectively. For samples in tape and reel please contact SiTime. 250 Unit Reel will incur a broken reel charge.",
    "FrequencyStability10Tip": "Available only on 3.3V/2.5V and 0 to 70(C)",
    "FrequencyStability15Tip": "Available either on 1.8V and 0 to 70(C) or on all 3.3V/2.5V",
    "ControlPinSDTip": "Center spread options are not available for Spread Disable control pin option",
    "SpreadOptionTip": "Available only on -20 to 70(C) and frequency under 75MHz",
    "SupplyVoltageTip": "Available only for CML output signaling",
    "PackageSizeTip": "With center metal pad",
    "SwingHTip": "Available only for CML and LVDS output signaling"
};

var SupportedDevices = ["1532", "1533", "1552", "1566", "1568", "1572", "1630",
    "1534", "1569", "1576", "1579", "8021",
    "9120", "9021", "9122", "9365", "9366", "9367",
    "1602", "2001", "2002", "8008", "8009", "8208", "8209",
    "1618", "2018", "2019", "2020", "2021", "8918", "8919", "8920", "8921",
    "9002", "9003", "9005",
    "3521", "3522", "3907", "3921", "3922",
    "3372", "3373", "3807", "3808", "3809",
    "5155", "5156", "5157", "5356", "5357", "5358", "5359",
    "5000", "5001", "5021", "5022",
    "5711", "5712",
    "5146", "5147", "5346", "5347", "5348", "5349",
    "2044", "2045", "8944", "8945",
    "9346", "9347",
    "9045",
    "3541", "3542",
    "3342", "3343",
    "2024", "2025", "8924", "8925", "8934", "8935",
    "9025",
    "9386", "9387",
    "5186", "5187", "5386", "5387"
];

var ArchivedDevices = ["2002", "3701", "3702", "8033", "8002", "8002AA", "8003", "8503", "8004",
    "8103", "8102", "8003XT", "8225", "8226",
    "3509", "3519", "3821", "3822", "5002", "5003", "5004", "5301", "9001",
    "9156", "9102", "9107", "9201", "9103", "9104", "9105",
];


function BaseCSClass() {
    if (arguments[0] === inheriting) return;
    var PartFamily;
    var PartNumberValue;
    var validationErrors;
    this.Initialization();
}
BaseCSClass.prototype.Reverse = function(partnumber, selector) {
    this.Parse(partnumber);
    this.Verify();

};
BaseCSClass.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 2);
    this.PartNumberValue = partNumber.substring(2, 7);
};
BaseCSClass.prototype.Verify = function() {
    return this.Validate();
};
BaseCSClass.prototype.Validate = function() {
    if (!isNumber(this.PartNumberValue))
        this.validationErrors.push(this.PartNumberValue + " - " + getErrorByErrorID("15"));
};

function BaseCSSiTClass() {
    if (arguments[0] === inheriting) return;
    var PartFamily;
    var PartNumberValue;
    this.SelectedRevisionLetter;
    this.SelectedTemperatureRange;
    this.RevisionLetter;
    this.TemperatureRange;
    var validationErrors;
    this.Initialization();
}
BaseCSSiTClass.prototype.Reverse = function(partnumber, selector) {
    partnumber = "SiT" + partnumber;
    this.Parse(partnumber);
    this.Verify();
};

BaseCSSiTClass.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
};
BaseCSSiTClass.prototype.Verify = function() {
    return this.Validate();
};
BaseCSSiTClass.prototype.Initialization = function() {
    this.RevisionLetter = new Array();
    this.TemperatureRange = new Array();
    this.SelectedRevisionLetter = getArrayKeyValueWithTrue();
    this.SelectedTemperatureRange = getArrayKeyValueWithTrue();
};
BaseCSSiTClass.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
};

function BaseViewModel() {
    if (arguments[0] === inheriting) return;
    var PartFamily;
    var Frequency;
    var MinFrequency;
    var MaxFrequency;
    var ListOfFrequency;
    var FrequencyHoles;
    var TemperatureFrequencyHoles;
    var LengthAfterPoint;
    var Mode;
    var FamilyDescription;
    var MinPartLenght;
    var Divider1;
    var Divider2;
    var Divider3;
    var Divider4;
    var SpreadType;
    var SerialMode;
    var Restrictions;

    var EvalBoards;

    var SelectedDivider1;
    var SelectedDivider2;
    var SelectedDivider3;
    var SelectedSpreadType;
    var SelectedSerialMode;
    var SelectedRevisionLetter;
    var SelectedTemperatureRange;
    var SelectedOutputDriverStrength;
    var SelectedPackageSize;
    var SelectedPackaging;
    var SelectedSupplyVoltage;
    var SelectedFrequencyStability;
    var SelectedSignalingType;
    var validationErrors;
    //this.SetMode(mode);
    //.SetMode("Decoder");
    this.Initialization();

};
BaseViewModel.prototype.Initialization = function(jsonData) {
    this.MinPartLenght = jsonData.MinPartLenght;
    this.LinkToProductPage = jsonData.LinkToProductPage;
    this.LengthAfterPoint = jsonData.LengthAfterPoint;
    this.RevisionLetter = jsonData.RevisionLetter;
    this.TemperatureRange = jsonData.TemperatureRange;
    this.OutputDriverStrength = jsonData.OutputDriverStrength;
    this.PackageSize = jsonData.PackageSize;
	this.SignallingGroup = jsonData.SignallingGroup;
    this.Reserved = jsonData.Reserved;
    this.Packaging = jsonData.Packaging;
    this.PackagingList2 = jsonData.PackagingList2;
    this.PackagingList1 = jsonData.PackagingList1;
    this.SupplyVoltage = jsonData.SupplyVoltage;
    this.SerialMode = jsonData.SerialMode;
    this.SpecialFeatures = jsonData.SpecialFeatures;
    this.I2CAddress = jsonData.I2CAddress;
    this.FrequencyStability = jsonData.FrequencyStability;
    this.FamilyDescription = jsonData.FamilyDescription;
    this.Divider1 = jsonData.Divider1;
    this.Divider2 = jsonData.Divider2;
    this.Divider3 = jsonData.Divider3;
    this.Divider4 = jsonData.Divider4;
    this.SpreadType = jsonData.SpreadType;
    this.PullRange = jsonData.VCMO;
    this.PartFamily = jsonData.PartFamily;
    this.MinFrequency = jsonData.MinFrequency;
    this.MaxFrequency = jsonData.MaxFrequency;
    this.ListOfFrequency = jsonData.ListOfFrequency;
    this.ListOfFrequency1 = jsonData.ListOfFrequency1;
    this.ListOfFrequency2 = jsonData.ListOfFrequency2;
    this.FrequencyHoles = jsonData.FrequencyHoles;
    this.FrequencyHolesList1 = jsonData.FrequencyHolesList1;
    this.FrequencyHolesList2 = jsonData.FrequencyHolesList2;
    this.FrequencyHolesList3 = jsonData.FrequencyHolesList3;
    this.FrequencyHolesList4 = jsonData.FrequencyHolesList4;
    this.FrequencyHolesList5 = jsonData.FrequencyHolesList5;
    this.FrequencyHolesList6 = jsonData.FrequencyHolesList6;
    this.Restrictions = jsonData.Restrictions;

    this.EvalBoards = jsonData.EvalBoards;

    this.Mode = jsonData.Modes;

    this.SelectedDivider1 = getArrayKeyValueWithTrue();
    this.SelectedDivider2 = getArrayKeyValueWithTrue();
    this.SelectedDivider3 = getArrayKeyValueWithTrue();
    this.SelectedDivider4 = getArrayKeyValueWithTrue();
    this.SelectedSpreadType = getArrayKeyValueWithTrue();
    this.SelectedSerialMode = getArrayKeyValueWithTrue();
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
    this.SelectedI2CAddress = getArrayKeyValueWithTrue();
    this.SelectedRevisionLetter = getArrayKeyValueWithTrue();
    this.SelectedTemperatureRange = getArrayKeyValueWithTrue();
    this.SelectedOutputDriverStrength = getArrayKeyValueWithTrue();
    this.SelectedPackageSize = getArrayKeyValueWithTrue();
    this.SelectedPackaging = getArrayKeyValueWithTrue();
    this.SelectedSupplyVoltage = getArrayKeyValueWithTrue();
    this.SelectedFrequencyStability = getArrayKeyValueWithTrue();
    this.SelectedSignalingType = getArrayKeyValueWithTrue();
    this.SelectedDivider2.key = "-";
    this.SelectedDivider3.key = "-";
    this.SelectedDivider4.key = "-";
    this.Frequency = getArrayKeyValueWithTrue();
    this.Frequency.key = "";
    /*if(this.PackagingList1!=undefined){
    	this.Packaging = this.PackagingList1;
    }*/

    if (this.FrequencyHolesList1 != undefined) {
        this.FrequencyHoles = this.FrequencyHolesList1;
    }



    this.validationErrors = new Array();
};
BaseViewModel.prototype.SetMode = function(mode) {
    this.Mode = mode;
}
BaseViewModel.prototype.Generate = function() {
    var currentFamily = this.PartFamily;
    if (currentFamily == "SiT8003XT")
        currentFamily = "SiT8003"

    return currentFamily + this.SelectedRevisionLetter.key + this.SelectedTemperatureRange.key + this.SelectedOutputDriverStrength.key;
};
BaseViewModel.prototype.GenerateForColor = function() {
    var result = new Array();
    var tempFamily = getArrayKeyValueWithTrue();
    tempFamily.key = this.PartFamily;
    result.push(tempFamily);
    result.push(this.SelectedRevisionLetter);
    result.push(this.SelectedTemperatureRange);
    result.push(this.SelectedOutputDriverStrength);
    return result;
};
BaseViewModel.prototype.GeneratePartnumber = function() {
    return this.Generate() + this.SelectedPackaging.key;
};
BaseViewModel.prototype.AllOptionsSelected = function() {
    if (this.OutputDriverStrength.length === 1 && this.Mode == "Generator")
        this.SelectedOutputDriverStrength.key = "-";
    val = this.SelectedRevisionLetter.key &&
        this.SelectedTemperatureRange.key &&
        this.SelectedOutputDriverStrength.key &&
        this.SelectedPackageSize.key &&
        this.SelectedPackaging.key &&
        this.SelectedSupplyVoltage.key &&
        this.SelectedFrequencyStability.key;
    if (val !== undefined)
        return true;
    else
        return false;
};
BaseViewModel.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedOutputDriverStrength, this.OutputDriverStrength)) {
        this.validationErrors.push(this.SelectedOutputDriverStrength.key + " - " + getErrorByErrorID("26"));
        this.SelectedOutputDriverStrength.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "E")) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "B")) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "S")) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (this.Mode == "Decoder") {

        if (!IsInArray(this.SelectedDivider2, this.Divider2)) {
            this.validationErrors.push(this.SelectedDivider2.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider2.value = false;

        }

        if (!IsInArray(this.SelectedDivider3, this.Divider3)) {
            this.validationErrors.push(this.SelectedDivider3.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider3.value = false;
        }
    }

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};
BaseViewModel.prototype.ToString = function() {
    var clipboardValue = "";
    clipboardValue += "Part Number:         " + "\t\t" + this.Generate() + "\n";
    clipboardValue += "Part Family:         " + "\t\t" + this.PartFamily + "(" + this.FamilyDescription + ")\n";
    clipboardValue += "Frequency:           " + "\t\t" + this.Frequency.key + "\n";
    clipboardValue += "Revision Letter:     " + "\t\t" + this.SelectedRevisionLetter.key + "\n";
    clipboardValue += "Temperature Range:   " + "\t\t" + GetValueFromArray(this.SelectedTemperatureRange, this.TemperatureRange) + "C\n";
    clipboardValue += "Frequency Stability: " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedFrequencyStability, this.FrequencyStability)) + " ppm\n";
    clipboardValue += "Supply Voltage:      " + "\t\t" + GetValueFromArray(this.SelectedSupplyVoltage, this.SupplyVoltage) + "V\n";
    clipboardValue += "Package Size:        " + "\t\t" + GetValueFromArray(this.SelectedPackageSize, this.PackageSize) + " mm\n";
    return clipboardValue;
};
BaseViewModel.prototype.GetClipboardValues = function() {
    var clipboardValue = this.ToString();
    clipboardValue += "Packaging:           " + "\t\t" + GetValueFromArray(this.SelectedPackaging, this.Packaging) + "\n";
    return clipboardValue;
}
BaseViewModel.prototype.PaintHTML = function(selector) {
    if (this.Mode == "Generator" || this.Mode == "Mixed") {
        $(selector).append($("<tr/>").append($("<td colspan='2'/>").append(getPartFamilyLabel(" Part Number Generator", this.PartFamily))));
    } else {
        if (this.FamilyDescription !== "" && this.FamilyDescription !== undefined) {
            $(selector).append(getFamilyDescription(this.FamilyDescription));
        }
    }
    //SiT1576, SiT1569, SiT1579 must be KHz
    if (this.PartFamily == "SiT1569" || this.PartFamily == "SiT1576" || this.PartFamily == "SiT1579" || this.PartFamily == "SiT1566") {
        $(selector).append(getFrequencyResource(this.Frequency, this.Mode, this, true));
        this.GetHTML(selector);
    } else {
        $(selector).append(getFrequencyResource(this.Frequency, this.Mode, this, false));
        this.GetHTML(selector);
    }
    if (this.Packaging !== undefined)
        AppendHTMLResource(selector, Headers.Packaging, this.Packaging, "packaging", this, "OnPackagingChanged", this.Mode);

    if (this.Mode == "Generator" || this.Mode == "Mixed") {

        $(selector).after(getGeneratorFooter());

        //temporary solution until datasheets won't be updated. Because this options should be avalible only in Decoder. Then just delete this block.
        if (this.PartFamily == "SiT5146" ||
            this.PartFamily == "SiT5147" ||
            this.PartFamily == "SiT5155" ||
            this.PartFamily == "SiT5156" ||
            this.PartFamily == "SiT5157" ||
            this.PartFamily == "SiT5346" ||
            this.PartFamily == "SiT5347" ||
            this.PartFamily == "SiT5348" ||
            this.PartFamily == "SiT5349" ||
            this.PartFamily == "SiT5356" ||
            this.PartFamily == "SiT5357" ||
            this.PartFamily == "SiT5358" ||
            this.PartFamily == "SiT5359") {
            hideOptionName("pin", "K");
            hideOptionName("pin", "L");

            hideOptionName("package", "W");
            
        }
    }
};
BaseViewModel.prototype.ShowGenerateResult = function() {
    this.validationErrors.length = 0;
    if (this.RevisionLetter.length > 1) {
        this.SelectedRevisionLetter.key = "B";
    } else if (this.RevisionLetter.length == 1) {
        this.SelectedRevisionLetter.key = this.RevisionLetter[0].key;
    }

    if (this.AllOptionsSelected()) {
        if (this.Validate()) {
            $("#partnumber").val(this.GeneratePartnumber()).css({
                color: "#000"
            });
            $("#partnumber").css({
                backgroundColor: "#FFFFFF"
            });

            // GA integration.
            var generatedPartNumber = $("#partnumber").val();
            var source_trail = '';
            if ($.cookie('sitime_source_trail')) {
              source_trail = $.cookie('sitime_source_trail');
            }

            // Push custom dimensions to GTM.
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
              'event' : 'Part Number Generated',
              'dimension1' : source_trail,
              'dimension2' : generatedPartNumber,
            });

            // Push custom dimensions to GA.
            ga('create', 'UA-636493-1', 'auto');
            ga('set', 'dimension1', source_trail);
            ga('set', 'dimension2', generatedPartNumber);
            ga('send', 'pageview', '/part-number-generator');

            $('.sitime-ga-btn').click(function() {
              var btnLabel = $(this).attr('value') ?? $(this).text();

              // Push custom dimensions to GA.
              ga('create', 'UA-636493-1', 'auto');
              ga('set', 'dimension1', source_trail);
              ga('set', 'dimension2', generatedPartNumber);
              ga('set', 'dimension3', btnLabel);
              ga('send', 'pageview', '/part-number-generator');
            });

            var evbPartNumber = this.GetEvalBoardResult();
            if (evbPartNumber == "NoEvalForPackage")
                $("#EVB_partnumber").val("No EVB avalible for this package.").css({
                    color: "#bbb"
                });
            else if (evbPartNumber == "NoEvalExist")
                $("#EVB_partnumber").val("Please contact SiTime").css({
                    color: "#bbb"
                });
            else {
                $("#EVB_partnumber").val(evbPartNumber).css({
                    color: "#000"
                });
                $("#EVB_partnumber").css({
                    backgroundColor: "#FFFFFF"
                });
            }
            //part for checking CustomMPNs.
            if (document.getElementById('customChecker') != null && document.getElementById('customChecker').checked){
                if (customDigitsChanged ())
                    {
                        generateCustomPartNumber(this);
                    }
                else {
                     $("#customPartNumber").val("(please select all the options)").css({
                        color: "#bbb"
                     });
                }
            }
                else 
                    {}
          
        } else if (this.Mode == "Generator") {
            $("#partnumber").val("(please select all the options)").css({
                color: "#bbb"
            });
            //alert(this.validationErrors);
        }
    } else if (this.Mode == "Generator") {
        $("#partnumber").val("(please select all the options)").css({
            color: "#bbb"
        });
        $("#EVB_partnumber").val("(please generate desire part number)").css({
            color: "#bbb"
        });
    }
};
BaseViewModel.prototype.GetHTML = function(selector) {
    AppendHTMLResource(selector, Headers.FrequencyStability, this.FrequencyStability, "tolerance", this, "OnFrequencyStabilityChanged", this.Mode);
    if (this.ListOfFrequency != undefined) {
        $(selector).append(AddListOfFrequencyInGenerator(this));
        initContextMenu(selector);
    }
    AppendHTMLResource(selector, Headers.TemperatureRange, this.TemperatureRange, "temprange", this, "OnTemperatureRangeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.OutputDriverStrength, this.OutputDriverStrength, "driverstrength", this, "OnOutputDriverStrengthChanged", this.Mode);
    AppendHTMLResource(selector, Headers.SupplyVoltage, this.SupplyVoltage, "voltage", this, "OnSupplyVoltageChanged", this.Mode);
    AppendHTMLResource(selector, Headers.PackageSize, this.PackageSize, "package", this, "OnPackageSizeChanged", this.Mode);
};
BaseViewModel.prototype.OnOutputDriverStrengthChanged = function() {
    this.SelectedOutputDriverStrength.key = getCheckedValue(this.SelectedOutputDriverStrength.key, 'driverstrength');
};
BaseViewModel.prototype.OnPackagingChanged = function() {
    this.SelectedPackaging.key = getCheckedValue(this.SelectedPackaging.key, 'packaging');
};
BaseViewModel.prototype.OnFrequencyStabilityChanged = function() {
    this.SelectedFrequencyStability.key = getCheckedValue(this.SelectedFrequencyStability.key, 'tolerance');
};
BaseViewModel.prototype.OnTemperatureRangeChanged = function() {
    this.SelectedTemperatureRange.key = getCheckedValue(this.SelectedTemperatureRange.key, 'temprange');
};
BaseViewModel.prototype.OnSupplyVoltageChanged = function() {
    this.SelectedSupplyVoltage.key = getCheckedValue(this.SelectedSupplyVoltage.key, 'voltage');
};
BaseViewModel.prototype.OnPackageSizeChanged = function() {
    this.SelectedPackageSize.key = getCheckedValue(this.SelectedPackageSize.key, 'package');
};
BaseViewModel.prototype.Verify = function() {
    this.validationErrors.length = 0;
    if (this.AllOptionsSelected()) {
        var tempExclusionTable = this.ExclusionTable();
        var tempValidate = this.Validate();
        if (tempExclusionTable && tempValidate)
            return true;
        else return false;
    } else return false;
};
BaseViewModel.prototype.SelectOptions = function() {
    SelectOption("driverstrength", this.SelectedOutputDriverStrength.key);
    SelectOption("tolerance", this.SelectedFrequencyStability.key);
    SelectOption("temprange", this.SelectedTemperatureRange.key);
    SelectOption("voltage", this.SelectedSupplyVoltage.key);
    SelectOption("package", this.SelectedPackageSize.key);
    SelectOption("packaging", this.SelectedPackaging.key);
};
BaseViewModel.prototype.Reverse = function(partnumber, selector) {
    partnumber = PartnumberAdapter(partnumber);
    partnumber = "SiT" + partnumber;
    this.Parse(partnumber);
    var ResultOtherError = this.Verify();
    var ResultFrequencyError = this.OnFrequencyChange();
    if (ResultFrequencyError && ResultOtherError) {
        //this.Mode = "Decoder";
        this.PaintHTML(selector, this.Mode);
        $("#partnumber").val(partnumber);
        var evbPartNumber = this.GetEvalBoardResult();
        if (evbPartNumber == "NoEvalForPackage")
            $("#EVB_partnumber").val("No EVB avalible for this package.").css({
                color: "#bbb"
            });
        else if (evbPartNumber == "NoEvalExist")
            $("#EVB_partnumber").val("Please contact SiTime").css({
                color: "#bbb"
            });
        else {
            $("#EVB_partnumber").val(evbPartNumber).css({
                color: "#000"
            });
            $("#EVB_partnumber").css({
                backgroundColor: "#FFFFFF"
            });
        }
        this.SelectOptions();
    }
    if ((this.validationErrors.length == 1) && (this.validationErrors[0] == getErrorByErrorID("9"))) {
        this.PaintHTML(selector, this.Mode);
        this.SelectOptions();
    }
};
BaseViewModel.prototype.AvailableOptionsShow = function() {};
BaseViewModel.prototype.ExclusionTable = function() {};
BaseViewModel.prototype.OnFrequencyChange = function() {
    try {
        if (this.Mode == "Generator")
            this.Frequency.key = $("#frequency").val();

        var freq = Number(this.Frequency.key);
        var FrequencyLengthAfterPoint = this.Frequency.key.length - this.Frequency.key.lastIndexOf(".") - 1;
        if (FrequencyLengthAfterPoint > this.LengthAfterPoint || isNaN(freq)) {
            throw (this.Frequency.key + "MHz - " + getErrorByErrorID("20") + this.LengthAfterPoint + getErrorByErrorID("22"));
        }

        this.Frequency.key = parseFloat(this.Frequency.key).toFixed(this.LengthAfterPoint);
        if (isNaN(this.Frequency.key)) {
            throw (getErrorByErrorID("18"));
        }
        if (this.ListOfFrequency === undefined) {
            if (this.Frequency.key < this.MinFrequency || this.Frequency.key > this.MaxFrequency) {
                throw (getErrorByErrorID("18"));
            }
        } else {
            if (this.ListOfFrequency.length > 0) {
                var temp = parseFloat(this.Frequency.key);
                if (!checkArrayEntry(temp, this.ListOfFrequency)) {
                    throw (getErrorByErrorID("19"));
                }
            } else {
                throw (getErrorByErrorID("18"));
            }
        }

        if (this.FrequencyHoles != undefined) {
            for (var item in this.FrequencyHoles) {
                if (this.Frequency.key >= parseFloat(item) && this.Frequency.key <= parseFloat(this.FrequencyHoles[item])) {
                    throw (getErrorByErrorID("18"));
                }
            }
        }
    } catch (FrequencyErrorResult) {
        this.Frequency.value = false;
        this.validationErrors.push(FrequencyErrorResult);
        if (this.Mode == "Generator") {
            showError(FrequencyErrorResult);
            $("#partnumber").val("(invalid frequency)").css({
                color: "#bbb"
            });
            $("#EVB_partnumber").val("(invalid frequency)").css({
                color: "#bbb"
            });
        }
        return false;
    }
    if (this.Mode == "Generator") {
        this.ShowGenerateResult();
        dismissError();
    }
    return true;
}
BaseViewModel.prototype.GetRestrictionResult = function() {
    for (i = 0; i < this.Restrictions.TableRestrictions.length; i++) {
        var ifError = true;
        for (j = 0; j < this.Restrictions.TableRestrictions[i].RestrictionOptions.length; j++) {
            var selectedOption = eval("this." + this.Restrictions.TableRestrictions[i].RestrictionOptions[j].key);
            if (this.Restrictions.TableRestrictions[i].RestrictionOptions[j].key == "Frequency") {
                if (ifError && selectedOption.key > this.Restrictions.TableRestrictions[i].RestrictionOptions[j].value) {
                    ifError == true;
                }
            } else if (ifError && selectedOption.key == this.Restrictions.TableRestrictions[i].RestrictionOptions[j].value) {
                ifError == true;
            } else {
                ifError = false;
            }
        }

        if (ifError) {
            this.validationErrors.push(getErrorByErrorID(this.Restrictions.TableRestrictions[i].ErrorID));
            return false;
        }
    }
    return true;
}

BaseViewModel.prototype.GetChangeOptionRestrictionResult = function() {
    var result = false;
    if (this.Restrictions !== undefined && this.Restrictions.ChangeOptionRestrictions !== undefined && this.Restrictions.ChangeOptionRestrictions.length > 0) {
        for (i = 0; i < this.Restrictions.ChangeOptionRestrictions.length; i++) {
            if (this.Restrictions.ChangeOptionRestrictions[i].OptionName == "PackageSize") {
                if (this.SelectedPackageSize.key == this.Restrictions.ChangeOptionRestrictions[i].OptionKey) {
                    if (this.Restrictions.ChangeOptionRestrictions[i].DependentOptionName == "Packaging") {
                        this.Packaging = eval("this." + this.Restrictions.ChangeOptionRestrictions[i].DependentOptionValue);
                        result = true;
                    } else if (this.Restrictions.ChangeOptionRestrictions[i].DependentOptionName == "FeaturePin") {
                        this.FeaturePin = eval("this." + this.Restrictions.ChangeOptionRestrictions[i].DependentOptionValue);
                        result = true;
                    }
                }
            } else if (this.Restrictions.ChangeOptionRestrictions[i].OptionName == "TemperatureRange") {
                if (this.SelectedTemperatureRange.key == this.Restrictions.ChangeOptionRestrictions[i].OptionKey) {
                    if (this.Restrictions.ChangeOptionRestrictions[i].DependentOptionName == "FrequencyHoles") {
                        this.FrequencyHoles = eval("this." + this.Restrictions.ChangeOptionRestrictions[i].DependentOptionValue);
                        this.OnFrequencyChange();
                    }
                }
            } else if (this.Restrictions.ChangeOptionRestrictions[i].OptionName == "ACDCCoupling") {
                if (this.SelectedACDCCoupling.key == this.Restrictions.ChangeOptionRestrictions[i].OptionKey) {
                    if (this.Restrictions.ChangeOptionRestrictions[i].DependentOptionName == "OutputVol") {
                        this.OutputVol = eval("this." + this.Restrictions.ChangeOptionRestrictions[i].DependentOptionValue);
                    }
                    if (this.Restrictions.ChangeOptionRestrictions[i].DependentOptionName == "OutputVoh") {
                        this.OutputVoh = eval("this." + this.Restrictions.ChangeOptionRestrictions[i].DependentOptionValue);
                    }
                }
            } else if (this.Restrictions.ChangeOptionRestrictions[i].OptionName == "SpreadSpectrum") {
                if (this.SelectedSpreadSpectrum.key == this.Restrictions.ChangeOptionRestrictions[i].OptionKey) {
                    if (this.Restrictions.ChangeOptionRestrictions[i].DependentOptionName == "FrequencyHoles") {
                        this.FrequencyHoles = eval("this." + this.Restrictions.ChangeOptionRestrictions[i].DependentOptionValue);
                        this.OnFrequencyChange();
                    }
                }
            }
        }
        return result;
    } else
        return result;
}




BaseViewModel.prototype.GetEvalBoardResult = function() {
    if (this.EvalBoards !== undefined && this.EvalBoards.TableEvalBoards.length > 0) {
        for (i = 0; i < this.EvalBoards.TableEvalBoards.length; i++) {
            var ifError = true;
            for (j = 0; j < this.EvalBoards.TableEvalBoards[i].EvalBoardOptions.length; j++) {
                var selectedOption = eval("this." + this.EvalBoards.TableEvalBoards[i].EvalBoardOptions[j].key);
                if (this.EvalBoards.TableEvalBoards[i].EvalBoardOptions[j].key == "Frequency") {
                    if (ifError && selectedOption.key > this.EvalBoards.TableEvalBoards[i].EvalBoardOptions[j].value) {
                        ifError == true;
                    }
                } else if (ifError && selectedOption.key == this.EvalBoards.TableEvalBoards[i].EvalBoardOptions[j].value) {
                    ifError == true;
                } else {
                    ifError = false;
                }
            }

            if (ifError) {
                //this.validationErrors.push(getErrorByErrorID(this.EvalBoards.TableEvalBoards[i].EvalBoardPartNumber));
                return this.EvalBoards.TableEvalBoards[i].EvalBoardPartNumber;
            }
        }
        return "NoEvalForPackage";
    } else
        return "NoEvalExist";
}

BaseViewModel.prototype.GetChangeOptionEvalBoardResult = function() {
    var result = false;
    if (this.EvalBoards !== undefined && this.EvalBoards.ChangeOptionEvalBoards !== undefined && this.EvalBoards.ChangeOptionEvalBoards.length > 0) {
        for (i = 0; i < this.EvalBoards.ChangeOptionEvalBoards.length; i++) {
            if (this.EvalBoards.ChangeOptionEvalBoards[i].OptionName == "PackageSize") {
                if (this.SelectedPackageSize.key == this.EvalBoards.ChangeOptionEvalBoards[i].OptionKey) {
                    if (this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionName == "Packaging") {
                        this.Packaging = eval("this." + this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionValue);
                        result = true;
                    } else if (this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionName == "FeaturePin") {
                        this.FeaturePin = eval("this." + this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionValue);
                        result = true;
                    }
                }
            } else if (this.EvalBoards.ChangeOptionEvalBoards[i].OptionName == "TemperatureRange") {
                if (this.SelectedTemperatureRange.key == this.EvalBoards.ChangeOptionEvalBoards[i].OptionKey) {
                    if (this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionName == "FrequencyHoles") {
                        this.FrequencyHoles = eval("this." + this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionValue);
                        this.OnFrequencyChange();
                    }
                }
            } else if (this.EvalBoards.ChangeOptionEvalBoards[i].OptionName == "ACDCCoupling") {
                if (this.SelectedACDCCoupling.key == this.EvalBoards.ChangeOptionEvalBoards[i].OptionKey) {
                    if (this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionName == "OutputVol") {
                        this.OutputVol = eval("this." + this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionValue);
                    }
                    if (this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionName == "OutputVoh") {
                        this.OutputVoh = eval("this." + this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionValue);
                    }
                }
            } else if (this.EvalBoards.ChangeOptionEvalBoards[i].OptionName == "SpreadSpectrum") {
                if (this.SelectedSpreadSpectrum.key == this.EvalBoards.ChangeOptionEvalBoards[i].OptionKey) {
                    if (this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionName == "FrequencyHoles") {
                        this.FrequencyHoles = eval("this." + this.EvalBoards.ChangeOptionEvalBoards[i].DependentOptionValue);
                        this.OnFrequencyChange();
                    }
                }
            }
        }
        return result;
    } else
        return result;
}




function BaseWithFrequencySelect() {
    if (arguments[0] === inheriting) return;
    BaseViewModel.call(this);
    var SelectedFrequencySelectOption;
};
BaseWithFrequencySelect.prototype = new BaseViewModel(inheriting);
BaseWithFrequencySelect.base = BaseViewModel.prototype;
BaseWithFrequencySelect.prototype.Initialization = function(jsonData) {
    BaseWithFrequencySelect.base.Initialization.call(this, jsonData);
    this.FrequencySelectOptions = jsonData.FrequencySelectOptions;
    this.SelectedFrequencySelectOption = getArrayKeyValueWithTrue();
};
BaseWithFrequencySelect.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFrequencySelectOption.key + "-" + this.Frequency.key;
    return result;
};
BaseWithFrequencySelect.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFrequencySelectOption);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
BaseWithFrequencySelect.prototype.AllOptionsSelected = function() {
    if (BaseWithFrequencySelect.base.AllOptionsSelected.call(this)) {
        if (this.SelectedFrequencySelectOption.key !== undefined)
            return true;
        else return false;
    } else return false;
};
BaseWithFrequencySelect.prototype.Parse = function(partNumber) {
    partNumber = PartnumberAdapter(partNumber);
    partNumber = "SiT" + partNumber;

    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFrequencySelectOption.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
BaseWithFrequencySelect.prototype.Validate = function() {
    var result = BaseWithFrequencySelect.base.Validate.call(this);
    if (!IsInArray(this.SelectedFrequencySelectOption, this.FrequencySelectOptions)) {
        this.validationErrors.push(this.SelectedFrequencySelectOption.key + " - " + getErrorByErrorID("21"));
        this.SelectedFrequencySelectOption.value = false;
        return false;
    } else return result;
};
BaseWithFrequencySelect.prototype.ToString = function() {
    var clipboardValue = BaseWithFrequencySelect.base.ToString.call(this);
    clipboardValue += "Frequency Select:    " + "\t\t" + GetValueFromArray(this.SelectedFrequencySelectOption, this.FrequencySelectOptions) + "\n";
    return clipboardValue;
};
BaseWithFrequencySelect.prototype.GetHTML = function(selector) {
    BaseWithFrequencySelect.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.FrequencySelect, this.FrequencySelectOptions, "fs", this, "OnFrequencySelectOptionsChanged", this.Mode);
};
BaseWithFrequencySelect.prototype.OnFrequencySelectOptionsChanged = function() {
    this.SelectedFrequencySelectOption.key = getCheckedValue(this.SelectedFrequencySelectOption.key, 'fs');
};
BaseWithFrequencySelect.prototype.SelectOptions = function() {
    BaseWithFrequencySelect.base.SelectOptions.call(this);
    SelectOption("fs", this.SelectedFrequencySelectOption.key);
};


function BaseWithVCMO() {
    if (arguments[0] === inheriting) return;
    BaseViewModel.call(this);
    var SelectedVCMO;
};
BaseWithVCMO.prototype = new BaseViewModel(inheriting);
BaseWithVCMO.base = BaseViewModel.prototype;
BaseWithVCMO.prototype.Initialization = function(jsonData) {
    BaseWithVCMO.base.Initialization.call(this, jsonData);
    this.VCMO = jsonData.VCMO;
    this.SelectedVCMO = getArrayKeyValueWithTrue();
};
BaseWithVCMO.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedVCMO.key + "-" + this.Frequency.key;
    return result;
};
BaseWithVCMO.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedVCMO);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
BaseWithVCMO.prototype.AllOptionsSelected = function() {
    if (BaseWithVCMO.base.AllOptionsSelected.call(this)) {
        if (this.SelectedVCMO.key !== undefined)
            return true;
        else return false;
    } else return false;
};
BaseWithVCMO.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedVCMO.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
BaseWithVCMO.prototype.Validate = function() {
    var result = BaseWithVCMO.base.Validate.call(this);
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
        return false;
    } else return result;
};
BaseWithVCMO.prototype.ToString = function() {
    var clipboardValue = BaseWithVCMO.base.ToString.call(this);
    clipboardValue += "VCMO:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedVCMO, this.VCMO)) + "\n";
    return clipboardValue;
};
BaseWithVCMO.prototype.GetHTML = function(selector) {
    BaseWithVCMO.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.VCMO, this.VCMO, "vcmo", this, "OnVCMOChanged", this.Mode);
};
BaseWithVCMO.prototype.OnVCMOChanged = function() {
    this.SelectedVCMO.key = getCheckedValue(this.SelectedVCMO.key, 'vcmo');
};
BaseWithVCMO.prototype.SelectOptions = function() {
    BaseWithVCMO.base.SelectOptions.call(this);
    SelectOption("vcmo", this.SelectedVCMO.key);
};


function VCMOWithSignalingType() {
    if (arguments[0] === inheriting) return;
    BaseWithVCMO.call(this);
    var SelectedSignalingType;
};
VCMOWithSignalingType.prototype = new BaseWithVCMO(inheriting);
VCMOWithSignalingType.base = BaseWithVCMO.prototype;
VCMOWithSignalingType.prototype.Initialization = function(jsonData) {
    VCMOWithSignalingType.base.Initialization.call(this, jsonData);
    this.SignalingType = jsonData.SignalingType;
    this.SelectedSignalingType = getArrayKeyValueWithTrue();
};
VCMOWithSignalingType.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignalingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedVCMO.key + "-" + this.Frequency.key;
    return result;
};
VCMOWithSignalingType.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignalingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedVCMO);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
VCMOWithSignalingType.prototype.AllOptionsSelected = function() {
    if (VCMOWithSignalingType.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSignalingType.key !== undefined)
            return true;
        else return false;
    } else return false;
};
VCMOWithSignalingType.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignalingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedVCMO.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
VCMOWithSignalingType.prototype.Validate = function() {
    var result = VCMOWithSignalingType.base.Validate.call(this);
    if (!IsInArray(this.SelectedSignalingType, this.SignalingType)) {
        this.validationErrors.push(this.SelectedSignalingType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSignalingType.value = false;
        return false;
    } else return result;
};
VCMOWithSignalingType.prototype.ToString = function() {
    var clipboardValue = VCMOWithSignalingType.base.ToString.call(this);
    clipboardValue += "SignallingType:     " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSignalingType, this.SignalingType)) + "\n";
    return clipboardValue;
};
VCMOWithSignalingType.prototype.GetHTML = function(selector) {
    VCMOWithSignalingType.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SignallingType, this.SignalingType, "signaling", this, "OnSelectedSignalingTypeChanged", this.Mode);
};
VCMOWithSignalingType.prototype.OnSelectedSignalingTypeChanged = function() {
    this.SelectedSignalingType.key = getCheckedValue(this.SelectedSignalingType.key, 'signaling');
};
VCMOWithSignalingType.prototype.SelectOptions = function() {
    VCMOWithSignalingType.base.SelectOptions.call(this);
    SelectOption("signaling", this.SelectedSignalingType.key);
};

function SignalingTypeWithSwing() {
    if (arguments[0] === inheriting) return;
    VCMOWithSignalingType.call(this);
    var SelectedSwing;
};
SignalingTypeWithSwing.prototype = new VCMOWithSignalingType(inheriting);
SignalingTypeWithSwing.base = VCMOWithSignalingType.prototype;
SignalingTypeWithSwing.prototype.Initialization = function(jsonData) {
    SignalingTypeWithSwing.base.Initialization.call(this, jsonData);
    this.Swing = jsonData.Swing;
    this.SelectedSwing = getArrayKeyValueWithTrue();
};
SignalingTypeWithSwing.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignalingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedSwing +
        this.SelectedSupplyVoltage.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
SignalingTypeWithSwing.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignalingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSwing);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignalingTypeWithSwing.prototype.AllOptionsSelected = function() {
    if (SignalingTypeWithSwing.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSwing.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SignalingTypeWithSwing.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignalingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSwing.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedVCMO.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
SignalingTypeWithSwing.prototype.Validate = function() {
    var result = SignalingTypeWithSwing.base.Validate.call(this);
    if (!IsInArray(this.SelectedSwing, this.Swing)) {
        this.validationErrors.push(this.SelectedSwing.key + " - " + getErrorByErrorID("37"));
        this.SelectedSwing.value = false;
        return false;
    } else return result;
};
SignalingTypeWithSwing.prototype.ToString = function() {
    var clipboardValue = SignalingTypeWithSwing.base.ToString.call(this);
    clipboardValue += "Swing:              " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSwing, this.Swing)) + "\n";
    return clipboardValue;
};
SignalingTypeWithSwing.prototype.GetHTML = function(selector) {
    SignalingTypeWithSwing.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.Swing, this.Swing, "swing", this, "OnSelectedSwingChanged", this.Mode);
};
SignalingTypeWithSwing.prototype.OnSelectedSwingChanged = function() {
    this.SelectedSwing.key = getCheckedValue(this.SelectedSwing.key, 'swing');
};
SignalingTypeWithSwing.prototype.SelectOptions = function() {
    SignalingTypeWithSwing.base.SelectOptions.call(this);
    SelectOption("swing", this.SelectedSwing.key);
};

function BaseWithACDCCoupling() {
    if (arguments[0] === inheriting) return;
    BaseViewModel.call(this);
    var SelectedACDCCoupling;
};
BaseWithACDCCoupling.prototype = new BaseViewModel(inheriting);
BaseWithACDCCoupling.base = BaseViewModel.prototype;
BaseWithACDCCoupling.prototype.Initialization = function(jsonData) {
    BaseWithACDCCoupling.base.Initialization.call(this, jsonData);
    this.ACDCCoupling = jsonData.ACDCCoupling;
    this.SelectedACDCCoupling = getArrayKeyValueWithTrue();
};
BaseWithACDCCoupling.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedACDCCoupling.key;
    return result;
};
BaseWithACDCCoupling.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedACDCCoupling);
    return result;
};
BaseWithACDCCoupling.prototype.AllOptionsSelected = function() {
    if (this.OutputDriverStrength.length === 1 && this.Mode == "Generator")
        this.SelectedOutputDriverStrength.key = "-";
    val = this.SelectedRevisionLetter.key &&
        this.SelectedTemperatureRange.key &&
        this.SelectedOutputDriverStrength.key &&
        this.SelectedPackageSize.key &&
        this.SelectedPackaging.key &&
        this.SelectedACDCCoupling.key &&
        this.SelectedFrequencyStability.key;
    if (val !== undefined)
        return true;
    else
        return false;
};
BaseWithACDCCoupling.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedOutputDriverStrength, this.OutputDriverStrength)) {
        this.validationErrors.push(this.SelectedOutputDriverStrength.key + " - " + getErrorByErrorID("26"));
        this.SelectedOutputDriverStrength.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
        this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
        this.SelectedPackaging.value = false;
    }
    if (!IsInArray(this.SelectedACDCCoupling, this.ACDCCoupling)) {
        this.validationErrors.push(this.SelectedACDCCoupling.key + " - " + getErrorByErrorID("14"));
        this.SelectedACDCCoupling.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (this.Mode == "Decoder") {
        if (!IsInArray(this.SelectedDivider2, this.Divider2)) {
            this.validationErrors.push(this.SelectedDivider2.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider2.value = false;
        }
        if (!IsInArray(this.SelectedDivider3, this.Divider3)) {
            this.validationErrors.push(this.SelectedDivider3.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider3.value = false;
        }
    }

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};
BaseWithACDCCoupling.prototype.ToString = function() {
    var clipboardValue = "";
    clipboardValue += "Part Number:         " + "\t\t" + this.Generate() + "\n";
    clipboardValue += "Part Family:         " + "\t\t" + this.PartFamily + "(" + this.FamilyDescription + ")\n";
    clipboardValue += "Frequency:           " + "\t\t" + this.Frequency.key + " KHz\n";
    clipboardValue += "Revision Letter:     " + "\t\t" + this.SelectedRevisionLetter.key + "\n";
    clipboardValue += "Temperature Range:   " + "\t\t" + GetValueFromArray(this.SelectedTemperatureRange, this.TemperatureRange) + "C\n";
    clipboardValue += "Frequency Stability: " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedFrequencyStability, this.FrequencyStability)) + " ppm\n";
    clipboardValue += "AC/DC Coupling:      " + "\t\t" + GetValueFromArray(this.SelectedACDCCoupling, this.ACDCCoupling) + "\n";
    clipboardValue += "Package Size:        " + "\t\t" + GetValueFromArray(this.SelectedPackageSize, this.PackageSize) + " mm\n";
    return clipboardValue;
};
BaseWithACDCCoupling.prototype.PaintHTML = function(selector) {
    if (this.Mode == "Generator" || this.Mode == "Mixed") {
        $(selector).append($("<tr/>").append($("<td colspan='2'/>").append(getPartFamilyLabel(" Part Number Generator", this.PartFamily))));
    } else {
        if (this.FamilyDescription !== "" && this.FamilyDescription !== undefined) {
            $(selector).append(getFamilyDescription(this.FamilyDescription));
        }
    }

    $(selector).append(getFrequencyResource(this.Frequency, this.Mode, this, true));
    this.GetHTML(selector);
    AppendHTMLResource(selector, Headers.Packaging, this.Packaging, "packaging", this, "OnPackagingChanged", this.Mode);

    if (this.Mode == "Generator" || this.Mode == "Mixed") {
        $(selector).after(getGeneratorFooter());
    }
};
BaseWithACDCCoupling.prototype.GetHTML = function(selector) {
    AppendHTMLResource(selector, Headers.FrequencyStability, this.FrequencyStability, "tolerance", this, "OnFrequencyStabilityChanged", this.Mode);
    if (this.ListOfFrequency != undefined) {
        $(selector).append(AddListOfFrequencyInGenerator(this));
        initContextMenu(selector);
    }
    AppendHTMLResource(selector, Headers.TemperatureRange, this.TemperatureRange, "temprange", this, "OnTemperatureRangeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.PackageSize, this.PackageSize, "package", this, "OnPackageSizeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.ACDCCoupling, this.ACDCCoupling, "acdccoupling", this, "OnACDCCouplingChanged", this.Mode);
};
BaseWithACDCCoupling.prototype.OnACDCCouplingChanged = function() {
    this.SelectedACDCCoupling.key = getCheckedValue(this.SelectedACDCCoupling.key, 'acdccoupling');
};
BaseWithACDCCoupling.prototype.SelectOptions = function() {
    SelectOption("tolerance", this.SelectedFrequencyStability.key);
    SelectOption("temprange", this.SelectedTemperatureRange.key);
    SelectOption("acdccoupling", this.SelectedACDCCoupling.key);
    SelectOption("package", this.SelectedPackageSize.key);
    SelectOption("packaging", this.SelectedPackaging.key);
};

function ACDCCouplingWithOutputVoh() {
    if (arguments[0] === inheriting) return;
    BaseWithACDCCoupling.call(this);
    var SelectedOutputVoh;
};
ACDCCouplingWithOutputVoh.prototype = new BaseWithACDCCoupling(inheriting);
ACDCCouplingWithOutputVoh.base = BaseWithACDCCoupling.prototype;
ACDCCouplingWithOutputVoh.prototype.Initialization = function(jsonData) {
    ACDCCouplingWithOutputVoh.base.Initialization.call(this, jsonData);
    this.OutputVoh = jsonData.OutputVoh;
    this.OutputVohList1 = jsonData.OutputVohList1;
    this.OutputVohList2 = jsonData.OutputVohList2;
    this.SelectedOutputVoh = getArrayKeyValueWithTrue();

    if (this.OutputVohList1 != undefined) {
        this.OutputVoh = this.OutputVohList1;
    }
    //this.MinPartLenght = 16;
};
ACDCCouplingWithOutputVoh.prototype.Generate = function() {
    var result = ACDCCouplingWithOutputVoh.base.Generate.call(this);
    result += this.SelectedOutputVoh.key;
    return result;
};
ACDCCouplingWithOutputVoh.prototype.GenerateForColor = function() {
    var result = ACDCCouplingWithOutputVoh.base.GenerateForColor.call(this);
    result.push(this.SelectedOutputVoh);
    return result;
};
ACDCCouplingWithOutputVoh.prototype.AllOptionsSelected = function() {
    if (ACDCCouplingWithOutputVoh.base.AllOptionsSelected.call(this)) {
        if (this.SelectedOutputVoh.key !== undefined)
            return true;
        else return false;
    } else return false;
};
ACDCCouplingWithOutputVoh.prototype.Validate = function() {
    var result = ACDCCouplingWithOutputVoh.base.Validate.call(this);
    if (!IsInArray(this.SelectedOutputVoh, this.OutputVoh)) {
        this.validationErrors.push(this.SelectedOutputVoh.key + " - " + getErrorByErrorID("27"));
        this.SelectedOutputVoh.value = false;
        return false;
    } else return result;
};
ACDCCouplingWithOutputVoh.prototype.ToString = function() {
    var clipboardValue = ACDCCouplingWithOutputVoh.base.ToString.call(this);
    clipboardValue += "Output Voh:          " + "\t\t" + GetValueFromArray(this.SelectedOutputVoh, this.OutputVoh) + " mV\n";
    return clipboardValue;
};
ACDCCouplingWithOutputVoh.prototype.GetHTML = function(selector) {
    ACDCCouplingWithOutputVoh.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.OutputVoh, this.OutputVoh, "outputvoh", this, "OnOutputVohChanged", this.Mode);
};
ACDCCouplingWithOutputVoh.prototype.OnOutputVohChanged = function() {
    this.SelectedOutputVoh.key = getCheckedValue(this.SelectedOutputVoh.key, 'outputvoh');
};
ACDCCouplingWithOutputVoh.prototype.SelectOptions = function() {
    ACDCCouplingWithOutputVoh.base.SelectOptions.call(this);
    SelectOption("outputvoh", this.SelectedOutputVoh.key);
};

function OutputVohWithOutputVol() {
    if (arguments[0] === inheriting) return;
    ACDCCouplingWithOutputVoh.call(this);
    var SelectedOutputVol;
};
OutputVohWithOutputVol.prototype = new ACDCCouplingWithOutputVoh(inheriting);
OutputVohWithOutputVol.base = ACDCCouplingWithOutputVoh.prototype;
OutputVohWithOutputVol.prototype.Initialization = function(jsonData) {
    OutputVohWithOutputVol.base.Initialization.call(this, jsonData);
    this.OutputVol = jsonData.OutputVol;
    this.OutputVolList1 = jsonData.OutputVolList1;
    this.OutputVolList2 = jsonData.OutputVolList2;
    this.SelectedOutputVol = getArrayKeyValueWithTrue();
    if (this.OutputVolList1 != undefined) {
        this.OutputVol = this.OutputVolList1;
    }


    //this.MinPartLenght = 16;
};
OutputVohWithOutputVol.prototype.Generate = function() {
    var result = OutputVohWithOutputVol.base.Generate.call(this);
    result += this.SelectedOutputVol.key + "-" + this.Frequency.key;;
    return result;
};
OutputVohWithOutputVol.prototype.GenerateForColor = function() {
    var result = OutputVohWithOutputVol.base.GenerateForColor.call(this);
    result.push(this.SelectedOutputVol);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
OutputVohWithOutputVol.prototype.AllOptionsSelected = function() {
    if (OutputVohWithOutputVol.base.AllOptionsSelected.call(this)) {
        if (this.SelectedOutputVol.key !== undefined)
            return true;
        else return false;
    } else return false;
};
OutputVohWithOutputVol.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedACDCCoupling.key = partNumber.substring(13, 14);
    this.SelectedOutputVoh.key = partNumber.substring(14, 15);
    this.SelectedOutputVol.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
OutputVohWithOutputVol.prototype.Validate = function() {
    var result = OutputVohWithOutputVol.base.Validate.call(this);
    if (!IsInArray(this.SelectedOutputVol, this.OutputVol)) {
        this.validationErrors.push(this.SelectedOutputVol.key + " - " + getErrorByErrorID("28"));
        this.SelectedOutputVol.value = false;
        return false;
    } else return result;
};
OutputVohWithOutputVol.prototype.ToString = function() {
    var clipboardValue = OutputVohWithOutputVol.base.ToString.call(this);
    clipboardValue += "Output Vol:          " + "\t\t" + GetValueFromArray(this.SelectedOutputVol, this.OutputVol) + " mV\n";
    return clipboardValue;
};
OutputVohWithOutputVol.prototype.GetHTML = function(selector) {
    OutputVohWithOutputVol.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.OutputVol, this.OutputVol, "outputvol", this, "OnOutputVolChanged", this.Mode);
};
OutputVohWithOutputVol.prototype.OnOutputVolChanged = function() {
    this.SelectedOutputVol.key = getCheckedValue(this.SelectedOutputVol.key, 'outputvol');
};
OutputVohWithOutputVol.prototype.SelectOptions = function() {
    OutputVohWithOutputVol.base.SelectOptions.call(this);
    SelectOption("outputvol", this.SelectedOutputVol.key);
};



function OutputVohWithOutputVolWithVoltage() {
    if (arguments[0] === inheriting) return;
    OutputVohWithOutputVol.call(this);
    var SelectedSupplyVoltage;
    //var SelectedFeaturePin;
};
OutputVohWithOutputVolWithVoltage.prototype = new OutputVohWithOutputVol(inheriting);
OutputVohWithOutputVolWithVoltage.base = OutputVohWithOutputVol.prototype;
OutputVohWithOutputVolWithVoltage.prototype.Initialization = function(jsonData) {
    OutputVohWithOutputVolWithVoltage.base.Initialization.call(this, jsonData);

    this.SupplyVoltage = jsonData.SupplyVoltage;
    this.SelectedSupplyVoltage = getArrayKeyValueWithTrue();

    //this.MinPartLenght = 16;
};
OutputVohWithOutputVolWithVoltage.prototype.Generate = function() {
    var result = BaseViewModel.base.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" + this.SelectedSupplyVoltag.key + "-" + this.SelectedACDCCoupling.key + this.SelectedOutputVoh.key +
        this.SelectedOutputVol.key + "-" + this.Frequency.key;
    return result;
};
OutputVohWithOutputVolWithVoltage.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedDivider3);
    result.push(this.SelectedACDCCoupling);
    result.push(this.SelectedOutputVoh);
    result.push(this.SelectedOutputVol);
    result.push(this.SelectedDivider4);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
OutputVohWithOutputVolWithVoltage.prototype.AllOptionsSelected = function() {
    if (OutputVohWithOutputVolWithVoltage.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSupplyVoltage.key !== undefined)
            return true;
        else return false;
    } else return false;
};
OutputVohWithOutputVolWithVoltage.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedDivider3.key = partNumber.substring(15, 16);
    this.SelectedACDCCoupling.key = partNumber.substring(16, 17);
    this.SelectedOutputVoh.key = partNumber.substring(17, 18);
    this.SelectedOutputVol.key = partNumber.substring(18, 19);
    this.SelectedDivider4.key = partNumber.substring(19, 20);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(20, endFrequency);
};

OutputVohWithOutputVolWithVoltage.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedOutputDriverStrength, this.OutputDriverStrength)) {
        this.validationErrors.push(this.SelectedOutputDriverStrength.key + " - " + getErrorByErrorID("26"));
        this.SelectedOutputDriverStrength.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedACDCCoupling, this.ACDCCoupling)) {
        this.validationErrors.push(this.SelectedACDCCoupling.key + " - " + getErrorByErrorID("14"));
        this.SelectedACDCCoupling.value = false;
    }
    if (!IsInArray(this.SelectedOutputVoh, this.OutputVoh)) {
        this.validationErrors.push(this.SelectedOutputVoh.key + " - " + getErrorByErrorID("27"));
        this.SelectedOutputVoh.value = false;
    }
    if (!IsInArray(this.SelectedOutputVol, this.OutputVol)) {
        this.validationErrors.push(this.SelectedOutputVol.key + " - " + getErrorByErrorID("28"));
        this.SelectedOutputVol.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (this.PackagingList1 != undefined) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.Mode == "Decoder") {
        if (!IsInArray(this.SelectedDivider2, this.Divider2)) {
            this.validationErrors.push(this.SelectedDivider2.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider2.value = false;
        }
        if (!IsInArray(this.SelectedDivider3, this.Divider3)) {
            this.validationErrors.push(this.SelectedDivider3.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider3.value = false;
        }
        if (!IsInArray(this.SelectedDivider4, this.Divider4)) {
            this.validationErrors.push(this.SelectedDivider4.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider4.value = false;
        }
    }

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};
OutputVohWithOutputVolWithVoltage.prototype.ToString = function() {
    var clipboardValue = OutputVohWithOutputVolWithVoltage.base.ToString.call(this);
    clipboardValue += "Supply Voltage:          " + "\t\t" + GetValueFromArray(this.SelectedSupplyVoltage, this.SupplyVoltage) + " mV\n";
    return clipboardValue;
};
OutputVohWithOutputVolWithVoltage.prototype.GetHTML = function(selector) {
    OutputVohWithOutputVolWithVoltage.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SupplyVoltage, this.SupplyVoltage, "voltage", this, "OnSupplyVoltageChanged", this.Mode);
};
OutputVohWithOutputVolWithVoltage.prototype.OnSupplyVoltageChanged = function() {
    this.SelectedSupplyVoltage.key = getCheckedValue(this.SelectedSupplyVoltage.key, 'voltage');
};
OutputVohWithOutputVolWithVoltage.prototype.SelectOptions = function() {
    OutputVohWithOutputVolWithVoltage.base.SelectOptions.call(this);
    SelectOption("voltage", this.SelectedSupplyVoltage.key);
};



function OutputVohWithOutputVolWithVoltagePin() {
    if (arguments[0] === inheriting) return;
    OutputVohWithOutputVolWithVoltage.call(this);

    var SelectedFeaturePin;
};
OutputVohWithOutputVolWithVoltagePin.prototype = new OutputVohWithOutputVolWithVoltage(inheriting);
OutputVohWithOutputVolWithVoltagePin.base = OutputVohWithOutputVolWithVoltage.prototype;
OutputVohWithOutputVolWithVoltagePin.prototype.Initialization = function(jsonData) {
    OutputVohWithOutputVolWithVoltagePin.base.Initialization.call(this, jsonData);

    this.FeaturePin = jsonData.FeaturePin;

    this.SelectedFeaturePin = getArrayKeyValueWithTrue();

    //this.MinPartLenght = 16;
};
OutputVohWithOutputVolWithVoltagePin.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" + this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + "-" + this.SelectedACDCCoupling.key + this.SelectedOutputVoh.key + this.SelectedOutputVol.key + "-" + this.Frequency.key;
    return result;
};
OutputVohWithOutputVolWithVoltagePin.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedDivider3);
    result.push(this.SelectedACDCCoupling);
    result.push(this.SelectedOutputVoh);
    result.push(this.SelectedOutputVol);
    result.push(this.SelectedDivider4);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
OutputVohWithOutputVolWithVoltagePin.prototype.AllOptionsSelected = function() {
    if (OutputVohWithOutputVolWithVoltagePin.base.AllOptionsSelected.call(this)) {
        if (this.SelectedFeaturePin.key !== undefined)
            return true;
        else return false;
    } else return false;
};
OutputVohWithOutputVolWithVoltagePin.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    this.SelectedACDCCoupling.key = partNumber.substring(17, 18);
    this.SelectedOutputVoh.key = partNumber.substring(18, 19);
    this.SelectedOutputVol.key = partNumber.substring(19, 20);
    this.SelectedDivider4.key = partNumber.substring(20, 21);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(21, endFrequency);
};
OutputVohWithOutputVolWithVoltagePin.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedOutputDriverStrength, this.OutputDriverStrength)) {
        this.validationErrors.push(this.SelectedOutputDriverStrength.key + " - " + getErrorByErrorID("26"));
        this.SelectedOutputDriverStrength.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }

    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedFeaturePin, this.FeaturePin)) {
        this.validationErrors.push(this.SelectedFeaturePin.key + " - " + getErrorByErrorID("25"));
        this.SelectedFeaturePin.value = false;
    }

    if (!IsInArray(this.SelectedACDCCoupling, this.ACDCCoupling)) {
        this.validationErrors.push(this.SelectedACDCCoupling.key + " - " + getErrorByErrorID("14"));
        this.SelectedACDCCoupling.value = false;
    }
    if (!IsInArray(this.SelectedOutputVoh, this.OutputVoh)) {
        this.validationErrors.push(this.SelectedOutputVoh.key + " - " + getErrorByErrorID("27"));
        this.SelectedOutputVoh.value = false;
    }
    if (!IsInArray(this.SelectedOutputVol, this.OutputVol)) {
        this.validationErrors.push(this.SelectedOutputVol.key + " - " + getErrorByErrorID("28"));
        this.SelectedOutputVol.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (this.PackagingList1 != undefined) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.Mode == "Decoder") {
        if (!IsInArray(this.SelectedDivider2, this.Divider2)) {
            this.validationErrors.push(this.SelectedDivider2.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider2.value = false;
        }
        if (!IsInArray(this.SelectedDivider3, this.Divider3)) {
            this.validationErrors.push(this.SelectedDivider3.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider3.value = false;
        }
        if (!IsInArray(this.SelectedDivider4, this.Divider4)) {
            this.validationErrors.push(this.SelectedDivider4.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider4.value = false;
        }
    }

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};
OutputVohWithOutputVolWithVoltagePin.prototype.ToString = function() {
    var clipboardValue = OutputVohWithOutputVolWithVoltagePin.base.ToString.call(this);
    clipboardValue += "Feauture Pin:          " + "\t\t" + GetValueFromArray(this.SelectedFeaturePin, this.FeaturePin) + " mV\n";
    return clipboardValue;
};
OutputVohWithOutputVolWithVoltagePin.prototype.GetHTML = function(selector) {
    OutputVohWithOutputVolWithVoltagePin.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.ControlPin, this.FeaturePin, "pin", this, "OnControlPinChanged", this.Mode);
};
OutputVohWithOutputVolWithVoltagePin.prototype.OnControlPinChanged = function() {
    this.SelectedFeaturePin.key = getCheckedValue(this.SelectedFeaturePin.key, 'pin');
};
OutputVohWithOutputVolWithVoltagePin.prototype.SelectOptions = function() {
    OutputVohWithOutputVolWithVoltagePin.base.SelectOptions.call(this);
    SelectOption("pin", this.SelectedFeaturePin.key);
};


function BaseWithPin() {
    if (arguments[0] === inheriting) return;
    BaseViewModel.call(this);
    var SelectedFeaturePin;
};
BaseWithPin.prototype = new BaseViewModel(inheriting);
BaseWithPin.base = BaseViewModel.prototype;
BaseWithPin.prototype.Initialization = function(jsonData) {
    BaseWithPin.base.Initialization.call(this, jsonData);
    this.FeaturePin = jsonData.FeaturePin;
    this.SelectedFeaturePin = getArrayKeyValueWithTrue();

    this.FeaturePinList1 = jsonData.FeaturePinList1;
    this.FeaturePinList2 = jsonData.FeaturePinList2;

    if (this.FeaturePinList1 != undefined) {
        this.FeaturePin = this.FeaturePinList1;
    }
};
BaseWithPin.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + "-" + this.Frequency.key;
    return result;
};
BaseWithPin.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
BaseWithPin.prototype.AllOptionsSelected = function() {
    if (BaseWithPin.base.AllOptionsSelected.call(this)) {
        if (this.SelectedFeaturePin.key !== undefined)
            return true;
        else return false;
    } else return false;
};
BaseWithPin.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
BaseWithPin.prototype.Validate = function() {
    var result = BaseWithPin.base.Validate.call(this);
    if (!IsInArray(this.SelectedFeaturePin, this.FeaturePin)) {
        this.validationErrors.push(this.SelectedFeaturePin.key + " - " + getErrorByErrorID("8"));
        this.SelectedFeaturePin.value = false;
        return false;
    } else return result;
};
BaseWithPin.prototype.ToString = function() {
    var clipboardValue = BaseWithPin.base.ToString.call(this);
    clipboardValue += "Feature Pin:         " + "\t\t" + GetValueFromArray(this.SelectedFeaturePin, this.FeaturePin) + "\n";
    return clipboardValue;
};
BaseWithPin.prototype.GetHTML = function(selector) {
    BaseWithPin.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.ControlPin, this.FeaturePin, "pin", this, "OnControlPinChanged", this.Mode);
};
BaseWithPin.prototype.OnControlPinChanged = function() {
    this.SelectedFeaturePin.key = getCheckedValue(this.SelectedFeaturePin.key, 'pin');
};
BaseWithPin.prototype.SelectOptions = function() {
    BaseWithPin.base.SelectOptions.call(this);
    SelectOption("pin", this.SelectedFeaturePin.key);
};


function FeaturePinWithSpecialFeatures() {
    if (arguments[0] === inheriting) return;
    BaseWithPin.call(this);
    var SelectedSpecialFeatures;
};
FeaturePinWithSpecialFeatures.prototype = new BaseWithPin(inheriting);
FeaturePinWithSpecialFeatures.base = BaseWithPin.prototype;
FeaturePinWithSpecialFeatures.prototype.Initialization = function(jsonData) {
    FeaturePinWithSpecialFeatures.base.Initialization.call(this, jsonData);
    this.SpecialFeatures = jsonData.SpecialFeatures;
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
};
FeaturePinWithSpecialFeatures.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedSpecialFeatures.key + this.Frequency.key;
    return result;
};
FeaturePinWithSpecialFeatures.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedSpecialFeatures);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
FeaturePinWithSpecialFeatures.prototype.AllOptionsSelected = function() {
    if (FeaturePinWithSpecialFeatures.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpecialFeatures.key !== undefined)
            return true;
        else return false;
    } else return false;
};
FeaturePinWithSpecialFeatures.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedSpecialFeatures.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
FeaturePinWithSpecialFeatures.prototype.Validate = function() {
    var result = FeaturePinWithSpecialFeatures.base.Validate.call(this);
    if (!IsInArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) {
        this.validationErrors.push(this.SelectedSpecialFeatures.key + " - " + getErrorByErrorID("44"));
        this.SelectedSpecialFeatures.value = false;
        return false;
    } else return result;
};
FeaturePinWithSpecialFeatures.prototype.ToString = function() {
    var clipboardValue = FeaturePinWithSpecialFeatures.base.ToString.call(this);
    clipboardValue += "Special Features:      " + "\t\t" + GetValueFromArray(this.SelectedSpecialFeatures, this.SpecialFeatures) + "\n";
    return clipboardValue;
};
FeaturePinWithSpecialFeatures.prototype.GetHTML = function(selector) {
    FeaturePinWithSpecialFeatures.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpecialFeatures, this.SpecialFeatures, "specialfeatures", this, "OnSpecialFeaturesChanged", this.Mode);
};
FeaturePinWithSpecialFeatures.prototype.OnSpecialFeaturesChanged = function() {
    this.SelectedSpecialFeatures.key = getCheckedValue(this.SelectedSpecialFeatures.key, 'specialfeatures');
};
FeaturePinWithSpecialFeatures.prototype.SelectOptions = function() {
    FeaturePinWithSpecialFeatures.base.SelectOptions.call(this);
    SelectOption("specialfeatures", this.SelectedSpecialFeatures.key);
};



function FeaturePinWithSpreadSpectrum() {
    if (arguments[0] === inheriting) return;
    BaseWithPin.call(this);
    var SelectedSpreadSpectrum;
};
FeaturePinWithSpreadSpectrum.prototype = new BaseWithPin(inheriting);
FeaturePinWithSpreadSpectrum.base = BaseWithPin.prototype;
FeaturePinWithSpreadSpectrum.prototype.Initialization = function(jsonData) {
    FeaturePinWithSpreadSpectrum.base.Initialization.call(this, jsonData);
    this.SpreadSpectrum = jsonData.SpreadSpectrum;
    this.SelectedSpreadSpectrum = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 16;
};
FeaturePinWithSpreadSpectrum.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedSpreadSpectrum.key + "-" + this.Frequency.key;
    return result;
};
FeaturePinWithSpreadSpectrum.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedSpreadSpectrum);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
FeaturePinWithSpreadSpectrum.prototype.AllOptionsSelected = function() {
    if (FeaturePinWithSpreadSpectrum.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpreadSpectrum.key !== undefined)
            return true;
        else return false;
    } else return false;
};
FeaturePinWithSpreadSpectrum.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedSpreadSpectrum.key = partNumber.substring(16, 17);
    this.SelectedDivider3.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
FeaturePinWithSpreadSpectrum.prototype.Validate = function() {
    var result = FeaturePinWithSpreadSpectrum.base.Validate.call(this);
    if (!IsInArray(this.SelectedSpreadSpectrum, this.SpreadSpectrum)) {
        this.validationErrors.push(this.SelectedSpreadSpectrum.key + " - " + getErrorByErrorID("35"));
        this.SelectedSpreadSpectrum.value = false;
        return false;
    } else return result;
};
FeaturePinWithSpreadSpectrum.prototype.ToString = function() {
    var clipboardValue = FeaturePinWithSpreadSpectrum.base.ToString.call(this);
    clipboardValue += "Spread Spectrum:     " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSpreadSpectrum, this.SpreadSpectrum)) + "%" + "\n";
    return clipboardValue;
};
FeaturePinWithSpreadSpectrum.prototype.GetHTML = function(selector) {
    FeaturePinWithSpreadSpectrum.base.GetHTML.call(this, selector, this.Mode);
    if ((this.PartFamily != "SiT9005") && (this.PartFamily != "SiT9025") && (this.PartFamily != "SiT9045")) {
        AppendHTMLResource(selector, Headers.SpreadSpectrum, this.SpreadSpectrum, "spread", this, "OnSpreadSpectrumChanged", this.Mode);
    }
};
FeaturePinWithSpreadSpectrum.prototype.OnSpreadSpectrumChanged = function() {
    this.SelectedSpreadSpectrum.key = getCheckedValue(this.SelectedSpreadSpectrum.key, 'spread');

    this.SelectedSpreadSpectrum.group = getCheckedGroup(this.SelectedSpreadSpectrum.key, 'spread'); //added for 9005/25 for hiding spreadType/spreadspectrum options by group attr
};

FeaturePinWithSpreadSpectrum.prototype.SelectOptions = function() {
    FeaturePinWithSpreadSpectrum.base.SelectOptions.call(this);
    if ((this.PartFamily != "SiT9005") && (this.PartFamily != "SiT9025") && (this.PartFamily != "SiT9045"))

        SelectOption("spread", this.SelectedSpreadSpectrum.key);

    else {
        if (this.SelectedSpreadType.key == "-" && this.SelectedSpreadSpectrum.key == "-")
            this.SelectedSpreadType.group = "nospread";
        else if (this.SelectedSpreadType.key == "-" && this.SelectedSpreadSpectrum.key != "-")
            this.SelectedSpreadType.group = "center";
        SelectOptionWithGroup("spread", this.SelectedSpreadType.group, this.SelectedSpreadSpectrum.key);
    }
};

function BaseWithSpreadType() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithSpreadSpectrum.call(this);
    var SelectedSpreadType;

};
BaseWithSpreadType.prototype = new FeaturePinWithSpreadSpectrum(inheriting);
BaseWithSpreadType.base = FeaturePinWithSpreadSpectrum.prototype;
BaseWithSpreadType.prototype.Initialization = function(jsonData) {
    BaseWithSpreadType.base.Initialization.call(this, jsonData);
    this.SpreadType = jsonData.SpreadType;
    this.SelectedSpreadType = getArrayKeyValueWithTrue();
};
BaseWithSpreadType.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedSpreadType.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedSpreadSpectrum.key + this.Frequency.key;
    return result;
};
BaseWithSpreadType.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);

    result.push(this.SelectedSpreadType);
    //
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedSpreadSpectrum);

    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
BaseWithSpreadType.prototype.AllOptionsSelected = function() {
    if (BaseWithSpreadType.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpreadType.key !== undefined)
            return true;
        else return false;
    } else return false;
};
BaseWithSpreadType.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedSpreadType.key = partNumber.substring(12, 13);
    //this.SelectedDivider2.key = partNumber.substring(12,13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedSpreadSpectrum.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;

    if (this.SelectedSpreadType.key == "-" || this.SelectedSpreadType.key == "H" || this.SelectedSpreadType.key == "R")
        this.SelectedSpreadType.group = "center";
    else if (this.SelectedSpreadType.key == "D" || this.SelectedSpreadType.key == "Q" || this.SelectedSpreadType.key == "G")
        this.SelectedSpreadType.group = "down";

    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
BaseWithSpreadType.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedOutputDriverStrength, this.OutputDriverStrength)) {
        this.validationErrors.push(this.SelectedOutputDriverStrength.key + " - " + getErrorByErrorID("26"));
        this.SelectedOutputDriverStrength.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "E")) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "B")) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedFeaturePin, this.FeaturePin)) {
        this.validationErrors.push(this.SelectedFeaturePin.key + " - " + getErrorByErrorID("8"));
        this.SelectedFeaturePin.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (!IsInArray(this.SelectedSpreadType, this.SpreadType)) {
        this.validationErrors.push(this.SelectedSpreadType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSpreadType.value = false;
    }
    if (!IsInArray(this.SelectedSpreadSpectrum, this.SpreadSpectrum)) {
        this.validationErrors.push(this.SelectedSpreadSpectrum.key + " - " + getErrorByErrorID("35"));
        this.SelectedSpreadSpectrum.value = false;
    }
    if (this.Mode == "Decoder") {
        if (!IsInArray(this.SelectedDivider3, this.Divider3)) {
            this.validationErrors.push(this.SelectedDivider3.key + " - " + getErrorByErrorID("16"));
            this.SelectedDivider3.value = false;
        }
    }

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};
BaseWithSpreadType.prototype.ToString = function() {
    var clipboardValue = BaseWithSpreadType.base.ToString.call(this);
    clipboardValue += "SpreadType:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSpreadType, this.SpreadType)) + "\n";
    return clipboardValue;
};
BaseWithSpreadType.prototype.GetHTML = function(selector) {
    BaseWithSpreadType.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpreadType, this.SpreadType, "spreadtype", this, "OnSelectedSpreadTypeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.SpreadSpectrum, this.SpreadSpectrum, "spread", this, "OnSpreadSpectrumChanged", this.Mode);
};
BaseWithSpreadType.prototype.OnSelectedSpreadTypeChanged = function() {
    this.SelectedSpreadType.key = getCheckedValue(this.SelectedSpreadType.key, 'spreadtype');

    this.SelectedSpreadType.group = getCheckedGroup(this.SelectedSpreadType.key, 'spreadtype');
};
BaseWithSpreadType.prototype.SelectOptions = function() {
    BaseWithSpreadType.base.SelectOptions.call(this);

    if ((this.PartFamily != "SiT9005") && (this.PartFamily != "SiT9025") && (this.PartFamily != "SiT9045"))
        SelectOption("spreadtype", this.SelectedSpreadType.key);
    else
        SelectOptionWithGroup("spreadtype", this.SelectedSpreadType.group, this.SelectedSpreadType.key);

};


function FeaturePinWithVCMO() {
    if (arguments[0] === inheriting) return;
    BaseWithPin.call(this);
    var SelectedVCMO;
};
FeaturePinWithVCMO.prototype = new BaseWithPin(inheriting);
FeaturePinWithVCMO.base = BaseWithPin.prototype;
FeaturePinWithVCMO.prototype.Initialization = function(jsonData) {
    FeaturePinWithVCMO.base.Initialization.call(this, jsonData);
    this.VCMO = jsonData.VCMO;
    this.SelectedVCMO = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 16;
};
FeaturePinWithVCMO.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + "-" + this.Frequency.key;
    return result;
};
FeaturePinWithVCMO.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
FeaturePinWithVCMO.prototype.AllOptionsSelected = function() {
    if (FeaturePinWithVCMO.base.AllOptionsSelected.call(this)) {
        if (this.SelectedVCMO.key !== undefined)
            return true;
        else return false;
    } else return false;
};
FeaturePinWithVCMO.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedVCMO.key = partNumber.substring(16, 17);
    this.SelectedDivider3.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
FeaturePinWithVCMO.prototype.Validate = function() {
    var result = FeaturePinWithVCMO.base.Validate.call(this);
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
        return false;
    } else return result;
};
FeaturePinWithVCMO.prototype.ToString = function() {
    var clipboardValue = FeaturePinWithVCMO.base.ToString.call(this)
    clipboardValue += "VCMO:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedVCMO, this.VCMO)) + "\n";
    return clipboardValue;
};
FeaturePinWithVCMO.prototype.GetHTML = function(selector) {
    FeaturePinWithVCMO.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.VCMO, this.VCMO, "vcmo", this, "OnVCMOChanged", this.Mode);
};
FeaturePinWithVCMO.prototype.OnVCMOChanged = function() {
    this.SelectedVCMO.key = getCheckedValue(this.SelectedVCMO.key, 'vcmo');
};
FeaturePinWithVCMO.prototype.SelectOptions = function() {
    FeaturePinWithVCMO.base.SelectOptions.call(this);
    SelectOption("vcmo", this.SelectedVCMO.key);
};

function FeaturePinWithVCMO_NoFreqDivider() {
    if (arguments[0] === inheriting) return;
    BaseWithPin.call(this);
    var SelectedVCMO;
};
FeaturePinWithVCMO_NoFreqDivider.prototype = new BaseWithPin(inheriting);
FeaturePinWithVCMO_NoFreqDivider.base = BaseWithPin.prototype;
FeaturePinWithVCMO_NoFreqDivider.prototype.Initialization = function(jsonData) {
    FeaturePinWithVCMO_NoFreqDivider.base.Initialization.call(this, jsonData);
    this.VCMO = jsonData.VCMO;
    this.SelectedVCMO = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 16;
};
FeaturePinWithVCMO_NoFreqDivider.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
FeaturePinWithVCMO_NoFreqDivider.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
FeaturePinWithVCMO_NoFreqDivider.prototype.AllOptionsSelected = function() {
    if (FeaturePinWithVCMO_NoFreqDivider.base.AllOptionsSelected.call(this)) {
        if (this.SelectedVCMO.key !== undefined)
            return true;
        else return false;
    } else return false;
};
FeaturePinWithVCMO_NoFreqDivider.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDivider2.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedVCMO.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
FeaturePinWithVCMO_NoFreqDivider.prototype.Validate = function() {
    var result = FeaturePinWithVCMO_NoFreqDivider.base.Validate.call(this);
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
        return false;
    } else return result;
};
FeaturePinWithVCMO_NoFreqDivider.prototype.ToString = function() {
    var clipboardValue = FeaturePinWithVCMO_NoFreqDivider.base.ToString.call(this)
    clipboardValue += "VCMO:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedVCMO, this.VCMO)) + "\n";
    return clipboardValue;
};
FeaturePinWithVCMO_NoFreqDivider.prototype.GetHTML = function(selector) {
    FeaturePinWithVCMO_NoFreqDivider.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.VCMO, this.VCMO, "vcmo", this, "OnVCMOChanged", this.Mode);
};
FeaturePinWithVCMO_NoFreqDivider.prototype.OnVCMOChanged = function() {
    this.SelectedVCMO.key = getCheckedValue(this.SelectedVCMO.key, 'vcmo');
};
FeaturePinWithVCMO_NoFreqDivider.prototype.SelectOptions = function() {
    FeaturePinWithVCMO_NoFreqDivider.base.SelectOptions.call(this);
    SelectOption("vcmo", this.SelectedVCMO.key);
};


function Pin_VCMO_SignallingType() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithVCMO.call(this);
    var SelectedSignallingType;
};
Pin_VCMO_SignallingType.prototype = new FeaturePinWithVCMO(inheriting);
Pin_VCMO_SignallingType.base = FeaturePinWithVCMO.prototype;
Pin_VCMO_SignallingType.prototype.Initialization = function(jsonData) {
    Pin_VCMO_SignallingType.base.Initialization.call(this, jsonData);
    this.SignallingType = jsonData.SignallingType;
    this.SelectedSignallingType = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 16;
};
Pin_VCMO_SignallingType.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
Pin_VCMO_SignallingType.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider2);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
Pin_VCMO_SignallingType.prototype.AllOptionsSelected = function() {
    if (Pin_VCMO_SignallingType.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSignallingType.key !== undefined)
            return true;
        else return false;
    } else return false;
};
Pin_VCMO_SignallingType.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedDivider2.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    this.SelectedVCMO.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
Pin_VCMO_SignallingType.prototype.Validate = function() {
    var result = Pin_VCMO_SignallingType.base.Validate.call(this);
    if (!IsInArray(this.SelectedSignallingType, this.SignallingType)) {
        this.validationErrors.push(this.SelectedSignallingType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSignallingType.value = false;
        return false;
    } else return result;
};
Pin_VCMO_SignallingType.prototype.ToString = function() {
    var clipboardValue = Pin_VCMO_SignallingType.base.ToString.call(this)
    clipboardValue += "SignallingType:                " + "\t\t" + GetValueFromArray(this.SelectedSignallingType, this.SignallingType) + "\n";
    return clipboardValue;
};
Pin_VCMO_SignallingType.prototype.GetHTML = function(selector) {
    AppendHTMLResource(selector, Headers.SignallingType, this.SignallingType, "signaling", this, "OnSignallingTypeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.ControlPin, this.FeaturePin, "pin", this, "OnControlPinChanged", this.Mode);
    AppendHTMLResource(selector, Headers.VCMO, this.VCMO, "vcmo", this, "OnVCMOChanged", this.Mode);
    AppendHTMLResource(selector, Headers.FrequencyStability, this.FrequencyStability, "tolerance", this, "OnFrequencyStabilityChanged", this.Mode);
    AppendHTMLResource(selector, Headers.TemperatureRange, this.TemperatureRange, "temprange", this, "OnTemperatureRangeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.SupplyVoltage, this.SupplyVoltage, "voltage", this, "OnSupplyVoltageChanged", this.Mode);
    AppendHTMLResource(selector, Headers.PackageSize, this.PackageSize, "package", this, "OnPackageSizeChanged", this.Mode);
};
Pin_VCMO_SignallingType.prototype.OnSignallingTypeChanged = function() {
    this.SelectedSignallingType.key = getCheckedValue(this.SelectedSignallingType.key, 'signaling');
};
Pin_VCMO_SignallingType.prototype.SelectOptions = function() {
    Pin_VCMO_SignallingType.base.SelectOptions.call(this);
    SelectOption("signaling", this.SelectedSignallingType.key);
};


function DeviceAddressWithFeaturePin() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithVCMO.call(this);
    var SelectedDeviceAddress;
};
DeviceAddressWithFeaturePin.prototype = new FeaturePinWithVCMO(inheriting);
DeviceAddressWithFeaturePin.base = FeaturePinWithVCMO.prototype;
DeviceAddressWithFeaturePin.prototype.Initialization = function(jsonData) {
    DeviceAddressWithFeaturePin.base.Initialization.call(this, jsonData);
    this.DeviceAddress = jsonData.DeviceAddress;
    this.SelectedDeviceAddress = getArrayKeyValueWithTrue();
};
DeviceAddressWithFeaturePin.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedDeviceAddress.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + this.SelectedDivider3.key + this.Frequency.key;
    return result;
};
DeviceAddressWithFeaturePin.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDeviceAddress);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
DeviceAddressWithFeaturePin.prototype.AllOptionsSelected = function() {
    if (DeviceAddressWithFeaturePin.base.AllOptionsSelected.call(this)) {
        if (this.SelectedDeviceAddress.key !== undefined)
            return true;
        else return false;
    } else return false;
};
DeviceAddressWithFeaturePin.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDeviceAddress.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedVCMO.key = partNumber.substring(16, 17);
    this.SelectedDivider3.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
DeviceAddressWithFeaturePin.prototype.Validate = function() {
    var result = DeviceAddressWithFeaturePin.base.Validate.call(this);
    if (!IsInArray(this.SelectedDeviceAddress, this.DeviceAddress)) {
        this.validationErrors.push(this.SelectedDeviceAddress.key + " - " + getErrorByErrorID("17"));
        this.SelectedDeviceAddress.value = false;
        return false;
    } else return result;
};
DeviceAddressWithFeaturePin.prototype.ToString = function() {
    var clipboardValue = DeviceAddressWithFeaturePin.base.ToString.call(this);
    clipboardValue += "Device Address:      " + "\t\t" + GetValueFromArray(this.SelectedDeviceAddress, this.DeviceAddress) + "\n";
    return clipboardValue;
};
DeviceAddressWithFeaturePin.prototype.GetHTML = function(selector) {
    DeviceAddressWithFeaturePin.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.DeviceAddress, this.DeviceAddress, "daddress", this, "OnDeviceAddressChanged", this.Mode);
};
DeviceAddressWithFeaturePin.prototype.OnDeviceAddressChanged = function() {
    this.SelectedDeviceAddress.key = getCheckedValue(this.SelectedDeviceAddress.key, 'daddress');
};
DeviceAddressWithFeaturePin.prototype.SelectOptions = function() {
    DeviceAddressWithFeaturePin.base.SelectOptions.call(this);
    SelectOption("daddress", this.SelectedDeviceAddress.key);
};


function DeviceAddressWithFeaturePinAndSpecialFeatures() {
    if (arguments[0] === inheriting) return;
    DeviceAddressWithFeaturePin.call(this);
    var SelectedSpecialFeatures;
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype = new DeviceAddressWithFeaturePin(inheriting);
DeviceAddressWithFeaturePinAndSpecialFeatures.base = DeviceAddressWithFeaturePin.prototype;
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.Initialization = function(jsonData) {
    DeviceAddressWithFeaturePinAndSpecialFeatures.base.Initialization.call(this, jsonData);
    this.SpecialFeatures = jsonData.SpecialFeatures;
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedDeviceAddress.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + this.SelectedSpecialFeatures.key + this.Frequency.key;
    return result;
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDeviceAddress);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.SelectedSpecialFeatures);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.AllOptionsSelected = function() {
    if (DeviceAddressWithFeaturePinAndSpecialFeatures.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpecialFeatures.key !== undefined)
            return true;
        else return false;
    } else return false;
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDeviceAddress.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedVCMO.key = partNumber.substring(16, 17);
    this.SelectedSpecialFeatures.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.Validate = function() {
    var result = DeviceAddressWithFeaturePinAndSpecialFeatures.base.Validate.call(this);
    if (!IsInArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) {
        this.validationErrors.push(this.SelectedSpecialFeatures.key + " - " + getErrorByErrorID("44"));
        this.SelectedSpecialFeatures.value = false;
        return false;
    } else return result;
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.ToString = function() {
    var clipboardValue = DeviceAddressWithFeaturePinAndSpecialFeatures.base.ToString.call(this);
    clipboardValue += "Special Features:      " + "\t\t" + GetValueFromArray(this.SelectedSpecialFeatures, this.SpecialFeatures) + "\n";
    return clipboardValue;
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.GetHTML = function(selector) {
    DeviceAddressWithFeaturePinAndSpecialFeatures.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpecialFeatures, this.SpecialFeatures, "specialfeatures", this, "OnSpecialFeaturesChanged", this.Mode);
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.OnSpecialFeaturesChanged = function() {
    this.SelectedSpecialFeatures.key = getCheckedValue(this.SelectedSpecialFeatures.key, 'specialfeatures');
};
DeviceAddressWithFeaturePinAndSpecialFeatures.prototype.SelectOptions = function() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.base.SelectOptions.call(this);
    SelectOption("specialfeatures", this.SelectedSpecialFeatures.key);
};


function SpreadTypeWithFeaturePinAndSpecialFeatures() {
    if (arguments[0] === inheriting) return;
    BaseWithSpreadType.call(this);
    var SelectedSpecialFeatures;
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype = new BaseWithSpreadType(inheriting);
SpreadTypeWithFeaturePinAndSpecialFeatures.base = BaseWithSpreadType.prototype;
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.Initialization = function(jsonData) {
    SpreadTypeWithFeaturePinAndSpecialFeatures.base.Initialization.call(this, jsonData);
    this.SpecialFeatures = jsonData.SpecialFeatures;
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedSpreadType.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedSpreadSpectrum.key + this.SelectedSpecialFeatures.key + this.Frequency.key;
    return result;
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSpreadType);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedSpreadSpectrum);
    result.push(this.SelectedSpecialFeatures);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.AllOptionsSelected = function() {
    if (SpreadTypeWithFeaturePinAndSpecialFeatures.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpecialFeatures.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedSpreadType.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedSpreadSpectrum.key = partNumber.substring(16, 17);
    this.SelectedSpecialFeatures.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;

    if (this.SelectedSpreadType.key == "-" || this.SelectedSpreadType.key == "H" || this.SelectedSpreadType.key == "R")
        this.SelectedSpreadType.group = "center";
    else if (this.SelectedSpreadType.key == "D" || this.SelectedSpreadType.key == "Q" || this.SelectedSpreadType.key == "G")
        this.SelectedSpreadType.group = "down";

    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.Validate = function() {
    var result = SpreadTypeWithFeaturePinAndSpecialFeatures.base.Validate.call(this);
    if (!IsInArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) {
        this.validationErrors.push(this.SelectedSpecialFeatures.key + " - " + getErrorByErrorID("44"));
        this.SelectedSpecialFeatures.value = false;
        return false;
    } else return result;
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.ToString = function() {
    var clipboardValue = SpreadTypeWithFeaturePinAndSpecialFeatures.base.ToString.call(this);
    clipboardValue += "Special Features:      " + "\t\t" + GetValueFromArray(this.SelectedSpecialFeatures, this.SpecialFeatures) + "\n";
    return clipboardValue;
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.GetHTML = function(selector) {
    SpreadTypeWithFeaturePinAndSpecialFeatures.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpecialFeatures, this.SpecialFeatures, "specialfeatures", this, "OnSpecialFeaturesChanged", this.Mode);
};
SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.OnSpecialFeaturesChanged = function() {
    this.SelectedSpecialFeatures.key = getCheckedValue(this.SelectedSpecialFeatures.key, 'specialfeatures');
};

SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.OnSelectedSpreadTypeChanged = function() {
    this.SelectedSpreadType.key = getCheckedValue(this.SelectedSpreadType.key, 'spreadtype');

    this.SelectedSpreadType.group = getCheckedGroup(this.SelectedSpreadType.key, 'spreadtype');
};

SpreadTypeWithFeaturePinAndSpecialFeatures.prototype.SelectOptions = function() {
    SpreadTypeWithFeaturePinAndSpecialFeatures.base.SelectOptions.call(this);
    SelectOption("specialfeatures", this.SelectedSpecialFeatures.key);
};


function FeaturePinWithSignallingType() {
    if (arguments[0] === inheriting) return;
    BaseWithPin.call(this);
    var SelectedSignallingType;
};
FeaturePinWithSignallingType.prototype = new BaseWithPin(inheriting);
FeaturePinWithSignallingType.base = BaseWithPin.prototype;
FeaturePinWithSignallingType.prototype.Initialization = function(jsonData) {
    FeaturePinWithSignallingType.base.Initialization.call(this, jsonData);
    this.SignallingType = jsonData.SignallingType;
    this.SelectedSignallingType = getArrayKeyValueWithTrue();
}
FeaturePinWithSignallingType.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.Frequency.key;
    return result;
};
FeaturePinWithSignallingType.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider3);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
FeaturePinWithSignallingType.prototype.AllOptionsSelected = function() {
    if (FeaturePinWithSignallingType.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSignallingType.key !== undefined)
            return true;
        else return false;
    } else return false;
};
FeaturePinWithSignallingType.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedDivider3.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
FeaturePinWithSignallingType.prototype.Validate = function() {
    var result = FeaturePinWithSignallingType.base.Validate.call(this);
    if (!IsInArray(this.SelectedSignallingType, this.SignallingType)) {
        this.validationErrors.push(this.SelectedSignallingType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSignallingType.value = false;
        return false;
    } else return result;
};
FeaturePinWithSignallingType.prototype.ToString = function() {
    var clipboardValue = FeaturePinWithSignallingType.base.ToString.call(this);
    clipboardValue += "Signalling Type:     " + "\t\t" + GetValueFromArray(this.SelectedSignallingType, this.SignallingType) + "\n";
    return clipboardValue;
};
FeaturePinWithSignallingType.prototype.GetHTML = function(selector) {
    AppendHTMLResource(selector, Headers.SignallingType, this.SignallingType, "signaling", this, "OnSignallingTypeChanged", this.Mode);
    FeaturePinWithSignallingType.base.GetHTML.call(this, selector, this.Mode);
};
FeaturePinWithSignallingType.prototype.OnSignallingTypeChanged = function() {
    this.SelectedSignallingType.key = getCheckedValue(this.SelectedSignallingType.key, 'signaling');
};
FeaturePinWithSignallingType.prototype.SelectOptions = function() {
    FeaturePinWithSignallingType.base.SelectOptions.call(this);
    SelectOption("signaling", this.SelectedSignallingType.key);
};

function FeaturePinWithSignallingTypeAndSpecialFeatures() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithSignallingType.call(this);
    var SelectedSpecialFeatures;
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype = new FeaturePinWithSignallingType(inheriting);
FeaturePinWithSignallingTypeAndSpecialFeatures.base = FeaturePinWithSignallingType.prototype;
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.Initialization = function(jsonData) {
    FeaturePinWithSignallingTypeAndSpecialFeatures.base.Initialization.call(this, jsonData);
    this.SpecialFeatures = jsonData.SpecialFeatures;
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
}
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedSpecialFeatures.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.Frequency.key;
    return result;
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSpecialFeatures);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.AllOptionsSelected = function() {
    if (FeaturePinWithSignallingTypeAndSpecialFeatures.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpecialFeatures.key !== undefined)
            return true;
        else return false;
    } else return false;
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSpecialFeatures.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.Validate = function() {
    var result = FeaturePinWithSignallingTypeAndSpecialFeatures.base.Validate.call(this);
    if (!IsInArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) {
        this.validationErrors.push(this.SelectedSpecialFeatures.key + " - " + getErrorByErrorID("44"));
        this.SelectedSpecialFeatures.value = false;
        return false;
    } else return result;
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.ToString = function() {
    var clipboardValue = FeaturePinWithSignallingTypeAndSpecialFeatures.base.ToString.call(this);
    clipboardValue += "Special Features:      " + "\t\t" + GetValueFromArray(this.SelectedSpecialFeatures, this.SpecialFeatures) + "\n";
    return clipboardValue;
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.GetHTML = function(selector) {
    FeaturePinWithSignallingTypeAndSpecialFeatures.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpecialFeatures, this.SpecialFeatures, "specialfeatures", this, "OnSpecialFeaturesChanged", this.Mode);
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.OnSpecialFeaturesChanged = function() {
    this.SelectedSpecialFeatures.key = getCheckedValue(this.SelectedSpecialFeatures.key, 'specialfeatures');
};
FeaturePinWithSignallingTypeAndSpecialFeatures.prototype.SelectOptions = function() {
    FeaturePinWithSignallingTypeAndSpecialFeatures.base.SelectOptions.call(this);
    SelectOption("specialfeatures", this.SelectedSpecialFeatures.key);
};

function SignallingTypeWithVCMO() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithSignallingType.call(this);
    var SelectedVCMO;
};
SignallingTypeWithVCMO.prototype = new FeaturePinWithSignallingType(inheriting);
SignallingTypeWithVCMO.base = FeaturePinWithSignallingType.prototype;
SignallingTypeWithVCMO.prototype.Initialization = function(jsonData) {
    SignallingTypeWithVCMO.base.Initialization.call(this, jsonData);
    this.VCMO = jsonData.VCMO;
    this.SelectedVCMO = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 16;
}
SignallingTypeWithVCMO.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
SignallingTypeWithVCMO.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider3);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignallingTypeWithVCMO.prototype.AllOptionsSelected = function() {
    if (SignallingTypeWithVCMO.base.AllOptionsSelected.call(this)) {
        if (this.SelectedVCMO.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SignallingTypeWithVCMO.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedDivider3.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    this.SelectedVCMO.key = partNumber.substring(17, 18);

    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
SignallingTypeWithVCMO.prototype.Validate = function() {
    var result = SignallingTypeWithVCMO.base.Validate.call(this);
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
        return false;
    } else return result;
};
SignallingTypeWithVCMO.prototype.ToString = function() {
    var clipboardValue = SignallingTypeWithVCMO.base.ToString.call(this);
    clipboardValue += "VCMO           :     " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedVCMO, this.VCMO)) + "\n";
    return clipboardValue;
};
SignallingTypeWithVCMO.prototype.GetHTML = function(selector) {
    SignallingTypeWithVCMO.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.VCMO, this.VCMO, "vcmo", this, "OnVCMOChanged", this.Mode);
};
SignallingTypeWithVCMO.prototype.OnVCMOChanged = function() {
    this.SelectedVCMO.key = getCheckedValue(this.SelectedVCMO.key, 'vcmo');
};
SignallingTypeWithVCMO.prototype.SelectOptions = function() {
    SignallingTypeWithVCMO.base.SelectOptions.call(this);
    SelectOption("vcmo", this.SelectedVCMO.key);
};

function SignallingGroupWithReserved() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithSignallingType.call(this);
	var SelectedReserved;
    var SelectedSignallingGroup;
};
SignallingGroupWithReserved.prototype = new FeaturePinWithSignallingType(inheriting);
SignallingGroupWithReserved.base = FeaturePinWithSignallingType.prototype;
SignallingGroupWithReserved.prototype.Initialization = function(jsonData) {
    SignallingGroupWithReserved.base.Initialization.call(this, jsonData);
    this.Reserved = jsonData.Reserved;
    this.SignallingGroup = jsonData.SignallingGroup;
    this.SelectedReserved = getArrayKeyValueWithTrue();
    this.SelectedSignallingGroup = getArrayKeyValueWithTrue();
	this.SelectedOutputDriverStrength.key = "-"; // SelectedSpecialFeatures must be instead DriveStrength. So we must init this option here for decoder. (SiT9501)
    //this.MinPartLenght = 16;
}
SignallingGroupWithReserved.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
	result = result.substring(0, 9);
    result += this.SelectedSignallingGroup.key + this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedReserved.key + this.Frequency.key;
    return result;
};
SignallingGroupWithReserved.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
	result.pop();//remove driverstrength from result. Instead of drivestrength SiT9501 have SignallingGroup 
    result.push(this.SelectedSignallingGroup);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider3);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedReserved);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignallingGroupWithReserved.prototype.AllOptionsSelected = function() {
    if (SignallingGroupWithReserved.base.AllOptionsSelected.call(this)) {
        if (this.SelectedReserved.key !== undefined && this.SelectedSignallingGroup.key !== undefined)
            return true;
        else return false;
    }else return false;
};
SignallingGroupWithReserved.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedSignallingGroup.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 12);
    this.SelectedPackageSize.key = partNumber.substring(12, 13);
    this.SelectedFrequencyStability.key = partNumber.substring(13, 14);
    this.SelectedDivider3.key = partNumber.substring(14, 15);
    this.SelectedSupplyVoltage.key = partNumber.substring(15, 17);
    this.SelectedFeaturePin.key = partNumber.substring(17, 18);
    this.SelectedReserved.key = partNumber.substring(18, 20);

    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(20, endFrequency);
};
SignallingGroupWithReserved.prototype.Validate = function() {
    var result = SignallingGroupWithReserved.base.Validate.call(this);
    if (!IsInArray(this.SelectedReserved, this.Reserved)) {
        this.validationErrors.push(this.SelectedReserved.key + " - " + getErrorByErrorID("48"));
        this.SelectedReserved.value = false;
        return false;
    }else if (!IsInArray(this.SelectedSignallingGroup, this.SignallingGroup)) {
        this.validationErrors.push(this.SelectedSignallingGroup.key + " - " + getErrorByErrorID("47"));
        this.SelectedSignallingGroup.value = false;
        return false;
    }else return result;
};
SignallingGroupWithReserved.prototype.ToString = function() {
    var clipboardValue = SignallingGroupWithReserved.base.ToString.call(this);
    clipboardValue += "Reserved           :     " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedReserved, this.Reserved)) + "\n";
    return clipboardValue;
};
SignallingGroupWithReserved.prototype.GetHTML = function(selector) {
	AppendHTMLResource(selector, Headers.SignallingGroup, this.SignallingGroup, "signalingGroup", this, "OnSignallingGroupChanged", this.Mode);
    SignallingGroupWithReserved.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.Reserved, this.Reserved, "reserved", this, "OnReservedChanged", this.Mode);
};
SignallingGroupWithReserved.prototype.OnReservedChanged = function() {
    this.SelectedReserved.key = getCheckedValue(this.SelectedReserved.key, 'reserved');
};

SignallingGroupWithReserved.prototype.OnSignallingGroupChanged = function() {
    this.SelectedSignallingGroup.key = getCheckedValue(this.SelectedSignallingGroup.key, 'signalingGroup');
};

SignallingGroupWithReserved.prototype.SelectOptions = function() {
    SignallingGroupWithReserved.base.SelectOptions.call(this);
    SelectOption("reserved", this.SelectedReserved.key);
    SelectOption("signalingGroup", this.SelectedSignallingGroup.key);
};


function SignallingTypeWithVCMOAndSpecialFeatures() {
    if (arguments[0] === inheriting) return;
    SignallingTypeWithVCMO.call(this);
    var SelectedSpecialFeatures;
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype = new SignallingTypeWithVCMO(inheriting);
SignallingTypeWithVCMOAndSpecialFeatures.base = SignallingTypeWithVCMO.prototype;
SignallingTypeWithVCMOAndSpecialFeatures.prototype.Initialization = function(jsonData) {
    SignallingTypeWithVCMOAndSpecialFeatures.base.Initialization.call(this, jsonData);
    this.SpecialFeatures = jsonData.SpecialFeatures;
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
    this.SelectedOutputDriverStrength.key = "-";
}
SignallingTypeWithVCMOAndSpecialFeatures.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result = result.substring(0, 9);
    result += this.SelectedSpecialFeatures.key + this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + "-" +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
	result.pop();//remove driverstrength from result. Instead of drivestrength we have SpecialFeatures 
    result.push(this.SelectedSpecialFeatures);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDivider3);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.AllOptionsSelected = function() {
    if (SignallingTypeWithVCMOAndSpecialFeatures.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpecialFeatures.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedSpecialFeatures.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedDivider3.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    this.SelectedVCMO.key = partNumber.substring(17, 18);

    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) {
        this.validationErrors.push(this.SelectedSpecialFeatures.key + " - " + getErrorByErrorID("44"));
        this.SelectedSpecialFeatures.value = false;
    }
    if (!IsInArray(this.SelectedSignallingType, this.SignallingType)) {
        this.validationErrors.push(this.SelectedSignallingType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSignallingType.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "E")) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "B")) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
    }
    if (!IsInArray(this.SelectedFeaturePin, this.FeaturePin)) {
        this.validationErrors.push(this.SelectedFeaturePin.key + " - " + getErrorByErrorID("25"));
        this.SelectedFeaturePin.value = false;
    }
    if (this.Mode == "Decoder") {}

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.ToString = function() {
    var clipboardValue = SignallingTypeWithVCMOAndSpecialFeatures.base.ToString.call(this);
    clipboardValue += "SpecialFeatures           :     " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) + "\n";
    return clipboardValue;
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.GetHTML = function(selector) {
    SignallingTypeWithVCMOAndSpecialFeatures.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpecialFeatures, this.SpecialFeatures, "specialfeatures", this, "OnSpecialFeaturesChanged", this.Mode);
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.OnSpecialFeaturesChanged = function() {
    this.SelectedSpecialFeatures.key = getCheckedValue(this.SelectedSpecialFeatures.key, 'specialfeatures');
};
SignallingTypeWithVCMOAndSpecialFeatures.prototype.SelectOptions = function() {
    SignallingTypeWithVCMOAndSpecialFeatures.base.SelectOptions.call(this);
    SelectOption("specialfeatures", this.SelectedSpecialFeatures.key);
};


function SignallingTypeVCMOWithSerialMode() {
    if (arguments[0] === inheriting) return;
    SignallingTypeWithVCMO.call(this);
    var SelectedSerialMode;
    var SelectedI2CAddress;
};
SignallingTypeVCMOWithSerialMode.prototype = new SignallingTypeWithVCMO(inheriting);
SignallingTypeVCMOWithSerialMode.base = SignallingTypeWithVCMO.prototype;
SignallingTypeVCMOWithSerialMode.prototype.Initialization = function(jsonData) {
    SignallingTypeVCMOWithSerialMode.base.Initialization.call(this, jsonData);
    this.SerialMode = jsonData.SerialMode;

    this.I2CAddress = jsonData.I2CAddress;

    this.SelectedSerialMode = getArrayKeyValueWithTrue();
    this.SelectedI2CAddress = getArrayKeyValueWithTrue();
};
SignallingTypeVCMOWithSerialMode.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedI2CAddress.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
SignallingTypeVCMOWithSerialMode.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    //result.push(this.SelectedSerialIF);
    result.push(this.SelectedI2CAddress);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignallingTypeVCMOWithSerialMode.prototype.AllOptionsSelected = function() {
    if (SignallingTypeVCMOWithSerialMode.base.AllOptionsSelected.call(this)) {
        if (this.SelectedI2CAddress.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SignallingTypeVCMOWithSerialMode.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedSerialMode.key = partNumber.substring(16, 17);
    this.SelectedI2CAddress.key = partNumber.substring(16, 17);
    this.SelectedVCMO.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};

SignallingTypeVCMOWithSerialMode.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedOutputDriverStrength, this.OutputDriverStrength)) {
        this.validationErrors.push(this.SelectedOutputDriverStrength.key + " - " + getErrorByErrorID("26"));
        this.SelectedOutputDriverStrength.value = false;
    }
    if (!IsInArray(this.SelectedSignallingType, this.SignallingType)) {
        this.validationErrors.push(this.SelectedSignallingType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSignallingType.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "E")) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "B")) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
    }
    if (!IsInArray(this.SelectedFeaturePin, this.FeaturePin)) {
        this.validationErrors.push(this.SelectedFeaturePin.key + " - " + getErrorByErrorID("25"));
        this.SelectedFeaturePin.value = false;
    }
    if (!IsInArray(this.SelectedI2CAddress, this.I2CAddress)) {
        this.validationErrors.push(this.SelectedI2CAddress.key + " - " + getErrorByErrorID("42"));
        this.SelectedI2CAddress.value = false;
    }
    if (this.Mode == "Decoder") {}

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};

SignallingTypeVCMOWithSerialMode.prototype.ToString = function() {
    var clipboardValue = SignallingTypeVCMOWithSerialMode.base.ToString.call(this);
    clipboardValue += "I2CAddress:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedI2CAddress, this.I2CAddress)) + "\n";
    return clipboardValue;
};
SignallingTypeVCMOWithSerialMode.prototype.GetHTML = function(selector) {
    SignallingTypeVCMOWithSerialMode.base.GetHTML.call(this, selector, this.Mode);

    AppendHTMLResource(selector, Headers.SerialMode, this.SerialMode, "serialmode", this, "OnSerialModeChanged", this.Mode);
    AppendHTMLResource(selector, Headers.I2CAddress, this.I2CAddress, "i2caddress", this, "OnI2CAdderssChanged", this.Mode);
    //AppendHTMLResource(selector,Headers.VCMO, this.VCMO, "vcmo",this,"OnVCMOChanged",this.Mode);
    //AppendHTMLResource(selector,Headers.SignallingType, this.SignalingType, "signaling",this,"OnSelectedSignalingTypeChanged",this.Mode);
};
SignallingTypeVCMOWithSerialMode.prototype.OnI2CAdderssChanged = function() {
    this.SelectedI2CAddress.key = getCheckedValue(this.SelectedI2CAddress.key, 'i2caddress');
};
SignallingTypeVCMOWithSerialMode.prototype.OnSerialModeChanged = function() {
    this.SelectedSerialMode.key = getCheckedValue(this.SelectedSerialMode.key, 'serialmode');
};
SignallingTypeVCMOWithSerialMode.prototype.SelectOptions = function() {
    SignallingTypeVCMOWithSerialMode.base.SelectOptions.call(this);
    SelectOption("i2caddress", this.SelectedI2CAddress.key);
    if (this.SelectedI2CAddress.key == "S")
        SelectOption("serialmode", "S");
    else
        SelectOption("serialmode", "I2C");
};

function SignallingTypeVCMOWithSerialModeAndSpecialFeatures() {
    if (arguments[0] === inheriting) return;
    SignallingTypeVCMOWithSerialMode.call(this);
    var SelectedSpecialFeatures;
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype = new SignallingTypeVCMOWithSerialMode(inheriting);
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.base = SignallingTypeVCMOWithSerialMode.prototype;
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.Initialization = function(jsonData) {
    SignallingTypeVCMOWithSerialModeAndSpecialFeatures.base.Initialization.call(this, jsonData);
    this.SpecialFeatures = jsonData.SpecialFeatures;

    this.SelectedOutputDriverStrength.key = "-"; // SelectedSpecialFeatures must be instead DriveStrength. So we must init this option here for decoder. (SiT3542)
    this.SelectedSpecialFeatures = getArrayKeyValueWithTrue();
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result = result.substring(0, 9);
    result += this.SelectedSpecialFeatures.key + this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedI2CAddress.key + this.SelectedVCMO.key + this.Frequency.key;
    return result;
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
	result.pop();//remove driverstrength from result. Instead of drivestrength we have SpecialFeatures 
    result.push(this.SelectedSpecialFeatures);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    //result.push(this.SelectedSerialIF);
    result.push(this.SelectedI2CAddress);
    result.push(this.SelectedVCMO);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.AllOptionsSelected = function() {
    if (SignallingTypeVCMOWithSerialModeAndSpecialFeatures.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpecialFeatures.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedSpecialFeatures.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedSerialMode.key = partNumber.substring(16, 17);
    this.SelectedI2CAddress.key = partNumber.substring(16, 17);
    this.SelectedVCMO.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};

SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.Validate = function() {
    if (!IsInArray(this.SelectedRevisionLetter, this.RevisionLetter)) {
        this.validationErrors.push(this.SelectedRevisionLetter.key + " - " + getErrorByErrorID("33"));
        this.SelectedRevisionLetter.value = false;
    }
    if (!IsInArray(this.SelectedTemperatureRange, this.TemperatureRange)) {
        this.validationErrors.push(this.SelectedTemperatureRange.key + " - " + getErrorByErrorID("38"));
        this.SelectedTemperatureRange.value = false;
    }
    if (!IsInArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) {
        this.validationErrors.push(this.SelectedSpecialFeatures.key + " - " + getErrorByErrorID("44"));
        this.SelectedSpecialFeatures.value = false;
    }
    if (!IsInArray(this.SelectedSignallingType, this.SignallingType)) {
        this.validationErrors.push(this.SelectedSignallingType.key + " - " + getErrorByErrorID("36"));
        this.SelectedSignallingType.value = false;
    }
    if (!IsInArray(this.SelectedPackageSize, this.PackageSize)) {
        this.validationErrors.push(this.SelectedPackageSize.key + " - " + getErrorByErrorID("30"));
        this.SelectedPackageSize.value = false;
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "E")) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 != undefined && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "B")) {
        var pack = this.PackagingList1;
        if (!IsInArray(this.SelectedPackaging, pack)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (this.PackagingList1 == undefined) {
        if (!IsInArray(this.SelectedPackaging, this.Packaging)) {
            this.validationErrors.push(this.SelectedPackaging.key + " - " + getErrorByErrorID("31"));
            this.SelectedPackaging.value = false;
        }
    }
    if (!IsInArray(this.SelectedSupplyVoltage, this.SupplyVoltage)) {
        this.validationErrors.push(this.SelectedSupplyVoltage.key + " - " + getErrorByErrorID("34"));
        this.SelectedSupplyVoltage.value = false;
    }
    if (!IsInArray(this.SelectedFrequencyStability, this.FrequencyStability)) {
        this.validationErrors.push(this.SelectedFrequencyStability.key + " - " + getErrorByErrorID("5"));
        this.SelectedFrequencyStability.value = false;
    }
    if (!IsInArray(this.SelectedVCMO, this.VCMO)) {
        this.validationErrors.push(this.SelectedVCMO.key + " - " + getErrorByErrorID("10"));
        this.SelectedVCMO.value = false;
    }
    if (!IsInArray(this.SelectedFeaturePin, this.FeaturePin)) {
        this.validationErrors.push(this.SelectedFeaturePin.key + " - " + getErrorByErrorID("25"));
        this.SelectedFeaturePin.value = false;
    }
    if (!IsInArray(this.SelectedI2CAddress, this.I2CAddress)) {
        this.validationErrors.push(this.SelectedI2CAddress.key + " - " + getErrorByErrorID("42"));
        this.SelectedI2CAddress.value = false;
    }
    if (this.Mode == "Decoder") {}

    if (this.validationErrors.length == 0)
        return true;
    else
        return false;
};

SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.ToString = function() {
    var clipboardValue = SignallingTypeVCMOWithSerialModeAndSpecialFeatures.base.ToString.call(this);
    clipboardValue += "SpecialFeatures:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSpecialFeatures, this.SpecialFeatures)) + "\n";
    return clipboardValue;
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.GetHTML = function(selector) {
    SignallingTypeVCMOWithSerialModeAndSpecialFeatures.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpecialFeatures, this.SpecialFeatures, "specialfeatures", this, "OnSpecialFeaturesChanged", this.Mode);
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.OnSpecialFeaturesChanged = function() {
    this.SelectedSpecialFeatures.key = getCheckedValue(this.SelectedI2CAddress.key, 'specialfeatures');
};
SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype.SelectOptions = function() {
    SignallingTypeVCMOWithSerialModeAndSpecialFeatures.base.SelectOptions.call(this);
    SelectOption("specialfeatures", this.SelectedSpecialFeatures.key);
};

function SignallingTypeWithSwingSelectOption() {
    if (arguments[0] === inheriting) return;
    FeaturePinWithSignallingType.call(this);
    var SelectedSwingSelectOption;
};
SignallingTypeWithSwingSelectOption.prototype = new FeaturePinWithSignallingType(inheriting);
SignallingTypeWithSwingSelectOption.base = FeaturePinWithSignallingType.prototype;
SignallingTypeWithSwingSelectOption.prototype.Initialization = function(jsonData) {
    SignallingTypeWithSwingSelectOption.base.Initialization.call(this, jsonData);
    this.SwingSelectOptions = jsonData.SwingSelectOptions;
    this.SelectedSwingSelectOption = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 15;
}
SignallingTypeWithSwingSelectOption.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedSwingSelectOption.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.Frequency.key;
    return result;
};
SignallingTypeWithSwingSelectOption.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSwingSelectOption);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SignallingTypeWithSwingSelectOption.prototype.AllOptionsSelected = function() {
    if (SignallingTypeWithSwingSelectOption.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSwingSelectOption.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SignallingTypeWithSwingSelectOption.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSwingSelectOption.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
SignallingTypeWithSwingSelectOption.prototype.Validate = function() {
    var result = SignallingTypeWithSwingSelectOption.base.Validate.call(this);
    if (!IsInArray(this.SelectedSwingSelectOption, this.SwingSelectOptions)) {
        this.validationErrors.push(this.SelectedSwingSelectOption.key + " - " + getErrorByErrorID("37"));
        this.SelectedSwingSelectOption.value = false;
        return false;
    } else return result;
};
SignallingTypeWithSwingSelectOption.prototype.ToString = function() {
    var clipboardValue = SignallingTypeWithSwingSelectOption.base.ToString.call(this);
    clipboardValue += "Swing Select:        " + "\t\t" + GetValueFromArray(this.SelectedSwingSelectOption, this.SwingSelectOptions) + "\n";
    return clipboardValue;
};
SignallingTypeWithSwingSelectOption.prototype.GetHTML = function(selector) {
    SignallingTypeWithSwingSelectOption.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.Swing, this.SwingSelectOptions, "swing", this, "OnSwingSelectOptionsChanged", this.Mode);
};
SignallingTypeWithSwingSelectOption.prototype.OnSwingSelectOptionsChanged = function() {
    this.SelectedSwingSelectOption.key = getCheckedValue(this.SelectedSwingSelectOption.key, 'swing');
};
SignallingTypeWithSwingSelectOption.prototype.SelectOptions = function() {
    SignallingTypeWithSwingSelectOption.base.SelectOptions.call(this);
    SelectOption("swing", this.SelectedSwingSelectOption.key);
};

function SwingSelectOptionWithSpreadSpectrum() {
    if (arguments[0] === inheriting) return;
    SignallingTypeWithSwingSelectOption.call(this);
    var SelectedSpreadSpectrum;
};
SwingSelectOptionWithSpreadSpectrum.prototype = new SignallingTypeWithSwingSelectOption(inheriting);
SwingSelectOptionWithSpreadSpectrum.base = SignallingTypeWithSwingSelectOption.prototype;
SwingSelectOptionWithSpreadSpectrum.prototype.Initialization = function(jsonData) {
    SwingSelectOptionWithSpreadSpectrum.base.Initialization.call(this, jsonData);
    this.SpreadSpectrum = jsonData.SpreadSpectrum;
    this.SelectedSpreadSpectrum = getArrayKeyValueWithTrue();
    //this.MinPartLenght = 16;
}
SwingSelectOptionWithSpreadSpectrum.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedSignallingType.key + this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedSwingSelectOption.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedSpreadSpectrum.key + this.Frequency.key;
    return result;
};
SwingSelectOptionWithSpreadSpectrum.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedSignallingType);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedSwingSelectOption);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedSpreadSpectrum);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
SwingSelectOptionWithSpreadSpectrum.prototype.AllOptionsSelected = function() {
    if (SwingSelectOptionWithSpreadSpectrum.base.AllOptionsSelected.call(this)) {
        if (this.SelectedSpreadSpectrum.key !== undefined)
            return true;
        else return false;
    } else return false;
};
SwingSelectOptionWithSpreadSpectrum.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedSignallingType.key = partNumber.substring(10, 11);
    this.SelectedPackageSize.key = partNumber.substring(11, 12);
    this.SelectedFrequencyStability.key = partNumber.substring(12, 13);
    this.SelectedSwingSelectOption.key = partNumber.substring(13, 14);
    this.SelectedSupplyVoltage.key = partNumber.substring(14, 16);
    this.SelectedFeaturePin.key = partNumber.substring(16, 17);
    this.SelectedSpreadSpectrum.key = partNumber.substring(17, 18);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(18, endFrequency);
};
SwingSelectOptionWithSpreadSpectrum.prototype.Validate = function() {
    var result = SwingSelectOptionWithSpreadSpectrum.base.Validate.call(this);
    if (!IsInArray(this.SelectedSpreadSpectrum, this.SpreadSpectrum)) {
        this.validationErrors.push(this.SelectedSpreadSpectrum.key + " - " + getErrorByErrorID("35"));
        this.SelectedSpreadSpectrum.value = false;
        return false;
    } else return result;
};
SwingSelectOptionWithSpreadSpectrum.prototype.ToString = function() {
    var clipboardValue = SwingSelectOptionWithSpreadSpectrum.base.ToString.call(this);
    clipboardValue += "Spread Spectrum:     " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedSpreadSpectrum, this.SpreadSpectrum)) + "\n";
    return clipboardValue;
};
SwingSelectOptionWithSpreadSpectrum.prototype.GetHTML = function(selector) {
    SwingSelectOptionWithSpreadSpectrum.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.SpreadSpectrum, this.SpreadSpectrum, "spread", this, "OnSpreadSpectrumChanged", this.Mode);
};
SwingSelectOptionWithSpreadSpectrum.prototype.OnSpreadSpectrumChanged = function() {
    this.SelectedSpreadSpectrum.key = getCheckedValue(this.SelectedSpreadSpectrum.key, 'spread');

};
SwingSelectOptionWithSpreadSpectrum.prototype.SelectOptions = function() {
    SwingSelectOptionWithSpreadSpectrum.base.SelectOptions.call(this);
    SelectOption("spread", this.SelectedSpreadSpectrum.key);
};

function DeviceAddressWithFeaturePinWithoutVCMO() {
    if (arguments[0] === inheriting) return;
    BaseWithPin.call(this);
    var SelectedDeviceAddress;
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype = new BaseWithPin(inheriting);
DeviceAddressWithFeaturePinWithoutVCMO.base = BaseWithPin.prototype;
DeviceAddressWithFeaturePinWithoutVCMO.prototype.Initialization = function(jsonData) {
    DeviceAddressWithFeaturePinWithoutVCMO.base.Initialization.call(this, jsonData);
    this.DeviceAddress = jsonData.DeviceAddress;
    this.SelectedDeviceAddress = getArrayKeyValueWithTrue();
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.Generate = function() {
    var result = BaseViewModel.prototype.Generate.call(this);
    result += this.SelectedPackageSize.key + this.SelectedFrequencyStability.key + this.SelectedDeviceAddress.key +
        this.SelectedSupplyVoltage.key + this.SelectedFeaturePin.key + this.SelectedDivider3.key + this.Frequency.key;
    return result;
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.GenerateForColor = function() {
    var result = BaseViewModel.prototype.GenerateForColor.call(this);
    result.push(this.SelectedPackageSize);
    result.push(this.SelectedFrequencyStability);
    result.push(this.SelectedDeviceAddress);
    result.push(this.SelectedSupplyVoltage);
    result.push(this.SelectedFeaturePin);
    result.push(this.SelectedDivider3);
    result.push(this.Frequency);
    result.push(this.SelectedPackaging);
    return result;
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.AllOptionsSelected = function() {
    if (DeviceAddressWithFeaturePinWithoutVCMO.base.AllOptionsSelected.call(this)) {
        if (this.SelectedDeviceAddress.key !== undefined)
            return true;
        else return false;
    } else return false;
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.Parse = function(partNumber) {
    this.PartFamily = partNumber.substring(0, 7);
    this.SelectedRevisionLetter.key = partNumber.substring(7, 8);
    this.SelectedTemperatureRange.key = partNumber.substring(8, 9);
    this.SelectedOutputDriverStrength.key = partNumber.substring(9, 10);
    this.SelectedPackageSize.key = partNumber.substring(10, 11);
    this.SelectedFrequencyStability.key = partNumber.substring(11, 12);
    this.SelectedDeviceAddress.key = partNumber.substring(12, 13);
    this.SelectedSupplyVoltage.key = partNumber.substring(13, 15);
    this.SelectedFeaturePin.key = partNumber.substring(15, 16);
    this.SelectedDivider3.key = partNumber.substring(16, 17);
    var endFrequency = partNumber.length;
    if (/^.*[A-Z].*/i.test(partNumber.substring(partNumber.length - 1, partNumber.length))) {
        this.SelectedPackaging.key = partNumber.substring(partNumber.length - 1, partNumber.length);
        endFrequency = endFrequency - 1;
    } else this.SelectedPackaging.key = "";
    this.Frequency.key = partNumber.substring(17, endFrequency);
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.Validate = function() {
    var result = DeviceAddressWithFeaturePinWithoutVCMO.base.Validate.call(this);
    if (!IsInArray(this.SelectedDeviceAddress, this.DeviceAddress)) {
        this.validationErrors.push(this.SelectedDeviceAddress.key + " - " + getErrorByErrorID("17"));
        this.SelectedDeviceAddress.value = false;
        return false;
    } else return result;
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.ToString = function() {
    var clipboardValue = DeviceAddressWithFeaturePinWithoutVCMO.base.ToString.call(this);
    clipboardValue += "Device Address:      " + "\t\t" + GetValueFromArray(this.SelectedDeviceAddress, this.DeviceAddress) + "\n";
    return clipboardValue;
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.GetHTML = function(selector) {
    DeviceAddressWithFeaturePinWithoutVCMO.base.GetHTML.call(this, selector, this.Mode);
    AppendHTMLResource(selector, Headers.DeviceAddress, this.DeviceAddress, "daddress", this, "OnDeviceAddressChanged", this.Mode);
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.OnDeviceAddressChanged = function() {
    this.SelectedDeviceAddress.key = getCheckedValue(this.SelectedDeviceAddress.key, 'daddress');
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.SelectOptions = function() {
    DeviceAddressWithFeaturePinWithoutVCMO.base.SelectOptions.call(this);
    SelectOption("daddress", this.SelectedDeviceAddress.key);
};
DeviceAddressWithFeaturePinWithoutVCMO.prototype.OnFrequencyChange = function() {
    try {
        if (this.Mode == "Generator" || this.Mode == "Mixed")
            this.Frequency.key = $("#frequency").val();
        var FrequencyLengthAfterPoint = this.Frequency.key.length - this.Frequency.key.lastIndexOf(".") - 1;
        if (FrequencyLengthAfterPoint > this.LengthAfterPoint) {
            throw (this.Frequency.key + "MHz - " + getErrorByErrorID("20") + this.LengthAfterPoint + getErrorByErrorID("22"));
        }
    } catch (FrequencyErrorResult) {
        this.Frequency.value = false;
        this.validationErrors.push(FrequencyErrorResult);
        if (this.Mode == "Generator") {
            showError(FrequencyErrorResult);
            $("#partnumber").val("(invalid frequency)").css({
                color: "#bbb"
            });
        }
        return false;
    }
    if (this.Mode == "Generator") {
        this.ShowGenerateResult();
        dismissError();
    }
    return true;
}


function Family1532() {
    OutputVohWithOutputVol.call(this);
};
Family1532.prototype = new OutputVohWithOutputVol(inheriting);
Family1532.base = OutputVohWithOutputVol.prototype;
Family1532.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1532");
    Family1532.base.Initialization.call(this, jsonData);
}
Family1532.prototype.ExclusionTable = function() {
    if (this.SelectedACDCCoupling.key == "A") {
        if (this.SelectedOutputVoh.key != "A" || this.SelectedOutputVol.key != "3") {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1532.base.GetRestrictionResult.call(this);
        }
    }
    if (this.SelectedACDCCoupling.key == "D") {
        if (this.SelectedOutputVoh.key == "C" && (this.SelectedOutputVol.key == "C")) {
            return Family1532.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "6")) {
            return Family1532.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "1" && (this.SelectedOutputVol.key == "4")) {
            return Family1532.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key == "4")) {

            return Family1532.base.GetRestrictionResult.call(this);
        } else {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1532.base.GetRestrictionResult.call(this);
        }
    }
    return Family1532.base.GetRestrictionResult.call(this);
    /*if(this.SelectedTemperatureRange.key=="C"){
    	if(this.SelectedFrequencyStability.key=="4"){
    		this.validationErrors.push(getErrorByErrorID("1"));
    		return false;
    	}
    }
    if(this.SelectedTemperatureRange.key=="I"){
    	if(this.SelectedFrequencyStability.key=="5"){
    		this.validationErrors.push(getErrorByErrorID("1"));
    		return false;
    	}
    }
    
    if(this.SelectedACDCCoupling.key == "D"){
    	if(this.SelectedOutputVoh.key == "6" && (this.SelectedOutputVol.key != "3" && this.SelectedOutputVol.key != "4")){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key != "3" && this.SelectedOutputVol.key != "4" && this.SelectedOutputVol.key != "5")){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "8" && (this.SelectedOutputVol.key == "7" || this.SelectedOutputVol.key == "8" )){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "9" && this.SelectedOutputVol.key == "8"){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "3" || this.SelectedOutputVol.key == "4")){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    }
    return true;*/
}

Family1532.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var tolerance = this.SelectedFrequencyStability.key;
    var acdc = this.SelectedACDCCoupling.key;


    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "5") {
            disableOption("temprange", "I");
            enableOption("temprange", "C");
            DeselectOption("temprange", "I");
            SelectOption("temprange", "C");
            this.SelectedTemperatureRange.key = "C";

        } else if (tolerance == "4") {
            disableOption("temprange", "C");
            enableOption("temprange", "I");
            DeselectOption("temprange", "C");
            SelectOption("temprange", "I");
            this.SelectedTemperatureRange.key = "I";
        }
    }

    if (acdc != this.lastAcdc) {
        this.lastAcdc = acdc;
        if (acdc == "A") {
            hideOption("outputvoh", "A");
            hideOption("outputvol", "3");
            SelectOption("outputvoh", "A");
            SelectOption("outputvol", "3");
            this.SelectedOutputVoh.key = "A";
            this.SelectedOutputVol.key = "3";
        } else if (acdc == "D") {
            showOption("outputvoh", "A");
            showOption("outputvol", "3");
            DeselectOption("outputvoh", "A");
            DeselectOption("outputvol", "3");
            SelectOption("outputvoh", "C");
            SelectOption("outputvol", "C");
            this.SelectedOutputVoh.key = "C";
            this.SelectedOutputVol.key = "C";

        }
    }

    if (this.SelectedOutputVoh.key != this.lastVoh) {
        this.lastVoh = this.SelectedOutputVoh.key;

        if (this.SelectedOutputVoh.key == "C") {
            SelectOption("outputvol", "C");
            this.SelectedOutputVol.key = "C"
            return true;
        } else if (this.SelectedOutputVoh.key == "1") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        } else if (this.SelectedOutputVoh.key == "2") {
            SelectOption("outputvol", "6");
            this.SelectedOutputVol.key = "6"
            return true;
        } else if (this.SelectedOutputVoh.key == "7") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        }
        this.lastVoh = this.SelectedOutputVoh.key;
        this.lastVol = this.SelectedOutputVol.key;
    }

    if (this.SelectedOutputVol.key != this.lastVol) {
        this.lastVol = this.SelectedOutputVol.key;

        if (this.SelectedOutputVol.key == "C") {
            SelectOption("outputvoh", "C");
            this.SelectedOutputVoh.key = "C";
            return true;
        } else if (this.SelectedOutputVol.key == "4") {
            SelectOption("outputvoh", "1");
            this.SelectedOutputVoh.key = "1";
            return true;
        } else if (this.SelectedOutputVol.key == "6") {
            SelectOption("outputvoh", "2");
            this.SelectedOutputVoh.key = "2";
            return true;
        }
    }
}

Family1532.prototype.Parse = function(partNumber) {
    Family1532.base.Parse.call(this, partNumber);
    Family1532.base.GetChangeOptionRestrictionResult.call(this);
}
Family1532.prototype.GetHTML = function(selector) {
    //if(this.SelectedACDCCoupling.key == "A"){
    //ACDCCouplingWithOutputVoh.base.GetHTML.call(this,selector,this.Mode);
    //AppendHTMLResource(selector,Headers.ACSwing, this.OutputVol, "outputvol",this,"OnOutputVolChanged",this.Mode);
    //}
    //else if(this.SelectedACDCCoupling.key == "D"){
    Family1532.base.GetHTML.call(this, selector, this.Mode);
    //}

};

function Family1533() {
    OutputVohWithOutputVol.call(this);
};
Family1533.prototype = new OutputVohWithOutputVol(inheriting);
Family1533.base = OutputVohWithOutputVol.prototype;
Family1533.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1533");
    Family1533.base.Initialization.call(this, jsonData);
}
Family1533.prototype.ExclusionTable = function() {
    if (this.SelectedACDCCoupling.key == "A") {
        if (this.SelectedOutputVoh.key != "A" || this.SelectedOutputVol.key != "3") {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1533.base.GetRestrictionResult.call(this);
        }
    }
    if (this.SelectedACDCCoupling.key == "D") {
        if (this.SelectedOutputVoh.key == "C" && (this.SelectedOutputVol.key == "C")) {
            return Family1533.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "6")) {
            return Family1533.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "1" && (this.SelectedOutputVol.key == "4")) {
            return Family1533.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key == "4")) {

            return Family1533.base.GetRestrictionResult.call(this);
        } else {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1533.base.GetRestrictionResult.call(this);
        }
    }
    return Family1533.base.GetRestrictionResult.call(this);
    /*if(this.SelectedTemperatureRange.key=="C"){
    	if(this.SelectedFrequencyStability.key=="4"){
    		this.validationErrors.push(getErrorByErrorID("1"));
    		return false;
    	}
    }
    if(this.SelectedTemperatureRange.key=="I"){
    	if(this.SelectedFrequencyStability.key=="5"){
    		this.validationErrors.push(getErrorByErrorID("1"));
    		return false;
    	}
    }
    
    if(this.SelectedACDCCoupling.key == "D"){
    	if(this.SelectedOutputVoh.key == "6" && (this.SelectedOutputVol.key != "3" && this.SelectedOutputVol.key != "4")){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key != "3" && this.SelectedOutputVol.key != "4" && this.SelectedOutputVol.key != "5")){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "8" && (this.SelectedOutputVol.key == "7" || this.SelectedOutputVol.key == "8" )){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "9" && this.SelectedOutputVol.key == "8"){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    	if(this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "3" || this.SelectedOutputVol.key == "4")){
    		this.validationErrors.push(getErrorByErrorID("2"));
    		return false;
    	}
    }

    return true;
    */
}

Family1533.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var tolerance = this.SelectedFrequencyStability.key;
    var acdc = this.SelectedACDCCoupling.key;


    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "5") {
            disableOption("temprange", "I");
            enableOption("temprange", "C");
            DeselectOption("temprange", "I");
            SelectOption("temprange", "C");
            this.SelectedTemperatureRange.key = "C";

        } else if (tolerance == "4") {
            disableOption("temprange", "C");
            enableOption("temprange", "I");
            DeselectOption("temprange", "C");
            SelectOption("temprange", "I");
            this.SelectedTemperatureRange.key = "I";
        }
    }

    if (acdc != this.lastAcdc) {
        this.lastAcdc = acdc;
        if (acdc == "A") {
            hideOption("outputvoh", "A");
            hideOption("outputvol", "3");
            SelectOption("outputvoh", "A");
            SelectOption("outputvol", "3");
            this.SelectedOutputVoh.key = "A";
            this.SelectedOutputVol.key = "3";
        } else if (acdc == "D") {
            showOption("outputvoh", "A");
            showOption("outputvol", "3");
            DeselectOption("outputvoh", "A");
            DeselectOption("outputvol", "3");
            SelectOption("outputvoh", "C");
            SelectOption("outputvol", "C");
            this.SelectedOutputVoh.key = "C";
            this.SelectedOutputVol.key = "C";

        }
    }

    if (this.SelectedOutputVoh.key != this.lastVoh) {
        this.lastVoh = this.SelectedOutputVoh.key;

        if (this.SelectedOutputVoh.key == "C") {
            SelectOption("outputvol", "C");
            this.SelectedOutputVol.key = "C"
            return true;
        } else if (this.SelectedOutputVoh.key == "1") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        } else if (this.SelectedOutputVoh.key == "2") {
            SelectOption("outputvol", "6");
            this.SelectedOutputVol.key = "6"
            return true;
        } else if (this.SelectedOutputVoh.key == "7") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        }
        this.lastVoh = this.SelectedOutputVoh.key;
        this.lastVol = this.SelectedOutputVol.key;
    }

    if (this.SelectedOutputVol.key != this.lastVol) {
        this.lastVol = this.SelectedOutputVol.key;

        if (this.SelectedOutputVol.key == "C") {
            SelectOption("outputvoh", "C");
            this.SelectedOutputVoh.key = "C";
            return true;
        } else if (this.SelectedOutputVol.key == "4") {
            SelectOption("outputvoh", "1");
            this.SelectedOutputVoh.key = "1";
            return true;
        } else if (this.SelectedOutputVol.key == "6") {
            SelectOption("outputvoh", "2");
            this.SelectedOutputVoh.key = "2";
            return true;
        }
    }
}


Family1533.prototype.Parse = function(partNumber) {
    Family1533.base.Parse.call(this, partNumber);
    Family1533.base.GetChangeOptionRestrictionResult.call(this);
}

function Family1630() {
    OutputVohWithOutputVol.call(this);
};
Family1630.prototype = new OutputVohWithOutputVol(inheriting);
Family1630.base = OutputVohWithOutputVol.prototype;
Family1630.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1630");
    Family1630.base.Initialization.call(this, jsonData);
}
Family1630.prototype.ExclusionTable = function() {
    return Family1630.base.GetRestrictionResult.call(this);

    /*if((this.SelectedTemperatureRange.key=="C") && (this.SelectedFrequencyStability.key !="5")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    if((this.SelectedTemperatureRange.key=="I") && (this.SelectedFrequencyStability.key !="4")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    if((this.SelectedTemperatureRange.key=="E") && (this.SelectedFrequencyStability.key !="6")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    return true;
    */
}

Family1630.prototype.Validate = function() {
    return Family1630.base.Validate.call(this);
}

Family1630.prototype.Parse = function(partNumber) {
    Family1630.base.Parse.call(this, partNumber);
    Family1630.base.GetChangeOptionRestrictionResult.call(this);
}

Family1630.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var tolerance = this.SelectedFrequencyStability.key;

    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "5") {
            this.SelectedTemperatureRange.key = "C";
            enableOption("temprange", "C");
            SelectOption("temprange", "C");
            disableOption("temprange", "I");
            DeselectOption("temprange", "I");
            disableOption("temprange", "E");
            DeselectOption("temprange", "E");
            disableOption("temprange", "D");
            DeselectOption("temprange", "D");

        } else if (tolerance == "4") {
            this.SelectedTemperatureRange.key = "I";
            enableOption("temprange", "I");
            SelectOption("temprange", "I");
            disableOption("temprange", "C");
            DeselectOption("temprange", "C");
            disableOption("temprange", "E");
            DeselectOption("temprange", "E");
            disableOption("temprange", "D");
            DeselectOption("temprange", "D");
        } else if (tolerance == "6") {
            this.SelectedTemperatureRange.key = "D";
            enableOption("temprange", "D");
            SelectOption("temprange", "D");
            enableOption("temprange", "E");
            disableOption("temprange", "C");
            DeselectOption("temprange", "C");
            disableOption("temprange", "I");
            DeselectOption("temprange", "I");
        }
    }

}

function Family1534() {
    OutputVohWithOutputVol.call(this);
};
Family1534.prototype = new OutputVohWithOutputVol(inheriting);
Family1534.base = OutputVohWithOutputVol.prototype;
Family1534.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1534");
    Family1534.base.Initialization.call(this, jsonData);
}
Family1534.prototype.ExclusionTable = function() {
    if (this.SelectedACDCCoupling.key == "A") {
        if (this.SelectedOutputVoh.key != "A" || this.SelectedOutputVol.key != "3") {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1534.base.GetRestrictionResult.call(this);
        }
    }
    if (this.SelectedACDCCoupling.key == "D") {
        if (this.SelectedOutputVoh.key == "C" && (this.SelectedOutputVol.key == "C")) {
            return Family1534.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "6")) {
            return Family1534.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "1" && (this.SelectedOutputVol.key == "4")) {
            return Family1534.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key == "4")) {

            return Family1534.base.GetRestrictionResult.call(this);
        } else {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1534.base.GetRestrictionResult.call(this);
        }
    }
    return Family1534.base.GetRestrictionResult.call(this);
    /*if(this.SelectedTemperatureRange.key=="C"){
    	if(this.SelectedFrequencyStability.key=="4"){
    		this.validationErrors.push(getErrorByErrorID("1"));
    		return false;
    	}
    }
    if(this.SelectedTemperatureRange.key=="I"){
    	if(this.SelectedFrequencyStability.key=="5"){
    		this.validationErrors.push(getErrorByErrorID("1"));
    		return false;
    	}
    }
    
    if (this.SelectedACDCCoupling.key == "A" && (this.SelectedOutputVoh.key == "C" || this.SelectedOutputVol.key == "C")){
    	this.validationErrors.push(getErrorByErrorID("2"));
    	return false;
    }
    
    if (this.SelectedACDCCoupling.key == "D" && this.SelectedOutputVoh.key == "A"){
    	this.validationErrors.push(getErrorByErrorID("3"));
    	return false;
    }
    
    return true;
    */
}

Family1534.prototype.Validate = function() {
    if (this.Frequency.key < 10)
        this.Frequency.key = "0" + this.Frequency.key;

    return Family1534.base.Validate.call(this);
}

Family1534.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var tolerance = this.SelectedFrequencyStability.key;
    var acdc = this.SelectedACDCCoupling.key;

    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "5") {
            this.SelectedTemperatureRange.key = "C";
            enableOption("temprange", "C");
            SelectOption("temprange", "C");
            disableOption("temprange", "I");
            DeselectOption("temprange", "I");
            disableOption("temprange", "D");
            DeselectOption("temprange", "D");

        } else if (tolerance == "4") {
            this.SelectedTemperatureRange.key = "I";
            enableOption("temprange", "I");
            SelectOption("temprange", "I");
            disableOption("temprange", "C");
            DeselectOption("temprange", "C");
            disableOption("temprange", "D");
            DeselectOption("temprange", "D");
        } else if (tolerance == "6") {
            this.SelectedTemperatureRange.key = "D";
            enableOption("temprange", "D");
            SelectOption("temprange", "D");
            disableOption("temprange", "C");
            DeselectOption("temprange", "C");
            disableOption("temprange", "I");
            DeselectOption("temprange", "I");
        }
    }

    if (acdc != this.lastAcdc) {
        this.lastAcdc = acdc;
        if (acdc == "A") {
            hideOption("outputvoh", "A");
            hideOption("outputvol", "3");
            SelectOption("outputvoh", "A");
            SelectOption("outputvol", "3");
            this.SelectedOutputVoh.key = "A";
            this.SelectedOutputVol.key = "3";
        } else if (acdc == "D") {
            showOption("outputvoh", "A");
            showOption("outputvol", "3");
            DeselectOption("outputvoh", "A");
            DeselectOption("outputvol", "3");
            SelectOption("outputvoh", "C");
            SelectOption("outputvol", "C");
            this.SelectedOutputVoh.key = "C";
            this.SelectedOutputVol.key = "C";

        }
    }

    if (this.SelectedOutputVoh.key != this.lastVoh) {
        this.lastVoh = this.SelectedOutputVoh.key;

        if (this.SelectedOutputVoh.key == "C") {
            SelectOption("outputvol", "C");
            this.SelectedOutputVol.key = "C"
            return true;
        } else if (this.SelectedOutputVoh.key == "1") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        } else if (this.SelectedOutputVoh.key == "2") {
            SelectOption("outputvol", "6");
            this.SelectedOutputVol.key = "6"
            return true;
        } else if (this.SelectedOutputVoh.key == "7") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        }
        this.lastVoh = this.SelectedOutputVoh.key;
        this.lastVol = this.SelectedOutputVol.key;
    }

    if (this.SelectedOutputVol.key != this.lastVol) {
        this.lastVol = this.SelectedOutputVol.key;

        if (this.SelectedOutputVol.key == "C") {
            SelectOption("outputvoh", "C");
            this.SelectedOutputVoh.key = "C";
            return true;
        } else if (this.SelectedOutputVol.key == "4") {
            SelectOption("outputvoh", "1");
            this.SelectedOutputVoh.key = "1";
            return true;
        } else if (this.SelectedOutputVol.key == "6") {
            SelectOption("outputvoh", "2");
            this.SelectedOutputVoh.key = "2";
            return true;
        }
    }
}


Family1534.prototype.Parse = function(partNumber) {
    Family1534.base.Parse.call(this, partNumber);
    Family1534.base.GetChangeOptionRestrictionResult.call(this);
}

function Family1552() {
    OutputVohWithOutputVol.call(this);
};
Family1552.prototype = new OutputVohWithOutputVol(inheriting);
Family1552.base = OutputVohWithOutputVol.prototype;
Family1552.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1552");
    Family1552.base.Initialization.call(this, jsonData);
}
Family1552.prototype.ExclusionTable = function() {
    if (this.SelectedACDCCoupling.key == "A") {
        if (this.SelectedOutputVoh.key != "A" || this.SelectedOutputVol.key != "3") {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1552.base.GetRestrictionResult.call(this);
        }
    }
    if (this.SelectedACDCCoupling.key == "D") {
        if (this.SelectedOutputVoh.key == "C" && (this.SelectedOutputVol.key == "C")) {
            return Family1552.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "6")) {
            return Family1552.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "1" && (this.SelectedOutputVol.key == "4")) {
            return Family1552.base.GetRestrictionResult.call(this);
        }
        if (this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key == "4")) {

            return Family1552.base.GetRestrictionResult.call(this);
        } else {
            this.validationErrors.push(getErrorByErrorID("2"));
            return Family1552.base.GetRestrictionResult.call(this);
        }
    }
    return Family1552.base.GetRestrictionResult.call(this);
}
/*
	if(this.SelectedTemperatureRange.key=="C"){
		if(this.SelectedFrequencyStability.key=="4"){
			this.validationErrors.push(getErrorByErrorID("1"));
			return false;
		}
	}
	if(this.SelectedTemperatureRange.key=="I"){
		if(this.SelectedFrequencyStability.key=="5"){
			this.validationErrors.push(getErrorByErrorID("1"));
			return false;
		}
	}
	
	if(this.SelectedACDCCoupling.key == "D"){
		if(this.SelectedOutputVoh.key == "6" && (this.SelectedOutputVol.key != "3" && this.SelectedOutputVol.key != "4")){
			this.validationErrors.push(getErrorByErrorID("2"));
			return false;
		}
		if(this.SelectedOutputVoh.key == "7" && (this.SelectedOutputVol.key != "3" && this.SelectedOutputVol.key != "4" && this.SelectedOutputVol.key != "5")){
			this.validationErrors.push(getErrorByErrorID("2"));
			return false;
		}
		if(this.SelectedOutputVoh.key == "8" && (this.SelectedOutputVol.key == "7" || this.SelectedOutputVol.key == "8" )){
			this.validationErrors.push(getErrorByErrorID("2"));
			return false;
		}
		if(this.SelectedOutputVoh.key == "9" && this.SelectedOutputVol.key == "8"){
			this.validationErrors.push(getErrorByErrorID("2"));
			return false;
		}
		if(this.SelectedOutputVoh.key == "2" && (this.SelectedOutputVol.key == "3" || this.SelectedOutputVol.key == "4")){
			this.validationErrors.push(getErrorByErrorID("2"));
			return false;
		}
	}

	return true;
	
}*/
/*Family1552.prototype.Parse = function(partNumber){
	Family1552.base.Parse.call(this,partNumber);
	Family1552.base.GetChangeOptionRestrictionResult.call(this);
}
	/*if(this.SelectedACDCCoupling.key == "A"){
		this.OutputVoh= new Array();
		this.OutputVol= new Array();
		this.OutputVol.push(createArrayObject("1","200mV"),createArrayObject("2","250mV"),createArrayObject("3","300mV"),createArrayObject("4","400mV"),
		createArrayObject("5","500mV"),createArrayObject("6","600mV"),createArrayObject("7","700mV"),createArrayObject("8","800mV"));
		this.OutputVoh.push(createArrayObject("A","AC-coupled Receiver", "150"));
	}
	else if(this.SelectedACDCCoupling.key == "D"){
		this.OutputVoh= new Array();
		this.OutputVol= new Array();
		this.OutputVol.push(createArrayObject("3","350mV"),createArrayObject("4","400mV"),createArrayObject("5","520mV"),createArrayObject("6","600mV"),
		createArrayObject("7","700mV"),createArrayObject("8","800mV"),createArrayObject("C","Rail-To-Rail"));
		this.OutputVoh.push(createArrayObject("2","1.225V"),createArrayObject("1","1.10V"),createArrayObject("0","1.00V"),createArrayObject("9","900mV"),
		createArrayObject("8","800mV"),createArrayObject("7","700mV"),createArrayObject("6","600mV"),createArrayObject("C","Rail-To-Rail"));
	}
}
Family1552.prototype.GetHTML = function(selector){
	if(this.SelectedACDCCoupling.key == "A"){
		ACDCCouplingWithOutputVoh.base.GetHTML.call(this,selector,this.Mode);
		AppendHTMLResource(selector,Headers.ACSwing, this.OutputVol, "outputvol",this,"OnOutputVolChanged",this.Mode);
	}
	else if(this.SelectedACDCCoupling.key == "D"){
		Family1552.base.GetHTML.call(this,selector,this.Mode);
	}
};
*/

Family1552.prototype.AvailableOptionsShow = function() {
    var acdc = this.SelectedACDCCoupling.key;

    if (acdc != this.lastAcdc) {
        this.lastAcdc = acdc;
        if (acdc == "A") {
            hideOption("outputvoh", "A");
            hideOption("outputvol", "3");
            SelectOption("outputvoh", "A");
            SelectOption("outputvol", "3");
            this.SelectedOutputVoh.key = "A";
            this.SelectedOutputVol.key = "3";
            SelectOptions();
        } else if (acdc == "D") {
            showOption("outputvoh", "A");
            showOption("outputvol", "3");
            DeselectOption("outputvoh", "A");
            DeselectOption("outputvol", "3");
            SelectOption("outputvoh", "C");
            SelectOption("outputvol", "C");
            SelectOptions();
            this.SelectedOutputVoh.key = "C";
            this.SelectedOutputVol.key = "C";
            SelectOptions();
        }
    }

    if (this.SelectedOutputVoh.key != this.lastVoh) {
        this.lastVoh = this.SelectedOutputVoh.key;

        if (this.SelectedOutputVoh.key == "C") {
            SelectOption("outputvol", "C");
            this.SelectedOutputVol.key = "C"
            return true;
        } else if (this.SelectedOutputVoh.key == "1") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        } else if (this.SelectedOutputVoh.key == "2") {
            SelectOption("outputvol", "6");
            this.SelectedOutputVol.key = "6"
            return true;
        } else if (this.SelectedOutputVoh.key == "7") {
            SelectOption("outputvol", "4");
            this.SelectedOutputVol.key = "4"
        }
        this.lastVoh = this.SelectedOutputVoh.key;
        this.lastVol = this.SelectedOutputVol.key;

    }

    if (this.SelectedOutputVol.key != this.lastVol) {
        this.lastVol = this.SelectedOutputVol.key;

        if (this.SelectedOutputVol.key == "C") {
            SelectOption("outputvoh", "C");
            this.SelectedOutputVoh.key = "C";
            return true;
        } else if (this.SelectedOutputVol.key == "4") {
            SelectOption("outputvoh", "1");
            this.SelectedOutputVoh.key = "1";
            return true;
        } else if (this.SelectedOutputVol.key == "6") {
            SelectOption("outputvoh", "2");
            this.SelectedOutputVoh.key = "2";
            return true;
        }
    }
}

function Family1566() {
    BaseWithPin.call(this);
};
Family1566.prototype = new BaseWithPin(inheriting);
Family1566.base = BaseWithPin.prototype;
Family1566.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1566");
    Family1566.base.Initialization.call(this, jsonData);
}
Family1566.prototype.ExclusionTable = function() {
    return true; //Family1566.base.GetRestrictionResult.call(this);
}

function Family1568() {
    OutputVohWithOutputVol.call(this);
};
Family1568.prototype = new OutputVohWithOutputVol(inheriting);
Family1568.base = OutputVohWithOutputVol.prototype;
Family1568.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1568");
    Family1568.base.Initialization.call(this, jsonData);
}
Family1568.prototype.ExclusionTable = function() {
    return true; //Family1568.base.GetRestrictionResult.call(this);
}

function Family1572() {
    OutputVohWithOutputVolWithVoltagePin.call(this);
};
Family1572.prototype = new OutputVohWithOutputVolWithVoltagePin(inheriting);
Family1572.base = OutputVohWithOutputVolWithVoltagePin.prototype;
Family1572.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1572");
    Family1572.base.Initialization.call(this, jsonData);
}
Family1572.prototype.ExclusionTable = function() {
    return true;
}

function Family1573() {
    OutputVohWithOutputVol.call(this);
};
Family1573.prototype = new OutputVohWithOutputVol(inheriting);
Family1573.base = OutputVohWithOutputVol.prototype;
Family1573.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1573");
    Family1573.base.Initialization.call(this, jsonData);
}
Family1573.prototype.ExclusionTable = function() {
    return true; //Family1573.base.GetRestrictionResult.call(this);
}

function Family1576() {
    BaseWithPin.call(this);
};
Family1576.prototype = new BaseWithPin(inheriting);
Family1576.base = BaseWithPin.prototype;
Family1576.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1576");
    Family1576.base.Initialization.call(this, jsonData);
}

Family1576.prototype.ExclusionTable = function() {
    return true;
}

Family1576.prototype.Validate = function() {
    //var result = BaseViewModel.prototype.OnFrequencyChange.call(this);
    if (this.Frequency.key != "")
        this.Frequency.key = PadToFour(this.Frequency.key)
    //if (result)
    //this.ShowGenerateResult();
    return Family1576.base.Validate.call(this);
}

function Family1579() {
    BaseWithPin.call(this);
};
Family1579.prototype = new BaseWithPin(inheriting);
Family1579.base = BaseWithPin.prototype;
Family1579.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1579");
    Family1579.base.Initialization.call(this, jsonData);
}

Family1579.prototype.ExclusionTable = function() {
    return true;
}

Family1579.prototype.Validate = function() {
    if (this.Frequency.key != "")
        this.Frequency.key = PadToFour(this.Frequency.key)
    return Family1579.base.Validate.call(this);
}

function Family1580() {
    OutputVohWithOutputVol.call(this);
};
Family1580.prototype = new OutputVohWithOutputVol(inheriting);
Family1580.base = OutputVohWithOutputVol.prototype;
Family1580.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1580");
    Family1580.base.Initialization.call(this, jsonData);
}
Family1580.prototype.ExclusionTable = function() {
    return true; //Family1568.base.GetRestrictionResult.call(this);
}

function Family1581() {
    BaseWithPin.call(this);
};
Family1581.prototype = new BaseWithPin(inheriting);
Family1581.base = BaseWithPin.prototype;
Family1581.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1581");
    Family1581.base.Initialization.call(this, jsonData);
}

Family1581.prototype.ExclusionTable = function() {
    return true;
}

Family1581.prototype.Validate = function() {
    //var result = BaseViewModel.prototype.OnFrequencyChange.call(this);
    if (this.Frequency.key != "")
        this.Frequency.key = PadToFour(this.Frequency.key)
    //if (result)
    //this.ShowGenerateResult();
    return Family1581.base.Validate.call(this);
}

function Family1569() {
    BaseWithPin.call(this);
};
Family1569.prototype = new BaseWithPin(inheriting);
Family1569.base = BaseWithPin.prototype;
Family1569.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1569");
    Family1569.base.Initialization.call(this, jsonData);
}

Family1569.prototype.ExclusionTable = function() {
    return true;
}

Family1569.prototype.Validate = function() {
    //var result = BaseViewModel.prototype.OnFrequencyChange.call(this);
    if (this.Frequency.key != "")
        this.Frequency.key = PadToFour(this.Frequency.key)
    //if (result)
    //this.ShowGenerateResult();
    return Family1569.base.Validate.call(this);
}

function Family1602() {
    BaseWithPin.call(this);
};
Family1602.prototype = new BaseWithPin(inheriting);
Family1602.base = BaseWithPin.prototype;
Family1602.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1602");
    Family1602.base.Initialization.call(this, jsonData);
}

Family1602.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }
}

/*Family1602.prototype.Validate = function(){
	if (this.SelectedPackageSize.key != ""){		

		if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2"){
			this.Packaging = this.PackagingList1;
			if(this.SelectedPackaging.key == ""){
				return Family1602.base.Validate.call(this);
			}
			else if(this.SelectedPackaging.key == "T"){
				this.SelectedPackaging.key = "D";
				return Family1602.base.Validate.call(this);
				
			}
			else if(this.SelectedPackaging.key == "Y"){
				this.SelectedPackaging.key = "E";
				return Family1602.base.Validate.call(this);				
				
			}
			else if(this.SelectedPackaging.key == "X"){
				this.SelectedPackaging.key = "G";
				return Family1602.base.Validate.call(this);
			}
			else {
				return Family1602.base.Validate.call(this);
			}
		}
		else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

			if(this.SelectedPackaging.key == "D"){
				this.SelectedPackaging.key = "T";
				if (this.Mode == "Decoder"){
				return Family1602.base.Validate.call(this);		
				}		
				
			}
			else if(this.SelectedPackaging.key == "E"){
				this.SelectedPackaging.key = "Y";
				if (this.Mode == "Decoder"){
				return Family1602.base.Validate.call(this);		
				}				
				
			}
			else if(this.SelectedPackaging.key == "G"){
				this.SelectedPackaging.key = "X";
				if (this.Mode == "Decoder"){
				return Family1602.base.Validate.call(this);		
				}		
				
			}
			else if(this.SelectedPackaging.key == ""){
				
				return Family1602.base.Validate.call(this);
			}	
			else if (this.SelectedPackaging.key !== "X" && this.SelectedPackaging.key !== "Y" && this.SelectedPackaging.key !== "T") {
			    return Family1602.base.Validate.call(this);
			}		
		}
				if (this.Mode == "Decoder"){
					return Family1602.base.Validate.call(this);		
				}				
		return this.SelectedPackaging.key;
	}*/

/*if((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" ))
{
	this.validationErrors.push(getErrorByErrorID("29"));
	return false;
}
if((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2" ))
{
	this.validationErrors.push(getErrorByErrorID("29"));
	return false;
}

return Family1602.base.Validate.call(this);*/
//return Family1602.base.Validate.call(this);
//}
/*Family1602.prototype.ExclusionTable = function(){
	return true;
}*/

Family1602.prototype.SelectOptions = function(partNumber) {
    Family1602.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D")) {
        SelectOption("packaging", "T");
    }
}

/*Family1602.prototype.Parse = function(partNumber){
	Family1602.base.Parse.call(this,partNumber);
	Family1602.base.GetChangeOptionRestrictionResult.call(this);
}*/

Family1602.prototype.ExclusionTable = function() {
    return Family1602.base.GetRestrictionResult.call(this);
}
/*Family1602.prototype.Validate = function(){
	if((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	if((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	
	return Family1602.base.Validate.call(this);
}

Family1602.prototype.ExclusionTable = function(){
	return true;
}
Family1602.prototype.Parse = function(partNumber){
	Family1602.base.Parse.call(this,partNumber);
	Family1602.base.GetChangeOptionRestrictionResult.call(this);
	return Family1602.base.GetRestrictionResult.call(this);
	if(this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2"){
		this.Packaging.push(createArrayObject("","Bulk"),createArrayObject("G","250U Reel"),createArrayObject("D","3KU"),createArrayObject("E","1KU"));
	}
	else if(this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3"){
		this.Packaging.push(createArrayObject("","Bulk"),createArrayObject("X","250U Reel"),createArrayObject("T","3KU"),createArrayObject("Y","1KU"));
	}
	else if(this.SelectedPackageSize.key === undefined || this.SelectedPackageSize.key=== inheriting ||  this.SelectedPackageSize.key == "X" || this.SelectedPackageSize.key == ""){
		this.Packaging.push(createArrayObject("","Bulk"),createArrayObject("G","250U Reel"),createArrayObject("X","250U Reel"),createArrayObject("D","3KU"),
							createArrayObject("T","3KU"),createArrayObject("E","1KU"),createArrayObject("Y","1KU"));
	}
}*/

function Family1618() {
    BaseWithPin.call(this);
};
Family1618.prototype = new BaseWithPin(inheriting);
Family1618.base = BaseWithPin.prototype;
Family1618.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT1618");
    Family1618.base.Initialization.call(this, jsonData);
}

Family1618.prototype.AvailableOptionsShow = function() {

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family1618.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family1618.base.Validate.call(this);
}
Family1618.prototype.ExclusionTable = function() {
    return true;
}
Family1618.prototype.Parse = function(partNumber) {
    Family1618.base.Parse.call(this, partNumber);
    Family1618.base.GetChangeOptionRestrictionResult.call(this);
    /*if(this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2"){
    	this.Packaging.push(createArrayObject("","Bulk"),createArrayObject("G","250U Reel"),createArrayObject("D","3KU"),createArrayObject("E","1KU"));
    }
    else if(this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3"){
    	this.Packaging.push(createArrayObject("","Bulk"),createArrayObject("X","250U Reel"),createArrayObject("T","3KU"),createArrayObject("Y","1KU"));
    }
    else if(this.SelectedPackageSize.key === undefined || this.SelectedPackageSize.key=== inheriting ||  this.SelectedPackageSize.key == "X" || this.SelectedPackageSize.key == ""){
    	this.Packaging.push(createArrayObject("","Bulk"),createArrayObject("G","250U Reel"),createArrayObject("X","250U Reel"),createArrayObject("D","3KU"),
    						createArrayObject("T","3KU"),createArrayObject("E","1KU"),createArrayObject("Y","1KU"));
    }*/
}

function Family2002() {
    BaseWithPin.call(this);
};
Family2002.prototype = new BaseWithPin(inheriting);
Family2002.base = BaseWithPin.prototype;
Family2002.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2002");
    Family2002.base.Initialization.call(this, jsonData);
}
/*Family2002.prototype.Validate = function(){
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
	return Family2002.base.Validate.call(this);
}*/
Family2002.prototype.ExclusionTable = function() {
    return true;
}

function Family8021() {
    BaseWithPin.call(this);
};
Family8021.prototype = new BaseWithPin(inheriting);
Family8021.base = BaseWithPin.prototype;
Family8021.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8021");
    Family8021.base.Initialization.call(this, jsonData);
}

Family8021.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var tolerance = this.SelectedFrequencyStability.key;

    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "3") {
            disableOption("temprange", "I");
            enableOption("temprange", "C");
            DeselectOption("temprange", "I");
            SelectOption("temprange", "C");
            this.SelectedTemperatureRange.key = "C";

        } else if (tolerance == "4") {
            enableOption("temprange", "I");
        }
    }
}

Family8021.prototype.ExclusionTable = function() {
    return Family8021.base.GetRestrictionResult.call(this);
}

function Family2045() {
    FeaturePinWithSpecialFeatures.call(this);
};
Family2045.prototype = new FeaturePinWithSpecialFeatures(inheriting);
Family2045.base = FeaturePinWithSpecialFeatures.prototype;
Family2045.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2045");
    Family2045.base.Initialization.call(this, jsonData);
}

Family2045.prototype.ExclusionTable = function() {
    return true;
}

Family2045.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.795999": "70.485001",
            "79.062999": "79.162001",
            "81.427999": "82.232001",
            "91.833999": "92.155001",
            "94.248999": "94.430001",
            "94.874999": "94.994001",
            "97.713999": "98.679001"
        };
    } else if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.239999": "70.827001",
            "78.714999": "79.561001",
            "80.159999": "80.174001",
            "80.779999": "82.632001",
            "91.833999": "95.474001",
            "96.191999": "96.209001",
            "96.935999": "99.158001"
        };
    } else {
        this.FrequencyHoles = {};
    }
}

Family2045.prototype.Parse = function(partNumber) {
    Family2045.base.Parse.call(this, partNumber);
    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "117.810999": "118.038001",
            "118.593999": "118.743001",
            "122.141999": "122.705001",
            "123.021999": "123.348001"
        };
    } else if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "115.200000": "119.342001",
            "120.238999": "120.262001",
            "121.169999": "121.243001",
            "121.600999": "123.948001"
        };
    } else {
        this.FrequencyHoles = {};
    }
}

function Family2044() {
    FeaturePinWithSpecialFeatures.call(this);
};
Family2044.prototype = new FeaturePinWithSpecialFeatures(inheriting);
Family2044.base = FeaturePinWithSpecialFeatures.prototype;
Family2044.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2044");
    Family2044.base.Initialization.call(this, jsonData);
}

Family2044.prototype.ExclusionTable = function() {
    return true;
}

Family2044.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.795999": "70.485001",
            "79.062999": "79.162001",
            "81.427999": "82.232001",
            "91.833999": "92.155001",
            "94.248999": "94.430001",
            "94.874999": "94.994001",
            "97.713999": "98.679001"
        };
    } else if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.239999": "70.827001",
            "78.714999": "79.561001",
            "80.159999": "80.174001",
            "80.779999": "82.632001",
            "91.833999": "95.474001",
            "96.191999": "96.209001",
            "96.935999": "99.158001"
        };
    } else {
        this.FrequencyHoles = {};
    }
}

Family2044.prototype.Parse = function(partNumber) {
    Family2044.base.Parse.call(this, partNumber);
    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.795999": "70.485001",
            "79.062999": "79.162001",
            "81.427999": "82.232001",
            "91.833999": "92.155001",
            "94.248999": "94.430001",
            "94.874999": "94.994001",
            "97.713999": "98.679001"
        };
    } else if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.239999": "70.827001",
            "78.714999": "79.561001",
            "80.159999": "80.174001",
            "80.779999": "82.632001",
            "91.833999": "95.474001",
            "96.191999": "96.209001",
            "96.935999": "99.158001"
        };
    } else {
        this.FrequencyHoles = {};
    }
}

function Family2018() {
    BaseWithPin.call(this);
};
Family2018.prototype = new BaseWithPin(inheriting);
Family2018.base = BaseWithPin.prototype;
Family2018.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2018");
    Family2018.base.Initialization.call(this, jsonData);
}

Family2018.prototype.ExclusionTable = function() {
    return true;
}

function Family2019() {
    BaseWithPin.call(this);
};
Family2019.prototype = new BaseWithPin(inheriting);
Family2019.base = BaseWithPin.prototype;
Family2019.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2019");
    Family2019.base.Initialization.call(this, jsonData);
}
/*Family2019.prototype.Validate = function(){
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
	return Family2002.base.Validate.call(this);
}*/
Family2019.prototype.ExclusionTable = function() {
    return true;
}

function Family2020() {
    BaseWithPin.call(this);
};
Family2020.prototype = new BaseWithPin(inheriting);
Family2020.base = BaseWithPin.prototype;
Family2020.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2020");
    Family2020.base.Initialization.call(this, jsonData);
}
/*Family2020.prototype.Validate = function(){
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
	return Family2002.base.Validate.call(this);
}*/
Family2020.prototype.ExclusionTable = function() {
    return true;
}

function Family2021() {
    BaseWithPin.call(this);
};
Family2021.prototype = new BaseWithPin(inheriting);
Family2021.base = BaseWithPin.prototype;
Family2021.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2021");
    Family2021.base.Initialization.call(this, jsonData);
}
/*
Family2021.prototype.Validate = function(){
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
	return Family2002.base.Validate.call(this);
}*/
Family2021.prototype.ExclusionTable = function() {
    return true;
}

function Family2024() {
    BaseWithPin.call(this);
};
Family2024.prototype = new BaseWithPin(inheriting);
Family2024.base = BaseWithPin.prototype;
Family2024.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2024");
    Family2024.base.Initialization.call(this, jsonData);
}
/*Family2024.prototype.Validate = function(){
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
		
	return Family2002.base.Validate.call(this);
}*/
Family2024.prototype.ExclusionTable = function() {
    return true;
}
/*Family2024.prototype.Parse = function(partNumber){
	Family2024.base.Parse.call(this,partNumber);
	
	if(this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A"){
		this.FrequencyHoles = {"61.223000":"61.674000","69.796000":"70.485000","79.063000":"79.162000","81.428000":"82.232000","91.834000":"92.155000",
		"94.249000":"94.430000","94.875000":"94.994000","97.714000":"98.679000"};
	}
	
	if(this.SelectedTemperatureRange.key == "M"){
		this.FrequencyHoles = {"61.223000":"61.674000","69.240000":"70.827000","78.715000":"79.561000","80.160000":"80.174000","80.780000":"82.632000",
			"91.834000":"95.474000","96.192000":"96.209000","96.936000":"99.158000"}
	}
}*/

Family2024.prototype.Parse = function(partNumber) {
    Family2024.base.Parse.call(this, partNumber);
    Family2024.base.GetChangeOptionRestrictionResult.call(this);
}

Family2024.prototype.SelectOptions = function(partNumber) {
    Family2024.base.SelectOptions.call(this, partNumber);
    if (this.SelectedPackaging.key == "A") {
        SelectOption("packaging", "G");
    } else if (this.SelectedPackaging.key == "B") {
        SelectOption("packaging", "E");
    } else if (this.SelectedPackaging.key == "C") {
        SelectOption("packaging", "D");
    }
}

function Family2025() {
    BaseWithPin.call(this);
};
Family2025.prototype = new BaseWithPin(inheriting);
Family2025.base = BaseWithPin.prototype;
Family2025.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2025");
    Family2025.base.Initialization.call(this, jsonData);
}
/*Family2025.prototype.Validate = function(){
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
		
	if(this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A"){
		this.FrequencyHoles = {"117.811000":"118.038000","118.594000":"118.743000","122.142000":"122.705000","123.022000":"123.348000"};
	}
	
	if(this.SelectedTemperatureRange.key == "M"){
		this.FrequencyHoles = {"120.239000":"120.262000","121.170000":"121.243000","121.601000":"123.948000"}
	}
	
	return Family2002.base.Validate.call(this);
}*/
Family2025.prototype.ExclusionTable = function() {
    return true;
}
Family2025.prototype.Parse = function(partNumber) {
    Family2025.base.Parse.call(this, partNumber);
    Family2025.base.GetChangeOptionRestrictionResult.call(this);
}

Family2025.prototype.SelectOptions = function(partNumber) {
    Family2025.base.SelectOptions.call(this, partNumber);
    if (this.SelectedPackaging.key == "A") {
        SelectOption("packaging", "G");
    } else if (this.SelectedPackaging.key == "B") {
        SelectOption("packaging", "E");
    } else if (this.SelectedPackaging.key == "C") {
        SelectOption("packaging", "D");
    }
}

function Family3372() {
    SignallingTypeWithVCMO.call(this);
};
Family3372.prototype = new SignallingTypeWithVCMO(inheriting);
Family3372.base = SignallingTypeWithVCMO.prototype;
Family3372.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3372");
    Family3372.base.Initialization.call(this, jsonData);
}

Family3372.prototype.ExclusionTable = function() {
    return Family3372.base.GetRestrictionResult.call(this);
}

Family3372.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "9");
            disableOption("tolerance", "3");
			enableOption("tolerance", "H");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "9");
            enableOption("tolerance", "2");
            enableOption("tolerance", "H");
        } else if (this.SelectedVCMO.key == "G" || this.SelectedVCMO.key == "H" || this.SelectedVCMO.key == "X" || this.SelectedVCMO.key == "Y" || this.SelectedVCMO.key == "Z" || this.SelectedVCMO.key == "U") {
			disableOption("tolerance", "H");
            enableOption("tolerance", "2");
            enableOption("tolerance", "9");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "M");
        } else if (this.SelectedFrequencyStability.key == "9") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "M");
            
        } else if (this.SelectedFrequencyStability.key == "3") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
		} else if (this.SelectedFrequencyStability.key == "H") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "G");
            disableOption("vcmo", "H");
            disableOption("vcmo", "X");
            disableOption("vcmo", "Y");
            disableOption("vcmo", "Z");
            disableOption("vcmo", "U");       
        }
    }
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            this.SelectedFeaturePin.key = "N";
            disableOption("pin", "E");
            SelectOption("pin", "N");

        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "N") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            enableOption("pin", "E");
            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family3372.prototype.Parse = function(partNumber) {
    Family3372.base.Parse.call(this, partNumber);
    Family3372.base.GetChangeOptionRestrictionResult.call(this);
}

Family3372.prototype.SelectOptions = function(partNumber) {
    Family3372.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }

    /*if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
    	SelectOption("packaging", "X");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
    	SelectOption("packaging", "Y");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
    	SelectOption("packaging", "T");
    }*/
}

function Family3373() {
    SignallingTypeWithVCMO.call(this);
};
Family3373.prototype = new SignallingTypeWithVCMO(inheriting);
Family3373.base = SignallingTypeWithVCMO.prototype;
Family3373.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3373");
    Family3373.base.Initialization.call(this, jsonData);
}

Family3373.prototype.ExclusionTable = function() {
    return Family3373.base.GetRestrictionResult.call(this);
}

Family3373.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "9");
            disableOption("tolerance", "3");
			enableOption("tolerance", "H");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "9");
            enableOption("tolerance", "2");
            enableOption("tolerance", "H");
        } else if (this.SelectedVCMO.key == "G" || this.SelectedVCMO.key == "H" || this.SelectedVCMO.key == "X" || this.SelectedVCMO.key == "Y" || this.SelectedVCMO.key == "Z" || this.SelectedVCMO.key == "U") {
			disableOption("tolerance", "H");
            enableOption("tolerance", "2");
            enableOption("tolerance", "9");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "M");
        } else if (this.SelectedFrequencyStability.key == "9") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "M");
            
        } else if (this.SelectedFrequencyStability.key == "3") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
		} else if (this.SelectedFrequencyStability.key == "H") {
			enableAllOptions("vcmo");
            disableOption("vcmo", "G");
            disableOption("vcmo", "H");
            disableOption("vcmo", "X");
            disableOption("vcmo", "Y");
            disableOption("vcmo", "Z");
            disableOption("vcmo", "U");       
        }
    }
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            this.SelectedFeaturePin.key = "N";
            disableOption("pin", "E");
            SelectOption("pin", "N");

        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "N") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            enableOption("pin", "E");
            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}


Family3373.prototype.Parse = function(partNumber) {
    Family3373.base.Parse.call(this, partNumber);
    Family3373.base.GetChangeOptionRestrictionResult.call(this);
}

Family3373.prototype.SelectOptions = function(partNumber) {
    Family3373.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }

    /*if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
    	SelectOption("packaging", "X");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
    	SelectOption("packaging", "Y");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
    	SelectOption("packaging", "T");
    }*/
}

function Family3701() {
    BaseWithVCMO.call(this);
};
Family3701.prototype = new BaseWithVCMO(inheriting);
Family3701.base = BaseWithVCMO.prototype;
Family3701.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3701");
    Family3701.base.Initialization.call(this, jsonData);
}
Family3701.prototype.ExclusionTable = function() {
    return Family3701.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="1")&&(this.SelectedTemperatureRange.key=="I")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    else return true;	
    */
}
Family3701.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "1");
    } else {
        enableOption("tolerance", "1");
    }
    if (this.SelectedFrequencyStability.key == "1") {
        disableOption("temprange", "I");
    } else {
        enableOption("temprange", "I");
    }
}

function Family3702() {
    SignalingTypeWithSwing.call(this);
};
Family3702.prototype = new SignalingTypeWithSwing(inheriting);
Family3702.base = SignalingTypeWithSwing.prototype;
Family3702.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3702");
    Family3702.base.Initialization.call(this, jsonData);
}
Family3702.prototype.ExclusionTable = function() {
    return Family3702.base.GetRestrictionResult.call(this);
    /*
	if((this.SelectedTemperatureRange.key=="C")||(this.SelectedTemperatureRange.key=="I")){
		if(this.SelectedFrequencyStability.key=="F"){
			this.validationErrors.push(getErrorByErrorID("5"));
			return false;
		}
		if((this.SelectedSupplyVoltage.key=="18")&&(this.SelectedFrequencyStability.key=="H")){
			this.validationErrors.push(getErrorByErrorID("5"));
			return false;
		}
	}
	else if(this.SelectedTemperatureRange.key=="N"){
		if((this.SelectedFrequencyStability.key=="F")&&(this.SelectedSupplyVoltage.key=="18")){
			this.validationErrors.push(getErrorByErrorID("5"));
			return false;
		}
		else if((this.SelectedFrequencyStability.key!="F")&&(this.SelectedFrequencyStability.key!="H")){
		this.validationErrors.push(getErrorByErrorID("5"));
			return false;
		}
	}
	return true;
*/
}
Family3702.prototype.AvailableOptionsShow = function() {
    /*if (this.SelectedFrequencyStability.key == "F"){
    	disableOption("temprange", "C");
    	disableOption("temprange", "I");
    	enableOption("temprange", "N");
    	if (this.SelectedSupplyVoltage.key == "18") disableOption("voltage", "18"); else enableOption("voltage", "18");
    }
    
    if (this.SelectedFrequencyStability.key == "H"){
    	if ()
    }
    
    if (this.SelectedFrequencyStability.key == "1" || this.SelectedFrequencyStability.key == "2" || this.SelectedFrequencyStability.key == "3"){
    	disableOption("temprange", "N");
    	enableOption("temprange", "C");
    	enableOption("temprange", "I");
    }*/
}

function Family8033() {
    BaseWithFrequencySelect.call(this);
};
Family8033.prototype = new BaseWithFrequencySelect(inheriting);
Family8033.base = BaseWithFrequencySelect.prototype;
Family8033.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8033");
    Family8033.base.Initialization.call(this, jsonData);
}
Family8033.prototype.ExclusionTable = function() {
    if (this.SelectedFrequencySelectOption.key == "B") this.MinFrequency = "3";
    if (this.SelectedFrequencySelectOption.key == "C") this.MinFrequency = "4";
    if (this.SelectedFrequencySelectOption.key == "D") this.MinFrequency = "8";

    return Family8033.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="1")&&(this.SelectedTemperatureRange.key=="I")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    else return true;
    */
}
Family8033.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "1");
    } else {
        enableOption("tolerance", "1");
    }
    if (this.SelectedFrequencyStability.key == "1") {
        disableOption("temprange", "I");
    } else {
        enableOption("temprange", "I");
    }
}

function Family8002() {
    BaseWithPin.call(this);
};
Family8002.prototype = new BaseWithPin(inheriting);
Family8002.base = BaseWithPin.prototype;
Family8002.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8002");
    Family8002.base.Initialization.call(this, jsonData);
}
Family8002.prototype.ExclusionTable = function() {
    return true;
}

function Family8002AA() {
    BaseWithPin.call(this);
};
Family8002AA.prototype = new BaseWithPin(inheriting);
Family8002AA.base = BaseWithPin.prototype;
Family8002AA.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8002AA");
    Family8002AA.base.Initialization.call(this, jsonData);
}
Family8002AA.prototype.ExclusionTable = function() {
    return true;
}

function Family8003() {
    BaseWithPin.call(this);
};
Family8003.prototype = new BaseWithPin(inheriting);
Family8003.base = BaseWithPin.prototype;
Family8003.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8003");
    Family8003.base.Initialization.call(this, jsonData);
}
Family8003.prototype.ExclusionTable = function() {
    return Family8003.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="1")&&(this.SelectedTemperatureRange.key=="I")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    else return true;
    */
}
Family8003.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "1");
    } else {
        enableOption("tolerance", "1");
    }
    if (this.SelectedFrequencyStability.key == "1") {
        disableOption("temprange", "I");
    } else {
        enableOption("temprange", "I");
    }
}

function Family8503() {
    BaseWithPin.call(this);
};
Family8503.prototype = new BaseWithPin(inheriting);
Family8503.base = BaseWithPin.prototype;
Family8503.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8503");
    Family8503.base.Initialization.call(this, jsonData);
}
Family8503.prototype.ExclusionTable = function() {
    return Family8503.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="1")&&(this.SelectedTemperatureRange.key=="I")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    else return true;
    */
}
Family8503.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "1");
    } else {
        enableOption("tolerance", "1");
    }
    if (this.SelectedFrequencyStability.key == "1") {
        disableOption("temprange", "I");
    } else {
        enableOption("temprange", "I");
    }
}

function Family8004() {
    BaseWithPin.call(this);
};
Family8004.prototype = new BaseWithPin(inheriting);
Family8004.base = BaseWithPin.prototype;
Family8004.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8004");
    Family8004.base.Initialization.call(this, jsonData);
}
Family8004.prototype.ExclusionTable = function() {
    return Family8004.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="1")&&(this.SelectedTemperatureRange.key=="I")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    else return true;
    */
}
Family8004.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "1");
    } else {
        enableOption("tolerance", "1");
    }
    if (this.SelectedFrequencyStability.key == "1") {
        disableOption("temprange", "I");
    } else {
        enableOption("temprange", "I");
    }
}

function Family8103() {
    BaseWithPin.call(this);
};
Family8103.prototype = new BaseWithPin(inheriting);
Family8103.base = BaseWithPin.prototype;
Family8103.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8103");
    Family8103.base.Initialization.call(this, jsonData);
}
Family8103.prototype.ExclusionTable = function() {
    return Family8103.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="1")&&(this.SelectedTemperatureRange.key=="I")){
    	this.validationErrors.push(getErrorByErrorID("1"));
    	return false;
    }
    else return true;
    */
}
Family8103.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "1");
    } else {
        enableOption("tolerance", "1");
    }
    if (this.SelectedFrequencyStability.key == "1") {
        disableOption("temprange", "I");
    } else {
        enableOption("temprange", "I");
    }
}

function Family8102() {
    BaseWithPin.call(this);
};
Family8102.prototype = new BaseWithPin(inheriting);
Family8102.base = BaseWithPin.prototype;
Family8102.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8102");
    Family8102.base.Initialization.call(this, jsonData);
};
Family8102.prototype.ExclusionTable = function() {
    return Family8102.base.GetRestrictionResult.call(this);
    /*
    if(this.SelectedFrequencyStability.key=="F"){
    	if(this.SelectedTemperatureRange.key!="N"){
    		this.validationErrors.push(getErrorByErrorID("6"));
    	    return false;
    	}
    	else if ((this.SelectedTemperatureRange.key=="N")&&(this.SelectedSupplyVoltage.key=="18")){
    		this.validationErrors.push(getErrorByErrorID("6"));
    	    return false;
    	}
    	else return true;
    }
    else if((this.SelectedSupplyVoltage.key=="18")&&(this.SelectedTemperatureRange.key!="N")&&(this.SelectedFrequencyStability.key=="H")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else return true;
    */
}
Family8102.prototype.AvailableOptionsShow = function() {
    if (this.SelectedSupplyVoltage.key == "18") {
        disableOption("tolerance", "F");
        disableOption("tolerance", "H");
        if (this.SelectedTemperatureRange.key == "N") {
            enableOption("tolerance", "H");
        }
    } else if (this.SelectedTemperatureRange.key == "C" || this.SelectedTemperatureRange.key == "I") {
        disableOption("tolerance", "F");
    } else {
        enableOption("tolerance", "F");
        enableOption("tolerance", "H");
    }
    if (this.SelectedFrequencyStability.key == "F") {
        disableOption("temprange", "C");
        disableOption("temprange", "I");
        disableOption("voltage", "18");
    } else if (this.SelectedFrequencyStability.key == "H") {
        disableOption("voltage", "18");
        enableOption("temprange", "C");
        enableOption("temprange", "I");
        if (this.SelectedTemperatureRange.key == "N") {
            enableOption("voltage", "18");
        }
    } else {
        enableOption("temprange", "C");
        enableOption("temprange", "I");
        enableOption("voltage", "18");
    }
}

function Family8208() {
    BaseWithPin.call(this);
};
Family8208.prototype = new BaseWithPin(inheriting);
Family8208.base = BaseWithPin.prototype;
Family8208.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8208");
    Family8208.base.Initialization.call(this, jsonData);
}
Family8208.prototype.ExclusionTable = function() {
    return true;
}

function Family8209() {
    BaseWithPin.call(this);
};
Family8209.prototype = new BaseWithPin(inheriting);
Family8209.base = BaseWithPin.prototype;
Family8209.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8209");
    Family8209.base.Initialization.call(this, jsonData);
}
Family8209.prototype.ExclusionTable = function() {
    return true;
}

function Family8003XT() {
    BaseWithPin.call(this);
};
Family8003XT.prototype = new BaseWithPin(inheriting);
Family8003XT.base = BaseWithPin.prototype;
Family8003XT.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8003XT");
    Family8003XT.base.Initialization.call(this, jsonData);
}
Family8003XT.prototype.ExclusionTable = function() {
    return true;
}

function Family8008() {
    BaseWithPin.call(this);
};
Family8008.prototype = new BaseWithPin(inheriting);
Family8008.base = BaseWithPin.prototype;
Family8008.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8008");
    Family8008.base.Initialization.call(this, jsonData);
}

Family8008.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }
}

Family8008.prototype.SelectOptions = function(partNumber) {
    Family8008.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D")) {
        SelectOption("packaging", "T");
    }
}
/*Family8008.prototype.AvailableOptionsShow = function(){
	if (this.SelectedPackageSize.key != ""){
		if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2"){
			if(this.SelectedPackaging.key == ""){
				return Family8008.base.Validate.call(this);
			}
			else if(this.SelectedPackaging.key == "T"){
				this.SelectedPackaging.key = "D";
				
			}
			else if(this.SelectedPackaging.key == "Y"){
				this.SelectedPackaging.key = "E";	
				
			}
			else if(this.SelectedPackaging.key == "X"){
				this.SelectedPackaging.key = "G";	
			
			}
			else {
				return Family8008.base.Validate.call(this);
			}
		}
		if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {
			if(this.SelectedPackaging.key == "D"){
				this.SelectedPackaging.key = "T";
				
			}
			else if(this.SelectedPackaging.key == "E"){
				this.SelectedPackaging.key = "Y";	
				
			}
			else if(this.SelectedPackaging.key == "G"){
				this.SelectedPackaging.key = "X";
				
			}
			else if(this.SelectedPackaging.key == ""){
				
				return Family8008.base.Validate.call(this);
			}
			else if (this.SelectedPackaging.key !== "X" && this.SelectedPackaging.key !== "Y" && this.SelectedPackaging.key !== "T"){
				return Family8008.base.Validate.call(this);
			}
		}
		return this.SelectedPackaging.key;
	}
	
	if((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	if((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	
	return Family8008.base.Validate.call(this);
}*/

/*Family8008.prototype.SelectOptions = function(partNumber){
	Family8008.base.SelectOptions.call(this,partNumber);
	if((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")){
		SelectOption("packaging", "G");
	}
	else if((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")){
		SelectOption("packaging", "E");
	}
	else if((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")){
		SelectOption("packaging", "D");
	}
}*/

/*Family8008.prototype.Parse = function(partNumber){
	Family8008.base.Parse.call(this,partNumber);
	Family8008.base.GetChangeOptionRestrictionResult.call(this);
}*/
Family8008.prototype.ExclusionTable = function() {
    return Family8008.base.GetRestrictionResult.call(this);
}

function Family8009() {
    BaseWithPin.call(this);
};
Family8009.prototype = new BaseWithPin(inheriting);
Family8009.base = BaseWithPin.prototype;
Family8009.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8009");
    Family8009.base.Initialization.call(this, jsonData);
}
Family8009.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }
}

Family8009.prototype.SelectOptions = function(partNumber) {
    Family8009.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D")) {
        SelectOption("packaging", "T");
    }
}
Family8009.prototype.ExclusionTable = function() {
    return Family8009.base.GetRestrictionResult.call(this);
}

function Family8225() {
    BaseWithPin.call(this);
};
Family8225.prototype = new BaseWithPin(inheriting);
Family8225.base = BaseWithPin.prototype;
Family8225.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8225");
    Family8225.base.Initialization.call(this, jsonData);
}
Family8225.prototype.ExclusionTable = function() {
    return true;
}

function Family8256() {
    BaseWithPin.call(this);
};
Family8256.prototype = new BaseWithPin(inheriting);
Family8256.base = BaseWithPin.prototype;
Family8256.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8256");
    Family8256.base.Initialization.call(this, jsonData);
}
Family8256.prototype.ExclusionTable = function() {
    return true;
}

function Family8918() {
    BaseWithPin.call(this);
};
Family8918.prototype = new BaseWithPin(inheriting);
Family8918.base = BaseWithPin.prototype;
Family8918.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8918");
    Family8918.base.Initialization.call(this, jsonData);
}

Family8918.prototype.AvailableOptionsShow = function() {

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family8918.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    /*if(this.SelectedPackaging.key == "T")
    	this.SelectedPackaging.key = "D";
    if(this.SelectedPackaging.key == "Y")
    	this.SelectedPackaging.key = "E";	
    if(this.SelectedPackaging.key == "X")
    	this.SelectedPackaging.key = "G";	*/
    return Family8918.base.Validate.call(this);
}
Family8918.prototype.ExclusionTable = function() {
    return true;
}
Family8918.prototype.Parse = function(partNumber) {
    Family8918.base.Parse.call(this, partNumber);
    Family8918.base.GetChangeOptionRestrictionResult.call(this);
}

function Family8919() {
    BaseWithPin.call(this);
};
Family8919.prototype = new BaseWithPin(inheriting);
Family8919.base = BaseWithPin.prototype;
Family8919.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8919");
    Family8919.base.Initialization.call(this, jsonData);
}

Family8919.prototype.AvailableOptionsShow = function() {

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family8919.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    /*if(this.SelectedPackaging.key == "T")
    	this.SelectedPackaging.key = "D";
    if(this.SelectedPackaging.key == "Y")
    	this.SelectedPackaging.key = "E";	
    if(this.SelectedPackaging.key == "X")
    	this.SelectedPackaging.key = "G";	*/
    return Family8919.base.Validate.call(this);
}
Family8919.prototype.ExclusionTable = function() {
    return true;
}
Family8919.prototype.Parse = function(partNumber) {
    Family8919.base.Parse.call(this, partNumber);
    Family8919.base.GetChangeOptionRestrictionResult.call(this);
}

function Family8920() {
    BaseWithPin.call(this);
};
Family8920.prototype = new BaseWithPin(inheriting);
Family8920.base = BaseWithPin.prototype;
Family8920.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8920");
    Family8920.base.Initialization.call(this, jsonData);
}

Family8920.prototype.AvailableOptionsShow = function() {

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family8920.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    /*if(this.SelectedPackaging.key == "T")
    	this.SelectedPackaging.key = "D";
    if(this.SelectedPackaging.key == "Y")
    	this.SelectedPackaging.key = "E";	
    if(this.SelectedPackaging.key == "X")
    	this.SelectedPackaging.key = "G";*/
    return Family8920.base.Validate.call(this);
}
Family8920.prototype.ExclusionTable = function() {
    return true;
}
Family8920.prototype.Parse = function(partNumber) {
    Family8920.base.Parse.call(this, partNumber);
    Family8920.base.GetChangeOptionRestrictionResult.call(this);
}

function Family8921() {
    BaseWithPin.call(this);
};
Family8921.prototype = new BaseWithPin(inheriting);
Family8921.base = BaseWithPin.prototype;
Family8921.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8921");
    Family8921.base.Initialization.call(this, jsonData);
}

Family8921.prototype.AvailableOptionsShow = function() {

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family8921.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    /*if(this.SelectedPackaging.key == "T")
    	this.SelectedPackaging.key = "D";
    if(this.SelectedPackaging.key == "Y")
    	this.SelectedPackaging.key = "E";	
    if(this.SelectedPackaging.key == "X")
    	this.SelectedPackaging.key = "G";*/
    return Family8921.base.Validate.call(this);
}
Family8921.prototype.ExclusionTable = function() {
    return true;
}
Family8921.prototype.Parse = function(partNumber) {
    Family8921.base.Parse.call(this, partNumber);
    Family8921.base.GetChangeOptionRestrictionResult.call(this);
}

function Family8924() {
    BaseWithPin.call(this);
};
Family8924.prototype = new BaseWithPin(inheriting);
Family8924.base = BaseWithPin.prototype;
Family8924.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8924");
    Family8924.base.Initialization.call(this, jsonData);
}
/*
Family8924.prototype.Validate = function(){
	if((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	if((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
	if(this.SelectedPackaging.key == "X")
		this.SelectedPackaging.key = "G";	
	return Family8924.base.Validate.call(this);
}
Family8924.prototype.ExclusionTable = function(){
	return true;
}*/

Family8924.prototype.Validate = function() {
    if (this.SelectedPackageSize.key != "") {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            if (this.SelectedPackaging.key == "") {
                return Family8924.base.Validate.call(this);
            } else if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";

            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";

            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
            } else {
                return Family8924.base.Validate.call(this);
            }
        }
        if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {
            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";

            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            } else if (this.SelectedPackaging.key == "") {

                return Family8924.base.Validate.call(this);
            } else if (this.SelectedPackaging.key !== "X" && this.SelectedPackaging.key !== "Y" && this.SelectedPackaging.key !== "T") {
                return Family8924.base.Validate.call(this);
            }
        }
        return this.SelectedPackaging.key;
    }

    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.795999": "70.485001",
            "79.062999": "79.162001",
            "81.427999": "82.232001",
            "91.833999": "92.155001",
            "94.248999": "94.430001",
            "94.874999": "94.994001",
            "97.713999": "98.679001"
        };
    }

    if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.239999": "70.827001",
            "78.714999": "79.561001",
            "80.159999": "80.174001",
            "80.779999": "82.632001",
            "91.833999": "95.474001",
            "96.191999": "96.209001",
            "96.935999": "99.158001"
        };
    }

    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family8924.base.Validate.call(this);
}
Family8924.prototype.ExclusionTable = function() {
    return Family8924.base.GetRestrictionResult.call(this);
}

Family8924.prototype.SelectOptions = function(partNumber) {
    Family8924.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G") || (this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E") || (this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D") || (this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "T");
    }
}

Family8924.prototype.Parse = function(partNumber) {
    Family8924.base.Parse.call(this, partNumber);
    Family8924.base.GetChangeOptionRestrictionResult.call(this);
}

function Family8925() {
    BaseWithPin.call(this);
};
Family8925.prototype = new BaseWithPin(inheriting);
Family8925.base = BaseWithPin.prototype;
Family8925.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8925");
    Family8925.base.Initialization.call(this, jsonData);
}
Family8925.prototype.Validate = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }

    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "117.811000": "118.038000",
            "118.594000": "118.743000",
            "122.142000": "122.705000",
            "123.022000": "123.348000"
        };
    }

    if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "120.239000": "120.262000",
            "121.170000": "121.243000",
            "121.601000": "123.948000"
        }
    }

    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family8925.base.Validate.call(this);
}
Family8925.prototype.ExclusionTable = function() {
    return Family8925.base.GetRestrictionResult.call(this);
}

Family8925.prototype.SelectOptions = function(partNumber) {
    Family8925.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G") || (this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E") || (this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D") || (this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "T");
    }
}

function Family8926() {
    BaseWithPin.call(this);
};
Family8926.prototype = new BaseWithPin(inheriting);
Family8926.base = BaseWithPin.prototype;
Family8926.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8926");
    Family8926.base.Initialization.call(this, jsonData);
}

Family8926.prototype.ExclusionTable = function() {
    return Family8926.base.GetRestrictionResult.call(this);
}
Family8926.prototype.Parse = function(partNumber) {
    Family8926.base.Parse.call(this, partNumber);
    Family8926.base.GetChangeOptionRestrictionResult.call(this);
}

Family8926.prototype.SelectOptions = function(partNumber) {
    Family8926.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "D");
    }
}

function Family8934() {
    BaseWithPin.call(this);
};
Family8934.prototype = new BaseWithPin(inheriting);
Family8934.base = BaseWithPin.prototype;
Family8934.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8934");
    Family8934.base.Initialization.call(this, jsonData);
}
/*
Family8934.prototype.Validate = function(){
	if((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	if((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2" ))
	{
		this.validationErrors.push(getErrorByErrorID("29"));
		return false;
	}
	if(this.SelectedPackaging.key == "T")
		this.SelectedPackaging.key = "D";
	if(this.SelectedPackaging.key == "Y")
		this.SelectedPackaging.key = "E";	
	if(this.SelectedPackaging.key == "X")
		this.SelectedPackaging.key = "G";	
	return Family8934.base.Validate.call(this);
}
Family8934.prototype.ExclusionTable = function(){
	return true;
}*/

Family8934.prototype.Validate = function() {
    if (this.SelectedPackageSize.key != "") {
        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {
            if (this.SelectedPackaging.key == "") {
                return Family8934.base.Validate.call(this);
            } else if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";

            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";

            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
            } else {
                return Family8934.base.Validate.call(this);
            }
        }
        if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {
            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";

            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            } else if (this.SelectedPackaging.key == "") {

                return Family8934.base.Validate.call(this);
            } else if (this.SelectedPackaging.key !== "X" && this.SelectedPackaging.key !== "Y" && this.SelectedPackaging.key !== "T") {
                return Family8934.base.Validate.call(this);
            }
        }
        return this.SelectedPackaging.key;
    }

    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.795999": "70.485001",
            "79.062999": "79.162001",
            "81.427999": "82.232001",
            "91.833999": "92.155001",
            "94.248999": "94.430001",
            "94.874999": "94.994001",
            "97.713999": "98.679001"
        };
    }

    if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.239999": "70.827001",
            "78.714999": "79.561001",
            "80.159999": "80.174001",
            "80.779999": "82.632001",
            "91.833999": "95.474001",
            "96.191999": "96.209001",
            "96.935999": "99.158001"
        };
    }

    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family8934.base.Validate.call(this);
}
Family8934.prototype.ExclusionTable = function() {
    return Family8934.base.GetRestrictionResult.call(this);
}

Family8934.prototype.SelectOptions = function(partNumber) {
    Family8934.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G") || (this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E") || (this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D") || (this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "T");
    }
}

Family8934.prototype.Parse = function(partNumber) {
    Family8934.base.Parse.call(this, partNumber);
    Family8934.base.GetChangeOptionRestrictionResult.call(this);
}

function Family8935() {
    BaseWithPin.call(this);
};
Family8935.prototype = new BaseWithPin(inheriting);
Family8935.base = BaseWithPin.prototype;
Family8935.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8935");
    Family8935.base.Initialization.call(this, jsonData);
}
Family8935.prototype.Validate = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }

    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "117.811000": "118.038000",
            "118.594000": "118.743000",
            "122.142000": "122.705000",
            "123.022000": "123.348000"
        };
    }

    if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "120.239000": "120.262000",
            "121.170000": "121.243000",
            "121.601000": "123.948000"
        }
    }

    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family8935.base.Validate.call(this);
}
Family8935.prototype.ExclusionTable = function() {
    return Family8935.base.GetRestrictionResult.call(this);
}

Family8935.prototype.SelectOptions = function(partNumber) {
    Family8935.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G") || (this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E") || (this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D") || (this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "T");
    }
}

function Family8944() {
    FeaturePinWithSpecialFeatures.call(this);
};
Family8944.prototype = new FeaturePinWithSpecialFeatures(inheriting);
Family8944.base = FeaturePinWithSpecialFeatures.prototype;
Family8944.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8944");
    Family8944.base.Initialization.call(this, jsonData);
}

Family8944.prototype.Validate = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }

    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.795999": "70.485001",
            "79.062999": "79.162001",
            "81.427999": "82.232001",
            "91.833999": "92.155001",
            "94.248999": "94.430001",
            "94.874999": "94.994001",
            "97.713999": "98.679001"
        };
    }

    if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "61.222999": "61.974001",
            "69.239999": "70.827001",
            "78.714999": "79.561001",
            "80.159999": "80.174001",
            "80.779999": "82.632001",
            "91.833999": "95.474001",
            "96.191999": "96.209001",
            "96.935999": "99.158001"
        };
    }

    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family8944.base.Validate.call(this);
}
Family8944.prototype.ExclusionTable = function() {
    return Family8944.base.GetRestrictionResult.call(this);
}

Family8944.prototype.SelectOptions = function(partNumber) {
    Family8944.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G") || (this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E") || (this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D") || (this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "T");
    }
}

Family8944.prototype.Parse = function(partNumber) {
    Family8944.base.Parse.call(this, partNumber);
    Family8944.base.GetChangeOptionRestrictionResult.call(this);
}


function Family8945() {
    FeaturePinWithSpecialFeatures.call(this);
};
Family8945.prototype = new FeaturePinWithSpecialFeatures(inheriting);
Family8945.base = FeaturePinWithSpecialFeatures.prototype;
Family8945.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT8945");
    Family8945.base.Initialization.call(this, jsonData);
}
Family8945.prototype.Validate = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }

    if (this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") {
        this.FrequencyHoles = {
            "117.811000": "118.038000",
            "118.594000": "118.743000",
            "122.142000": "122.705000",
            "123.022000": "123.348000"
        };
    }

    if (this.SelectedTemperatureRange.key == "M") {
        this.FrequencyHoles = {
            "120.239000": "120.262000",
            "121.170000": "121.243000",
            "121.601000": "123.948000"
        }
    }

    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "8" || this.SelectedPackageSize.key == "3")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    if ((this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "1" || this.SelectedPackageSize.key == "2")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family8945.base.Validate.call(this);
}
Family8945.prototype.ExclusionTable = function() {
    return Family8945.base.GetRestrictionResult.call(this);
}

Family8945.prototype.SelectOptions = function(partNumber) {
    Family8945.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "3" || this.SelectedPackageSize.key == "8") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "G") || (this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "E") || (this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "7" || this.SelectedPackageSize.key == "2" || this.SelectedPackageSize.key == "1") && (this.SelectedPackaging.key == "D") || (this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "T");
    }
}

function Family3509() {
    DeviceAddressWithFeaturePinWithoutVCMO.call(this);
};
Family3509.prototype = new DeviceAddressWithFeaturePinWithoutVCMO(inheriting);
Family3509.base = DeviceAddressWithFeaturePinWithoutVCMO.prototype;
Family3509.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3509");
    Family3509.base.Initialization.call(this, jsonData);
}
Family3509.prototype.ExclusionTable = function() {
    return true;
}
Family3509.prototype.PaintHTML = function(selector) {
    if (this.Mode == "Generator" || this.Mode == "Mixed") {
        $(selector).append($("<tr/>").append($("<td colspan='2'/>").append(getPartFamilyLabel(" Part Number Generator", this.PartFamily))));
    }

    if (this.FamilyDescription !== "" && this.FamilyDescription !== undefined) {
        $(selector).append(getFamilyDescription(this.FamilyDescription));
    }

    this.GetHTML(selector);
    AppendHTMLResource(selector, Headers.Packaging, this.Packaging, "packaging", this, "OnPackagingChanged", this.Mode);

    if (this.Mode == "Generator" || this.Mode == "Mixed") {
        $(selector).after(getGeneratorFooter());
    }
};
Family3509.prototype.ToString = function() {
    var clipboardValue = "";
    clipboardValue += "Part Number:         " + "\t\t" + this.Generate() + "\n";
    clipboardValue += "Part Family:         " + "\t\t" + this.PartFamily + "(" + this.FamilyDescription + ")\n";
    clipboardValue += "Revision Letter:     " + "\t\t" + this.SelectedRevisionLetter.key + "\n";
    clipboardValue += "Temperature Range:   " + "\t\t" + GetValueFromArray(this.SelectedTemperatureRange, this.TemperatureRange) + "C\n";
    clipboardValue += "Frequency Stability: " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedFrequencyStability, this.FrequencyStability)) + " ppm\n";
    clipboardValue += "Supply Voltage:      " + "\t\t" + GetValueFromArray(this.SelectedSupplyVoltage, this.SupplyVoltage) + "V\n";
    clipboardValue += "Package Size:        " + "\t\t" + GetValueFromArray(this.SelectedPackageSize, this.PackageSize) + " mm\n";
    clipboardValue += "Feature Pin:         " + "\t\t" + GetValueFromArray(this.SelectedFeaturePin, this.FeaturePin) + "\n";
    clipboardValue += "Device Address:      " + "\t\t" + GetValueFromArray(this.SelectedDeviceAddress, this.DeviceAddress) + "\n";
    return clipboardValue;
};

/*function Family3519(){
	DeviceAddressWithFeaturePin.call(this);
};
Family3519.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family3519.base = DeviceAddressWithFeaturePin.prototype;
Family3519.prototype.Initialization = function(){
	var jsonData = getJSONObject("SiT3519");
	Family3519.base.Initialization.call(this, jsonData);
	
}*/
/*Family3519.prototype.ExclusionTable = function(){
	return Family3519.base.GetRestrictionResult.call(this);
	/*
	if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
		this.validationErrors.push(getErrorByErrorID("6"));
		return false;
	}
	else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
		this.validationErrors.push(getErrorByErrorID("6"));
		return false;
	}

	return true;
	*/
/*}
Family3519.prototype.OnFrequencyChange = function(){
	try{
		if(this.Mode=="Generator")
			this.Frequency.key = $("#frequency").val();
		var FrequencyLengthAfterPoint = this.Frequency.key.length - this.Frequency.key.lastIndexOf(".")-1;
		if(FrequencyLengthAfterPoint>this.LengthAfterPoint){
			 throw(this.Frequency.key+"MHz - "+getErrorByErrorID("20")+this.LengthAfterPoint+getErrorByErrorID("22"));
		}
	}
	catch(FrequencyErrorResult){
		this.Frequency.value = false;
		this.validationErrors.push(FrequencyErrorResult);
		if(this.Mode == "Generator"){
			showError(FrequencyErrorResult);
			$("#partnumber").val("(invalid frequency)").css({color: "#bbb"});
		}
		return false;
	}
	if(this.Mode == "Generator"){
		this.ShowGenerateResult();
		dismissError();
	}
	return true;
}
Family3519.prototype.PaintHTML  = function(selector){
	if(this.Mode=="Generator"){
		$(selector).append($("<tr/>").append($("<td colspan='2'/>").append(getPartFamilyLabel(" Part Number Generator",this.PartFamily))));
	}
	
	if(this.FamilyDescription !== "" && this.FamilyDescription !== undefined){
		$(selector).append(getFamilyDescription(this.FamilyDescription));
	}
	
	this.GetHTML(selector);
	AppendHTMLResource(selector,Headers.Packaging, this.Packaging, "packaging",this,"OnPackagingChanged",this.Mode);	

	if(this.Mode=="Generator"){
		$(selector).after(getGeneratorFooter());
	}
};
Family3519.prototype.ToString = function(){
	var clipboardValue = "";
	clipboardValue += "Part Number:         " + "\t\t" + this.Generate() + "\n";
	clipboardValue += "Part Family:         " + "\t\t" + this.PartFamily + "("+this.FamilyDescription+")\n";
	clipboardValue += "Revision Letter:     " + "\t\t" + this.SelectedRevisionLetter.key + "\n";
	clipboardValue += "Temperature Range:   " + "\t\t" + GetValueFromArray(this.SelectedTemperatureRange,this.TemperatureRange) + "C\n";
	clipboardValue += "Frequency Stability: " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedFrequencyStability,this.FrequencyStability)) + " ppm\n";
	clipboardValue += "Supply Voltage:      " + "\t\t" + GetValueFromArray(this.SelectedSupplyVoltage,this.SupplyVoltage) + "V\n";
	clipboardValue += "Package Size:        " + "\t\t" + GetValueFromArray(this.SelectedPackageSize,this.PackageSize) + " mm\n";
	clipboardValue += "Feature Pin:         " + "\t\t" + GetValueFromArray(this.SelectedFeaturePin,this.FeaturePin) + "\n";
	clipboardValue += "VCMO:                " + "\t\t" + ParsePlusmn(GetValueFromArray(this.SelectedVCMO,this.VCMO)) + "\n";
	clipboardValue += "Device Address:      " + "\t\t" + GetValueFromArray(this.SelectedDeviceAddress,this.DeviceAddress) + "\n";
	return clipboardValue;
};
*/

function Family3342() {
    SignallingTypeWithVCMOAndSpecialFeatures.call(this);
};
Family3342.prototype = new SignallingTypeWithVCMOAndSpecialFeatures(inheriting);
Family3342.base = SignallingTypeWithVCMOAndSpecialFeatures.prototype;
Family3342.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3342");
    Family3342.base.Initialization.call(this, jsonData);
}

Family3342.prototype.ExclusionTable = function() {
    return Family3342.base.GetRestrictionResult.call(this);
}

Family3342.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "9");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "9");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "9");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "9") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");

        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family3342.prototype.Parse = function(partNumber) {
    Family3342.base.Parse.call(this, partNumber);
    Family3342.base.GetChangeOptionRestrictionResult.call(this);
}

Family3342.prototype.SelectOptions = function(partNumber) {
    Family3342.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }

    /*if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
    	SelectOption("packaging", "X");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
    	SelectOption("packaging", "Y");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
    	SelectOption("packaging", "T");
    }*/
}

function Family3343() {
    SignallingTypeWithVCMOAndSpecialFeatures.call(this);
};
Family3343.prototype = new SignallingTypeWithVCMOAndSpecialFeatures(inheriting);
Family3343.base = SignallingTypeWithVCMOAndSpecialFeatures.prototype;
Family3343.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3343");
    Family3343.base.Initialization.call(this, jsonData);
}

Family3343.prototype.ExclusionTable = function() {
    return Family3343.base.GetRestrictionResult.call(this);
}

Family3343.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "9");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "9");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "9");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "9") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");

        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family3343.prototype.Parse = function(partNumber) {
    Family3343.base.Parse.call(this, partNumber);
    Family3343.base.GetChangeOptionRestrictionResult.call(this);
}

Family3343.prototype.SelectOptions = function(partNumber) {
    Family3343.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "E") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }

    /*if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
    	SelectOption("packaging", "X");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
    	SelectOption("packaging", "Y");
    }
    else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
    	SelectOption("packaging", "T");
    }*/
}


function Family3521() {
    SignallingTypeVCMOWithSerialMode.call(this);
};
Family3521.prototype = new SignallingTypeVCMOWithSerialMode(inheriting);
Family3521.base = SignallingTypeVCMOWithSerialMode.prototype;
Family3521.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3521");
    Family3521.base.Initialization.call(this, jsonData);
}
Family3521.prototype.ExclusionTable = function() {
    return Family3521.base.GetRestrictionResult.call(this);
}

Family3521.prototype.AvailableOptionsShow = function() {
    var serialMode = this.SelectedSerialMode.key;

    if (serialMode != this.lastserialMode) {
        this.lastserialMode = serialMode;
        if (this.SelectedSerialMode.key) {
            if (this.SelectedSerialMode.key == "S") {
                this.SelectedI2CAddress.key = "S";
                HideHTMLResource("I2C Address", "i2caddress");
            }
            if (this.SelectedSerialMode.key == "I2C") {
                this.SelectedI2CAddress.key = "0";
                SelectOption("i2caddress", "0");
                ShowHTMLResource("I2C Address", "i2caddress");
                showOption("i2caddress", "S");
            }
        }
    }
    if (this.SelectedI2CAddress.key) {
        if (this.SelectedI2CAddress.key == "S") {
            this.SelectedSerialMode.key = "S";
            SelectOption("serialmode", "S");
        } else {
            SelectOption("serialmode", "I2C");
            this.SelectedSerialMode.key = "I2C";
        }
    }
}

Family3521.prototype.Validate = function() {
    return Family3521.base.Validate.call(this);
}

Family3521.prototype.Parse = function(partNumber) {
    Family3521.base.Parse.call(this, partNumber);
    Family3521.base.GetChangeOptionRestrictionResult.call(this);
}

function Family3522() {
    SignallingTypeVCMOWithSerialMode.call(this);
};
Family3522.prototype = new SignallingTypeVCMOWithSerialMode(inheriting);
Family3522.base = SignallingTypeVCMOWithSerialMode.prototype;
Family3522.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3522");
    Family3522.base.Initialization.call(this, jsonData);
}
Family3522.prototype.ExclusionTable = function() {
    return Family3522.base.GetRestrictionResult.call(this);
}

Family3522.prototype.AvailableOptionsShow = function() {
    var serialMode = this.SelectedSerialMode.key;

    if (serialMode != this.lastserialMode) {
        this.lastserialMode = serialMode;
        if (this.SelectedSerialMode.key) {
            if (this.SelectedSerialMode.key == "S") {
                this.SelectedI2CAddress.key = "S";
                HideHTMLResource("I2C Address", "i2caddress");
            }
            if (this.SelectedSerialMode.key == "I2C") {
                this.SelectedI2CAddress.key = "0";
                SelectOption("i2caddress", "0");
                ShowHTMLResource("I2C Address", "i2caddress");
                showOption("i2caddress", "S");
            }
        }
    }
    if (this.SelectedI2CAddress.key) {
        if (this.SelectedI2CAddress.key == "S") {
            this.SelectedSerialMode.key = "S";
            SelectOption("serialmode", "S");
        } else {
            SelectOption("serialmode", "I2C");
            this.SelectedSerialMode.key = "I2C";
        }
    }
	
	if (this.SelectedSignallingType.key) {
        if (this.SelectedSignallingType.key == "4") {
            this.FrequencyHoles = {
                "500.000001": "725.000000"
            };
        } else {
            this.FrequencyHoles = {};
        }
    }
}

Family3522.prototype.Validate = function() {
    return Family3522.base.Validate.call(this);
}

Family3522.prototype.Parse = function(partNumber) {
    Family3522.base.Parse.call(this, partNumber);
    if (this.SelectedSignallingType.key) {
        if (this.SelectedSignallingType.key == "4") {
            this.FrequencyHoles = {
                "500.000001": "725.000000"
            };
        } else {
            this.FrequencyHoles = {};
        }
    }
}


function Family3541() {
    SignallingTypeVCMOWithSerialModeAndSpecialFeatures.call(this);
};
Family3541.prototype = new SignallingTypeVCMOWithSerialModeAndSpecialFeatures(inheriting);
Family3541.base = SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype;
Family3541.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3541");
    Family3541.base.Initialization.call(this, jsonData);
}
Family3541.prototype.ExclusionTable = function() {
    return Family3541.base.GetRestrictionResult.call(this);
}

Family3541.prototype.AvailableOptionsShow = function() {
    var serialMode = this.SelectedSerialMode.key;

    if (serialMode != this.lastserialMode) {
        this.lastserialMode = serialMode;
        if (this.SelectedSerialMode.key) {
            if (this.SelectedSerialMode.key == "S") {
                this.SelectedI2CAddress.key = "S";
                HideHTMLResource("I2C Address", "i2caddress");
            }
            if (this.SelectedSerialMode.key == "I2C") {
                this.SelectedI2CAddress.key = "0";
                SelectOption("i2caddress", "0");
                ShowHTMLResource("I2C Address", "i2caddress");
                showOption("i2caddress", "S");
            }
        }
    }
    if (this.SelectedI2CAddress.key) {
        if (this.SelectedI2CAddress.key == "S") {
            this.SelectedSerialMode.key = "S";
            SelectOption("serialmode", "S");
        } else {
            SelectOption("serialmode", "I2C");
            this.SelectedSerialMode.key = "I2C";
        }
    }
}

Family3541.prototype.Validate = function() {
    return Family3541.base.Validate.call(this);
}

Family3541.prototype.Parse = function(partNumber) {
    Family3541.base.Parse.call(this, partNumber);
    Family3541.base.GetChangeOptionRestrictionResult.call(this);
}


function Family3542() {
    SignallingTypeVCMOWithSerialModeAndSpecialFeatures.call(this);
};
Family3542.prototype = new SignallingTypeVCMOWithSerialModeAndSpecialFeatures(inheriting);
Family3542.base = SignallingTypeVCMOWithSerialModeAndSpecialFeatures.prototype;
Family3542.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3542");
    Family3542.base.Initialization.call(this, jsonData);
}
Family3542.prototype.ExclusionTable = function() {
    return Family3542.base.GetRestrictionResult.call(this);
}

Family3542.prototype.AvailableOptionsShow = function() {
    var serialMode = this.SelectedSerialMode.key;

    if (serialMode != this.lastserialMode) {
        this.lastserialMode = serialMode;
        if (this.SelectedSerialMode.key) {
            if (this.SelectedSerialMode.key == "S") {
                this.SelectedI2CAddress.key = "S";
                HideHTMLResource("I2C Address", "i2caddress");
            }
            if (this.SelectedSerialMode.key == "I2C") {
                this.SelectedI2CAddress.key = "0";
                SelectOption("i2caddress", "0");
                ShowHTMLResource("I2C Address", "i2caddress");
                showOption("i2caddress", "S");
            }
        }
    }
    if (this.SelectedI2CAddress.key) {
        if (this.SelectedI2CAddress.key == "S") {
            this.SelectedSerialMode.key = "S";
            SelectOption("serialmode", "S");
        } else {
            SelectOption("serialmode", "I2C");
            this.SelectedSerialMode.key = "I2C";
        }
    }
}

Family3542.prototype.Validate = function() {
    return Family3542.base.Validate.call(this);
}

Family3542.prototype.Parse = function(partNumber) {
    Family3542.base.Parse.call(this, partNumber);
    Family3542.base.GetChangeOptionRestrictionResult.call(this);
}


function Family3807() {
    FeaturePinWithVCMO.call(this);
};
Family3807.prototype = new FeaturePinWithVCMO(inheriting);
Family3807.base = FeaturePinWithVCMO.prototype;
Family3807.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3807");
    Family3807.base.Initialization.call(this, jsonData);
}
Family3807.prototype.ExclusionTable = function() {
    return Family3807.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedPackageSize.key == "G" || this.SelectedPackageSize.key == "2") && (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S")){
    	this.validationErrors.push(getErrorByErrorID("8"));
    	return false;
    }
    
    return true;
    */
}
Family3807.prototype.Parse = function(partNumber) {
    Family3807.base.Parse.call(this, partNumber);
    Family3807.base.GetChangeOptionRestrictionResult.call(this);
}
Family3807.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "G" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
            disableOption("pin", "E");
            disableOption("pin", "S");
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
            enableOption("pin", "E");
            enableOption("pin", "S");
        }
    }

    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S") {
            disableOption("package", "G");
            disableOption("package", "2");
        } else {
            enableOption("package", "G");
            enableOption("package", "2");
        }
    }
}

function Family3808() {
    FeaturePinWithVCMO.call(this);
};
Family3808.prototype = new FeaturePinWithVCMO(inheriting);
Family3808.base = FeaturePinWithVCMO.prototype;
Family3808.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3808");
    Family3808.base.Initialization.call(this, jsonData);
}
Family3808.prototype.ExclusionTable = function() {
    return Family3808.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedPackageSize.key == "G" || this.SelectedPackageSize.key == "2") && (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S")){
    	this.validationErrors.push(getErrorByErrorID("8"));
    	return false;
    }
    
    return true;
    */
}
Family3808.prototype.Parse = function(partNumber) {
    Family3808.base.Parse.call(this, partNumber);
    Family3808.base.GetChangeOptionRestrictionResult.call(this);
}
Family3808.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "G" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
            disableOption("pin", "E");
            disableOption("pin", "S");
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
            enableOption("pin", "E");
            enableOption("pin", "S");
        }
    }

    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S") {
            disableOption("package", "G");
            disableOption("package", "2");
        } else {
            enableOption("package", "G");
            enableOption("package", "2");
        }
    }
}

function Family3809() {
    FeaturePinWithVCMO.call(this);
};
Family3809.prototype = new FeaturePinWithVCMO(inheriting);
Family3809.base = FeaturePinWithVCMO.prototype;
Family3809.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3809");
    Family3809.base.Initialization.call(this, jsonData);
}
Family3809.prototype.ExclusionTable = function() {
    return Family3809.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedPackageSize.key == "G" || this.SelectedPackageSize.key == "2") && (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S")){
    	this.validationErrors.push(getErrorByErrorID("8"));
    	return false;
    }
    
    return true;
    */
}
Family3809.prototype.Parse = function(partNumber) {
    Family3809.base.Parse.call(this, partNumber);
    Family3809.base.GetChangeOptionRestrictionResult.call(this);
}
Family3809.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }

    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "G" || this.SelectedPackageSize.key == "2") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
            disableOption("pin", "E");
            disableOption("pin", "S");
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
            enableOption("pin", "E");
            enableOption("pin", "S");
        }
    }

    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S") {
            disableOption("package", "G");
            disableOption("package", "2");
        } else {
            enableOption("package", "G");
            enableOption("package", "2");
        }
    }
}

function Family3821() {
    SignallingTypeWithVCMO.call(this);
};
Family3821.prototype = new SignallingTypeWithVCMO(inheriting);
Family3821.base = SignallingTypeWithVCMO.prototype;
Family3821.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3821");
    Family3821.base.Initialization.call(this, jsonData);
}

Family3821.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            enableOption("pin", "E");
            disableOption("pin", "N");
            DeselectOption("pin", "N");
            SelectOption("pin", "E");
            SelectOptions();
            this.SelectedFeaturePin.key = "E";

        } else if (this.SelectedPackageSize.key == "B") {
            enableOption("pin", "N");
            disableOption("pin", "E");
            DeselectOption("pin", "E");
            SelectOption("pin", "N");
            SelectOptions();
            this.SelectedFeaturePin.key = "N";
        }
    }
}

Family3821.prototype.ExclusionTable = function() {
    return Family3821.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedFeaturePin.key == "N") && (this.SelectedPackageSize.key != "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    else if((this.SelectedPackageSize.key == "B") && ( this.SelectedFeaturePin.key != "N" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    else if((this.SelectedFeaturePin.key == "E") && (this.SelectedPackageSize.key == "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    
    return true;
    */
}
Family3821.prototype.Parse = function(partNumber) {
    Family3821.base.Parse.call(this, partNumber);
    Family3821.base.GetChangeOptionRestrictionResult.call(this);
}

function Family3822() {
    SignallingTypeWithVCMO.call(this);
};
Family3822.prototype = new SignallingTypeWithVCMO(inheriting);
Family3822.base = SignallingTypeWithVCMO.prototype;
Family3822.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3822");
    Family3822.base.Initialization.call(this, jsonData);
}

Family3822.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            enableOption("pin", "E");
            disableOption("pin", "N");
            DeselectOption("pin", "N");
            SelectOption("pin", "E");
            SelectOptions();
            this.SelectedFeaturePin.key = "E";

        } else if (this.SelectedPackageSize.key == "B") {
            enableOption("pin", "N");
            disableOption("pin", "E");
            DeselectOption("pin", "E");
            SelectOption("pin", "N");
            SelectOptions();
            this.SelectedFeaturePin.key = "N";
        }
    }
}

Family3822.prototype.ExclusionTable = function() {
    return Family3822.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedFeaturePin.key == "N") && (this.SelectedPackageSize.key != "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    else if((this.SelectedFeaturePin.key == "E") && (this.SelectedPackageSize.key == "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    
    return true;
    */
}
Family3822.prototype.Parse = function(partNumber) {
    Family3822.base.Parse.call(this, partNumber);
    Family3822.base.GetChangeOptionRestrictionResult.call(this);
}

function Family3921() {
    SignallingTypeWithVCMO.call(this);
};
Family3921.prototype = new SignallingTypeWithVCMO(inheriting);
Family3921.base = SignallingTypeWithVCMO.prototype;
Family3921.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3921");
    Family3921.base.Initialization.call(this, jsonData);
}
Family3921.prototype.ExclusionTable = function() {
    return Family3822.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedFeaturePin.key == "N") && (this.SelectedPackageSize.key != "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    else if((this.SelectedFeaturePin.key == "E") && (this.SelectedPackageSize.key == "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    
    return true;
    */
}
Family3921.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }
	
	    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");

        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family3921.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    return Family3921.base.Validate.call(this);
}

function Family3922() {
    SignallingTypeWithVCMO.call(this);
};
Family3922.prototype = new SignallingTypeWithVCMO(inheriting);
Family3922.base = SignallingTypeWithVCMO.prototype;
Family3922.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3922");
    Family3922.base.Initialization.call(this, jsonData);
}
Family3922.prototype.ExclusionTable = function() {
    return Family3922.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if((this.SelectedFeaturePin.key == "N") && (this.SelectedPackageSize.key != "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    else if((this.SelectedFeaturePin.key == "E") && (this.SelectedPackageSize.key == "B" )){
    	this.validationErrors.push(getErrorByErrorID("23"));
    	return false;
    }
    
    return true;
    */
}
Family3922.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }
	    if (this.SelectedPackageSize.key) {
        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");

        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family3922.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
    return Family3922.base.Validate.call(this);
}

function Family3907() {
    DeviceAddressWithFeaturePin.call(this);
};
Family3907.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family3907.base = DeviceAddressWithFeaturePin.prototype;
Family3907.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT3907");
    Family3907.base.Initialization.call(this, jsonData);
}
Family3907.prototype.Parse = function(partNumber) {
    Family3907.base.Parse.call(this, partNumber);
    Family3907.base.GetChangeOptionRestrictionResult.call(this);
}
/*
Family3907.prototype.Parse = function(partNumber){
	Family3907.base.Parse.call(this,partNumber);
	this.FeaturePin = new Array();
	if(this.SelectedPackageSize.key == "2")
	{
		this.FeaturePin.push(createArrayObject("N","No Available"));
	}
	else
	{
		this.FeaturePin.push(createArrayObject("N","No Connect"));
	}
}*/
Family3907.prototype.ExclusionTable = function() {
    return Family3907.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedVCMO.key=="M")&&((this.SelectedFrequencyStability.key=="3")||(this.SelectedFrequencyStability.key=="2"))){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else if((this.SelectedVCMO.key=="B")&&(this.SelectedFrequencyStability.key=="3")){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    else return true;
    */
}
Family3907.prototype.AvailableOptionsShow = function() {
    if (this.SelectedVCMO.key) {
        if (this.SelectedVCMO.key == "M") {
            disableOption("tolerance", "2");
            disableOption("tolerance", "3");
        } else if (this.SelectedVCMO.key == "B") {
            disableOption("tolerance", "3");
            enableOption("tolerance", "2");
        } else {
            enableOption("tolerance", "2");
            enableOption("tolerance", "3");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "2") {
            disableOption("vcmo", "M");
            enableOption("vcmo", "B");
        } else if (this.SelectedFrequencyStability.key == "3") {
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
        } else {
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
    }
}

function Family5722() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5722.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5722.base = DeviceAddressWithFeaturePin.prototype;
Family5722.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5722");
    Family5722.base.Initialization.call(this, jsonData);
}
Family5722.prototype.ExclusionTable = function() {
    return Family5722.base.GetRestrictionResult.call(this);
}

Family5722.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

            }
            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
                /*disableOption("daddress", "0");
                disableOption("daddress", "1");
                disableOption("daddress", "2");
                disableOption("daddress", "3");
                disableOption("daddress", "4");
                disableOption("daddress", "5");
                disableOption("daddress", "6");
                disableOption("daddress", "7");
                disableOption("daddress", "8");
                disableOption("daddress", "9");
                disableOption("daddress", "A");
                disableOption("daddress", "B");
                disableOption("daddress", "C");
                disableOption("daddress", "D");
                disableOption("daddress", "E");
                disableOption("daddress", "F");
                disableOption("daddress", "G");*/
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");
                /*enableOption("daddress", "0");
                enableOption("daddress", "1");
                enableOption("daddress", "2");
                enableOption("daddress", "3");
                enableOption("daddress", "4");
                enableOption("daddress", "5");
                enableOption("daddress", "6");
                enableOption("daddress", "7");
                enableOption("daddress", "8");
                enableOption("daddress", "9");
                enableOption("daddress", "A");
                enableOption("daddress", "B");
                enableOption("daddress", "C");
                enableOption("daddress", "D");
                enableOption("daddress", "E");
                enableOption("daddress", "F");
                enableOption("daddress", "G");*/
            }
        }
    }
}

function Family5721() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5721.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5721.base = DeviceAddressWithFeaturePin.prototype;
Family5721.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5721");
    Family5721.base.Initialization.call(this, jsonData);
}
Family5721.prototype.ExclusionTable = function() {
    return Family5721.base.GetRestrictionResult.call(this);
}

Family5721.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

            }
            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
                /*disableOption("daddress", "0");
                disableOption("daddress", "1");
                disableOption("daddress", "2");
                disableOption("daddress", "3");
                disableOption("daddress", "4");
                disableOption("daddress", "5");
                disableOption("daddress", "6");
                disableOption("daddress", "7");
                disableOption("daddress", "8");
                disableOption("daddress", "9");
                disableOption("daddress", "A");
                disableOption("daddress", "B");
                disableOption("daddress", "C");
                disableOption("daddress", "D");
                disableOption("daddress", "E");
                disableOption("daddress", "F");
                disableOption("daddress", "G");*/
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");
                /*enableOption("daddress", "0");
                enableOption("daddress", "1");
                enableOption("daddress", "2");
                enableOption("daddress", "3");
                enableOption("daddress", "4");
                enableOption("daddress", "5");
                enableOption("daddress", "6");
                enableOption("daddress", "7");
                enableOption("daddress", "8");
                enableOption("daddress", "9");
                enableOption("daddress", "A");
                enableOption("daddress", "B");
                enableOption("daddress", "C");
                enableOption("daddress", "D");
                enableOption("daddress", "E");
                enableOption("daddress", "F");
                enableOption("daddress", "G");*/
            }
        }
    }
}

function Family5712() {
    BaseWithPin.call(this);
};
Family5712.prototype = new BaseWithPin(inheriting);
Family5712.base = BaseWithPin.prototype;
Family5712.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5712");
    Family5712.base.Initialization.call(this, jsonData);
}

Family5712.prototype.ExclusionTable = function() {
    return true;
}

Family5712.prototype.Parse = function(partNumber) {
    Family5712.base.Parse.call(this, partNumber);
    Family5712.base.GetChangeOptionRestrictionResult.call(this);
}

function Family5711() {
    BaseWithPin.call(this); //FeaturePinWithVCMO_NoFreqDivider
};
Family5711.prototype = new BaseWithPin(inheriting);
Family5711.base = BaseWithPin.prototype;
Family5711.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5711");
    Family5711.base.Initialization.call(this, jsonData);
}

Family5711.prototype.ExclusionTable = function() {
    return true;
}

Family5711.prototype.Parse = function(partNumber) {
    Family5711.base.Parse.call(this, partNumber);
    Family5711.base.GetChangeOptionRestrictionResult.call(this);
}

function Family5000() {
    FeaturePinWithVCMO.call(this);
};
Family5000.prototype = new FeaturePinWithVCMO(inheriting);
Family5000.base = FeaturePinWithVCMO.prototype;
Family5000.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5000");
    Family5000.base.Initialization.call(this, jsonData);
}
/*Family5000.prototype.OnWaveFormChanged = function(){
	this.SelectedOutputDriverStrength.key = getCheckedValue(this.SelectedOutputDriverStrength.key,'waveform');
}
Family5000.prototype.GetHTML = function(selector,mode){
	Family5000.base.GetHTML.call(this, selector,mode);
	$(selector).append(getResource(Headers.WaveForm, this.OutputDriverStrength, "waveform",this,"OnWaveFormChanged",mode));
}
Family5000.prototype.SelectOptions = function(){
	Family5000.base.SelectOptions.call(this);
	SelectOption("waveform", this.SelectedOutputDriverStrength);
};*/
Family5000.prototype.Validate = function() {
    var result = Family5000.base.Validate.call(this);
    if (this.SelectedVCMO.key != "Q" && this.SelectedVCMO.key != "0") {
        this.validationErrors.push(getErrorByErrorID("10"));
        return false;
    } else return result;
}
Family5000.prototype.ExclusionTable = function() {
    return Family5000.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFrequencyStability.key=="K" || this.SelectedFrequencyStability.key=="A")&&(this.SelectedTemperatureRange.key=="I" || this.SelectedTemperatureRange.key=="C")){
    	this.validationErrors.push(getErrorByErrorID("9"));
    	return false;
    }
    if (this.SelectedFrequencyStability.key=="B" && this.SelectedTemperatureRange.key=="I"){
    	this.validationErrors.push(getErrorByErrorID("9"));
    	return false;
    }
    
    if(this.SelectedFeaturePin.key=="V"){
    	if (this.SelectedVCMO.key!="Q"){
    		this.validationErrors.push(getErrorByErrorID("10"));
    		return false;
    	}
    	else{
    		if (!IsInArray(this.SelectedVCMO, this.VCMO)){
    			this.validationErrors.push(getErrorByErrorID("10"));
    			return false;
    		}
    	}
    }
    
    return true;
    */
}
Family5000.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            this.SelectedVCMO.key = "Q";
            enableOption("vcmo", "Q");
            SelectOption("vcmo", "Q");
            disableOption("vcmo", "0");
            DeselectOption("vcmo", "0");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "0";
            disableOption("vcmo", "Q");
            DeselectOption("vcmo", "Q");
            enableOption("vcmo", "0");
            SelectOption("vcmo", "0");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "K" || this.SelectedFrequencyStability.key == "A") {
            disableOption("temprange", "I");
            disableOption("temprange", "C");
        } else {
            if (this.SelectedFrequencyStability.key == "B") {
                disableOption("temprange", "I");
                enableOption("temprange", "C");
            } else {
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
        }
    }

    if (this.SelectedTemperatureRange.key) {
        if (this.SelectedTemperatureRange.key == "I" || this.SelectedTemperatureRange.key == "C") {
            disableOption("tolerance", "K");
            disableOption("tolerance", "A");

            if (this.SelectedTemperatureRange.key == "I") disableOption("tolerance", "B");
            else enableOption("tolerance", "B");
        } else {
            enableOption("tolerance", "K");
            enableOption("tolerance", "A");
        }
    }
}

function Family5001() {
    FeaturePinWithVCMO.call(this);
};
Family5001.prototype = new FeaturePinWithVCMO(inheriting);
Family5001.base = FeaturePinWithVCMO.prototype;
Family5001.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5001");
    Family5001.base.Initialization.call(this, jsonData);
}
/*Family5001.prototype.OnWaveFormChanged = function(){
	this.SelectedOutputDriverStrength.key = getCheckedValue(this.SelectedOutputDriverStrength.key,'waveform');
}*/

// Add a new row (Output waveform and all options disabled)
/*Family5001.prototype.GetHTML = function(selector,mode){
	Family5001.base.GetHTML.call(this, selector,mode);
	$(selector).append(getResource(Headers.WaveForm, this.OutputDriverStrength, "waveform",this,"OnWaveFormChanged",mode));
}*/
Family5001.prototype.SelectOptions = function() {
    Family5001.base.SelectOptions.call(this);
    SelectOption("driverstrength", this.SelectedOutputDriverStrength);
};

/*Family5001.prototype.Validate = function(){
	//var result = BaseViewModel.prototype.OnFrequencyChange.call(this);
	if (this.Frequency.key > 50 ){
		disableOption("driverstrength", "C");
		DeselectOption("driverstrength", "C");
		SelectOption("driverstrength", "-");
		this.SelectedOutputDriverStrength.key = "-";		
		SelectOptions();		
	}
	else
		enableOption("driverstrength", "C");
	if (this.Frequency.key > 50 && this.SelectedOutputDriverStrength.key == "C"){
		this.validationErrors.push(getErrorByErrorID("40"));
		return false;
	}
	return Family5001.base.Validate.call(this);
}*/

Family5001.prototype.ExclusionTable = function() {
    return Family5001.base.GetRestrictionResult.call(this);
}

Family5001.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            enableOption("vcmo", "Q");
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "0";
            SelectOption("vcmo", "0");
            disableOption("vcmo", "Q");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
            DeselectOption("vcmo", "Q");
            DeselectOption("vcmo", "M");
            DeselectOption("vcmo", "B");
        }
    }
    if (this.SelectedOutputDriverStrength.key) {
        if (this.SelectedOutputDriverStrength.key == "C") {
            this.FrequencyHoles = {
                "50.000001": "80.000000"
            };
        } else {
            this.FrequencyHoles = {};
        }
    }
}


Family5001.prototype.Parse = function(partNumber) {
    Family5001.base.Parse.call(this, partNumber);
    if (this.SelectedOutputDriverStrength.key) {
        if (this.SelectedOutputDriverStrength.key == "C") {
            this.FrequencyHoles = {
                "50.000001": "80.000000"
            };
        } else {
            this.FrequencyHoles = {};
        }
    }
}

function Family5002() {
    FeaturePinWithVCMO.call(this);
};
Family5002.prototype = new FeaturePinWithVCMO(inheriting);
Family5002.base = FeaturePinWithVCMO.prototype;
Family5002.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5002");
    Family5002.base.Initialization.call(this, jsonData);
}
Family5002.prototype.Validate = function() {
    var result = Family5002.base.Validate.call(this);
    if (this.SelectedVCMO.key != "Q" && this.SelectedVCMO.key != "M" && this.SelectedVCMO.key != "B" && this.SelectedVCMO.key != "0") {
        this.validationErrors.push(getErrorByErrorID("10"));
        return false;
    } else return result;
}
Family5002.prototype.ExclusionTable = function() {
    return Family5002.base.GetRestrictionResult.call(this);
    /*
    if(this.SelectedFrequencyStability.key=="A" && (this.SelectedTemperatureRange.key=="I" || this.SelectedTemperatureRange.key=="C")){
    	this.validationErrors.push(getErrorByErrorID("9"));
    	return false;
    }
    if (this.SelectedFrequencyStability.key=="B" && this.SelectedTemperatureRange.key=="I"){
    	this.validationErrors.push(getErrorByErrorID("9"));
    	return false;
    }
    
    if((this.SelectedFeaturePin.key=="V")&&(this.SelectedVCMO.key=="0")){
    		this.validationErrors.push(getErrorByErrorID("10"));
    		return false;
    }
    else if((this.SelectedFeaturePin.key!="V")&&(this.SelectedVCMO.key!="0")){
    		this.validationErrors.push(getErrorByErrorID("10"));
    		return false;
    }
    return true;
    */
}
Family5002.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            enableOption("vcmo", "Q");
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "0";
            SelectOption("vcmo", "0");
            disableOption("vcmo", "Q");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
            DeselectOption("vcmo", "Q");
            DeselectOption("vcmo", "M");
            DeselectOption("vcmo", "B");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "A") {
            disableOption("temprange", "I");
            disableOption("temprange", "C");
        } else {
            if (this.SelectedFrequencyStability.key == "B") disableOption("temprange", "I");
            else enableOption("temprange", "I");

            enableOption("temprange", "C");
        }
    }

    if (this.SelectedTemperatureRange.key) {
        if (this.SelectedTemperatureRange.key == "I" || this.SelectedTemperatureRange.key == "C") {
            disableOption("tolerance", "A");

            if (this.SelectedTemperatureRange.key == "I") disableOption("tolerance", "B");
            else enableOption("tolerance", "B");
        } else enableOption("tolerance", "A");
    }
}

function Family5003() {
    FeaturePinWithVCMO.call(this);
};
Family5003.prototype = new FeaturePinWithVCMO(inheriting);
Family5003.base = FeaturePinWithVCMO.prototype;
Family5003.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5003");
    Family5003.base.Initialization.call(this, jsonData);
}
Family5003.prototype.Validate = function() {
    var result = Family5003.base.Validate.call(this);
    if (this.SelectedVCMO.key != "Q" && this.SelectedVCMO.key != "M" && this.SelectedVCMO.key != "B" && this.SelectedVCMO.key != "0") {
        this.validationErrors.push(getErrorByErrorID("10"));
        return false;
    } else return result;
}
Family5003.prototype.ExclusionTable = function() {
    Family5003.base.GetRestrictionResult.call(this);

    this.validationErrors.push(getErrorByErrorID("9"));
    return false;
    /*
    	if((this.SelectedFeaturePin.key=="V")&&(this.SelectedVCMO.key=="0")){
    			this.validationErrors.push(getErrorByErrorID("10"));
    			return false;
    	}
    	else if((this.SelectedFeaturePin.key!="V")&&(this.SelectedVCMO.key!="0")){
    			this.validationErrors.push(getErrorByErrorID("10"));
    			return false;
    	}
    */
}
Family5003.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            enableOption("vcmo", "Q");
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "0";
            SelectOption("vcmo", "0");
            disableOption("vcmo", "Q");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
            DeselectOption("vcmo", "Q");
            DeselectOption("vcmo", "M");
            DeselectOption("vcmo", "B");
        }
    }
}

function Family5004() {
    FeaturePinWithVCMO.call(this);
};
Family5004.prototype = new FeaturePinWithVCMO(inheriting);
Family5004.base = FeaturePinWithVCMO.prototype;
Family5004.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5004");
    Family5004.base.Initialization.call(this, jsonData);
}
Family5004.prototype.Validate = function() {
    var result = Family5004.base.Validate.call(this);
    if (this.SelectedVCMO.key != "Q" && this.SelectedVCMO.key != "M" && this.SelectedVCMO.key != "B" && this.SelectedVCMO.key != "0") {
        this.validationErrors.push(getErrorByErrorID("10"));
        return false;
    } else return result;
}
Family5004.prototype.ExclusionTable = function() {
    return Family5004.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedFeaturePin.key=="V")&&(this.SelectedVCMO.key=="0")){
    		this.validationErrors.push(getErrorByErrorID("10"));
    		return false;
    }
    else if((this.SelectedFeaturePin.key!="V")&&(this.SelectedVCMO.key!="0")){
    		this.validationErrors.push(getErrorByErrorID("10"));
    		return false;
    }
    return true;
    */
}
Family5004.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            enableOption("vcmo", "Q");
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "0";
            SelectOption("vcmo", "0");
            disableOption("vcmo", "Q");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
            DeselectOption("vcmo", "Q");
            DeselectOption("vcmo", "M");
            DeselectOption("vcmo", "B");
        }
    }
}

function Family5021() {
    Pin_VCMO_SignallingType.call(this);
};
Family5021.prototype = new Pin_VCMO_SignallingType(inheriting);
Family5021.base = Pin_VCMO_SignallingType.prototype;
Family5021.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5021");
    Family5021.base.Initialization.call(this, jsonData);
}
Family5021.prototype.Validate = function() {
    var result = Family5021.base.Validate.call(this);
    if (this.SelectedFeaturePin.key != "V" && this.SelectedVCMO.key != "-") {
        this.validationErrors.push(getErrorByErrorID("39"));
        return false;
    } else {
        return result;
    }
    return result;
}
Family5021.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            enableOption("vcmo", "Q");
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "-";
            SelectOption("vcmo", "-");
            disableOption("vcmo", "Q");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
            DeselectOption("vcmo", "Q");
            DeselectOption("vcmo", "M");
            DeselectOption("vcmo", "B");
        }
    }
}
Family5021.prototype.ExclusionTable = function() {
    return true;
}

function Family5022() {
    Pin_VCMO_SignallingType.call(this);
};
Family5022.prototype = new Pin_VCMO_SignallingType(inheriting);
Family5022.base = Pin_VCMO_SignallingType.prototype;
Family5022.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5022");
    Family5022.base.Initialization.call(this, jsonData);
}
Family5022.prototype.Validate = function() {
    var result = Family5022.base.Validate.call(this);
    if (this.SelectedFeaturePin.key != "V" && this.SelectedVCMO.key != "-") {
        this.validationErrors.push(getErrorByErrorID("39"));
        return false;
    } else {
        return result;
    }
    return result;
}
Family5022.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "V") {
            enableOption("vcmo", "Q");
            enableOption("vcmo", "M");
            enableOption("vcmo", "B");
        }
        if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "S" || this.SelectedFeaturePin.key == "N") {
            this.SelectedVCMO.key = "-";
            SelectOption("vcmo", "-");
            disableOption("vcmo", "Q");
            disableOption("vcmo", "M");
            disableOption("vcmo", "B");
            DeselectOption("vcmo", "Q");
            DeselectOption("vcmo", "M");
            DeselectOption("vcmo", "B");
        }
    }
}
Family5022.prototype.ExclusionTable = function() {
    return true;
}

function Family5901() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5901.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5901.base = DeviceAddressWithFeaturePin.prototype;
Family5901.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5901");
    Family5901.base.Initialization.call(this, jsonData);
}
Family5901.prototype.ExclusionTable = function() {
    return Family5901.base.GetRestrictionResult.call(this);
}

Family5901.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5902() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5902.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5902.base = DeviceAddressWithFeaturePin.prototype;
Family5902.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5902");
    Family5902.base.Initialization.call(this, jsonData);
}
Family5902.prototype.ExclusionTable = function() {
    return Family5902.base.GetRestrictionResult.call(this);
}

Family5902.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5903() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5903.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5903.base = DeviceAddressWithFeaturePin.prototype;
Family5903.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5903");
    Family5903.base.Initialization.call(this, jsonData);
}
Family5903.prototype.ExclusionTable = function() {
    return Family5903.base.GetRestrictionResult.call(this);
}

Family5903.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5904() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5904.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5904.base = DeviceAddressWithFeaturePin.prototype;
Family5904.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5904");
    Family5904.base.Initialization.call(this, jsonData);
}
Family5904.prototype.ExclusionTable = function() {
    return Family5904.base.GetRestrictionResult.call(this);
}

Family5904.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5906() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5906.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5906.base = DeviceAddressWithFeaturePin.prototype;
Family5906.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5906");
    Family5906.base.Initialization.call(this, jsonData);
}
Family5906.prototype.ExclusionTable = function() {
    return Family5906.base.GetRestrictionResult.call(this);
}

Family5906.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5907() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5907.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5907.base = DeviceAddressWithFeaturePin.prototype;
Family5907.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5907");
    Family5907.base.Initialization.call(this, jsonData);
}
Family5907.prototype.ExclusionTable = function() {
    return Family5907.base.GetRestrictionResult.call(this);
}

Family5907.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5908() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5908.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5908.base = DeviceAddressWithFeaturePin.prototype;
Family5908.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5908");
    Family5908.base.Initialization.call(this, jsonData);
}
Family5908.prototype.ExclusionTable = function() {
    return Family5908.base.GetRestrictionResult.call(this);
}

Family5908.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5909() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5909.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5909.base = DeviceAddressWithFeaturePin.prototype;
Family5909.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5909");
    Family5909.base.Initialization.call(this, jsonData);
}
Family5909.prototype.ExclusionTable = function() {
    return Family5909.base.GetRestrictionResult.call(this);
}

Family5909.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;
    var freqStability = this.SelectedFrequencyStability.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/
    if (freqStability != this.lastfreqStability) {
        this.lastfreqStability = freqStability;
        if (this.SelectedFrequencyStability.key) {
            if (this.SelectedFrequencyStability.key == "R"){
                disableOption("temprange", "C");
                disableOption("temprange", "I");
                disableOption("temprange", "E");

                enableOption("temprange", "N");
                SelectOption("temprange", "N");
                this.SelectedTemperatureRange.key = "N";
            }

            if (this.SelectedFrequencyStability.key == "Q"){
                enableOption("temprange", "C");
                enableOption("temprange", "I");
                enableOption("temprange", "E");

                disableOption("temprange", "N");
                DeselectOption("temprange", "N");
                this.SelectedTemperatureRange.key = "";
            }
        }
    }

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5910() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5910.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5910.base = DeviceAddressWithFeaturePin.prototype;
Family5910.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5910");
    Family5910.base.Initialization.call(this, jsonData);
}
Family5910.prototype.ExclusionTable = function() {
    return Family5910.base.GetRestrictionResult.call(this);
}

Family5910.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5351() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5351.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5351.base = DeviceAddressWithFeaturePin.prototype;
Family5351.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5351");
    Family5351.base.Initialization.call(this, jsonData);
}
Family5351.prototype.ExclusionTable = function() {
    return Family5351.base.GetRestrictionResult.call(this);
}

Family5351.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

            }
            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
                /*disableOption("daddress", "0");
                disableOption("daddress", "1");
                disableOption("daddress", "2");
                disableOption("daddress", "3");
                disableOption("daddress", "4");
                disableOption("daddress", "5");
                disableOption("daddress", "6");
                disableOption("daddress", "7");
                disableOption("daddress", "8");
                disableOption("daddress", "9");
                disableOption("daddress", "A");
                disableOption("daddress", "B");
                disableOption("daddress", "C");
                disableOption("daddress", "D");
                disableOption("daddress", "E");
                disableOption("daddress", "F");
                disableOption("daddress", "G");*/
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");
                /*enableOption("daddress", "0");
                enableOption("daddress", "1");
                enableOption("daddress", "2");
                enableOption("daddress", "3");
                enableOption("daddress", "4");
                enableOption("daddress", "5");
                enableOption("daddress", "6");
                enableOption("daddress", "7");
                enableOption("daddress", "8");
                enableOption("daddress", "9");
                enableOption("daddress", "A");
                enableOption("daddress", "B");
                enableOption("daddress", "C");
                enableOption("daddress", "D");
                enableOption("daddress", "E");
                enableOption("daddress", "F");
                enableOption("daddress", "G");*/
            }
        }
    }
}

function Family5352() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5352.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5352.base = DeviceAddressWithFeaturePin.prototype;
Family5352.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5352");
    Family5352.base.Initialization.call(this, jsonData);
}
Family5352.prototype.ExclusionTable = function() {
    return Family5352.base.GetRestrictionResult.call(this);
}

Family5352.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

            }
            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
                /*disableOption("daddress", "0");
                disableOption("daddress", "1");
                disableOption("daddress", "2");
                disableOption("daddress", "3");
                disableOption("daddress", "4");
                disableOption("daddress", "5");
                disableOption("daddress", "6");
                disableOption("daddress", "7");
                disableOption("daddress", "8");
                disableOption("daddress", "9");
                disableOption("daddress", "A");
                disableOption("daddress", "B");
                disableOption("daddress", "C");
                disableOption("daddress", "D");
                disableOption("daddress", "E");
                disableOption("daddress", "F");
                disableOption("daddress", "G");*/
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");
                /*enableOption("daddress", "0");
                enableOption("daddress", "1");
                enableOption("daddress", "2");
                enableOption("daddress", "3");
                enableOption("daddress", "4");
                enableOption("daddress", "5");
                enableOption("daddress", "6");
                enableOption("daddress", "7");
                enableOption("daddress", "8");
                enableOption("daddress", "9");
                enableOption("daddress", "A");
                enableOption("daddress", "B");
                enableOption("daddress", "C");
                enableOption("daddress", "D");
                enableOption("daddress", "E");
                enableOption("daddress", "F");
                enableOption("daddress", "G");*/
            }
        }
    }
}

function Family5301() {
    FeaturePinWithVCMO.call(this);
};
Family5301.prototype = new FeaturePinWithVCMO(inheriting);
Family5301.base = FeaturePinWithVCMO.prototype;
Family5301.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5301");
    Family5301.base.Initialization.call(this, jsonData);
}
Family5301.prototype.ExclusionTable = function() {
    return Family5301.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedTemperatureRange.key=="C")){
    	this.validationErrors.push(getErrorByErrorID("9"));
    	return false;
    }
    else return true;
    */
}
Family5301.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key) {
        if (this.SelectedTemperatureRange.key == "C") {
            disableOption("tolerance", "Q");
            disableOption("tolerance", "P");
        } else {
            enableOption("tolerance", "Q");
            enableOption("tolerance", "P");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "Q" || this.SelectedFrequencyStability.key == "P")
            disableOption("temprange", "C");
        else
            enableOption("temprange", "C");
    }
}

function Family5302() {
    FeaturePinWithVCMO.call(this);
};
Family5302.prototype = new FeaturePinWithVCMO(inheriting);
Family5302.base = FeaturePinWithVCMO.prototype;
Family5302.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5302");
    Family5302.base.Initialization.call(this, jsonData);
}
Family5302.prototype.ExclusionTable = function() {
    return Family5302.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedTemperatureRange.key=="C")){
    	this.validationErrors.push(getErrorByErrorID("9"));
    	return false;
    }
    else return true;
    */
}
Family5302.prototype.AvailableOptionsShow = function() {
    if (this.SelectedTemperatureRange.key) {
        if (this.SelectedTemperatureRange.key == "C") {
            disableOption("tolerance", "Q");
            disableOption("tolerance", "P");
        } else {
            enableOption("tolerance", "Q");
            enableOption("tolerance", "P");
        }
    }

    if (this.SelectedFrequencyStability.key) {
        if (this.SelectedFrequencyStability.key == "Q" || this.SelectedFrequencyStability.key == "P")
            disableOption("temprange", "C");
        else
            enableOption("temprange", "C");
    }
}

function Family5359() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5359.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5359.base = DeviceAddressWithFeaturePin.prototype;
Family5359.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5359");
    Family5359.base.Initialization.call(this, jsonData);
}
Family5359.prototype.ExclusionTable = function() {
    return Family5359.base.GetRestrictionResult.call(this);
}

Family5359.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;
	//decoder should have only 189.000001 to 199.999999 freq. holes. 
	this.FrequencyHoles = {"189.000001": "199.999999", "200.000000": "207.000000"};  
    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (this.SelectedFrequencyStability.key != this.lasttemprange) {
        this.lasttemprange = this.SelectedFrequencyStability.key;
        if (this.SelectedFrequencyStability.key == "R") {
            enableOption("temprange", "N");
        } else if (this.SelectedFrequencyStability.key == "Q") {
            this.SelectedTemperatureRange.key = "I";
            SelectOption("temprange", "I");
            disableOption("temprange", "N");
        }
    }

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

Family5359.prototype.Parse = function(partNumber) {
    Family5359.base.Parse.call(this, partNumber);
        this.FrequencyHoles = {"189.000001": "199.999999"};  
}

function Family5358() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5358.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5358.base = DeviceAddressWithFeaturePin.prototype;
Family5358.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5358");
    Family5358.base.Initialization.call(this, jsonData);
}
Family5358.prototype.ExclusionTable = function() {
    return Family5358.base.GetRestrictionResult.call(this);
}
Family5358.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/
    if (this.SelectedFrequencyStability.key != this.lasttemprange) {
        this.lasttemprange = this.SelectedFrequencyStability.key;
        if (this.SelectedFrequencyStability.key == "R") {
            enableOption("temprange", "N");
        } else if (this.SelectedFrequencyStability.key == "Q") {
            this.SelectedTemperatureRange.key = "I";
            SelectOption("temprange", "I");
            disableOption("temprange", "N");
        }
    }
    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5387() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5387.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5387.base = DeviceAddressWithFeaturePin.prototype;
Family5387.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5387");
    Family5387.base.Initialization.call(this, jsonData);
}
Family5387.prototype.ExclusionTable = function() {
    return Family5387.base.GetRestrictionResult.call(this);
}

Family5387.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5386() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5386.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5386.base = DeviceAddressWithFeaturePin.prototype;
Family5386.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5386");
    Family5386.base.Initialization.call(this, jsonData);
}
Family5386.prototype.ExclusionTable = function() {
    return Family5386.base.GetRestrictionResult.call(this);
}

Family5386.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5357() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5357.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5357.base = DeviceAddressWithFeaturePin.prototype;
Family5357.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5357");
    Family5357.base.Initialization.call(this, jsonData);
}
Family5357.prototype.ExclusionTable = function() {
    return Family5357.base.GetRestrictionResult.call(this);
}

Family5357.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;
	//decoder should have only 189.000001 to 199.999999 freq. holes. 
	this.FrequencyHoles = {"189.000001": "199.999999", "200.000000": "207.000000"};  
    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

Family5357.prototype.Parse = function(partNumber) {
    Family5357.base.Parse.call(this, partNumber);
        this.FrequencyHoles = {"189.000001": "199.999999"};  
}

function Family5356() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5356.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5356.base = DeviceAddressWithFeaturePin.prototype;
Family5356.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5356");
    Family5356.base.Initialization.call(this, jsonData);
}
Family5356.prototype.ExclusionTable = function() {
    return Family5356.base.GetRestrictionResult.call(this);
}

Family5356.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5187() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5187.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5187.base = DeviceAddressWithFeaturePin.prototype;
Family5187.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5187");
    Family5187.base.Initialization.call(this, jsonData);
}
Family5187.prototype.ExclusionTable = function() {
    return Family5187.base.GetRestrictionResult.call(this);
}

Family5187.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5186() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5186.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5186.base = DeviceAddressWithFeaturePin.prototype;
Family5186.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5186");
    Family5186.base.Initialization.call(this, jsonData);
}
Family5186.prototype.ExclusionTable = function() {
    return Family5186.base.GetRestrictionResult.call(this);
}

Family5186.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");
            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5157() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5157.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5157.base = DeviceAddressWithFeaturePin.prototype;
Family5157.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5157");
    Family5157.base.Initialization.call(this, jsonData);
}
Family5157.prototype.ExclusionTable = function() {
    return Family5157.base.GetRestrictionResult.call(this);
}

Family5157.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;
	//decoder should have only 189.000001 to 199.999999 freq. holes. 
	this.FrequencyHoles = {"189.000001": "199.999999", "200.000000": "207.000000"};  
    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

Family5157.prototype.Parse = function(partNumber) {
    Family5157.base.Parse.call(this, partNumber);
        this.FrequencyHoles = {"189.000001": "199.999999"};  
}

function Family5156() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5156.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5156.base = DeviceAddressWithFeaturePin.prototype;
Family5156.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5156");
    Family5156.base.Initialization.call(this, jsonData);
}
Family5156.prototype.ExclusionTable = function() {
    return Family5156.base.GetRestrictionResult.call(this);
}

Family5156.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5155() {
    DeviceAddressWithFeaturePin.call(this);
};
Family5155.prototype = new DeviceAddressWithFeaturePin(inheriting);
Family5155.base = DeviceAddressWithFeaturePin.prototype;
Family5155.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5155");
    Family5155.base.Initialization.call(this, jsonData);
}
Family5155.prototype.ExclusionTable = function() {
    return Family5155.base.GetRestrictionResult.call(this);
}

Family5155.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5349() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.call(this);
};
Family5349.prototype = new DeviceAddressWithFeaturePinAndSpecialFeatures(inheriting);
Family5349.base = DeviceAddressWithFeaturePinAndSpecialFeatures.prototype;
Family5349.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5349");
    Family5349.base.Initialization.call(this, jsonData);
}
Family5349.prototype.ExclusionTable = function() {
    return Family5349.base.GetRestrictionResult.call(this);
}

Family5349.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5348() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.call(this);
};
Family5348.prototype = new DeviceAddressWithFeaturePinAndSpecialFeatures(inheriting);
Family5348.base = DeviceAddressWithFeaturePinAndSpecialFeatures.prototype;
Family5348.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5348");
    Family5348.base.Initialization.call(this, jsonData);
}
Family5348.prototype.ExclusionTable = function() {
    return Family5348.base.GetRestrictionResult.call(this);
}

Family5348.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5347() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.call(this);
};
Family5347.prototype = new DeviceAddressWithFeaturePinAndSpecialFeatures(inheriting);
Family5347.base = DeviceAddressWithFeaturePinAndSpecialFeatures.prototype;
Family5347.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5347");
    Family5347.base.Initialization.call(this, jsonData);
}
Family5347.prototype.ExclusionTable = function() {
    return Family5347.base.GetRestrictionResult.call(this);
}

Family5347.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5346() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.call(this);
};
Family5346.prototype = new DeviceAddressWithFeaturePinAndSpecialFeatures(inheriting);
Family5346.base = DeviceAddressWithFeaturePinAndSpecialFeatures.prototype;
Family5346.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5346");
    Family5346.base.Initialization.call(this, jsonData);
}
Family5346.prototype.ExclusionTable = function() {
    return Family5346.base.GetRestrictionResult.call(this);
}

Family5346.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5147() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.call(this);
};
Family5147.prototype = new DeviceAddressWithFeaturePinAndSpecialFeatures(inheriting);
Family5147.base = DeviceAddressWithFeaturePinAndSpecialFeatures.prototype;
Family5147.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5147");
    Family5147.base.Initialization.call(this, jsonData);
}
Family5147.prototype.ExclusionTable = function() {
    return Family5147.base.GetRestrictionResult.call(this);
}

Family5147.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family5146() {
    DeviceAddressWithFeaturePinAndSpecialFeatures.call(this);
};
Family5146.prototype = new DeviceAddressWithFeaturePinAndSpecialFeatures(inheriting);
Family5146.base = DeviceAddressWithFeaturePinAndSpecialFeatures.prototype;
Family5146.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT5146");
    Family5146.base.Initialization.call(this, jsonData);
}
Family5146.prototype.ExclusionTable = function() {
    return Family5146.base.GetRestrictionResult.call(this);
}

Family5146.prototype.AvailableOptionsShow = function() {
    var featurePin = this.SelectedFeaturePin.key;

    /*var VCMO = new Array("R", "Q", "M", "B", "C", "E", "F", "G", "H", "X", "L", "Y", "S", "Z", "U");
    var inputVCMO = this.PullRange;*/

    if (featurePin != this.lastfeaturePin) {
        this.lastfeaturePin = featurePin;
        if (this.SelectedFeaturePin.key) {
            if (this.SelectedFeaturePin.key == "V") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                enableOption("vcmo", "T");
                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "J" || this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "L" || this.SelectedFeaturePin.key == "K") {
                this.SelectedVCMO.key = "T";
                SelectOption("vcmo", "T");
                this.SelectedPackageSize.key = "F";
                SelectOption("package", "F");

                disableOption("vcmo", "0");
                DeselectOption("vcmo", "0");
                enableOption("vcmo", "T");
                enableOption("vcmo", "R");
                enableOption("vcmo", "Q");
                enableOption("vcmo", "M");
                enableOption("vcmo", "B");
                enableOption("vcmo", "C");
                enableOption("vcmo", "E");
                enableOption("vcmo", "F");
                enableOption("vcmo", "G");
                enableOption("vcmo", "H");
                enableOption("vcmo", "X");
                enableOption("vcmo", "L");
                enableOption("vcmo", "Y");
                enableOption("vcmo", "S");
                enableOption("vcmo", "Z");
                enableOption("vcmo", "U");

                disableOption("package", "8");
                disableOption("package", "D");
                disableOption("package", "N");
                disableOption("package", "I");
                disableOption("package", "M");

                /*for (var element in inputVCMO){
                $.each(inputVCMO[element].key, function(iter, item){
                	if ($.inArray($(item).val(), VCMO) != -1){
                		enableOption("vcmo", item);
                	}
                	else{
                		if ($(item).val() != "")
                			disableOption("vcmo", item);
                	}
                });
                }*/
            }
            if (this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedVCMO.key = "0";
                enableOption("vcmo", "0");
                SelectOption("vcmo", "0");
                disableOption("vcmo", "T");
                disableOption("vcmo", "R");
                disableOption("vcmo", "Q");
                disableOption("vcmo", "M");
                disableOption("vcmo", "B");
                disableOption("vcmo", "C");
                disableOption("vcmo", "E");
                disableOption("vcmo", "F");
                disableOption("vcmo", "G");
                disableOption("vcmo", "H");
                disableOption("vcmo", "X");
                disableOption("vcmo", "L");
                disableOption("vcmo", "Y");
                disableOption("vcmo", "S");
                disableOption("vcmo", "Z");
                disableOption("vcmo", "U");

                enableOption("package", "8");
                enableOption("package", "D");
                enableOption("package", "N");
                enableOption("package", "I");
                enableOption("package", "M");

            }

            if (this.SelectedFeaturePin.key == "V" || this.SelectedFeaturePin.key == "E" || this.SelectedFeaturePin.key == "N") {
                this.SelectedDeviceAddress.key = "-";
                SelectOption("daddress", "-");
                HideHTMLResource("Device Address", "daddress");
            }

            if (this.SelectedFeaturePin.key == "I" || this.SelectedFeaturePin.key == "J") {
                this.SelectedDeviceAddress.key = "0";
                DeselectOption("daddress", "-");
                SelectOption("daddress", "0");
                disableOption("daddress", "-");
                ShowHTMLResource("Device Address", "daddress");

            }
        }
    }
}

function Family9001() {
    FeaturePinWithSpreadSpectrum.call(this);
};
Family9001.prototype = new FeaturePinWithSpreadSpectrum(inheriting);
Family9001.base = FeaturePinWithSpreadSpectrum.prototype;
Family9001.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9001");
    Family9001.base.Initialization.call(this, jsonData);
}
Family9001.prototype.ExclusionTable = function() {
    return Family9001.base.GetRestrictionResult.call(this);
    /*
    if (this.SelectedFeaturePin.key == "D" && (this.SelectedSpreadSpectrum.key == "1" || this.SelectedSpreadSpectrum.key == "2" || this.SelectedSpreadSpectrum.key == "3")){
    	this.validationErrors.push(getErrorByErrorID("11"));
    	return false;
    }
    else return true;
    */
}
Family9001.prototype.AvailableOptionsShow = function() {
    if (this.SelectedFeaturePin.key) {
        if (this.SelectedFeaturePin.key == "D") {
            disableOption("spread", "1");
            disableOption("spread", "2");
            disableOption("spread", "3");
        } else {
            enableOption("spread", "1");
            enableOption("spread", "2");
            enableOption("spread", "3");
        }
    }
    if (this.SelectedSpreadSpectrum.key == "1" || this.SelectedSpreadSpectrum.key == "2" || this.SelectedSpreadSpectrum.key == "3")
        disableOption("pin", "D");
    else
        enableOption("pin", "D");
}
Family9001.prototype.Parse = function(partNumber) {
    Family9001.base.Parse.call(this, partNumber);
    Family9001.base.GetChangeOptionRestrictionResult.call(this);
}
/*Family9001.prototype.Parse = function(partNumber){
	Family9001.base.Parse.call(this,partNumber);
	
	if(this.SelectedSpreadSpectrum.key == "3"){
		this.FrequencyHoles = {"1.04":"1.1","1.248":"1.284","1.388":"1.444","1.784":"1.926","2.082":"2.202","2.498":"2.566","2.774":"2.89","3.57":"3.85","4.16":"4.4",
		"4.99":"5.13","5.55":"5.78","7.14":"7.7","8.33":"8.8","9.99":"10.27","11.09":"11.55","14.27":"15.41","16.65":"17.61","19.99":"20.55","22.2":"23.1","28.55":"30.8",
		"33.3":"35.2","39.95":"41.1","44.4":"46.2","57.11":"61.61","66.61":"73.91","79.91":"92.41","99.91":"105.61","114.21":"123.31","133.21":"147.91","159.91":"184.91",
		"199.91":"220"}
	}
	else if(this.SelectedSpreadSpectrum.key == "2"){
		this.FrequencyHoles = {"1.042":"1.098","1.252":"1.28","1.39":"1.44","1.788":"1.92","2.086":"2.194","2.502":"2.562","2.782":"2.882","3.578":"3.842","4.17":"4.39",
		"5.01":"5.12","5.56":"5.76","7.15":"7.68","8.35":"8.78","10":"10.25","11.13":"11.53","14.31":"15.37","16.69":"17.57","19.99":"20.5","22.25":"23.05","28.6":"30.75",
		"33.4":"35.1","40.05":"41","44.5":"46.1","57.21":"61.41","66.81":"73.71","80.11":"92.21","100.21":"105.41","114.51":"122.91","133.61":"147.51","160.31":"184.41",
		"200.41":"220"}
	}
	else if(this.SelectedSpreadSpectrum.key == "1"){
		this.FrequencyHoles = {"1.036":"1.106","1.242":"1.29","1.38":"1.452","1.656":"1.658","1.776":"1.936","2.07":"2.21","2.486":"2.582","2.762":"2.902","3.314":"3.318",
		"3.55":"3.87","4.14":"4.42","4.97":"5.16","5.52":"5.8","7.1":"7.74","8.28":"8.85","9.94":"10.31","11.05":"11.61","13.25":"13.27","14.21":"15.49","16.57":"17.69",
		"19.89":"20.65","22.1":"23.2","26.5":"26.55","28.4":"30.95","33.15":"35.4","39.75":"41.3","44.2":"46.45","53.01":"53.11","56.81":"61.91","66.31":"74.31",
		"79.51":"92.91","99.41":"106.21","113.61":"123.91","132.61":"148.71","159.11":"185.91","198.91":"220"}
	}
	else if(this.SelectedSpreadSpectrum.key == "6"){
		this.FrequencyHoles = {"1.0426":"1.10275","1.25112":"1.28721","1.39147":"1.44761","1.78846":"1.930815","2.087205":"2.207505","2.504245":"2.572415",
		"2.780935":"2.897225","3.578925":"3.859625","4.1704":"4.411","5.002475":"5.142825","5.563875":"5.79445","7.15785":"7.71925","8.350825":"8.822",
		"10.014975":"10.295675","11.117725":"11.578875","14.305675":"15.448525","16.691625":"17.654025","20.039975":"20.601375","22.2555":"23.15775","28.621375":"30.877",
		"33.38325":"35.288","40.049875":"41.20275","44.511":"46.3155","57.252775":"61.764025","66.776525":"74.094775","80.109775":"92.641025","100.159775":"105.874025",
		"114.495525":"123.618275","133.543025":"148.279775","160.309775":"185.372275","200.409775":"220.55"}
	}
	else if(this.SelectedSpreadSpectrum.key == "5"){
		this.FrequencyHoles = {"1.04721":"1.10349","1.25826":"1.2864","1.39695":"1.4472","1.79694":"1.9296","2.09643":"2.20497","2.51451":"2.57481","2.79591":"2.89641",
		"3.59589":"3.86121","4.19085":"4.41195","5.03505":"5.1456","5.5878":"5.7888","7.18575":"7.7184","8.39175":"8.8239","10.05":"10.30125","11.18565":"11.58765",
		"14.38155":"15.44685","16.77345":"17.65785","20.08995":"20.6025","22.36125":"23.16525","28.743":"30.90375","33.567":"35.2755","40.25025":"41.205",
		"44.7225":"46.3305","57.49605":"61.71705","67.14405":"74.07855","80.51055":"92.67105","100.71105":"105.93705","115.08255":"123.52455","134.27805":"148.24755",
		"161.11155":"185.33205","201.41205":"221.1"}
	}
	else if(this.SelectedSpreadSpectrum.key == "4"){
		this.FrequencyHoles = {"1.04636":"1.11706","1.25442":"1.3029","1.3938":"1.46652","1.67256":"1.67458","1.79376":"1.95536","2.0907":"2.2321","2.51086":"2.60782",
		"2.78962":"2.93102","3.34714":"3.35118","3.5855":"3.9087","4.1814":"4.4642","5.0197":"5.2116","5.5752":"5.858","7.171":"7.8174","8.3628":"8.9385",
		"10.0394":"10.4131","11.1605":"11.7261","13.3825":"13.4027","14.3521":"15.6449","16.7357":"17.8669","20.0889":"20.8565","22.321":"23.432","26.765":"26.8155",
		"28.684":"31.2595","33.4815":"35.754","40.1475":"41.713","44.642":"46.9145","53.5401":"53.6411","57.3781":"62.5291","66.9731":"75.0531","80.3051":"93.8391",
		"100.4041":"107.2721","114.7461":"125.1491","133.9361":"150.1971","160.7011":"187.7691","200.8991":"222.2"}
	}
}*/

function Family9002() {
    SwingSelectOptionWithSpreadSpectrum.call(this);
};
Family9002.prototype = new SwingSelectOptionWithSpreadSpectrum(inheriting);
Family9002.base = SwingSelectOptionWithSpreadSpectrum.prototype;
Family9002.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9002");
    Family9002.base.Initialization.call(this, jsonData);
}
Family9002.prototype.ExclusionTable = function() {
    return Family9002.base.GetRestrictionResult.call(this);
    /*
    if((this.SelectedSupplyVoltage.key=="18")&&(this.SelectedSignallingType.key!="3")){
    	this.validationErrors.push(getErrorByErrorID("12"));
    	return false;
    }
    else if((this.SelectedSwingSelectOption.key=="H")&&(this.SelectedSignallingType.key!="3")&&(this.SelectedSignallingType.key!="2")){
    	this.validationErrors.push(getErrorByErrorID("12"));
    	return false;
    }
    else
    	return true;
    */
}

Family9002.prototype.AvailableOptionsShow = function() {
    disableOption("voltage", "18");
    if (this.SelectedSwingSelectOption.key == "H") {
        disableOption("signaling", "0");
        disableOption("signaling", "1");
        disableOption("signaling", "4");
        if (this.SelectedSignallingType.key == "3")
            enableOption("voltage", "18");
    } else {
        enableOption("signaling", "0");
        enableOption("signaling", "1");
        enableOption("signaling", "4");
    }
    if (this.SelectedSignallingType.key != "3") {
        disableOption("voltage", "18");
        disableOption("swing", "H");
        if (this.SelectedSignallingType.key == "2")
            enableOption("swing", "H");
    } else {
        enableOption("voltage", "18");
        enableOption("swing", "H");
        if (this.SelectedSupplyVoltage.key == "18") {
            disableOption("signaling", "0");
            disableOption("signaling", "1");
            disableOption("signaling", "2");
            disableOption("signaling", "4");
        } else {
            enableOption("signaling", "0");
            enableOption("signaling", "1");
            enableOption("signaling", "2");
            enableOption("signaling", "4");
        }
    }
}

function Family9003() {
    FeaturePinWithSpreadSpectrum.call(this);
};
Family9003.prototype = new FeaturePinWithSpreadSpectrum(inheriting);
Family9003.base = FeaturePinWithSpreadSpectrum.prototype;
Family9003.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9003");
    Family9003.base.Initialization.call(this, jsonData);
}
Family9003.prototype.ExclusionTable = function() {
    return Family9003.base.GetRestrictionResult.call(this);

    /*	if((this.SelectedTemperatureRange.key == "I")&&((this.SelectedSpreadSpectrum.key == "D")||(this.SelectedSpreadSpectrum.key == "Q"))){
    		this.validationErrors.push(getErrorByErrorID("13"));
    		return false;
    	}
    	else if((this.SelectedTemperatureRange.key == "C")&&((this.SelectedSpreadSpectrum.key == "D")||(this.SelectedSpreadSpectrum.key == "Q"))){
    		if(parseFloat(this.Frequency.key) > 75){
    			this.validationErrors.push(getErrorByErrorID("13"));
    			return false;
    		}
    		else return true;
    	}
    	else return true;*/

}
Family9003.prototype.AvailableOptionsShow = function() {
    if ((this.SelectedTemperatureRange.key == "I") || (this.Frequency.key > 75 && this.SelectedTemperatureRange.key == "C")) {
        disableOption("spread", "D");
        disableOption("spread", "Q");
        DeselectOption("spread", "D");
        DeselectOption("spread", "Q");
    } else {
        enableOption("spread", "D");
        enableOption("spread", "Q");
    }

    if (this.SelectedSpreadSpectrum.key == "D" || this.SelectedSpreadSpectrum.key == "Q") disableOption("temprange", "I");
    else enableOption("temprange", "I");

}

Family9003.prototype.Validate = function() {
    //var result = BaseViewModel.prototype.OnFrequencyChange.call(this);
    if ((this.SelectedTemperatureRange.key == "I") && ((this.SelectedSpreadSpectrum.key == "D") || (this.SelectedSpreadSpectrum.key == "Q"))) {
        this.validationErrors.push(getErrorByErrorID("13"));
        return false;
    }
    if ((this.SelectedTemperatureRange.key == "C") && ((this.SelectedSpreadSpectrum.key == "D") || (this.SelectedSpreadSpectrum.key == "Q"))) {
        if (parseFloat(this.Frequency.key) > 75) {
            this.validationErrors.push(getErrorByErrorID("13"));
            return false;
        }
    }

    if ((this.SelectedTemperatureRange.key == "I") || this.Frequency.key > 75 && this.SelectedTemperatureRange.key == "C") {
        disableOption("spread", "D");
        disableOption("spread", "Q");
        DeselectOption("spread", "D");
        DeselectOption("spread", "Q");
        SelectOptions();
        if (this.SelectedSpreadSpectrum.key == "Q" || this.SelectedSpreadSpectrum.key == "D")
            this.SelectedSpreadSpectrum.key = "-";
    } else {
        enableOption("spread", "D");
        enableOption("spread", "Q");
    }

    if (this.SelectedSpreadSpectrum.key == "D" || this.SelectedSpreadSpectrum.key == "Q") disableOption("temprange", "I");
    else enableOption("temprange", "I");

    return Family9003.base.Validate.call(this);
}

Family9003.prototype.Parse = function(partNumber) {
    Family9003.base.Parse.call(this, partNumber);
    Family9003.base.GetChangeOptionRestrictionResult.call(this);
}


function Family9005() {
    BaseWithSpreadType.call(this);
};
Family9005.prototype = new BaseWithSpreadType(inheriting);
Family9005.base = BaseWithSpreadType.prototype;
Family9005.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9005");
    Family9005.base.Initialization.call(this, jsonData);
}
Family9005.prototype.ExclusionTable = function() {
    return Family9005.base.GetRestrictionResult.call(this);
}

Family9005.prototype.AvailableOptionsShow = function() {
    var spreadtype = this.SelectedSpreadType.key;
    var spreadspectrum = this.SelectedSpreadSpectrum.group;

    if (spreadtype != this.lastspreadtype) {
        this.lastspreadtype = spreadtype;
        if (spreadtype == "-" || spreadtype == "H") {
            showOptionGroup("spread", "center");
            showOptionGroup("spreadtype", "down");
            selectOptionGroup("center", this.SelectedSpreadSpectrum.key);
            hideOptionGroup("spread", "down");
        } else if (spreadtype == "D" || spreadtype == "G") {
            showOptionGroup("spread", "down");
            showOptionGroup("spreadtype", "center");
            hideOptionGroup("spread", "center");
            selectOptionGroup("down", this.SelectedSpreadSpectrum.key);
        }
    }

    if (spreadspectrum != this.lastspreadspectrum) {
        this.lastspreadspectrum = spreadspectrum;
        if (spreadspectrum == "center") {
            showOptionGroup("spreadtype", "center");
            hideOptionGroup("spreadtype", "down");
        } else if (spreadspectrum == "down") {
            showOptionGroup("spreadtype", "down");
            hideOptionGroup("spreadtype", "center");
        }
    }

    if ((spreadtype == "-" || spreadtype == "H") && this.SelectedSpreadSpectrum.key == "P") {
        this.FrequencyHoles = {
            "120.100000": "121.100000"
        };
    } else if ((spreadtype == "D" || spreadtype == "G") && this.SelectedSpreadSpectrum.key == "P") {
        this.FrequencyHoles = {
            "120.100000": "122.300000",
            "122.900000": "123.100000",
            "123.500000": "124.000000",
            "124.900000": "125.200000"
        };
    } else if ((spreadtype == "D" || spreadtype == "G") && this.SelectedSpreadSpectrum.key == "O") {
        this.FrequencyHoles = {
            "121.000000": "121.300000"
        };
    } else {
        this.FrequencyHoles = {};
    }
}

Family9005.prototype.Parse = function(partNumber) {
    Family9005.base.Parse.call(this, partNumber);
    if ((this.SelectedSpreadType.key == "-" || this.SelectedSpreadType.key == "H") && this.SelectedSpreadSpectrum.key == "P") {
        this.FrequencyHoles = {
            "120.100000": "121.100000"
        };
    } else if ((this.SelectedSpreadType.key == "D" || this.SelectedSpreadType.key == "G") && this.SelectedSpreadSpectrum.key == "P") {
        this.FrequencyHoles = {
            "120.100000": "122.300000",
            "122.900000": "123.100000",
            "123.500000": "124.000000",
            "124.900000": "125.200000"
        };
    } else if ((this.SelectedSpreadType.key == "D" || this.SelectedSpreadType.key == "G") && this.SelectedSpreadSpectrum.key == "O") {
        this.FrequencyHoles = {
            "121.000000": "121.300000"
        };
    } else {
        this.FrequencyHoles = {};
    }
}

Family9005.prototype.Validate = function(partNumber) {
    return Family9005.base.Validate.call(this, partNumber);
}



function Family9025() {
    BaseWithSpreadType.call(this);
};
Family9025.prototype = new BaseWithSpreadType(inheriting);
Family9025.base = BaseWithSpreadType.prototype;
Family9025.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9025");
    Family9025.base.Initialization.call(this, jsonData);
}
Family9025.prototype.ExclusionTable = function() {
    return Family9025.base.GetRestrictionResult.call(this);
}

Family9025.prototype.Validate = function() {
    var spreadtype = this.SelectedSpreadType.key;
    var selectedSpreadSpectrum = this.SelectedSpreadSpectrum.key;

    var spreadtypegroup = this.SelectedSpreadType.group;
    var spreadspectrumgroup = this.SelectedSpreadSpectrum.group;

    if (spreadtype != "-" && spreadtypegroup != "nospread" && selectedSpreadSpectrum == "-") {
        this.validationErrors.push(getErrorByErrorID("45"));
        return false;

    }
    return Family9025.base.Validate.call(this);
}

Family9025.prototype.AvailableOptionsShow = function() {
    var spreadtype = this.SelectedSpreadType.key;

    var spreadtypegroup = this.SelectedSpreadType.group;
    var spreadspectrumgroup = this.SelectedSpreadSpectrum.group;

    if (spreadtype != this.lastspreadtype || spreadtypegroup != this.lastspreadtypegroup) {
        this.lastspreadtype = spreadtype;
        this.lastspreadtypegroup = spreadtypegroup;
        if ((spreadtype == "-" || spreadtype == "H" || spreadtype == "R") && spreadtypegroup == "center") {
            ShowHTMLResource("Spread Option (%)", "spread");
            showOptionGroup("spread", "center");
            showOptionGroup("spreadtype", "down");
            hideOptionGroup("spread", "down");
            hideOptionName("spread", "-");

            selectOptionGroup("center", "A");
            this.SelectedSpreadSpectrum.key = "A";

            enableOption("pin", "D");

            //return;
        } else if ((spreadtype == "D" || spreadtype == "G" || spreadtype == "Q") && spreadtypegroup == "down") {
            ShowHTMLResource("Spread Option (%)", "spread");
            showOptionGroup("spread", "down");
            showOptionGroup("spreadtype", "center");
            hideOptionGroup("spread", "center");
            hideOptionName("spread", "-");

            selectOptionGroup("down", "A");
            this.SelectedSpreadSpectrum.key = "A";

            enableOption("pin", "D");

            //return;
        } else if (spreadtype == "-" && spreadtypegroup == "nospread") {
            HideHTMLResource("Spread Option (%)", "spread");
            SelectOption("spread", "-");
            this.SelectedSpreadSpectrum.key = "-";

            DeselectOption("pin", "D");
            disableOption("pin", "D");
            SelectOption("pin", "S");
            this.SelectedFeaturePin.key = "S";

            //return;
        }
        if (spreadtype == "R" || spreadtype == "Q") {
            var SpreadArray = new Array("I", "J", "K", "L", "M", "N", "O", "P");
            SpreadArray.forEach(function(element) {
                disableOption("spread", element);
            });
            //return;
        } else {
            var SpreadArray = new Array("I", "J", "K", "L", "M", "N", "O", "P");
            SpreadArray.forEach(function(element) {
                enableOption("spread", element);
            });
        }
    }

    if (spreadspectrumgroup != this.lastspreadspectrum) {
        this.lastspreadspectrum = spreadspectrumgroup;
        if (this.SelectedSpreadSpectrum.key == "-" && this.SelectedSpreadType.key == undefined) {
            HideHTMLResource("Spread Option (%)", "spread");
            selectOptionGroup("nospread", "-");
            this.SelectedSpreadType.key = "-";

            DeselectOption("pin", "D");
            disableOption("pin", "D");
            SelectOption("pin", "S");
            this.SelectedFeaturePin.key = "S";
        } else if (spreadspectrumgroup == "center") {
            showOptionGroup("spreadtype", "center");
            //hideOptionGroup("spreadtype", "down");
        } else if (spreadspectrumgroup == "down") {
            showOptionGroup("spreadtype", "down");
            //hideOptionGroup("spreadtype", "center");
        }
    }

    if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && spreadtypegroup == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.700000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "119.900000": "124.500000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "102.700000",
                "119.600000": "128.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "86.100000",
                "100.100000": "103.400000",
                "119.400000": "129.200000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "74.500000": "75.800000",
                "85.400000": "88.500000",
                "99.300000": "106.200000",
                "119.200000": "132.700000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && spreadtypegroup == "down") {
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "120.100000": "123.200000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "100.100000": "101.600000",
                "120.100000": "127.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "85.800000": "87.400000",
                "100.100000": "102.400000",
                "102.900000": "104.800000",
                "120.100000": "128.100000",
                "128.600000": "131.100000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && spreadtypegroup == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "120.100000": "120.900000",
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.70000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "100.100000": "102.900000",
                "119.800000": "128.600000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "85.800000": "86.300000",
                "100.100000": "103.500000",
                "119.600000": "129.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "74.600000": "75.900000",
                "85.600000": "88.600000",
                "99.500000": "106.300000",
                "119.400000": "132.900000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "60.100000": "60.200000",
                "66.500000": "67.700000",
                "74.500000": "77.400000",
                "85.400000": "90.300000",
                "99.300000": "108.400000",
                "119.100000": "135.500000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && spreadtypegroup == "down") {
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "120.100000": "123.400000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "101.800000",
                "120.100000": "127.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "87.500000",
                "100.100000": "102.600000",
                "102.800000": "105.000000",
                "120.100000": "128.200000",
                "128.500000": "131.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "75.100000": "75.600000",
                "85.800000": "88.200000",
                "100.100000": "105.800000",
                "120.100000": "132.300000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else {
        this.FrequencyHoles = {};
    }
}

Family9025.prototype.Parse = function(partNumber) {
    Family9025.base.Parse.call(this, partNumber);
    if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && this.SelectedSpreadType.group == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.700000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "119.900000": "124.500000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "102.700000",
                "119.600000": "128.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "86.100000",
                "100.100000": "103.400000",
                "119.400000": "129.200000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "74.500000": "75.800000",
                "85.400000": "88.500000",
                "99.300000": "106.200000",
                "119.200000": "132.700000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && this.SelectedSpreadType.group == "down") {
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "120.100000": "123.200000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "100.100000": "101.600000",
                "120.100000": "127.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "85.800000": "87.400000",
                "100.100000": "102.400000",
                "102.900000": "104.800000",
                "120.100000": "128.100000",
                "128.600000": "131.100000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && this.SelectedSpreadType.group == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "120.100000": "120.900000",
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.70000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "100.100000": "102.900000",
                "119.800000": "128.600000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "85.800000": "86.300000",
                "100.100000": "103.500000",
                "119.600000": "129.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "74.600000": "75.900000",
                "85.600000": "88.600000",
                "99.500000": "106.300000",
                "119.400000": "132.900000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "60.100000": "60.200000",
                "66.500000": "67.700000",
                "74.500000": "77.400000",
                "85.400000": "90.300000",
                "99.300000": "108.400000",
                "119.100000": "135.500000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && this.SelectedSpreadType.group == "down") {
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "120.100000": "123.400000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "101.800000",
                "120.100000": "127.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "87.500000",
                "100.100000": "102.600000",
                "102.800000": "105.000000",
                "120.100000": "128.200000",
                "128.500000": "131.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "75.100000": "75.600000",
                "85.800000": "88.200000",
                "100.100000": "105.800000",
                "120.100000": "132.300000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else {
        this.FrequencyHoles = {};
    }
}

Family9025.prototype.SelectOptions = function(partNumber) {
    Family9025.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "D");
    }
}

function Family9045() {
    SpreadTypeWithFeaturePinAndSpecialFeatures.call(this);
};
Family9045.prototype = new SpreadTypeWithFeaturePinAndSpecialFeatures(inheriting);
Family9045.base = SpreadTypeWithFeaturePinAndSpecialFeatures.prototype;
Family9045.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9045");
    Family9045.base.Initialization.call(this, jsonData);
}
Family9045.prototype.ExclusionTable = function() {
    return Family9045.base.GetRestrictionResult.call(this);
}

Family9045.prototype.Validate = function() {
    var spreadtype = this.SelectedSpreadType.key;
    var selectedSpreadSpectrum = this.SelectedSpreadSpectrum.key;

    var spreadtypegroup = this.SelectedSpreadType.group;
    var spreadspectrumgroup = this.SelectedSpreadSpectrum.group;

    if (spreadtype != "-" && spreadtypegroup != "nospread" && selectedSpreadSpectrum == "-") {
        this.validationErrors.push(getErrorByErrorID("45"));
        return false;

    }
    return Family9045.base.Validate.call(this);
}

Family9045.prototype.AvailableOptionsShow = function() {
    var spreadtype = this.SelectedSpreadType.key;

    var spreadtypegroup = this.SelectedSpreadType.group;
    var spreadspectrumgroup = this.SelectedSpreadSpectrum.group;

    if (spreadtype != this.lastspreadtype || spreadtypegroup != this.lastspreadtypegroup) {
        this.lastspreadtype = spreadtype;
        this.lastspreadtypegroup = spreadtypegroup;
        if ((spreadtype == "-" || spreadtype == "H" || spreadtype == "R") && spreadtypegroup == "center") {
            ShowHTMLResource("Spread Option (%)", "spread");
            showOptionGroup("spread", "center");
            showOptionGroup("spreadtype", "down");
            hideOptionGroup("spread", "down");
            hideOptionName("spread", "-");

            selectOptionGroup("center", "A");
            this.SelectedSpreadSpectrum.key = "A";

            enableOption("pin", "D");

            return;
        } else if ((spreadtype == "D" || spreadtype == "G" || spreadtype == "Q") && spreadtypegroup == "down") {
            ShowHTMLResource("Spread Option (%)", "spread");
            showOptionGroup("spread", "down");
            showOptionGroup("spreadtype", "center");
            hideOptionGroup("spread", "center");
            hideOptionName("spread", "-");

            selectOptionGroup("down", "A");
            this.SelectedSpreadSpectrum.key = "A";

            enableOption("pin", "D");

            return;
        } else if (spreadtype == "-" && spreadtypegroup == "nospread") {
            HideHTMLResource("Spread Option (%)", "spread");
            SelectOption("spread", "-");
            this.SelectedSpreadSpectrum.key = "-";

            DeselectOption("pin", "D");
            disableOption("pin", "D");
            SelectOption("pin", "S");
            this.SelectedFeaturePin.key = "S";

            return;
        }
    }

    if (spreadspectrumgroup != this.lastspreadspectrum) {
        this.lastspreadspectrum = spreadspectrumgroup;
        if (this.SelectedSpreadSpectrum.key == "-" && this.SelectedSpreadType.key == undefined) {
            HideHTMLResource("Spread Option (%)", "spread");
            selectOptionGroup("nospread", "-");
            this.SelectedSpreadType.key = "-";

            DeselectOption("pin", "D");
            disableOption("pin", "D");
            SelectOption("pin", "S");
            this.SelectedFeaturePin.key = "S";
        } else if (spreadspectrumgroup == "center") {
            showOptionGroup("spreadtype", "center");
            //hideOptionGroup("spreadtype", "down");
        } else if (spreadspectrumgroup == "down") {
            showOptionGroup("spreadtype", "down");
            //hideOptionGroup("spreadtype", "center");
        }
    }

    if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && spreadtypegroup == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.700000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "119.900000": "124.500000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "102.700000",
                "119.600000": "128.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "86.100000",
                "100.100000": "103.400000",
                "119.400000": "129.200000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "74.500000": "75.800000",
                "85.400000": "88.500000",
                "99.300000": "106.200000",
                "119.200000": "132.700000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && spreadtypegroup == "down") {
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "120.100000": "123.200000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "100.100000": "101.600000",
                "120.100000": "127.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "85.800000": "87.400000",
                "100.100000": "102.400000",
                "102.900000": "104.800000",
                "120.100000": "128.100000",
                "128.600000": "131.100000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && spreadtypegroup == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "120.100000": "120.900000",
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.70000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "100.100000": "102.900000",
                "119.800000": "128.600000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "85.800000": "86.300000",
                "100.100000": "103.500000",
                "119.600000": "129.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "74.600000": "75.900000",
                "85.600000": "88.600000",
                "99.500000": "106.300000",
                "119.400000": "132.900000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "60.100000": "60.200000",
                "66.500000": "67.700000",
                "74.500000": "77.400000",
                "85.400000": "90.300000",
                "99.300000": "108.400000",
                "119.100000": "135.500000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && spreadtypegroup == "down") {
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "120.100000": "123.400000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "101.800000",
                "120.100000": "127.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "87.500000",
                "100.100000": "102.600000",
                "102.800000": "105.000000",
                "120.100000": "128.200000",
                "128.500000": "131.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "75.100000": "75.600000",
                "85.800000": "88.200000",
                "100.100000": "105.800000",
                "120.100000": "132.300000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else {
        this.FrequencyHoles = {};
    }
}

Family9045.prototype.Parse = function(partNumber) {
    Family9045.base.Parse.call(this, partNumber);
    if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && this.SelectedSpreadType.group == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.700000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "119.900000": "124.500000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "102.700000",
                "119.600000": "128.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "86.100000",
                "100.100000": "103.400000",
                "119.400000": "129.200000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "74.500000": "75.800000",
                "85.400000": "88.500000",
                "99.300000": "106.200000",
                "119.200000": "132.700000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if ((this.SelectedTemperatureRange.key == "E" || this.SelectedTemperatureRange.key == "A") && this.SelectedSpreadType.group == "down") {
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "120.100000": "123.200000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "100.100000": "101.600000",
                "120.100000": "127.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "85.800000": "87.400000",
                "100.100000": "102.400000",
                "102.900000": "104.800000",
                "120.100000": "128.100000",
                "128.600000": "131.100000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && this.SelectedSpreadType.group == "center") {
        if (this.SelectedSpreadSpectrum.key == "K") {
            this.FrequencyHoles = {
                "120.100000": "120.900000",
                "149.900000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "L") {
            this.FrequencyHoles = {
                "120.100000": "120.70000",
                "148.800000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "100.100000": "102.900000",
                "119.800000": "128.600000",
                "149.600000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "85.800000": "86.300000",
                "100.100000": "103.500000",
                "119.600000": "129.400000",
                "149.300000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "74.600000": "75.900000",
                "85.600000": "88.600000",
                "99.500000": "106.300000",
                "119.400000": "132.900000",
                "149.100000": "150.000000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "60.100000": "60.200000",
                "66.500000": "67.700000",
                "74.500000": "77.400000",
                "85.400000": "90.300000",
                "99.300000": "108.400000",
                "119.100000": "135.500000",
                "148.900000": "150.000000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else if (this.SelectedTemperatureRange.key == "M" && this.SelectedSpreadType.group == "down") {
        if (this.SelectedSpreadSpectrum.key == "M") {
            this.FrequencyHoles = {
                "120.100000": "123.400000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "N") {
            this.FrequencyHoles = {
                "100.100000": "101.800000",
                "120.100000": "127.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "O") {
            this.FrequencyHoles = {
                "85.800000": "87.500000",
                "100.100000": "102.600000",
                "102.800000": "105.000000",
                "120.100000": "128.200000",
                "128.500000": "131.300000"
            };
            return;
        }
        if (this.SelectedSpreadSpectrum.key == "P") {
            this.FrequencyHoles = {
                "75.100000": "75.600000",
                "85.800000": "88.200000",
                "100.100000": "105.800000",
                "120.100000": "132.300000"
            };
            return;
        } else {
            this.FrequencyHoles = {};
        }
    } else {
        this.FrequencyHoles = {};
    }
}

Family9045.prototype.SelectOptions = function(partNumber) {
    Family9045.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackaging.key == "A")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackaging.key == "B")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackaging.key == "C")) {
        SelectOption("packaging", "D");
    }
}

function Family9501() {
    SignallingGroupWithReserved.call(this);
};
Family9501.prototype = new SignallingGroupWithReserved(inheriting);
Family9501.base = SignallingGroupWithReserved.prototype;
Family9501.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9501");
    Family9501.base.Initialization.call(this, jsonData);
}
Family9501.prototype.ExclusionTable = function() {
    return Family9501.base.GetRestrictionResult.call(this);
    //return true;
}

Family9501.prototype.AvailableOptionsShow = function() {
    var signalingGroup = this.SelectedSignallingGroup.key;

    if (signalingGroup != this.lastSignalingGroup) {
        this.lastSignalingGroup = signalingGroup;
        if (this.SelectedSignallingGroup.key == "-") {
            showAllOptions("signaling");		
			hideOptionName("signaling", "FS");
			SelectOption("signaling", "01");
			this.SelectedSignallingType.key = "01";
        } else if (this.SelectedSignallingGroup.key == "1") {
            showAllOptions("signaling");		
			SelectOption("signaling", "FS");
			this.SelectedSignallingType.key = "FS";
			hideOptionName("signaling", "01");
			hideOptionName("signaling", "02");
			hideOptionName("signaling", "04");
			hideOptionName("signaling", "08");
        }
    }
}


Family9501.prototype.Parse = function(partNumber) {
    Family9501.base.Parse.call(this, partNumber);
    Family9501.base.GetChangeOptionRestrictionResult.call(this);
}

function Family9375() {
    SignallingGroupWithReserved.call(this);
};
Family9375.prototype = new SignallingGroupWithReserved(inheriting);
Family9375.base = SignallingGroupWithReserved.prototype;
Family9375.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9375");
    Family9375.base.Initialization.call(this, jsonData);
}
Family9375.prototype.ExclusionTable = function() {
    return Family9375.base.GetRestrictionResult.call(this);
    //return true;
}

Family9375.prototype.AvailableOptionsShow = function() {
    var signalingGroup = this.SelectedSignallingGroup.key;

    if (signalingGroup != this.lastSignalingGroup) {
        this.lastSignalingGroup = signalingGroup;
        if (this.SelectedSignallingGroup.key == "-") {
            showAllOptions("signaling");		
			hideOptionName("signaling", "FS");
			SelectOption("signaling", "01");
			this.SelectedSignallingType.key = "01";
        } else if (this.SelectedSignallingGroup.key == "1") {
            showAllOptions("signaling");		
			SelectOption("signaling", "FS");
			this.SelectedSignallingType.key = "FS";
			hideOptionName("signaling", "01");
			hideOptionName("signaling", "02");
			hideOptionName("signaling", "04");
			hideOptionName("signaling", "08");
        }
    }
}


Family9375.prototype.Parse = function(partNumber) {
    Family9375.base.Parse.call(this, partNumber);
    Family9375.base.GetChangeOptionRestrictionResult.call(this);
}

function Family9120() {
    FeaturePinWithSignallingType.call(this);
};
Family9120.prototype = new FeaturePinWithSignallingType(inheriting);
Family9120.base = FeaturePinWithSignallingType.prototype;
Family9120.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9120");
    Family9120.base.Initialization.call(this, jsonData);
}
Family9120.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && this.SelectedPackageSize.key != "B") {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family9120.base.Validate.call(this);
}
Family9120.prototype.ExclusionTable = function() {
    return true;
}
Family9120.prototype.Parse = function(partNumber) {
    Family9120.base.Parse.call(this, partNumber);
    Family9120.base.GetChangeOptionRestrictionResult.call(this);
}

Family9120.prototype.AvailableOptionsShow = function() {
    var pack = this.SelectedPackageSize.key;

    if (pack != this.lastpack) {
        this.lastpack = pack;
        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            DeselectOption("packaging", "G");
            DeselectOption("packaging", "E");
            DeselectOption("packaging", "D");
        }
    }
}

function Family9121() {
    FeaturePinWithSignallingType.call(this);
};
Family9121.prototype = new FeaturePinWithSignallingType(inheriting);
Family9121.base = FeaturePinWithSignallingType.prototype;
Family9121.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9121");
    Family9121.base.Initialization.call(this, jsonData);
}
Family9121.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && this.SelectedPackageSize.key != "B") {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family9121.base.Validate.call(this);
}
Family9121.prototype.ExclusionTable = function() {
    return true;
}
Family9121.prototype.Parse = function(partNumber) {
    Family9121.base.Parse.call(this, partNumber);
    Family9121.base.GetChangeOptionRestrictionResult.call(this);
}

Family9121.prototype.AvailableOptionsShow = function() {
    var pack = this.SelectedPackageSize.key;

    if (pack != this.lastpack) {
        this.lastpack = pack;
        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            DeselectOption("packaging", "G");
            DeselectOption("packaging", "E");
            DeselectOption("packaging", "D");
        }
    }
}

function Family9122() {
    FeaturePinWithSignallingType.call(this);
};
Family9122.prototype = new FeaturePinWithSignallingType(inheriting);
Family9122.base = FeaturePinWithSignallingType.prototype;
Family9122.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9122");
    Family9122.base.Initialization.call(this, jsonData);
}
Family9122.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && this.SelectedPackageSize.key != "B") {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family9122.base.Validate.call(this);
}
Family9122.prototype.ExclusionTable = function() {
    return true;
}
Family9122.prototype.Parse = function(partNumber) {
    Family9122.base.Parse.call(this, partNumber);
    Family9122.base.GetChangeOptionRestrictionResult.call(this);
}

Family9122.prototype.AvailableOptionsShow = function() {
    var pack = this.SelectedPackageSize.key;

    if (pack != this.lastpack) {
        this.lastpack = pack;
        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            DeselectOption("packaging", "G");
            DeselectOption("packaging", "E");
            DeselectOption("packaging", "D");
        }
    }
}

function Family9156() {
    FeaturePinWithSignallingType.call(this);
};
Family9156.prototype = new FeaturePinWithSignallingType(inheriting);
Family9156.base = FeaturePinWithSignallingType.prototype;
Family9156.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9156");
    Family9156.base.Initialization.call(this, jsonData);
}

Family9156.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {

            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") {

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";

            }
        }
    }
}

Family9156.prototype.SelectOptions = function(partNumber) {
    Family9156.base.SelectOptions.call(this, partNumber);
    if ((this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") && (this.SelectedPackaging.key == "X")) {
        SelectOption("packaging", "G");
    } else if ((this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") && (this.SelectedPackaging.key == "Y")) {
        SelectOption("packaging", "E");
    } else if ((this.SelectedPackageSize.key == "C" || this.SelectedPackageSize.key == "D") && (this.SelectedPackaging.key == "T")) {
        SelectOption("packaging", "D");
    }
    if ((this.SelectedPackageSize.key == "B") && (this.SelectedPackaging.key == "G")) {
        SelectOption("packaging", "X");
    } else if ((this.SelectedPackageSize.key == "B") && (this.SelectedPackaging.key == "E")) {
        SelectOption("packaging", "Y");
    } else if ((this.SelectedPackageSize.key == "B") && (this.SelectedPackaging.key == "D")) {
        SelectOption("packaging", "T");
    }
}

Family9156.prototype.Validate = function() {
    if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && this.SelectedPackageSize.key != "B") {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }

    return Family9156.base.Validate.call(this);
}
Family9156.prototype.ExclusionTable = function() {
    return true;
}
Family9156.prototype.Parse = function(partNumber) {
    Family9156.base.Parse.call(this, partNumber);
    Family9156.base.GetChangeOptionRestrictionResult.call(this);
}

function Family9102() {
    SignallingTypeWithSwingSelectOption.call(this);
};
Family9102.prototype = new SignallingTypeWithSwingSelectOption(inheriting);
Family9102.base = SignallingTypeWithSwingSelectOption.prototype;
Family9102.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9102");
    Family9102.base.Initialization.call(this, jsonData);
}
Family9102.prototype.ExclusionTable = function() {
    return Family9102.base.GetRestrictionResult.call(this);
    /*
    if (this.SelectedFrequencyStability.key == "F"){
    	if (this.SelectedTemperatureRange.key == "C" || this.SelectedTemperatureRange.key == "I"){
    		this.validationErrors.push(getErrorByErrorID("6"));
    		return false;
    	}
    	else if (this.SelectedSupplyVoltage.key == "18"){
    		this.validationErrors.push(getErrorByErrorID("6"));
    		return false;
    	}
    }
    
    if (this.SelectedFrequencyStability.key == "H" && (this.SelectedTemperatureRange.key == "C" || this.SelectedTemperatureRange.key == "I") && this.SelectedSupplyVoltage.key == "18"){
    	this.validationErrors.push(getErrorByErrorID("6"));
    	return false;
    }
    
    if (this.SelectedSignallingType.key == "0" || this.SelectedSignallingType.key == "1" || this.SelectedSignallingType.key == "4"){
    	if (this.SelectedSwingSelectOption.key == "H"){
    		this.validationErrors.push(getErrorByErrorID("12"));
    		return false;
    	}
    	else if (this.SelectedSupplyVoltage.key == "18"){
    		this.validationErrors.push(getErrorByErrorID("12"));
    		return false;
    	}
    }
    else{
    	if (this.SelectedSignallingType.key == "2" && (this.SelectedSupplyVoltage.key == "18")){
    		this.validationErrors.push(getErrorByErrorID("12"));
    		return false;
    	}
    }
    
    return true;
    */
}
Family9102.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var voltage = this.SelectedSupplyVoltage.key;
    var tolerance = this.SelectedFrequencyStability.key;
    var signaling = this.SelectedSignallingType.key;
    var swing = this.SelectedSwingSelectOption.key;

    var highSwingAllowed = signaling == "2" || signaling == "3";

    if (signaling != this.lastSignaling) {
        this.lastSignaling = signaling;
        if (signaling != "3")
            disableOption("voltage", "18");
        else {
            if (tolerance != "F" && !(tolerance == "H" && tempRange != "N"))
                enableOption("voltage", "18");
        }
        if (highSwingAllowed)
            enableOption("swing", "H");
        else
            disableOption("swing", "H");
    }

    if (swing != this.lastSwing) {
        this.lastSwing = swing;
        if (swing == "H") {
            disableOption("signaling", "0");
            disableOption("signaling", "1");
            disableOption("signaling", "4");
        } else {
            enableOption("signaling", "0");
            enableOption("signaling", "1");
            enableOption("signaling", "4");
        }
    }

    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "F") {
            disableOption("temprange", "I");
            disableOption("temprange", "C");
            disableOption("voltage", "18");
        } else if (tolerance == "H") {
            if (voltage == "18") {
                enableOption("temprange", "N");
                disableOption("temprange", "I");
                disableOption("temprange", "C");
            } else {
                disableOption("voltage", "18");
                enableOption("temprange", "N");
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
            if (tempRange == "N" && signaling == "3")
                enableOption("voltage", "18");
        } else {
            enableOption("temprange", "N");
            enableOption("temprange", "I");
            enableOption("temprange", "C");
            if (signaling == "3")
                enableOption("voltage", "18");
        }
    }

    if (voltage != this.lastVoltage) {
        this.lastVoltage = voltage;
        if (voltage == "18") {
            disableOption("tolerance", "F");
            if (tempRange != "N")
                disableOption("tolerance", "H");
            else
                enableOption("tolerance", "H");
            disableOption("signaling", "0");
            disableOption("signaling", "1");
            disableOption("signaling", "2");
            disableOption("signaling", "4");

            if (tolerance == "H") {
                disableOption("temprange", "I");
                disableOption("temprange", "C");
            } else {
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
        } else {
            enableOption("tolerance", "H");
            if (tempRange != "N")
                disableOption("tolerance", "F");
            else
                enableOption("tolerance", "F");
            enableOption("signaling", "2");
            if (swing != "H") {
                enableOption("signaling", "0");
                enableOption("signaling", "1");
                enableOption("signaling", "4");
            }
            if (tolerance == "F") {
                disableOption("temprange", "I");
                disableOption("temprange", "C");
            } else {
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
        }
    }

    if (tempRange != this.lastRange) {
        this.lastRange = tempRange;
        if (tempRange != "N") {
            disableOption("tolerance", "F");
            if (voltage == "18")
                disableOption("tolerance", "H");
            else
                enableOption("tolerance", "H");
        } else {
            if (voltage == "18") {
                disableOption("tolerance", "F");
                enableOption("tolerance", "H");
            } else
                enableOption("tolerance", "F");
            if (tolerance == "H")
                enableOption("voltage", "18");
        }
    }
}

function Family9107() {
    SignallingTypeWithSwingSelectOption.call(this);
};
Family9107.prototype = new SignallingTypeWithSwingSelectOption(inheriting);
Family9107.base = SignallingTypeWithSwingSelectOption.prototype;
Family9107.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9107");
    Family9107.base.Initialization.call(this, jsonData);
}
Family9107.prototype.ExclusionTable = function() {
    return Family9107.base.GetRestrictionResult.call(this);

}
Family9107.prototype.AvailableOptionsShow = function() {
    var tempRange = this.SelectedTemperatureRange.key;
    var voltage = this.SelectedSupplyVoltage.key;
    var tolerance = this.SelectedFrequencyStability.key;
    var signaling = this.SelectedSignallingType.key;
    var swing = this.SelectedSwingSelectOption.key;

    var highSwingAllowed = signaling == "2" || signaling == "3";

    if (signaling != this.lastSignaling) {
        this.lastSignaling = signaling;
        if (signaling != "3")
            disableOption("voltage", "18");
        else {
            if (tolerance != "F" && !(tolerance == "H" && tempRange != "N"))
                enableOption("voltage", "18");
        }
        if (highSwingAllowed)
            enableOption("swing", "H");
        else
            disableOption("swing", "H");
    }

    if (swing != this.lastSwing) {
        this.lastSwing = swing;
        if (swing == "H") {
            disableOption("signaling", "0");
            disableOption("signaling", "1");
        } else {
            enableOption("signaling", "0");
            enableOption("signaling", "1");
        }
    }

    if (tolerance != this.lastTolerance) {
        this.lastTolerance = tolerance;
        if (tolerance == "F") {
            disableOption("temprange", "I");
            disableOption("temprange", "C");
            disableOption("voltage", "18");
        } else if (tolerance == "H") {
            if (voltage == "18") {
                enableOption("temprange", "N");
                disableOption("temprange", "I");
                disableOption("temprange", "C");
            } else {
                disableOption("voltage", "18");
                enableOption("temprange", "N");
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
            if (tempRange == "N" && signaling == "3")
                enableOption("voltage", "18");
        } else {
            enableOption("temprange", "N");
            enableOption("temprange", "I");
            enableOption("temprange", "C");
            if (signaling == "3")
                enableOption("voltage", "18");
        }
    }

    if (voltage != this.lastVoltage) {
        this.lastVoltage = voltage;
        if (voltage == "18") {
            disableOption("tolerance", "F");
            if (tempRange != "N")
                disableOption("tolerance", "H");
            else
                enableOption("tolerance", "H");
            disableOption("signaling", "0");
            disableOption("signaling", "1");
            disableOption("signaling", "2");

            if (tolerance == "H") {
                disableOption("temprange", "I");
                disableOption("temprange", "C");
            } else {
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
        } else {
            enableOption("tolerance", "H");
            if (tempRange != "N")
                disableOption("tolerance", "F");
            else
                enableOption("tolerance", "F");
            enableOption("signaling", "2");
            if (swing != "H") {
                enableOption("signaling", "0");
                enableOption("signaling", "1");
            }
            if (tolerance == "F") {
                disableOption("temprange", "I");
                disableOption("temprange", "C");
            } else {
                enableOption("temprange", "I");
                enableOption("temprange", "C");
            }
        }
    }

    if (tempRange != this.lastRange) {
        this.lastRange = tempRange;
        if (tempRange != "N") {
            disableOption("tolerance", "F");
            if (voltage == "18")
                disableOption("tolerance", "H");
            else
                enableOption("tolerance", "H");
        } else {
            if (voltage == "18") {
                disableOption("tolerance", "F");
                enableOption("tolerance", "H");
            } else
                enableOption("tolerance", "F");
            if (tolerance == "H")
                enableOption("voltage", "18");
        }
    }
}

function Family9201() {
    BaseWithPin.call(this);
};
Family9201.prototype = new BaseWithPin(inheriting);
Family9201.base = BaseWithPin.prototype;
Family9201.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9201");
    Family9201.base.Initialization.call(this, jsonData);
}
Family9201.prototype.ExclusionTable = function() {
    return true;
}

function Family2001() {
    BaseWithPin.call(this);
};
Family2001.prototype = new BaseWithPin(inheriting);
Family2001.base = BaseWithPin.prototype;
Family2001.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT2001");
    Family2001.base.Initialization.call(this, jsonData);
}
Family2001.prototype.ExclusionTable = function() {
    return true;
}

function Family9346() {
    FeaturePinWithSignallingTypeAndSpecialFeatures.call(this);
};
Family9346.prototype = new FeaturePinWithSignallingTypeAndSpecialFeatures(inheriting);
Family9346.base = FeaturePinWithSignallingTypeAndSpecialFeatures.prototype;
Family9346.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9346");
    Family9366.base.Initialization.call(this, jsonData);
}
Family9346.prototype.ExclusionTable = function() {
    return Family9346.base.GetRestrictionResult.call(this);
    //return true;
}

Family9346.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

Family9346.prototype.Parse = function(partNumber) {
    Family9346.base.Parse.call(this, partNumber);
    Family9346.base.GetChangeOptionRestrictionResult.call(this);
}


function Family9347() {
    FeaturePinWithSignallingTypeAndSpecialFeatures.call(this);
};
Family9347.prototype = new FeaturePinWithSignallingTypeAndSpecialFeatures(inheriting);
Family9347.base = FeaturePinWithSignallingTypeAndSpecialFeatures.prototype;
Family9347.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9347");
    Family9347.base.Initialization.call(this, jsonData);
}
Family9347.prototype.ExclusionTable = function() {
    return Family9347.base.GetRestrictionResult.call(this);
    //return true;
}

Family9347.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }

    if (this.SelectedSignallingType.key == "1" || this.SelectedSignallingType.key == "2") {
        this.FrequencyHoles = {};
    } else if (this.SelectedSignallingType.key == "4") {
        this.FrequencyHoles = {
            "500.000001": "725.000000"
        };
    }
}

Family9347.prototype.Parse = function(partNumber) {
    Family9347.base.Parse.call(this, partNumber);
    Family9347.base.GetChangeOptionRestrictionResult.call(this);
}

Family9347.prototype.Validate = function(partNumber) {

    if (this.SelectedSignallingType.key == "1" || this.SelectedSignallingType.key == "2") {
        this.FrequencyHoles = {};
    } else if (this.SelectedSignallingType.key == "4") {
        this.FrequencyHoles = {
            "500.000001": "725.000000"
        };
    }

    return Family9347.base.Validate.call(this, partNumber);
}

function Family9365() {
    FeaturePinWithSignallingType.call(this);
};
Family9365.prototype = new FeaturePinWithSignallingType(inheriting);
Family9365.base = FeaturePinWithSignallingType.prototype;
Family9365.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9365");
    Family9365.base.Initialization.call(this, jsonData);
}
Family9365.prototype.ExclusionTable = function() {
    return Family9365.base.GetRestrictionResult.call(this);
    //return true;
}

Family9365.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

/*Family9365.prototype.Validate = function(){
		
	if (this.SelectedPackaging.key != ""){
		if (this.SelectedPackageSize.key == "B"){
			if(this.SelectedPackaging.key == ""){
				return Family9365.base.Validate.call(this);
			}
			else if(this.SelectedPackaging.key == "T"){
				this.SelectedPackaging.key = "D";
				
			}
			else if(this.SelectedPackaging.key == "Y"){
				this.SelectedPackaging.key = "E";	
				
			}
			else if(this.SelectedPackaging.key == "X"){
				this.SelectedPackaging.key = "G";	
			
			}
			else {
				return Family9365.base.Validate.call(this);
			}
		}
		if (this.SelectedPackageSize.key == "E") {
			if(this.SelectedPackaging.key == "D"){
				this.SelectedPackaging.key = "T";
				
			}
			else if(this.SelectedPackaging.key == "E"){
				this.SelectedPackaging.key = "Y";	
				
			}
			else if(this.SelectedPackaging.key == "G"){
				this.SelectedPackaging.key = "X";
				
			}
			else if(this.SelectedPackaging.key == ""){
				
				return Family9365.base.Validate.call(this);
			}
			else if (this.SelectedPackaging.key !== "X" && this.SelectedPackaging.key !== "Y" && this.SelectedPackaging.key !== "T"){
				return Family9365.base.Validate.call(this);
			}
		}
		
		return this.SelectedPackaging.key;
	}
	
	return Family9365.base.Validate.call(this);
}*/

Family9365.prototype.Parse = function(partNumber) {
    Family9365.base.Parse.call(this, partNumber);
    Family9365.base.GetChangeOptionRestrictionResult.call(this);
}


/*Family9365.prototype.SelectOptions = function(partNumber){
	Family9365.base.SelectOptions.call(this,partNumber);
	if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" ))&& (this.SelectedPackaging.key == "X")){
		SelectOption("packaging", "G");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "Y")){
		SelectOption("packaging", "E");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "T")){
		SelectOption("packaging", "D");
	}
	
	if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
		SelectOption("packaging", "X");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
		SelectOption("packaging", "Y");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
		SelectOption("packaging", "T");
	}
}*/

function Family9366() {
    FeaturePinWithSignallingType.call(this);
};
Family9366.prototype = new FeaturePinWithSignallingType(inheriting);
Family9366.base = FeaturePinWithSignallingType.prototype;
Family9366.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9366");
    Family9366.base.Initialization.call(this, jsonData);
}
Family9366.prototype.ExclusionTable = function() {
    return Family9366.base.GetRestrictionResult.call(this);
    //return true;
}

Family9366.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}


Family9366.prototype.Parse = function(partNumber) {
    Family9366.base.Parse.call(this, partNumber);
    Family9366.base.GetChangeOptionRestrictionResult.call(this);
}

/*Family9366.prototype.SelectOptions = function(partNumber){
	Family9366.base.SelectOptions.call(this,partNumber);
	if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" ))&& (this.SelectedPackaging.key == "X")){
		SelectOption("packaging", "G");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "Y")){
		SelectOption("packaging", "E");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "T")){
		SelectOption("packaging", "D");
	}
	
	if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
		SelectOption("packaging", "X");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
		SelectOption("packaging", "Y");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
		SelectOption("packaging", "T");
	}
}*/

function Family9367() {
    FeaturePinWithSignallingType.call(this);
};
Family9367.prototype = new FeaturePinWithSignallingType(inheriting);
Family9367.base = FeaturePinWithSignallingType.prototype;
Family9367.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9367");
    Family9367.base.Initialization.call(this, jsonData);
}
Family9367.prototype.ExclusionTable = function() {
    return Family9367.base.GetRestrictionResult.call(this);
    //return true;
}

Family9367.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }

    if (this.SelectedSignallingType.key == "1" || this.SelectedSignallingType.key == "2" || this.SelectedSignallingType.key == "5") {
        this.FrequencyHoles = {};
    } else if (this.SelectedSignallingType.key == "4") {
        this.FrequencyHoles = {
            "500.000001": "725.000000"
        };
    }
}


Family9367.prototype.Parse = function(partNumber) {
    Family9367.base.Parse.call(this, partNumber);
    Family9367.base.GetChangeOptionRestrictionResult.call(this);
}

/*Family9367.prototype.SelectOptions = function(partNumber){
	Family9367.base.SelectOptions.call(this,partNumber);
	if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" ))&& (this.SelectedPackaging.key == "X")){
		SelectOption("packaging", "G");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "Y")){
		SelectOption("packaging", "E");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "T")){
		SelectOption("packaging", "D");
	}
	
	if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
		SelectOption("packaging", "X");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
		SelectOption("packaging", "Y");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
		SelectOption("packaging", "T");
	}
}*/

Family9367.prototype.Validate = function(partNumber) {

    if (this.SelectedSignallingType.key == "1" || this.SelectedSignallingType.key == "2") {
        this.FrequencyHoles = {};
    } else if (this.SelectedSignallingType.key == "4") {
        this.FrequencyHoles = {
            "500.000001": "725.000000"
        };
    }

    return Family9367.base.Validate.call(this, partNumber);
}

function Family9368() {
    FeaturePinWithSignallingType.call(this);
};
Family9368.prototype = new FeaturePinWithSignallingType(inheriting);
Family9368.base = FeaturePinWithSignallingType.prototype;
Family9368.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9368");
    Family9365.base.Initialization.call(this, jsonData);
}
Family9368.prototype.ExclusionTable = function() {
    return Family9365.base.GetRestrictionResult.call(this);
    //return true;
}

Family9368.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";
                SelectOption("packaging", "D");
            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
                SelectOption("packaging", "E");
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
                SelectOption("packaging", "G");
            }
        } else if (this.SelectedPackageSize.key == "E" || this.SelectedPackageSize.key == "C") {
            showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");

            if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";
                SelectOption("packaging", "T");
            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
                SelectOption("packaging", "Y");
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
                SelectOption("packaging", "X");
            }
        }
    }
}

/*Family9368.prototype.Validate = function(){
		
	if (this.SelectedPackaging.key != ""){
		if (this.SelectedPackageSize.key == "B"){
			if(this.SelectedPackaging.key == ""){
				return Family9365.base.Validate.call(this);
			}
			else if(this.SelectedPackaging.key == "T"){
				this.SelectedPackaging.key = "D";
				
			}
			else if(this.SelectedPackaging.key == "Y"){
				this.SelectedPackaging.key = "E";	
				
			}
			else if(this.SelectedPackaging.key == "X"){
				this.SelectedPackaging.key = "G";	
			
			}
			else {
				return Family9365.base.Validate.call(this);
			}
		}
		if (this.SelectedPackageSize.key == "E") {
			if(this.SelectedPackaging.key == "D"){
				this.SelectedPackaging.key = "T";
				
			}
			else if(this.SelectedPackaging.key == "E"){
				this.SelectedPackaging.key = "Y";	
				
			}
			else if(this.SelectedPackaging.key == "G"){
				this.SelectedPackaging.key = "X";
				
			}
			else if(this.SelectedPackaging.key == ""){
				
				return Family9365.base.Validate.call(this);
			}
			else if (this.SelectedPackaging.key !== "X" && this.SelectedPackaging.key !== "Y" && this.SelectedPackaging.key !== "T"){
				return Family9365.base.Validate.call(this);
			}
		}
		
		return this.SelectedPackaging.key;
	}
	
	return Family9365.base.Validate.call(this);
}*/

Family9368.prototype.Parse = function(partNumber) {
    Family9368.base.Parse.call(this, partNumber);
    Family9368.base.GetChangeOptionRestrictionResult.call(this);
}


/*Family9368.prototype.SelectOptions = function(partNumber){
	Family9368.base.SelectOptions.call(this,partNumber);
	if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" ))&& (this.SelectedPackaging.key == "X")){
		SelectOption("packaging", "G");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "Y")){
		SelectOption("packaging", "E");
	}
	else if(((this.SelectedPackageSize.key == "E" ) || (this.SelectedPackageSize.key == "C" )) && (this.SelectedPackaging.key == "T")){
		SelectOption("packaging", "D");
	}
	
	if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "G")){
		SelectOption("packaging", "X");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "E")){
		SelectOption("packaging", "Y");
	}
	else if((this.SelectedPackageSize.key == "B" ) && (this.SelectedPackaging.key == "D")){
		SelectOption("packaging", "T");
	}
}*/

function Family9386() {
    FeaturePinWithSignallingType.call(this);
};
Family9386.prototype = new FeaturePinWithSignallingType(inheriting);
Family9386.base = FeaturePinWithSignallingType.prototype;
Family9386.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9386");
    Family9386.base.Initialization.call(this, jsonData);
}
Family9386.prototype.ExclusionTable = function() {
    return Family9386.base.GetRestrictionResult.call(this);
    //return true;
}

Family9386.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

        if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
			if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
            }

        } else if (this.SelectedPackageSize.key == "E") {
			showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";

            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
            }
        }
    }
}

Family9386.prototype.Parse = function(partNumber) {
    Family9386.base.Parse.call(this, partNumber);
    Family9386.base.GetChangeOptionRestrictionResult.call(this);
}


Family9386.prototype.Validate = function() {
    /*if ((this.SelectedPackaging.key == "D" || this.SelectedPackaging.key == "E" || this.SelectedPackaging.key == "G") && (this.SelectedPackageSize.key == "E")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }
	if ((this.SelectedPackaging.key == "Y" || this.SelectedPackaging.key == "T" || this.SelectedPackaging.key == "X") && (this.SelectedPackageSize.key == "B")) {
        this.validationErrors.push(getErrorByErrorID("29"));
        return false;
    }*/
    return Family9386.base.Validate.call(this);
}


function Family9387() {
    FeaturePinWithSignallingType.call(this);
};
Family9387.prototype = new FeaturePinWithSignallingType(inheriting);
Family9387.base = FeaturePinWithSignallingType.prototype;
Family9387.prototype.Initialization = function() {
    var jsonData = getJSONObject("SiT9387");
    Family9387.base.Initialization.call(this, jsonData);
}
Family9387.prototype.ExclusionTable = function() {
    return Family9387.base.GetRestrictionResult.call(this);
    //return true;
}

Family9387.prototype.AvailableOptionsShow = function() {
    if (this.SelectedPackageSize.key != "") {

      if (this.SelectedPackageSize.key == "B") {
            showAllOptions("packaging");
            hideOptionName("packaging", "X");
            hideOptionName("packaging", "Y");
            hideOptionName("packaging", "T");
			if (this.SelectedPackaging.key == "D") {
                this.SelectedPackaging.key = "T";

            } else if (this.SelectedPackaging.key == "E") {
                this.SelectedPackaging.key = "Y";
            } else if (this.SelectedPackaging.key == "G") {
                this.SelectedPackaging.key = "X";
            }		

        } else if (this.SelectedPackageSize.key == "E") {
			showAllOptions("packaging");
            hideOptionName("packaging", "G");
            hideOptionName("packaging", "E");
            hideOptionName("packaging", "D");
            if (this.SelectedPackaging.key == "T") {
                this.SelectedPackaging.key = "D";

            } else if (this.SelectedPackaging.key == "Y") {
                this.SelectedPackaging.key = "E";
            } else if (this.SelectedPackaging.key == "X") {
                this.SelectedPackaging.key = "G";
            }
        }
		
		if (this.SelectedSignallingType.key) {
			if (this.SelectedSignallingType.key == "4") {
				this.FrequencyHoles = {
					"500.000001": "725.000000"
				};
			} else {
				this.FrequencyHoles = {};
			}
    }
    }
}

Family9387.prototype.Parse = function(partNumber) {
    Family9387.base.Parse.call(this, partNumber);
    if (this.SelectedSignallingType.key) {
        if (this.SelectedSignallingType.key == "4") {
            this.FrequencyHoles = {
                "500.000001": "725.000000"
            };
        } else {
            this.FrequencyHoles = {};
        }
    }
}


Family9387.prototype.Validate = function() {
    
    return Family9387.base.Validate.call(this);
}

function FamilyCS() {
    BaseCSClass.call(this);
}
FamilyCS.prototype = new BaseCSClass(inheriting);
FamilyCS.base = BaseCSClass.prototype;
FamilyCS.prototype.Initialization = function() {
    this.validationErrors = new Array();
    this.validationErrors.push(getErrorByErrorID("9"));
};

function Family9103() {
    BaseCSSiTClass.call(this);
}
Family9103.prototype = new BaseCSSiTClass(inheriting);
Family9103.base = BaseCSSiTClass.prototype;
Family9103.prototype.Initialization = function() {
    Family9103.base.Initialization.call(this);
    this.RevisionLetter.push(createArrayObject("A", "Revision"));
    this.TemperatureRange.push(createArrayObject("C", "-20 to 70"), createArrayObject("I", "-40 to 85"));
    this.validationErrors = new Array();
    this.validationErrors.push(getErrorByErrorID("9"));
};

function Family9104() {
    BaseCSSiTClass.call(this);
}
Family9104.prototype = new BaseCSSiTClass(inheriting);
Family9104.base = BaseCSSiTClass.prototype;
Family9104.prototype.Initialization = function() {
    Family9104.base.Initialization.call(this);
    this.RevisionLetter.push(createArrayObject("A", "Revision"));
    this.TemperatureRange.push(createArrayObject("C", "-20 to 70"), createArrayObject("I", "-40 to 85"));
    this.validationErrors = new Array();
    this.validationErrors.push(getErrorByErrorID("9"));
};

function Family9105() {
    BaseCSSiTClass.call(this);
}
Family9105.prototype = new BaseCSSiTClass(inheriting);
Family9105.base = BaseCSSiTClass.prototype;
Family9105.prototype.Initialization = function() {
    Family9105.base.Initialization.call(this);
    this.RevisionLetter.push(createArrayObject("A", "Revision"));
    this.TemperatureRange.push(createArrayObject("C", "-20 to 70"), createArrayObject("I", "-40 to 85"));
    this.validationErrors = new Array();
    this.validationErrors.push(getErrorByErrorID("9"));
};

/////////Takes partnumber in format '9002AC-........'
function CreateGeneratorEntity(partnumber, mode) {
    partnumber = PartnumberAdapter(partnumber);

    if (partnumber.substring(0, 2).toUpperCase() == "CS")
        var family = partnumber.substring(0, 2);
    else {
        var family = partnumber.substring(0, 4);
        var XTValue = partnumber.substring(4, 6);
        var temptemperatureRange = partnumber.substring(5, 6);
        var packageSize = partnumber.substring(7, 8);
    }
    var obj;
    if ((family == "8003") && ((packageSize == "6") || (XTValue == "XT"))) {
        obj = new Family8003XT();
    } else if ((temptemperatureRange == "A") && (family == "8002")) {
        obj = new Family8002AA();
    } else {
        try {
            obj = eval("new Family" + family + "()");
        } catch (ex) {

        }
    }
    try {
        if (partnumber.length < obj.MinPartLenght) {
            obj.validationErrors = new Array();
            obj.validationErrors.push(getErrorByErrorID("32"));
        }
    } catch (ex) {}
    if (obj != undefined) {
        obj.Mode = mode;
        /*if (obj.Mode.includes(mode))
        	obj.Mode = mode;
        else
        	if (obj.Mode.length == 1 && obj.Mode.includes("Decoder"))
        		obj.Mode = "Decoder"
        	else
        		obj.validationErrors.push("Please use Part Number Decoder for this part number.");*/
    }
    return obj;
}