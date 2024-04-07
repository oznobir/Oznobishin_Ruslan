<?php

namespace Project\Models;

use Core\Model;

class PurchaseModel extends Model
{
    public function setPurchaseFormOrder($parameters)
    {
        $query = "INSERT INTO `purchase`(`order_id`, `product_id`, `price`, `amount`) VALUES(:orderId,:id,:price,:count)";
        return self::execMulti($query, $parameters);
    }
}