<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2019/3/22
 * Time: 16:25
 */

namespace Core;

if (!defined("ACCESS")){
    header("location:../index.php");
}
class Application
{
     public static function run(){
         //echo __METHOD__;//显示方法名
         //echo __LINE__;
         //echo __DIR__."config/";
        // echo __CLASS__;
         self::setChar();
         self::setDIR();
         self::setSys();
         self::loadConfig();
         self::autoLoad();
         self::analyseURL();
         self::startSession();
         self::dispath();
     }

     private static function setChar(){
         header("content-type:text/html;charset=utf-8");
     }

     private static function setSys(){
         if (!ini_get("display_errors")){
             ini_set('display_errors','on');
             ini_set("error_reporting",E_ALL);
         }
     }

     private static function setDIR(){
         define("DIR",str_replace('Core','',str_replace("\\",'/',__DIR__)));
         define('MY_CONF',DIR.'Config/');
         define('MY_CONT',DIR.'Controller/');
         define('MY_CORE',DIR.'Core/');
         define('MY_MODE',DIR.'Model/');
         define('MY_PUB',DIR.'Public/');
         define('MY_VIEW',DIR.'View/');
         define('MY_VEN',DIR.'Vendor/');

     }

     private static function loadConfig(){
         $GLOBALS['config'] = include_once (MY_CONF.'config.php');
     }
     //类的自动加载
     private static function loadCore($name){
         $fileName = MY_CORE.basename($name).".class.php";
         if (file_exists($fileName)){
             include_once("$fileName");
         }
     }

     private static function loadController($name){
         $fileName = MY_CONT.basename($name).".class.php";
         if (file_exists($fileName)){
             include_once ("$fileName");
         }
     }

    private static function loadModel($name){
        $fileName = MY_MODEL.basename($name).".class.php";
        if (file_exists($fileName)){
            include_once ("$fileName");
        }
    }

    private static function loadVendor($name){
        $fileName = MY_VEND.basename($name).".class.php";
        if (file_exists($fileName)){
            include_once ("$fileName");
        }
    }

     private static function autoLoad(){
         spl_autoload_register('self::loadCore');
         spl_autoload_register(array(__CLASS__,'loadController'));
         spl_autoload_unregister('self::loadModel');
         spl_autoload_unregister('self::loadVendor');
     }

     private static function analyseURL(){
         $module = isset($_REQUEST['module'])?ucfirst(strtolower($_REQUEST['module'])):'Privilege';
         $action = isset($_REQUEST['action'])?strtolower($_REQUEST['action']):'index';
         define('MODULE',$module);
         define('ACTION',$action);
     }

     //分发请求
    private static function dispath(){
         $module = "Controller\\".MODULE.'Controller';
         $action = ACTION;
         $obj = new $module;
         $obj->$action();
    }

    private static function startSession(){
         session_start();
    }




}