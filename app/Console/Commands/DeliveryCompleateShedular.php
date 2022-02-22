<?php

namespace App\Console\Commands;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Console\Command;
use PDO;

class DeliveryCompleateShedular extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delivery_backup:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command for backup daily delivery completed order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::where('status', 3)->where('date', date('Y-m-d'))->get();
        foreach($orders as $order){
            $order_id                            = $order->id;
            $product_delivery                    = new DeliveryOrder();
            $product_delivery->amount            = $order->amount;
            $product_delivery->shipping_address  = $order->shipping_address;
            $product_delivery->order_tracking_no = $order->order_tracking_no;
            $product_delivery->date              = $order->date;
            $product_delivery->status            = $order->status;
            $product_delivery->user_id           = $order->user_id;
            $product_delivery->save();

            $this->deliveryOrderDetails($order_id, $product_delivery->id);
        }
        try{
            Order::where('status', 3)->where('date', date('Y-m-d'))->delete(); // delete order table data
            $this->info("Successfully moved daily delivery complete orders");
        }catch(Exception $e){
            $this->info($e->getMessage());
        }
        

        

    }

    
    /**
     * Product Delivery Details Table Data Store
     *
     * @return int
     */
    public function deliveryOrderDetails($order_id, $product_delivery_id){
        $orders = OrderDetail::where('order_id', $order_id)->where('status', 1)->get();
        foreach($orders as $order){
            $delivery_order_details                    = new DeliveryOrderDetail();
            $delivery_order_details->product_id        = $order->product_id;
            $delivery_order_details->delivery_order_id = $product_delivery_id;
            $delivery_order_details->price             = $order->price;
            $delivery_order_details->qty               = $order->qty;
            $delivery_order_details->save();
        }    
        OrderDetail::where('order_id', $order_id)->where('status', 1)->delete(); // delete order details table data    
    }
}
