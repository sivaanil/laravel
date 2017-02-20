<?php
namespace Unified\Http\Helpers;

/**
 * Provides a passthrough to the Panel model for Unified UI.
 *
 * @author Golnaz.Rouhi
 * @mod Created Unified\Models\Panel, converted DB calls. - Anthony.Levensalor (3/15/16)
 */
class PanelsHelper {

    //put your code here
    public static function getAllPanels() {
        $panels = Panel::select(['name', 'type'])
            ->get();

       $panel = array();
        for ($i = 0; $i < count($panels); $i ++) {
            $panel[$i]['name'] = $panels[$i]->name;
            $panel[$i]['type'] = $panels[$i]->type;
        }

        return $panel;
    }

    public static function getPanelsByDeviceType($type) {
        return Panel::getByDeviceType($type);
    }

}
