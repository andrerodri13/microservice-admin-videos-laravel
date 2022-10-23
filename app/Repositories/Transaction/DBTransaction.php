<?php

namespace App\Repositories\Transaction;

use Core\UseCase\Interfaces\TransactionInterface;
use Illuminate\Support\Facades\DB;

class DBTransaction implements TransactionInterface
{
    /**
     * DBTransaction constructor.
     */
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}
