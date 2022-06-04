<?php


namespace App\Service\QualityIndicator;


use App\Models\Table\QualityIndicatorTable;
use App\Models\Table\QualityIndicatorSignatureTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class QualityIndicatorService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;
    protected $signatureTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        QualityIndicatorSignatureTable $signatureTable,
        QualityIndicatorTable $model
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
                                ->with('program')
                                ->with('subProgram')
                                ->with('qualityGoal')
                                ->with('document')
                                ->with('signature')
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
                                ->with('program')
                                ->with('subProgram')
                                ->with('qualityGoal')
                                ->with('document')
                                ->with('signature')
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

            $qualityIndicator = $this->model->newQuery()->create([
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
                    'indicator_id'                 => $qualityIndicator->id,
                    'user_id'                      => $signatures['user_id'],
                    'level'                        => $signatures['level'],
                ]);
            }

            if (!empty($data['document_id'])) {
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($qualityIndicator),
                    'fileable_id'   => $qualityIndicator->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($qualityIndicator);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $qualityIndicator   =   $this->model->newQuery()->find($id);
        $oldImage                  =   $qualityIndicator->document()->first();

        \DB::beginTransaction();

        try {
            $qualityIndicator->program_id        =   $data['program_id'];
            $qualityIndicator->sub_program_id    =   $data['sub_program_id'];
            $qualityIndicator->month             =   $data['month'];
            $qualityIndicator->quality_goal_id   =   $data['quality_goal_id'];
            $qualityIndicator->human             =   $data['human'];
            $qualityIndicator->tools             =   $data['tools'];
            $qualityIndicator->method            =   $data['method'];
            $qualityIndicator->policy            =   $data['policy'];
            $qualityIndicator->environment       =   $data['environment'];
            $qualityIndicator->next_plan         =   $data['next_plan'];
            $qualityIndicator->save();

            if (!empty($data['document_id'])) {
                if (!empty($oldImage)) {
                    $oldImage->delete();
                }
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($qualityIndicator),
                    'fileable_id'   => $qualityIndicator->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($qualityIndicator);
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
