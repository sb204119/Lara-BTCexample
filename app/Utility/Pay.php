<?php
/**
 * Copyright (c) 2023.
 * Be your self
 */

namespace App\Marketplace\Utility;


class Pay
{
    /**
     * @return array
     */
    public static function getTransaction()
    {
        $transaction = json_decode(bitcoind()->listtransactions());
        $array[] = array_pop($transaction);

        if ($array[0]->confirmations > 2){
            $status_confrim = true;
        }else{
            $status_confrim = false;
        }

        $data = [
            'status' => $status_confrim,
            'amount' => $array[0]->amount,
            'address' => $array[0]->address,
            'txid' => $array[0]->txid,
            't_received' => $array[0]->timereceived
        ];

        return $data;
    }
}