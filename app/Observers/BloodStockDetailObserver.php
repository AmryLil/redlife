<?php

namespace App\Observers;

use App\Models\BloodStock;
use App\Models\BloodStockDetail;

class BloodStockDetailObserver
{
    // Saat membuat detail baru
    public function created(BloodStockDetail $detail)
    {
        $this->updateBloodStockTotal($detail->blood_stock_id);
    }

    // Saat mengupdate detail
    public function updated(BloodStockDetail $detail)
    {
        // Jika pindah blood_stock_id
        if ($detail->isDirty('blood_stock_id')) {
            $this->updateBloodStockTotal($detail->getOriginal('blood_stock_id'));
            $this->updateBloodStockTotal($detail->blood_stock_id);
        } else {
            $this->updateBloodStockTotal($detail->blood_stock_id);
        }
    }

    // Saat menghapus detail
    public function deleted(BloodStockDetail $detail)
    {
        $this->updateBloodStockTotal($detail->blood_stock_id);
    }

    private function updateBloodStockTotal($bloodStockId)
    {
        $total = BloodStockDetail::where('blood_stock_id', $bloodStockId)
            ->sum('quantity');

        BloodStock::where('id', $bloodStockId)
            ->update(['total_quantity' => $total]);
    }
}
