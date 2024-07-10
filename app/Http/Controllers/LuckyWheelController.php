<?php

namespace App\Http\Controllers;
// LuckyWheelController.php

use App\Awards;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Award;
use App\Models\AwardUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LuckyWheelController extends Controller {

    public function spin(Request $request)
    {
        $user = $request->user();

        if ($user->points < 15) {
            return response()->json(['error' => 'Insufficient points'], 422);
        }

        try {
            $awards = Awards::where('inventory', '>', 0)->lockForUpdate()->get();
            if ($awards->isEmpty())
                return response()->json(['error' => 'No awards available'], 503);

            $totalCoefficient = $awards->sum('coefficient');
            $randomValue = rand(1, $totalCoefficient);

            $selectedAward = null;
            foreach ($awards as $award) {
                if ($randomValue <= $award->coefficient) {
                    $selectedAward = $award;
                    break;
                }
                $randomValue -= $award->coefficient;
            }

            if ($selectedAward) {
                $user->points -= 15;
                $user->save();

                if ($selectedAward->title != 'empty') {
                    if ($selectedAward->inventory > 0) {
                        $selectedAward->inventory -= 1;
                        $selectedAward->save();
                    } else {
                        return response()->json(['error' => 'Award out of stock'], 409);
                    }
                }

                DB::table("award_user")->insert([
                    'user_id' => $user->id,
                    'award_id' => $selectedAward->id,
                    'time' => Carbon::now(),
                ]);

                return response()->json(['title' => $selectedAward->title]);
            }


            return response()->json(['error' => 'No award selected'], 503);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
