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

    public function getAll($search = null, $year = null, $subProgram = null, $type = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($subProgram, function ($query, $subProgram) {
                                    return $query->where('sub_program_id', $subProgram);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $subProgram = null, $perPage = 15, $page = null, $type = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($subProgram, function ($query, $subProgram) {
                                    return $query->where('sub_program_id', $subProgram);
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
                                ->with('profileIndicator')
                                ->with('firstPic')
                                ->with('secondPic')
                                ->with('assignBy')
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
                'title'                     => $data['title'],
                'program_id'                =>  $data['program_id'],
                'sub_program_id'            =>  $data['sub_program_id'],
                'month_target'              =>  $data['month_target'],
                'month'                     =>  $data['month'],
                'quality_goal'              =>  $data['quality_goal'],
                'human'                     =>  $data['human'],
                'tools'                     =>  $data['tools'],
                'method'                    =>  $data['method'],
                'policy'                    =>  $data['policy'],
                'environment'               =>  $data['environment'],
                'next_plan'                 =>  $data['next_plan'],
                'first_pic_id'              =>  $data['first_pic_id'],
                'second_pic_id'             =>  $data['second_pic_id'] ?? null,
                'created_by'                =>  $data['created_by'],
                'assign_by'                 =>  $data['assign_by'],
                'type'                      =>  $data['type'],
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
            $indicator->month_target      =   $data['month_target'];
            $indicator->month             =   $data['month'];
            $indicator->quality_goal_id   =   $data['quality_goal_id'];
            $indicator->human             =   $data['human'];
            $indicator->tools             =   $data['tools'];
            $indicator->method            =   $data['method'];
            $indicator->policy            =   $data['policy'];
            $indicator->environment       =   $data['environment'];
            $indicator->next_plan         =   $data['next_plan'];
            $indicator->first_pic_id      =   $data['first_pic_id'];
            $indicator->second_pic_id     =   $data['second_pic_id'] ?? null;
            $indicator->created_by        =   $data['created_by'];
            $indicator->assign_by         =   $data['assign_by'];
            $indicator->save();

            if(!empty($data['signature'])) {
                $indicator->signature()->delete();
                foreach($data['signature'] as $signatures) {
                    $this->signatureTable->newQuery()->create([
                        'indicator_id'                 => $indicator->id,
                        'user_id'                      => $signatures['user_id'],
                        'level'                        => $signatures['level'],
                    ]);
                }
            }

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

    public function getSignature($id, $input)
    {
        $program_id = $input->get('program_id', null);
        $year = $input->get('year', null);
        $status = $input->get('status', null);
        $type = $input->get('type', null);

        $result = $this->model->newQuery()
                                ->whereHas('signature', function($query) use ($id) {
                                    $query->where('user_id', $id);
                                })
                                ->with('profileIndicator')
                                ->with('subProgram')
                                ->with('signature')
                                ->when($program_id, function ($query, $program_id) {
                                    return $query->whereIn('program_id', explode(',', $program_id));
                                })
                                ->when($status, function ($query, $status) {
                                    return $query->where('status', $status === 'signed' ? '>' : '=', 0);
                                })
                                ->when($type, function ($query, $type) {
                                    return $query->where('type', $type);
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function changeStatus($id, $data)
    {
        $indicator   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();
        try {
            if (isset($data['status']) == 'rejected') {
                $indicator->update([
                    'status' => -1,
                ]);
            } else {
                $signature = $indicator->signature()->where('user_id', $data['user_id'])->first();
                $signature->update([
                    'signed' => 1,
                    'signed_at' => date('Y-m-d H:i:s'),
                ]);
                if ($indicator->signature()->where('signed', 0)->count() == 0) {
                    $indicator->update([
                        'status' => 3,
                    ]);
                }
            }
            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicator);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }

        return $this->sendSuccess($indicator);
    }
}
