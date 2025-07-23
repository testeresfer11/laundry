<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class IncomeReport implements FromCollection , WithHeadings,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $orders;
    public function __construct($orders)
    {
        $this->orders = $orders;
    }
    public function collection()
    {

        $processedOrders = $this->orders->map(function ($order) {
            $order->user_id = $order->user ? $order->user->full_name : 'N/A';
            $order->service_name = $order->service_name;  
            $order->pickup_driver_id = $order->pickupDriver ? $order->pickupDriver->full_name : 'N/A';
            $order->delivery_driver_id = $order->deliveryDriver ? $order->deliveryDriver->full_name : 'N/A';
            return $order;
        });
        return $processedOrders;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Order By',
            'Order Type',
            // 'Services',
            'Amount',
            'Pickup Address',
            'Pickup Driver',
            'Pickup Date',
            'Pickup Time',
            'Delivery Address',
            'Delivery Driver',
            'Delivery Date',
            'Delivery Time',
            'Order At',	
            'Completed At',
        ];
    }

    public function registerEvents(): array
    {
    return [
        AfterSheet::class    => function(AfterSheet $event) 
            {
                $cellRange = 'A1:G1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Calibri');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
   }

}
