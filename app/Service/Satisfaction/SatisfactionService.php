<?php


namespace App\Service\Satisfaction;


use App\Models\Table\SatisfactionTable;
use App\Models\Table\CustomerComplaintTable;
use App\Models\Table\HealthServiceTable;
use App\Models\Table\SatisfactionDetailTable;
use App\Models\Table\IndicatorSignatureTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class SatisfactionService extends AppService implements AppServiceInterface
{
    protected $detailModel;
    protected $healthServiceTable;
    protected $complaintModel;

    public function __construct(
        SatisfactionTable $model,
        SatisfactionDetailTable $detailModel,
        HealthServiceTable $healthServiceTable,
        CustomerComplaintTable $complaintModel,
    )
    {
        $this->detailModel = $detailModel;
        $this->healthServiceTable = $healthServiceTable;
        $this->complaintModel = $complaintModel;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('health_service_id','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null, $type = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('health_service_id','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
                                ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        
        try {
            $isExists = $this->model->newQuery()
                                    ->where('health_service_id', $data['health_service_id'])
                                    ->where('month', $data['month'])
                                    ->whereYear('created_at', date('Y'))
                                    ->first();

            if(!$isExists) {
                $satisfaction = $this->model->newQuery()->create([
                    'health_service_id'         =>  $data['health_service_id'],
                    'month'                     =>  $data['month'],
                    'average'                   =>  $data['average'],
                    'created_id'                =>  \Auth::user()->id
                ]);
    
                if (!empty($data['units'])) {
                    foreach ($data['units'] as $v) {
                        $this->detailModel->create([
                            'satisfaction_id'   => $satisfaction->id,
                            'service_name'      => $v['service_name'],
                            'value'             => $v['value'],
                            'total'             => $v['total'],
                            'percentage'        => $v['percent'],
                        ]);
                    }
                }

                \DB::commit(); // commit the changes
                return $this->sendSuccess($satisfaction);
            }
            
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, 'Bulan yang anda masukan telah inputkan sebelumnya', 400);

        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        return $this->sendError(null, 'Forbidden', 403);
    }

    public function delete($id)
    {
        return $this->sendError(null, 'Forbidden', 403);
    }

    public function chart()
    {
        $services = $this->healthServiceTable->get();
        $result = [];
        foreach ($services as $service) {
            $findMe = $this->model->newQuery()->with(['satisfactionDetail'])->where('health_service_id', $service->id)->whereYear('created_at', date('Y'))->orderBy('month', 'asc')->get();
            if(count($findMe)) {
                $result[] = [
                    'health_service'        => $service->name,
                    'year'                  => date('Y'),
                    'result'                => $findMe
                ];
            }
        }

        return $this->sendSuccess($result);
    }

    public function info() 
    {
        $satisfaction = $this->complaintModel->newQuery()->count();
        $complaint = $this->complaintModel->newQuery()->where('status', 'DONE')->count();
        $result = [
            'complaint_in'       => $satisfaction,
            'complaint_done'     => $complaint
        ];

        return $this->sendSuccess($result);
    }
}
