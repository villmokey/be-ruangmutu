<?php


namespace App\Service\Indicator;


use App\Models\Table\IndicatorProfileTable;
use App\Models\Table\IndicatorProfileSignatureTable;
use App\Models\Table\IndicatorProfileDimensionTable;
use App\Models\Table\IndicatorProfileTypeTable;
use App\Models\Table\IndicatorProfileAnalystPeriodTable;
use App\Models\Table\IndicatorProfileDataPeriodTable;
use App\Models\Table\IndicatorProfileDataFrequencyTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IndicatorProfileService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;
    protected $signatureTable;
    protected $dimensionTable;
    protected $typeTable;
    protected $analystPeriodTable;
    protected $dataPeriodTable;
    protected $dataFrequencyTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        IndicatorProfileSignatureTable $signatureTable,
        IndicatorProfileDimensionTable $dimensionTable,
        IndicatorProfileTypeTable $typeTable,
        IndicatorProfileAnalystPeriodTable $analystPeriodTable,
        IndicatorProfileDataPeriodTable $dataPeriodTable,
        IndicatorProfileDataFrequencyTable $dataFrequencyTable,
        IndicatorProfileTable $model
    )
    {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->signatureTable       =   $signatureTable;
        $this->dimensionTable       =   $dimensionTable;
        $this->typeTable            =   $typeTable;
        $this->analystPeriodTable   =   $analystPeriodTable;
        $this->dataPeriodTable      =   $dataPeriodTable;
        $this->dataFrequencyTable   =   $dataFrequencyTable;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null, $subProgram = null)
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

    public function getPaginated($search = null, $year = null, $subProgram = null, $perPage = 15, $page = null)
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
                                ->with('firstPic')
                                ->with('secondPic')
                                ->with('assignBy')
                                ->with('document')
                                ->with('signature')
                                ->with('qualityDimension')
                                ->with('indicatorType')
                                ->with('dataFrequency')
                                ->with('dataPeriod')
                                ->with('analystPeriod')
                                ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $indicatorProfile = $this->model->newQuery()->create([
                'program_id'                =>  $data['program_id'],
                'sub_program_id'            =>  $data['sub_program_id'],
                'title'                     =>  $data['title'],
                'indicator_selection_based' =>  $data['indicator_selection_based'],
                'objective'                 =>  $data['objective'],
                'operational_definition'    =>  $data['operational_definition'],
                'measurement_status'        =>  $data['measurement_status'],
                'numerator'                 =>  $data['numerator'],
                'denominator'               =>  $data['denominator'],
                'achievement_target'        =>  $data['achievement_target'],
                'criteria'                  =>  $data['criteria'],
                'measurement_formula'       =>  $data['measurement_formula'],
                'data_collection_design'    =>  $data['data_collection_design'],
                'data_source'               =>  $data['data_source'],
                'population'                =>  $data['population'],
                'data_presentation'         =>  $data['data_presentation'],
                'first_pic_id'              =>  $data['first_pic_id'],
                'second_pic_id'             =>  $data['second_pic_id'] ?? null,
                'created_by'                =>  $data['created_by'],
                'assign_by'                 =>  $data['assign_by'],
            ]);

            $this->relationStore($data, $indicatorProfile->id);

            if (!empty($data['document_id'])) {
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($indicatorProfile),
                    'fileable_id'   => $indicatorProfile->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicatorProfile);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $indicatorProfile   =   $this->model->newQuery()->find($id);
        $oldImage                  =   $indicatorProfile->document()->first();

        \DB::beginTransaction();

        try {
            $indicatorProfile->program_id        =   $data['program_id'];
            $indicatorProfile->sub_program_id    =   $data['sub_program_id'];
            $indicatorProfile->title             =   $data['title'];
            $indicatorProfile->indicator_selection_based =   $data['indicator_selection_based'];
            $indicatorProfile->quality_dimension =   $data['quality_dimension'];
            $indicatorProfile->objective         =   $data['objective'];
            $indicatorProfile->operational_definition =   $data['operational_definition'];
            $indicatorProfile->measurement_status    =   $data['measurement_status'];
            $indicatorProfile->numerator         =   $data['numerator'];
            $indicatorProfile->denominator       =   $data['denominator'];
            $indicatorProfile->achievement_target   =  $data['achievement_target'];
            $indicatorProfile->criteria    =   $data['criteria'];
            $indicatorProfile->measurement_formula   =   $data['measurement_formula'];
            $indicatorProfile->data_collection_design    =   $data['data_collection_design'];
            $indicatorProfile->data_source       =   $data['data_source'];
            $indicatorProfile->population        =   $data['population'];
            $indicatorProfile->data_presentation  =   $data['data_presentation'];
            $indicatorProfile->first_pic_id            =   $data['first_pic_id'];
            $indicatorProfile->second_pic_id            =   $data['second_pic_id'] ?? null;
            $indicatorProfile->assign_by            =   $data['assign_by'];
            $indicatorProfile->save();

            $this->relationUpdate($data, $indicatorProfile);

            if (!empty($data['document_id'])) {
                if (!empty($oldImage)) {
                    $oldImage->delete();
                }
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($indicatorProfile),
                    'fileable_id'   => $indicatorProfile->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicatorProfile);
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

    public function getAllQualityGoal($search = null, $year = null)
    {
        $result =   $this->model->newQuery()
                                ->select('title')
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginatedQualityGoal($search = null, $year = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->select('id', 'title')
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

    private function relationStore($data, $id)
    {
        if($data['signature'] != null) {
            foreach($data['signature'] as $signatures) {
                $this->signatureTable->newQuery()->create([
                    'indicator_profile_id'            => $id,
                    'user_id'               => $signatures['user_id'],
                    'level'                 => $signatures['level'],
                ]);
            }
        }

        if($data['quality_dimension'] != null) {
            foreach($data['quality_dimension'] as $qualityDimension) {
                $this->dimensionTable->newQuery()->create([
                    'indicator_profile_id'            => $id,
                    'name'                  => $qualityDimension['name'],
                ]);
            }
        }

        if($data['indicator_type'] != null) {
            foreach($data['indicator_type'] as $indicatorType) {
                $this->typeTable->newQuery()->create([
                    'indicator_profile_id'        => $id,
                    'name'              => $indicatorType['name'],
                ]);
            }
        }

        if($data['data_collection_frequency'] != null) {
            foreach($data['data_collection_frequency'] as $dataFrequency) {
                $this->dataFrequencyTable->newQuery()->create([
                    'indicator_profile_id'        => $id,
                    'name'              => $dataFrequency['name'],
                ]);
            }
        }

        if($data['data_collection_period'] != null) {
            foreach($data['data_collection_period'] as $dataPeriod) {
                $this->dataPeriodTable->newQuery()->create([
                    'indicator_profile_id'        => $id,
                    'name'              => $dataPeriod['name'],
                ]);
            }
        }

        if($data['data_analyst_period'] != null) {
            foreach($data['data_analyst_period'] as $analystPeriod) {
                $this->analystPeriodTable->newQuery()->create([
                    'indicator_profile_id'        => $id,
                    'name'              => $analystPeriod['name'],
                ]);
            }
        }
    }

    private function relationUpdate($data, $indicatorProfile)
    {
        if($data['quality_dimension'] != null) {
            $this->dimensionTable->where('indicator_profile_id', $indicatorProfile->id)->delete();
            foreach($data['quality_dimension'] as $qualityDimension) {
                $this->dimensionTable->newQuery()->create([
                    'indicator_profile_id'        => $indicatorProfile->id,
                    'name'              => $qualityDimension['name'],
                ]);
            }
        }

        if($data['indicator_type'] != null) {
            $this->typeTable->where('indicator_profile_id', $indicatorProfile->id)->delete();
            foreach($data['indicator_type'] as $indicatorType) {
                $this->typeTable->newQuery()->create([
                    'indicator_profile_id'        => $indicatorProfile->id,
                    'name'              => $indicatorType['name'],
                ]);
            }
        }

        if($data['data_collection_frequency'] != null) {
            $this->dataFrequencyTable->where('indicator_profile_id', $indicatorProfile->id)->delete();
            foreach($data['data_collection_frequency'] as $dataFrequency) {
                $this->dataFrequencyTable->newQuery()->create([
                    'indicator_profile_id'        => $indicatorProfile->id,
                    'name'              => $dataFrequency['name'],
                ]);
            }
        }

        if($data['data_collection_period'] != null) {
            $this->dataPeriodTable->where('indicator_profile_id', $indicatorProfile->id)->delete();
            foreach($data['data_collection_period'] as $dataPeriod) {
                $this->dataPeriodTable->newQuery()->create([
                    'indicator_profile_id'        => $indicatorProfile->id,
                    'name'              => $dataPeriod['name'],
                ]);
            }
        }

        if($data['data_analyst_period'] != null) {
            $this->analystPeriodTable->where('indicator_profile_id', $indicatorProfile->id)->delete();
            foreach($data['data_analyst_period'] as $analystPeriod) {
                $this->analystPeriodTable->newQuery()->create([
                    'indicator_profile_id'        => $indicatorProfile->id,
                    'name'              => $analystPeriod['name'],
                ]);
            }
        }
    }

    public function getSignature($id)
    {
        $result = $this->model->newQuery()
                                ->whereHas('signature', function($query) use ($id) {
                                    $query->where('user_id', $id);
                                })
                                ->with('signature')
                                ->get();

        return $this->sendSuccess($result);
    }

    public function changeStatus($id, $data)
    {
        $indicatorProfile   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();
        try {
            if (isset($data['status']) == 'rejected') {
                $indicatorProfile->update([
                    'status' => 'rejected',
                ]);
            } else {
                $signature = $indicatorProfile->signature()->where('user_id', $data['user_id'])->first();
                $signature->update([
                    'signed' => 1,
                ]);
                if ($indicatorProfile->signature()->where('signed', 0)->count() == 0) {
                    $indicatorProfile->update([
                        'status' => 'approved',
                    ]);
                }
            }
            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicatorProfile);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }

        return $this->sendSuccess($indicator);
    }
}
