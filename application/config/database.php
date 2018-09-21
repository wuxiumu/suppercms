<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//数据库配置文件
$active_group = 'default';
$query_builder = TRUE;
//数据库可配置几种方案
//数据库配置方案一：
$db['default'] = array(
    'dsn'	=> '',
    'hostname' => '47.95.123.86',
    'username' => 'xiaomi',
    'password' => 'Y!1y@2K#3J$4',
    'database' => 'bocgi',
    'dbdriver' => 'mysqli',
    'dbprefix' => 'bro_',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
//数据库配置方案二：
$db['rptech_mysql'] = array(
    'dsn'	=> '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'rptech',
    'dbdriver' => 'mysqli',
    'dbprefix' => 'bro_',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
