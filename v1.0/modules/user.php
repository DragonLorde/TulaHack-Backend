<?php

class User {

    private $base;

    function __construct($conn)
    {
        $this->base = $conn;
    }

    public function getProductsFeed1() {
        $query = 'SELECT * FROM `productslist` INNER JOIN products ON productId = products.id INNER JOIN delivery ON deliveryId = delivery.id WHERE 1';
        $stmt = $this->base->prepare($query);
        $stmt->execute();

        $arr = [
            "data" => [
                "productData" => [
                    "count" => 0,
                    "products" => [],
                    "promoProducts" => []
                ]
            ],
            "status" => true,
            "code"=> 200
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['productData']['products'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "serviceCount" => "",
                "promoType" => $row['promoType'],
                "promoProcent" => $row['promoProcent'],
                "deliveryData" => $this->GetDelivery($row['productId']),
            ]);
        }

        $query = 'SELECT * FROM `productslist` INNER JOIN products ON productId = products.id INNER JOIN delivery ON deliveryId = delivery.id WHERE products.promoType = 1';
        $stmt = $this->base->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['productData']['promoProducts'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "serviceCount" => "",
                "promoType" => $row['promoType'],
                "promoProcent" => $row['promoProcent'],
                "deliveryData" => $this->GetDelivery($row['productId']),
            ]);
        }

        $query = 'SELECT COUNT(*) FROM `productslist` INNER JOIN products ON productId = products.id INNER JOIN delivery ON deliveryId = delivery.id WHERE 1';
        $stmt = $this->base->prepare($query);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_LAZY);
        $arr['data']['productData']['count'] = $res['COUNT(*)'];

        echo json_encode($arr , JSON_UNESCAPED_UNICODE);

    }

    public function GetDeliveryBest() {
        $query = 'SELECT * FROM `delivery` WHERE `raiting` > 4';
        $arr = [
            "deliveryData" => [
                "count" => '',
                "BestDelivery" => [],
            ],
            "status" => true,
            "code" => 200
        ];

        $stmt = $this->base->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['deliveryData']['BestDelivery'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "raiting" => $row['raiting'],
                "TimeAvg" => $row['TimeAvg'],
                "avgPriceDelivery" => $row['avgPriceDelivery'],
                "icon" => $row['icon']
            ]);
        }

        $query = 'SELECT count(*) FROM `delivery` WHERE `raiting` > 4';
        $stmt = $this->base->prepare($query);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_LAZY);
        $arr['deliveryData']['count'] = $res['count(*)'];

        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }

    public function GetProduct($null ,$productID) {
        $query = 'SELECT * FROM `products` WHERE `id` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute([ $productID ]);
        $res = $stmt->fetch(PDO::FETCH_LAZY);

        $arr = [
            "data" => [
                "productData" => [
                    "productId" => $res['id'],
                    "title" => $res['title'],
                    "text" => [
                        "composition" => $res['composition'],
                        "weight" => $res['weight'],
                        "shelfLife" => $res['shelfLife']
                    ],
                    "promoType" => $res['promoType'],
                    "promoProcent" => $res['promoProcent'],
                    "deliveryData" => [
                        "count" => "",
                        "delivery" => $this->GetDelivery($res['id']),
                    ],


                ],
                "status" => true,
                "code" => 200
            ]
        ];

        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }

    public function getProductsFeed($null , $word) {
        $query = "SELECT * FROM `products`";
        $stmt = $this->base->prepare($query);
        $stmt->execute();
        
        $arr = [
            "data" => [
                "productData" =>  [
                    "count" => "",
                    "products" => [

                    ],
                    "promoProducts" => [

                    ]
                ]
            ],
            "status" => true,
            "code" => 200
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['productData']['products'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "serviceCount" => "",
                "promoType" => $row['promoType'],
                "promoProcent" => $row['promoProcent'],
                "deliveryData" => $this->GetDelivery($row['id']),
            ]); 
        }

        $query = "SELECT * FROM `products` WHERE `promoType` = 1";
        $stmt = $this->base->prepare($query);
        $stmt->execute();


        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['productData']['promoProducts'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "serviceCount" => "",
                "promoType" => $row['promoType'],
                "promoProcent" => $row['promoProcent'],
                "deliveryData" => $this->GetDelivery($row['id']),
            ]); 
        }


        $query = "SELECT count(*) FROM `products` WHERE `title` LIKE CONCAT('%', ? , '%') ";
        $stmt = $this->base->prepare($query);
        $stmt->execute([$word]);

        $res = $stmt->fetch(PDO::FETCH_LAZY);

        $arr['data']['productData']['count'] = $res['count(*)'];

        http_response_code(200);
        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }

    public function Search($null , $word) {
        $query = "SELECT * FROM `products` WHERE `title` LIKE CONCAT('%', ? , '%') ";
        $stmt = $this->base->prepare($query);
        $stmt->execute([$word]);
        
        $arr = [
            "data" => [
                "productData" =>  [
                    "word" => $word,
                    "count" => "",
                    "products" => [

                    ]
                ]
            ],
            "status" => true,
            "code" => 200
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['productData']['products'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "serviceCount" => "",
                "promoType" => $row['promoType'],
                "promoProcent" => $row['promoProcent'],
                "deliveryData" => $this->GetDelivery($row['id']),
            ]); 
        }

        $query = "SELECT count(*) FROM `products` WHERE `title` LIKE CONCAT('%', ? , '%') ";
        $stmt = $this->base->prepare($query);
        $stmt->execute([$word]);

        $res = $stmt->fetch(PDO::FETCH_LAZY);

        $arr['data']['productData']['count'] = $res['count(*)'];

        http_response_code(200);
        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }

    public function GetCatalog() {
        $query = 'SELECT * FROM `catalog`';
        $stmt = $this->base->prepare($query);
        $stmt->execute();

        $arr = [
            "data" => [
                "catalogData" => []
            ],
            "status" => true,
            "code" => 200
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['catalogData'], [
                "title" => $row['title'] ,
                "img" => $row['img'],
                "name" => $row['name']
            ]);
        }

        http_response_code(200);
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);

    }

    public function GetProductsCatalog($null , $word) {
        $query = "SELECT * FROM `products` WHERE `catalogName` = ?";
        $stmt = $this->base->prepare($query);
        $stmt->execute([$word]);
        
        $arr = [
            "data" => [
                "productData" =>  [
                    "count" => "",
                    "products" => [

                    ]
                ]
            ],
            "status" => true,
            "code" => 200
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['productData']['products'] , [
                "id" => $row['id'],
                "title" => $row['title'],
                "serviceCount" => "",
                "promoType" => $row['promoType'],
                "promoProcent" => $row['promoProcent'],
                "deliveryData" => $this->GetDelivery($row['id']),
            ]); 
        }

        $query = "SELECT count(*) FROM `products`  WHERE `catalogName` = ?";
        $stmt = $this->base->prepare($query);
        $stmt->execute([$word]);

        $res = $stmt->fetch(PDO::FETCH_LAZY);

        $arr['data']['productData']['count'] = $res['count(*)'];

        http_response_code(200);
        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }

    public function orders($null, $id) {
        $query = 'SELECT * FROM `orders` WHERE `userID` = ? ORDER BY `orders`.`status` ASC';
        $stmt = $this->base->prepare($query);
        $stmt->execute([ $id ]);

        $arr = [
            "data" => [
                "orderData" => []
            ],
            "status" => true,
            "code" => 200,
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['orderData'],  [
                "orderId" => $row['orderId'], 
                "productItems" => $this->OrderPack($row['orderId']),
                "price" => $row['price'],
                "deliveryTime" => $row['deliveryTime'],
                "status" => $row['status'],
                "deliveryList" => $this->OrderPackDelivery($row['orderId'])
            ]);
        }

        http_response_code(200);
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    public function CurentOrders($null, $id) {
        $query = 'SELECT * FROM `orders` WHERE `userID` = ? ANd `status` = 0';
        $stmt = $this->base->prepare($query);
        $stmt->execute([ $id ]);

        $arr = [
            "data" => [
                "orderData" => []
            ],
            "status" => true,
            "code" => 200,
        ];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr['data']['orderData'],  [
                "orderId" => $row['orderId'], 
                "productItems" => $this->OrderPack($row['orderId']),
                "price" => $row['price'],
                "deliveryTime" => $row['deliveryTime'],
                "status" => $row['status'],
                "deliveryList" => $this->OrderPackDelivery($row['orderId'])
            ]);
        }

        http_response_code(200);
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    public function GetCart($null, $id) {
        $query = 'SELECT * FROM `cart` WHERE `userId` = ?';

        $stmt = $this->base->prepare($query);
        $stmt->execute( [ $id ] );

        $arr = [
            "data" => [
               "productData" => [
                   "products" => [
                       
                   ]
               ] 
            ]
        ];

        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            array_push($arr['data']['productData']['products'], [
                "deliveryData" => $this->GetDeliveryCart($row['deliveryId'])
            ]);
        }

        echo json_encode($arr, JSON_UNESCAPED_UNICODE);

    }

    private function GetFoodCart($id) {
        $query = 'SELECT * FROM `products` WHERE `id` = ?';

        $stmt = $this->base->prepare($query);
        $stmt->execute( [ $id ] );
        $res = $stmt->fetch();
        
    }

    private function GetDeliveryCart($id) {
        $query = 'SELECT * FROM `delivery` WHERE `id` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute( [ $id ] );
        $res = $stmt->fetch();
        return $res;
    }

    private function OrderPack($id) {
        $query = 'SELECT * FROM `orderitems` WHERE `orderId` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute([$id]);
        $arr = [];
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            array_push($arr , [
                "productId" => $row['productId'],
                "title" => $this->GetTitleProduct( $row['productId'] )
            ]);
        }
        return $arr;
    }

    private function OrderPackDelivery($id) {
        $query = 'SELECT * FROM `orderdelivery` WHERE `orderId` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute([$id]);
        $arr = [];
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            array_push($arr , $this->GetTitleDelivery($row['deliveryId']));
        }
        return $arr;
    }

    private function GetTitleDelivery($id) {
        $query = 'SELECT * FROM `delivery` WHERE `id` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res;
    }

    private function GetTitleProduct($id) {
        $query = 'SELECT `title`FROM `products` WHERE `id` = ?';
        $stmt = $this->base->prepare($query);
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res['title'];
    }

    private function GetDelivery($productID) {
        $query = 'SELECT * FROM `productslist` INNER JOIN delivery ON productslist.deliveryId = delivery.id WHERE `productId` = ?';
        
        $stmt = $this->base->prepare($query);
        $stmt->execute([$productID]);

        $arr = [];

        while ($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            array_push($arr , [
                "title" => $row['title'],
                "raiting" => $row['raiting'],
                "TimeAvg" => $row['TimeAvg'],
                "avgPriceDelivery" => $row['avgPriceDelivery'],
                "price" => $row['price'],
            ]);
        }


        return $arr;

    }
}


$user = new User($conn);
