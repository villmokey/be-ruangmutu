<?php


namespace App\Service\Dashboard;

use App\Models\Table\IndicatorProfileTable;
use App\Models\Table\IndicatorTable;
use App\Models\Table\PerformanceTable;
use App\Models\Table\HealthServiceTable;
use App\Models\Table\SatisfactionTable;
use App\Models\Table\DocumentTable;

use App\Service\AppService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardService extends AppService
{
    protected $indicator;
    protected $healthServiceTable;
    protected $satisfactionTable;
    protected $documentTable;

    public function __construct(
        IndicatorTable $indicator,
        IndicatorProfileTable $model,
        HealthServiceTable $healthServiceTable,
        SatisfactionTable $satisfactionTable,
        DocumentTable $documentTable
    )
    {
        $this->indicator            = $indicator;
        $this->healthServiceTable   = $healthServiceTable;
        $this->satisfactionTable    = $satisfactionTable;
        $this->documentTable        = $documentTable;
        parent::__construct($model);
    }

    public function dashboard($input)
    {
        $unreached = 0;
        $year = $input->get('year', null);
        $program_id = $input->get('program_id', null);
        $type = $input->get('type', 'quality');
        $search = $input->get('search', null);
        $perPage = $input->get('per_page', 1);
        $page = $input->get('page', 1);

        $total = $this->indicator->newQuery()->where('type', $type)->count();
        $queryUnreached = $this->model->newQuery()->with(['indicator'])->where('type', $type)->get();

        foreach ($queryUnreached as $item) {
            if(count($item->indicator) > 0) {
                foreach($item->indicator as $indicate) {
                    if($item->achievement_target >= $indicate->month_target) {
                        $unreached += 1;
                    }
                }
            }
        }


        $selected = $this->indicator->newQuery()
                    ->when($search, function ($query) use($search) {
                        return $query->where('title', 'LIKE', '%'. $search . '%');
                    })
                    ->when($program_id, function ($query) use($program_id) {
                        return $query->where('program_id', $program_id);
                    })
                    ->when($type, function ($query) use($type) {
                        return $query->where('type', $type);
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
                                ->when($search, function ($query) use($search) {
                                    return $query->where('title', 'LIKE', '%'. $search . '%');
                                })
                                ->when($program_id, function ($query) use($program_id) {
                                    return $query->whereIn('program_id', explode(',', $program_id));
                                })
                                ->when($type, function ($query) use($type) {
                                    return $query->whereIn('type', explode(',', $type));
                                })
                                ->when($year, function ($query) use($year) {
                                    return $query->where('created_at', 'LIKE', $year . '%');
                                })
                                ->orderBy('created_at', 'desc')
                                ->paginate((int)$perPage, ['*'], null, $page);

        $enc = json_decode($indicator->toJson(), true);
        
        foreach($enc['data'] as $key => $value) {
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
            ->where('title', $value['id'])
            ->where('sub_program_id', $value['sub_program_id'])
            // ->where('type', $value->type)
            ->join('programs', 'programs.id', '=', 'indicators.sub_program_id')
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
        $document_type = $input->get('document_type', null);
        $variant = $input->get('variant', 'indicator');
        $type = $input->get('type', null);
        $search = $input->get('search', null);
        $page = $input->get('page', 1);
        $perPage = $input->get('per_page', 15);

        $results = [];
        if($variant === 'indicator') {
            if(!$document_type || $document_type === 'indicator') {
                $results = $this->indicator->newQuery()
                                        ->with(['profileIndicator','program', 'subProgram'])                        
                                        ->when($search, function ($query) use($search) {
                                            $query->whereHas('profileIndicator', function ($q) use ($search) {
                                                $q->where('title', 'like', '%'.$search.'%');
                                            });
                                        })->when($year, function ($query) use($year) {
                                            return $query->where('created_at', 'LIKE', $year . '%');
                                        })->when($program_id, function ($query) use($program_id) {
                                            return $query->whereIn('program_id', explode(',', $program_id));
                                        })
                                        ->when($type, function ($query) use($type) {
                                            return $query->where('type', $type);
                                        })
                                        ->when($year, function ($query) use($year) {
                                            return $query->where('created_at', 'LIKE', $year . '%');
                                        })->paginate((int)$perPage, ['*'], null, $page);
                
            }
        }else {
            if (!$document_type || $document_type === 'indicator_profile') {
                $results = $this->model->newQuery()
                ->with(['program', 'subProgram'])                        
                                        ->when($program_id, function ($query) use($program_id) {
                                            return $query->whereIn('program_id', explode(',', $program_id));
                                        })
                                        ->when($search, function ($query) use($search) {
                                            return $query->where('title', 'LIKE', $search . '%');
                                        })
                                        ->when($year, function ($query) use($year) {
                                            return $query->where('created_at', 'LIKE', $year . '%');
                                        })
                                        ->when($type, function ($query) use($type) {
                                            return $query->where('type', $type);
                                        })->paginate((int)$perPage, ['*'], null, $page);
            
            }
        }
        
        return $this->sendSuccess($results);
    }

    public function recapIndicator($year = null)
    {
        $year = $year ?? date('Y');
        $queries = \DB::select("SELECT 
                        i.month,
                        CAST(CASE
                                WHEN month = 'januari' THEN 1
                                WHEN month = 'februari' THEN 2
                                WHEN month = 'maret' THEN 3
                                WHEN month = 'april' THEN 4
                                WHEN month = 'mei' THEN 5
                                WHEN month = 'juni' THEN 6
                                WHEN month = 'juli' THEN 7
                                WHEN month = 'agustus' THEN 8
                                WHEN month = 'september' THEN 9
                                WHEN month = 'oktober' THEN 10
                                WHEN month = 'november' THEN 11
                                WHEN month = 'desember' THEN 12
                                ELSE month
                            END as SIGNED) as month_number,
                        i.month_target
                        from indicators i 
                        where type = 'quality'
                        and YEAR(created_at) = :year
                        order by month_number ASC
                        ", ['year' => $year]);

        $calculates = [];
        $results = [];

        if($queries && count($queries) > 0) {
            foreach ($queries as $key => $value) {
                if($key > 0) {
                    if($queries[$key-1]->month === $value->month) {
                        $calculates[count($calculates) - 1]['indicator_total']   += 1;
                        $calculates[count($calculates) - 1]['total']            += $value->month_target;
                    }else {
                        $calculates[] = [
                            'month'             => $value->month,
                            'month_number'      => $value->month_number,
                            'month_target'      => $value->month_target,
                            'total'             => $value->month_target,
                            'indicator_total'   => 1,
                        ];
                    }
                } else {
                    $calculates[] = [
                        'month'             => $value->month,
                        'month_number'      => $value->month_number,
                        'month_target'      => $value->month_target,
                        'total'             => $value->month_target,
                        'indicator_total'   => 1,
                    ];
                }
            }
        
            if($calculates && count($calculates) > 0) {
                foreach ($calculates as $value) {
                    $results[] = [
                        'month'             => $value['month'],
                        'month_number'      => $value['month_number'],
                        'indicator_total'   => $value['indicator_total'],
                        'average'           => $value['total'] / $value['indicator_total'],
                    ];
                }
            }
        }

        return $this->sendSuccess(['year' => $year, 'results' => $results]);
    }

    public function recapPerformance($year = null) 
    {
        $year = $year ?? date('Y');
        $queries = \DB::select("SELECT 
                        i.month,
                        CAST(CASE
                                WHEN month = 'januari' THEN 1
                                WHEN month = 'februari' THEN 2
                                WHEN month = 'maret' THEN 3
                                WHEN month = 'april' THEN 4
                                WHEN month = 'mei' THEN 5
                                WHEN month = 'juni' THEN 6
                                WHEN month = 'juli' THEN 7
                                WHEN month = 'agustus' THEN 8
                                WHEN month = 'september' THEN 9
                                WHEN month = 'oktober' THEN 10
                                WHEN month = 'november' THEN 11
                                WHEN month = 'desember' THEN 12
                                ELSE month
                            END as SIGNED) as month_number,
                        i.month_target
                        from indicators i 
                        where type = 'performance'
                        and YEAR(created_at) = :year
                        order by month_number ASC
                        ", ['year' => $year]);

        $calculates = [];
        $results = [];
        
        if($queries && count($queries) > 0) {
            foreach ($queries as $key => $value) {
                if($key > 0) {
                    if($queries[$key-1]->month === $value->month) {
                        $calculates[count($calculates) - 1]['indicator_total']   += 1;
                        $calculates[count($calculates) - 1]['total']             += $value->month_target;
                    }else {
                        $calculates[] = [
                            'month'             => $value->month,
                            'month_number'      => $value->month_number,
                            'month_target'      => $value->month_target,
                            'total'             => $value->month_target,
                            'indicator_total'   => 1,
                        ];
                    }
                } else {
                    $calculates[] = [
                        'month'             => $value->month,
                        'month_number'      => $value->month_number,
                        'month_target'      => $value->month_target,
                        'total'             => $value->month_target,
                        'indicator_total'   => 1,
                    ];
                }
            }

            if($calculates && count($calculates) > 0) {
                foreach ($calculates as $value) {
                    $results[] = [
                        'month'             => $value['month'],
                        'month_number'      => $value['month_number'],
                        'indicator_total'   => $value['indicator_total'],
                        'average'           => ceil($value['total'] / $value['indicator_total']),
                    ];
                }
            }
        }

        return $this->sendSuccess(['year' => $year, 'results' => $results]);
    }

    public function recapComplaint($year = null) 
    {
        $year = $year ?? date('Y');
        
        $queries = \DB::select("SELECT
                                    extract(month from complaint_date) as month,
                                    case when status = 'DONE' then count(status) else 0 end as done,
                                    case when status = 'PENDING' then count(status) else 0 end as pending
                                from customer_complaints cc 
                                where YEAR(complaint_date) = :year
                                group by status, month
                                order by month asc", ['year' => $year]);
        
        $calculates = [];
        $results = [];

        if($queries && count($queries) > 0) {
            foreach ($queries as $key => $value) {
                if($key > 0) {
                    if($queries[$key-1]->month === $value->month) {
                        $calculates[count($calculates) - 1]['done']              += $value->done;
                        $calculates[count($calculates) - 1]['pending']           += $value->pending;
                    }else {
                        array_push($calculates, [
                            'month'             => $value->month,
                            'done'              => $value->done,
                            'pending'           => $value->pending,
                        ]);
                    }
                } else {
                    array_push($calculates, [
                        'month'             => $value->month,
                        'done'              => $value->done,
                        'pending'           => $value->pending,
                    ]);
                }
            }
        
            if($calculates && count($calculates) > 0) {
                foreach ($calculates as $value) {
                    $results[] = [
                        'month'             => $value['month'],
                        'done'              => $value['done'],
                        'pending'           => $value['pending'],
                    ];
                }
            }
        }

        return $this->sendSuccess(['year' => $year, 'results' => $results]);
    }

    public function recapSatisfaction($year = null) 
    {
        $year = $year ?? date('Y');

        $services = $this->healthServiceTable->get();
        $results = [];
        foreach ($services as $service) {
            $findMe = $this->satisfactionTable->newQuery()
                                                ->with(['satisfactionDetail'])
                                                ->where('health_service_id', $service->id)
                                                ->whereYear('created_at', $year)
                                                ->orderBy(\DB::raw('CAST(month as SIGNED)'), 'ASC')
                                                ->get();
            if(count($findMe)) {
                $results[] = [
                    'health_service'        => $service->name,
                    'year'                  => $year,
                    'result'                => $findMe
                ];
            }
        }

        return $this->sendSuccess($results);
    }

    public function eventInfo($year = null)
    {
        try {
            if($year === date('Y')) {
                $upcoming = \DB::select("SELECT * FROM events e WHERE start_date > CURDATE() limit 5");
                $realized = \DB::select("SELECT * FROM events e WHERE start_date < CURDATE() AND is_realized = true limit 5");
            }else {
                $upcoming = \DB::select("SELECT * FROM events e WHERE YEAR(start_date) = $year limit 5");
                $realized = \DB::select("SELECT * FROM events e WHERE YEAR(start_date) = $year AND is_realized = true limit 5");
            }
    
            $result = [
                'realized'      => $realized,
                'upcoming'      => $upcoming
            ];
    
            return $this->sendSuccess($result);
        } catch (\Exception $e) {
            return $this->sendError([], 'Belum ada data');
        }
    }

    public function documentInfo($year = null)
    {
        try {
            $total = $this->documentTable->newQuery()->when($year, function($q) use ($year) {
                $q->whereYear('created_at', $year);
            })->count();
            $thisYear = $this->documentTable->newQuery()->whereYear('created_at', date('Y'))->count();
    
            $result = [
                'total'         => $total,
                'this_year'     => $thisYear
            ];
    
            return $this->sendSuccess($result);
        } catch (\Exception $e) {
            return $this->sendError([], 'Belum ada data');
        }
    }
}
