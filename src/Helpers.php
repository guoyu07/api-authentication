<?php

namespace Lingxi\Signature;

/**
 * Defines a few helper methods.
 *
 * @author guoyongrong <handsomegyr@gmail.com>
 */
class Helpers
{
    /**
     * 检测一个字符串否为Json字符串
     *
     * @param string $string
     * @return true/false
     *
     */
    public static function isJson($string)
    {
        if (strpos($string, "{") !== false) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        } else {
            return false;
        }
    }

    /**
     * 除去数组中的空值和签名参数
     *
     * @param $para 签名参数组
     * @return 去掉空值,null与签名参数后的新签名参数组
     */
    public static function paraFilter($para)
    {
        $para_filter = array();
        while (list ($key, $val) = each($para)) {
            if (strtolower(trim($key)) === "signature"
                || (!is_array($val) && (trim($val) === "" || $val === null))
                || (is_array($val) && empty($val)))
                continue;
            else
                $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 对数组排序
     *
     * @param $para 排序前的数组
     *            return 排序后的数组
     */
    public static function argSort($para)
    {
        ksort($para, SORT_STRING);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     *
     * @param $para 需要拼接的数组
     *            return 拼接完成以后的字符串
     */
    public static function createLinkstring($para)
    {
        $para = static::toStringArray($para);

        $arg = "";
        while (list ($key, $val) = each($para)) {
            if(is_array($val)) {
                $val = json_encode($val);
            } elseif (is_integer($val)) {
                $val = (string) $val;
            } elseif (is_bool($val)) {
                $val = (string) ((int) $val);
            }
            $arg .= $key . "=" . $val . "&";
        }
        // 去掉最后一个&字符
        $arg = substr($arg, 0, strlen($arg) - 1);
        return $arg;
    }

    /**
     * 获取随机字符串
     *
     * @param number $length
     * @return string
     */
    public static function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i ++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public static function toStringArray($params)
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return static::toStringArray($item);
            } elseif (is_bool($item)) {
                return (string) ((int) $item);
            } else {
                return (string) $item;
            }
        }, $params);
    }
}
