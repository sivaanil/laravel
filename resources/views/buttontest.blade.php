<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{!! asset('css/styles/jqx.base.css') !!}"/>
        <script type="text/javascript" src="{!! asset('js/vendor/jquery.js') !!}"></script>
        <script src="{!! asset('js/vendor/jqwidgets/jqx-all.js') !!}"></script>
        <script src="{!! asset('/js/lib/c2adapter.js') !!}"></script>
        <script src="{!! asset('/js/lib/c2button.js') !!}"></script>
        <script src="{!! asset('/js/lib/c2input.js') !!}"></script>
        <script src="{!! asset('/js/lib/c2tree.js') !!}"></script>
        <script src="{!! asset('/js/lib/c2devicetree.js') !!}"></script>
        <script src="{!! asset('/js/lib/c2radio.js') !!}"></script>
        <script>
            $(function () {

                /**
                 * Button examples using c2button.js
                 */
                // Regular push button example (jqxButton)
                var pushButtonOptions = {
                    adapterType: "jqx",
                    buttonType: 'button',
                    callback: function() {
                        alert("Push button clicked");
                    }
                };
                var pushButton = $('#pushButton').c2button(pushButtonOptions);
                pushButton.prop('width', '500px');
                // jqxLinkButton example
                var linkButtonOptions = {
                    buttonType: 'link',
                    callback: function() {
                        alert("Link button clicked");
                    }
                };
                var linkButton = $('#linkButton').c2button(linkButtonOptions);
                // jqxToggleButton example
                var toggleButtonOptions = {
                    buttonType: 'toggle',
                    toggled: false,
                    callback: function() {
                        var toggled = $(this).jqxToggleButton('toggled');
                        if (toggled) {
                            $(this)[0].value = "On";
                        } else {
                            $(this)[0].value = "Off";
                        }
                    }
                };
                var toggleButton = $('#toggleButton').c2button(toggleButtonOptions);

                // jqxSwitchButton Example
                var switchButtonOptions = {
                    buttonType: 'switch',
                    checked: true,
                    callback: function(event) {
                        var checked = event.args.check;
                        alert(`Checked = ${checked}`);
                    }
                };
                var switchButton = $('#switchButton').c2button(switchButtonOptions);


                // jqxButtonGroup example
                var buttonGroupOptions = {
                    buttonType: 'group',
                    mode: 'radio',
                };
                var buttonGroup = $('#buttonGroup').c2button(buttonGroupOptions);


                /**
                 * Input examples using c2input.js
                 */

                var standardOptions = {
                    adapterType: 'jqx',
                    inputType: 'input',
                    width: '100px',
                    placeHolder: 'Standard Input'
                };

                // Standard input
                $('#standardInput').c2input(standardOptions);

                // standard input with source array
                var source = ["Anthony", "Igor", "Craig", "Franz", "Golnaz", "Lorenzo", "Susannah", "Ira"];
                standardOptions.source = source;
                $('#stdInputWithSource').c2input(standardOptions);

                // DateTimeInput
                var dateTimeOptions = {
                    inputType: 'datetime'
                };
                $('#dateTimeInput').c2input(dateTimeOptions);

                // FormattedInput
                // Allows the input of decimal, hexidecimal, octal, etc numbers
                var formattedOptions = {
                    radix: 'hexadecimal',
                    inputType: 'format',
                    spinButtons: true,
                    dropDown: true,
                    height: 25,
                    width: 250
                };
                $('#formattedInput').c2input(formattedOptions);

                // MaskedInput (phone number)
                var maskedOptions = {
                    inputType: 'mask',
                    mask: '(###) ###-####',
                    width: 200
                };

                $('#phoneMaskedInput').c2input(maskedOptions);

                // Mask for IP address (IPV4)
                maskedOptions.mask = "###.###.###";
                $('#ipMaskedInput').c2input(maskedOptions);

                // NumberInput
                var numberOptions = {
                    inputType: 'number',
                    width: 200,
                    spinButtons: true,
                    symbol: '$',
                    textAlign: 'right',
                    decimalDigits: 2
                };
                $('#numberInput').c2input(numberOptions);

                // Password input
                var passwordOptions = {
                    inputType: 'password',
                };
                $('#passwordInput').c2input(passwordOptions);


                // Textarea input
                var areaOptions = {
                    inputType: 'textarea',
                    placeHolder: 'Enter something here...'
                };
                $('#areaInput').c2input(areaOptions);


/**
                var tree = null;
                // Initialize the tree using the same dataset as in the network tree
                $.ajax({
                    url: '/networkTree/loadFirstLevel',
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        // Initialize the tree here
                        var treeOptions = {
                            source: data.nodes,
                            checkboxes: true,
                            incrementalSearch: true,
                            height: '300px',
                            width: '300px'
                        };
                        tree = $('#tree').c2tree(treeOptions);
                    }
                });

                $('#treeSearch').c2input({
                    keypress: function() {
                        tree.filter($('#treeSearch').val());
                    }
                });
*/
                var deviceTree = null;
                var deviceTreeOptions = {
                    checkboxes: true,
                    incrementalSearch: true,
                    height: '300px',
                    width: '300px'
                };
                console.log("Device Tree Options: before instantiation");
                console.log(deviceTreeOptions);
                // Initialize the device tree (It should be providing its own source based on the network tree)
                deviceTree = $('#deviceTree').c2devicetree(deviceTreeOptions);


                // C2Radio Examples
                var radioOptions = {
                    inputType: 'radio',
                    groupName: 'sample_radios',
                    change: function(event) {
                        var checked = event.args.checked;
                        alert("Radio button was changed!");
                    }
                };
                $('#radio1').c2radio(radioOptions);
                $('#radio2').c2radio(radioOptions);

                // Checkbox examples
                var checkboxOptions = {
                    inputType: "checkbox",
                    change: function(event) {
                        var checked = event.args.checked;
                        alert("Checked: " + checked);
                    }
                };
                $('#checkbox').c2radio(checkboxOptions);

/**
                // Device types dropdown list
                var deviceTypeSource = {
                    datatype: 'json',
                    beforeLoadComplete: function(records) {
                        var data = [
                            { id: '0', name: 'All Devices' }
                        ];
                        for (var i = 0; i < records.length; ++i) {
                            var dt = records[i];
                            dt.name = dt.vendor + ' ' + dt.model;
                            data.push(dt);
                        }
                        return data;
                    },
                    url: '/deviceTypes',
                    async: true
                };
                var dataAdapter = new $.jqx.dataAdapter(deviceTypeSource);
                $('#deviceTypeSelect').jqxDropDownList({
                    source: dataAdapter, displayMember: "name", valueMember: "id"
                });
                $('#deviceTypeSelect').change(function(event) {
                    var dtid = event.args.item.value;
                    tree.filterByDeviceType(dtid);
                });
*/
            });

        </script>
    </head>
    <body>
        <h2>C<sup>2</sup> Custom Component Examples</h2>

        <h3>Device Tree Component</h3>
        <div id="deviceTree"></div>
        <hr />

