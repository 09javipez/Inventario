<?php

namespace App\Services;

use App\Models\Inventory;


class KardexService
{
   public function getLastRecord($productId, $warehouseId)
   {
        //kardex
        $lastRecord = Inventory::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->latest('id')
            ->first();
        return [
            'quantity' => $lastRecord?->quantity_balance ?? 0,
            'cost' => $lastRecord?->cost_balance ?? 0,
            'total' => $lastRecord?->total_balance ?? 0,
            'date' => $lastRecord?->created_at ?? null,
        ];
   }

   public function registerEntry($model, array $product, $warehouseId, $detail)
   {
        $lastRecord = $this->getLastRecord($product['id'], $warehouseId);

        $newQuantityBalance = $lastRecord['quantity'] + $product['quantity'];
        $newTotalBalance = $lastRecord['total'] + ($product['quantity'] * $product['price']);
        $newCostBalance = $newTotalBalance / $newQuantityBalance;

        $model->inventories()->create([
            'detail' => $detail,
            'quantity_in' => $product['quantity'],
            'cost_in' => $product['price'],
            'total_in' => $product['quantity'] * $product['price'],
            'quantity_balance' => $newQuantityBalance,
            'cost_balance' => $newCostBalance,
            'total_balance' => $newTotalBalance,
            'product_id' => $product['id'],
            'warehouse_id' => $warehouseId,
        ]);

   }
   public function registerExit($model, array $product, $warehouseId, $detail)
    {
        $lastRecord = $this->getLastRecord(
            $product['id'],
            $warehouseId
        );

        $newQuantityBalance = $lastRecord['quantity'] - $product['quantity'];

        $costOut = $lastRecord['cost'];

        $newTotalBalance = $lastRecord['total'] - (
            $product['quantity'] * $costOut
        );

        $newCostBalance = $newQuantityBalance > 0
            ? $newTotalBalance / $newQuantityBalance
            : 0;

        $model->inventories()->create([
            'detail' => $detail,

            'quantity_out' => $product['quantity'],
            'cost_out' => $costOut,
            'total_out' => $product['quantity'] * $costOut,

            'quantity_balance' => $newQuantityBalance,
            'cost_balance' => $newCostBalance,
            'total_balance' => $newTotalBalance,

            'product_id' => $product['id'],
            'warehouse_id' => $warehouseId,
        ]);
    }
}


