<?php

namespace AlexAzartsev\Heroicon\Http\Controllers;

use AlexAzartsev\Heroicon\Heroicon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IconController extends Controller
{
    public function show(Request $request)
    {
        $data = [];

        $values = explode(',', $request->sets);
        foreach ($values as $value) {
            $key = array_search($value, array_column(Heroicon::$supportedSets, 'value'));

            $set = Heroicon::$supportedSets[$key];

            $data[] = [
                'value' => $set['value'],
                'label' => $set['label'],
                'icons' => Heroicon::prepareIcons($set['value'], $set['path']),
            ];
        }

        return $data;
    }
}
