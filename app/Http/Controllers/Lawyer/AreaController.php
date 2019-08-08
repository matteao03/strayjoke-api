<?php

namespace App\Http\Controllers\Lawyer;

use Illuminate\Http\Request;
use Cblink\Region\Region;

class AreaController extends Controller
{
    protected $level2 = [1, 18, 790, 2236, 3609, 3628];
    public function getAllProvinces()
    {
        $region = new Region();
        return response()->json($region->allProvinces()); // 全部省份    
    }

    public function getCitiesByProvinceId(Request $request)
    {
        $id = $request->query('provinceId');
        $region = new Region();
        return response()->json([
            'nodes' => $region->nest($id),
            'leaf' => in_array($id, $this->level2)
        ]);
    }

    public function getAreasByCityId(Request $request)
    {
        $id = $request->query('cityId');
        $region = new Region();
        return response()->json($region->nest($id));
    }
}
