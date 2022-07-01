<?php


namespace App\Service\Dashboard;

use App\Models\Table\IndicatorProfileTable;
use App\Models\Table\IndicatorTable;

use App\Service\AppService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardService extends AppService
{
    protected $indicator;

    public function __construct(
        IndicatorTable $indicator,
        IndicatorProfileTable $model
    )
    {
        $this->indicator        = $indicator;
        parent::__construct($model);
    }

    public function dashboard()
    {
        $result = $this->model->newQuery()
                                ->select(
                                    'id',
                                    'title',
                                    'sub_program_id',
                                    DB::RAW('EXTRACT(YEAR FROM created_at) as year'),
                                )
                                ->with('subProgram')
                                ->has('indicator')
                                ->get();

        foreach($result as $key => $value) {
            $result[$key]['month'] = $this->indicator->newQuery()
            ->select(
                'indicators.id',
                'indicators.month',
                'indicators.month_target',
                'indicators.quality_goal',
                'indicators.human',
                'indicators.tools',
                'indicators.method',
                'indicators.policy',
                'indicators.environment',
                'indicators.next_plan',
                )
            ->where('title', $value->id)
            ->where('sub_program_id', $value->sub_program_id)
            ->join('sub_programs', 'sub_programs.id', '=', 'indicators.sub_program_id')
            ->get()
            ->toArray();
        }

        return $this->sendSuccess($result);
    }


}
