<?php


namespace App\Service\Indicator;


use App\Models\Table\IndicatorTable;
use App\Models\Table\IndicatorSignatureTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class IndicatorService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;
    protected $signatureTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        IndicatorSignatureTable $signatureTable,
        IndicatorTable $model
    )
    {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->signatureTable       =   $signatureTable;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
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
                                ->with('program')
                                ->with('subProgram')
                                ->with('qualityGoal')
                                ->with('document')
                                ->with('signature')
                                ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $indicator = $this->model->newQuery()->create([
                'program_id'                =>  $data['program_id'],
                'sub_program_id'            =>  $data['sub_program_id'],
                'month'                     =>  $data['month'],
                'quality_goal_id'           =>  $data['quality_goal_id'],
                'human'                     =>  $data['human'],
                'tools'                     =>  $data['tools'],
                'method'                    =>  $data['method'],
                'policy'                    =>  $data['policy'],
                'environment'               =>  $data['environment'],
                'next_plan'                 =>  $data['next_plan'],
            ]);

            foreach($data['signature'] as $signatures) {
                $this->signatureTable->newQuery()->create([
                    'indicator_id'                 => $indicator->id,
                    'user_id'                      => $signatures['user_id'],
                    'level'                        => $signatures['level'],
                ]);
            }

            if (!empty($data['document_id'])) {
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($indicator),
                    'fileable_id'   => $indicator->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicator);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $indicator   =   $this->model->newQuery()->find($id);
        $oldImage                  =   $indicator->document()->first();

        \DB::beginTransaction();

        try {
            $indicator->program_id        =   $data['program_id'];
            $indicator->sub_program_id    =   $data['sub_program_id'];
            $indicator->month             =   $data['month'];
            $indicator->quality_goal_id   =   $data['quality_goal_id'];
            $indicator->human             =   $data['human'];
            $indicator->tools             =   $data['tools'];
            $indicator->method            =   $data['method'];
            $indicator->policy            =   $data['policy'];
            $indicator->environment       =   $data['environment'];
            $indicator->next_plan         =   $data['next_plan'];
            $indicator->save();

            if (!empty($data['document_id'])) {
                if (!empty($oldImage)) {
                    $oldImage->delete();
                }
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($indicator),
                    'fileable_id'   => $indicator->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicator);
        } catch (\Exception $exception) {

            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

}