<!--
        <h3>Tree Component</h3>
        <input type="text" id="treeSearch" />
        <div id="deviceTypeSelect"></div>
        <div id="tree"></div>
-->
        <h3>Buttons</h3>
        <input type="button" value="Push button" id="pushButton"></input>
        <hr />
        <a href="http://google.com" target="_blank" id="linkButton">Link Button</a>
        <hr />
        <input type="button" id="toggleButton" value="Off" />
        <hr />
        <div id="switchButton"></div>
        <hr />
        <div id="buttonGroup">
            <button id="button1">Button 1</button>
            <button id="button2">Button 2</button>
            <button id="button3">Button 3</button>
        </div>

        <h3>Inputs</h3>
            <h4>Standard Input</h4>
            <input type="text" id="standardInput" />
            <br clear="both" />
            <h4>Standard Input With Source Array</h4>
            <input type="text" id="stdInputWithSource" />
            <br clear="both" />
            <h4>DateTimeInput</h4>
            <div id="dateTimeInput"></div>
            <br clear="both" />
            <h4>FormattedInput</h4>
            <div id="formattedInput">
                <input type="text" />
                <div></div>
                <div></div>
            </div>
            <br clear="both" />
            <h4>Masked Input</h4>
            <div id="phoneMaskedInput"></div>
            <br clear="both" />
            <div id="ipMaskedInput"></div>
            <br clear="both" />
            <h4>Number Input</h4>
            <div id="numberInput"></div>
            <br clear="both" />
            <h4>Password Input</h4>
            <input type="password" id="passwordInput" />
            <br clear="both" />
            <h4>Text Area</h4>
            <div id="areaInput"></div>
        <h3>C2Radio</h3>
            <h4>Simple Radio Inputs</h4>
            <div id="radio1">Option 1</div>
            <div id="radio2">Option 2</div>
            <hr />
        <h3>C2Radio - Checkbox</h3>
            <h4>Simple Checkbox Input</h4>
            <div id="checkbox">Checkbox</div>
            <hr />
    </body>
</html>


