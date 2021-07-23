<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use App\SendMessage;
use Ramsey\Uuid\Type\Integer;

class OrderController extends Controller
{

    function Orderitems($items,$id){
        $orderitem = new OrderItems();
       foreach($items as $item){
            $orderitem->create([
                'orderid' => $id,
                'productid' => $item['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);
        }
    }

    function confirmOrder(Request $request){
        $order = Order::where(['userid'=>$request->userid,'ordernum'=>$request->orderid])->first();
        // dd($order);
        $order->ispaid = 1;
        // $order->paymentmethod = "";
        $order->paymentid = $request->paymentid;
        $order->razorpaysign = $request->signature;
        $address = UserAddress::where(["address->address line 1" => $request->addline1,
        "address->address line 2" => $request->addline2,
        "address->state" => $request->state,
        "address->city" => $request->city,
        "address->zip" => $request->zip])->get();
        if($address == null){
            UserAddress::create([
                'userid' => $request->userid,
                'address' => ["address line 1" => $request->addline1,
                "address line 2" => $request->addline2,
                "state" => $request->state,
                "city" => $request->city,
                "zip" => $request->zip]
            ]);
        }
        $order->address = [
            "address line 1" => $request->addline1,
            "address line 2" => $request->addline2,
            "state" => $request->state,
            "city" => $request->city,
            "zip" => $request->zip
        ];
        $order->save();

        $user = User::where("id",$request->userid)->first();

        if($order){
            Cart::where('userid',$request->userid)->delete();
                // Mail::to($user->email)->send(new WelcomeMail);
                if($user->email){
                    $data = ['name' => $user->name,'email'=>$user->eamil,"orderid"=>$order->ordernum];
                    Mail::send('emails.order', $data, function ($message) use($user) {
                    $message->from('abhi884707@gmail.com', 'Abhishek Mouriya');
                    // $message->sender('john@johndoe.com', 'John Doe');
                    $message->to($user->email, $user->name);
                    // $message->cc('john@johndoe.com', 'John Doe');
                    // $message->bcc('john@johndoe.com', 'John Doe');
                    // $message->replyTo('john@johndoe.com', 'John Doe');
                    $message->subject('Order Confirmation Email from Fassla');
                    // $message->priority(3);
                    // $message->attach('pathToFile');
                    });
                }

                // $content = "Your Order has been Placed Successfully, and your Order Id Is ". $order->ordernum;
                // SendMessage::sendmessage($user->phone,$content);

        }

        return response()->json("Your Order Created Successfully");
        // return $order;
    }

    function placeOrder(Request $request){
        $neworder = new Order();
        $receipt = Str::random(20);

        $api = new Api(env('KEY_ID'),env('KEY_SECRET'));

        $order = $api->order->create([
            'receipt'=> $receipt,
            'amount' => $request->totall * 100,
            'currency' => 'INR'
        ]);
        $neworder->ordernum = $order['id'];
        // dd($order['id']);
        $neworder->userid = $request->userid;
        $neworder->status = 1;
        $neworder->grandtotal = $request->totall;
        $neworder->itemcount = $request->itemcount;
        $neworder->save();
        if($request->has("items")){
            // dd($request->items);
            $this->Orderitems($request->items,$neworder->id);
        }

        return response()->json(["orderid"=>$neworder->ordernum]);
    }

    function orderList($id){
        return Order::join("order_items","orders.id","=","order_items.orderid")
        ->where(["orders.userid"=>$id,"orders.ispaid"=>1])
        // ->select("orders.address")
        ->get();
    }
}
