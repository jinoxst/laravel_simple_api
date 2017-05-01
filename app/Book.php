<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Book extends Model
{
    /**
     * @param int $quantity
     * @return int
     */
    public function decrementInventory($quantity)
    {
        $columns = [
            'inventory' => DB::raw(sprintf('inventory - %d', $quantity)),
        ];

        return DB::table($this->getTable())
            ->where('id', $this->id)
            ->where('inventory', '>=', $quantity)
            ->update($columns);
    }

    /**
     * @param int $quantity
     * @return int
     */
    public function incrementInventory($quantity)
    {
        $columns = [
            'inventory' => DB::raw(sprintf('inventory + %d', $quantity)),
        ];

        return DB::table($this->getTable())
            ->where('id', $this->id)
            ->update($columns);
    }
}
