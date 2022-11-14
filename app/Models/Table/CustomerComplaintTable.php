<?php

namespace App\Models\Table;

use App\Models\Entity\CustomerComplaint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComplaintTable extends CustomerComplaint
{
	public function attachments(){
		return $this->morphMany(FileTable::class, 'fileable')->where('group', 'complaint_files');
	} 

	public function proof(){
		return $this->morphMany(FileTable::class, 'fileable')->where('group', 'proof_complaint_files');
	} 

    public function program() {
        return $this->belongsTo(ProgramTable::class, 'program_id')->select(['id', 'name']);
    }

    public function healthService() {
        return $this->belongsTo(HealthServiceTable::class, 'health_service_id')->select(['id', 'name']);
    }
    
    public function creator() {
        return $this->belongsTo(UserTable::class, 'created_id')->select(['id', 'name']);
    }

    public static function generateCode()
	{
		$dateCode = date('y') . date('m') . date('d');

		$lastCode = self::select([\DB::raw('MAX(customer_complaints.complaint_id) AS last_code')])
			->where('complaint_id', 'like', $dateCode . '%')
			->first();

		$lastComplaintCode = !empty($lastCode) ? $lastCode['last_code'] : null;
		
		$orderCode = $dateCode . '00001';
		if ($lastComplaintCode) {
			$lastComplaintNumber = str_replace($dateCode, '', $lastComplaintCode);
			$nextCode = sprintf('%05d', (int)$lastComplaintNumber + 1);
			
			$orderCode = $dateCode . $nextCode;
		}

		if (self::_isComplaintCodeExists($orderCode)) {
			return self::generateCode();
		}

		return $orderCode;
	}

    private static function _isComplaintCodeExists($orderCode)
	{
		return self::where('complaint_id', '=', $orderCode)->exists();
	}
}
