<?php


namespace App\Traits;


trait LogUserTrait
{
    // public $logName = false;
    // public $logId = false;
    // public $logged = false;

    public function beforeInsert(array $data)
    {
        if ($this->logId == true) {
            $data['data']['cid'] = auth()->id();
        }
        if ($this->logName == true) {
            if (auth()->id()) {
                $data['data']['cname'] = getProfile()->name;
            }
        }
        return $data;
    }

    public function beforeUpdate(array $data)
    {
        if ($this->logId == true) {
            $data['data']['uid'] = auth()->id();
        }
        if ($this->logName == true) {
            $data['data']['uname'] = getProfile()->name;
        }

        return $data;
    }

    public function beforeDelete(array $data)
    {
        if ($this->useSoftDeletes == true) {
            if ($this->logId == true) {
                $dataUpdate['did'] = auth()->id();
            }
            if ($this->logName == true) {
                $dataUpdate['dname'] = getProfile()->name;
            }
            $this->db->table($this->table)->where($this->primaryKey, $data['id'][0])->update($dataUpdate);
        }

        if ($data['id']) {
            $lastData = $this->find($data['id']);
            // if object convert to array
            if (is_object($lastData)) {
                $lastData = $lastData->toArray();
            }

            session()->set('tempData', $lastData);
        }

        return true;
    }

    public function afterInsert(array $data)
    {
        log_activity('insert', $this->table, $data['id'], $data['data']);
        return $data;
    }

    public function afterUpdate(array $data)
    {
        log_activity('update', $this->table, $data['id'], $data['data']);
        return $data;
    }

    public function afterDelete(array $data)
    {
        log_activity('delete', $this->table, ($data['id'] ?? 0), session()->get('tempData') ?? []);
        return $data;
    }
}
