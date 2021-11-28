<?php

class Servise {
    
    private $base;

    function __construct($conn)
    {
        $this->base = $conn;        
    }

    public function Login($data) {
        if(empty($data['login']) || empty($data['password'])) ErrorMaker::ErrorHeandler("not data" , 400 );

        $query = 'SELECT * FROM `users` WHERE `login` = ? and `password` = ?';

        $stmt = $this->base->prepare($query);
        $stmt->execute([ $data['login'] , $data['password'] ]);
        $res = $stmt->fetch();
        if($res) {
            http_response_code(201);
            echo json_encode([
                "data" => $res,
                "status" => true,
                "code" => 201
            ]);
            exit();
        } 
            ErrorMaker::ErrorHeandler("incorect login or pass" , 401); 
    }

    public function Register($data) {
        
        if(empty( $data['name']) || empty($data['surname']) || empty($data['city']) || empty( $data['district'] ) || empty($data['age']) || empty($data['phone']) || empty($data['login']) ||empty($data['password'])) ErrorMaker::ErrorHeandler('not data' , 400);

        $query = 'INSERT INTO `users`(`uuid`, `login`, `password`, `name`, `surname`, `city`, `district`, `age`, `phone`) VALUES (?,?,?,?,?,?,?,?,?)';

        $uuid = uniqid();

        $exsist = $this->CheckUser($data['login'] , $data['phone']);

        if($exsist == false) ErrorMaker::ErrorHeandler("user already exists" , 401);

        $stmt = $this->base->prepare($query);
        $stmt->execute([ $uuid, $data['login'] , $data['password'] , $data['name'] , $data['surname'] , $data['city'] , $data['district'] , $data['age'] , $data['phone'] ]);
        if($stmt) {
            http_response_code(202);
            echo json_encode([
                "data" => [
                    "uuid" => $uuid,
                ],
                "status" => true,
                "code" => 201
            ]);
        }
    }

    private function CheckUser($login , $phone) {
        $query = 'SELECT * FROM `users` WHERE `password` = ? or `phone` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute([ $login, $phone ]);
        $row = $stmt->fetch();
        if($row) {
            return false;
        } 
            return true;
    }

    public function GetUserInfo($data) {
        if(empty($data['uuid'])) ErrorMaker::ErrorHeandler("not data" , 400 );

        $query = 'SELECT * FROM `users` WHERE `uuid` = ?';

        $stmt = $this->base->prepare($query);
        $stmt->execute([ $data['uuid'] ]);
        $res = $stmt->fetch();
        if($res) {
            http_response_code(201);
            echo json_encode([
                "data" => $res,
                "status" => true,
                "code" => 201
            ]);
            exit();
        } 
            ErrorMaker::ErrorHeandler("incorect login or pass" , 401); 
    }

    public function CreateOrder($data) {

        if(empty($data['price']) || empty($data['deliveryTime']) || empty($data['productsId']) || empty($data['deliveryId']) || empty($data['userId'])) ErrorMaker::ErrorHeandler("not data" , 400);

        $orderID = rand(10, 50).rand(10, 50).rand(10, 50).rand(10, 20);
        $query = 'INSERT INTO `orders` ( `orderId`, `price`, `deliveryTime`, `status` , `userID`) VALUES (?,?,?,?,?)';

        $stmt = $this->base->prepare($query);
        $stmt->execute([$orderID, $data['price'] , $data['deliveryTime'], 0 , $data['userId']]);

        if($stmt) {
            $this->CreateOrderItems(json_decode($data['productsId']) , $orderID);
            $this->CreateOrderDelivery(json_decode($data['deliveryId']), $orderID);
        }
            echo json_encode([
                "orderId" => $orderID,
                "status" => true,
                "code" => 202
                ]
            );
    }

    private function CreateOrderItems($data , $orderID) {
        $query = 'INSERT INTO `orderdelivery`( `orderId`, `deliveryId`) VALUES (?,?)';
        
        for($i = 0; $i < count($data); $i++) {
            $stmt = $this->base->prepare($query);
            $stmt->execute([$orderID, $data[$i]]);
        }
    }

    private function CreateOrderDelivery($data, $orderID) {
        $query = 'INSERT INTO `orderitems`(`orderId`, `productId`) VALUES (?,?)';
        for($i = 0; $i < count($data); $i++) {
            $stmt = $this->base->prepare($query);
            $stmt->execute([$orderID , $data[$i]]);
        }
    }

    public function ConfirmOrder($data) {

        if(empty($data['orderId'])) ErrorMaker::ErrorHeandler("not oreder id" , 400);

        $query = 'UPDATE `orders` SET `status`= ? wHERE orderId = ?';

        $stmt = $this->base->prepare($query);
        $stmt->execute([ true , $data['orderId'] ]);

        if($stmt) {
            http_response_code(202);
            echo json_encode([
                "status" => true,
                "code" => 202
            ]);
        }

    }

    public function AddCart($data) {
        if(empty($data['productId']) || empty($data['userId']) || empty($data['deliveryId'])) ErrorMaker::ErrorHeandler("not data" , 400);

        $query = 'INSERT INTO `cart`( `productId`, `userId`, `deliveryId`) VALUES (?,?,?)';

        $stmt = $this->base->prepare($query);
        $stmt->execute([ $data['productId'] , $data['userId'] , $data['deliveryId'] ]);
        if($stmt) {
            http_response_code(202);
            echo json_encode([
                "status" => true,
                "code" => 202
            ]);
        }
    }

    private function BaseRequest($query) {
        $stmt = $this->base->prepare($query);
        $stmt->execute();
    }

    private function ChekEmpty($filed) {
        return empty($filed) ? false: true;
    }
}

$servise = new Servise($conn);