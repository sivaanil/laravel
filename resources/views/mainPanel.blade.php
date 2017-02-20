<div id="main" ng-app="panel">
    <div ng-controller="canvasCtrl" ng-init="getAllPanels()">

        <div class="panelMenuContainer">
            <manu-button-crtl
                    control-model="controls"
                    control-menu-action="menuActionControl(panel)">
            </manu-button-crtl>

        </div>
        <div>
            <panel-content
                    panel-model="openPanels">
            </panel-content>
        </div>

        <div>

        </div>
    </div>
</div>

