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

    public function dashboard($input)
    {
        $unreached = 0;
        $year = $input->get('year', null);
        $program_id = $input->get('program_id', null);

        $total = $this->indicator->newQuery()->count();
        $queryUnreached = $this->model->newQuery()->with(['indicator'])->get();

        foreach ($queryUnreached as $item) {
            if(count($item->indicator) > 0) {
                foreach($item->indicator as $indicate) {
                    if($item->achievement_target >= $indicate->month_target) {
                        $unreached += 1;
                    }
                }
            }
        }


        $selected = $this->indicator->newQuery()->when($program_id, function ($query) use($program_id) {
                        return $query->where('program_id', $program_id);
                    })
                    ->when($year, function ($query) use($year) {
                        return $query->where('created_at', 'LIKE', $year . '%');
                    })->count();
                    
        $indicator = $this->model->newQuery()
                                ->select(
                                    'id',
                                    'title',
                                    'achievement_target',
                                    'created_at',
                                    'sub_program_id',
                                    DB::RAW('EXTRACT(YEAR FROM created_at) as year'),
                                )
                                ->with('subProgram')
                                ->has('indicator')
                                ->when($program_id, function ($query) use($program_id) {
                                    return $query->whereIn('program_id', explode(',', $program_id));
                                })
                                ->when($year, function ($query) use($year) {
                                    return $query->where('created_at', 'LIKE', $year . '%');
                                })
                                ->get();

        foreach($indicator as $key => $value) {
            $indicator[$key]['month'] = $this->indicator->newQuery()
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

        $result['total_all'] = $total ?? 0;
        $result['total_selected'] = $selected ?? 0;
        $result['total_unreached'] = $unreached;
        $result['result'] = $indicator;

        return $this->sendSuccess($result);
    }

    public function indicatorDataList($input) {
        $year = $input->get('year', null);
        $program_id = $input->get('program_id', null);
        $type = $input->get('type', null);

        $results = [];
        if(!$type || $type === 'indicator') {
            $indicators = $this->indicator->newQuery()
                                    ->with(['profileIndicator','program', 'subProgram'])                        
                                    ->when($program_id, function ($query) use($program_id) {
                                        return $query->whereIn('program_id', explode(',', $program_id));
                                    })
                                    ->when($year, function ($query) use($year) {
                                        return $query->where('created_at', 'LIKE', $year . '%');
                                    })->get();

            foreach ($indicators as $ind) {
                $results[] = [
                    'id' => $ind->title,
                    'indicator_id' => $ind->id,
                    'title' => $ind->profileIndicator->title,
                    'is_profile_indicator' => false,
                    'month' => $ind->month,
                    'created_at' => $ind->created_at
                ];
            }
            
        }
        
        if (!$type || $type === 'indicator_profile') {
            $profiles = $this->model->newQuery()
            ->with(['program', 'subProgram'])                        
                                    ->when($program_id, function ($query) use($program_id) {
                                        return $query->whereIn('program_id', explode(',', $program_id));
                                    })
                                    ->when($year, function ($query) use($year) {
                                        return $query->where('created_at', 'LIKE', $year . '%');
                                    })->get();
            
            foreach ($profiles as $ind) {
                $results[] = [
                    'id' => $ind->id,
                    'indicator_id' => null,
                    'title' => $ind->title,
                    'is_profile_indicator' => true,
                    'month' => null,
                    'created_at' => $ind->created_at
                ];
            }
        }

        return $this->sendSuccess($results);
    }


}
