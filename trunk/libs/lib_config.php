<?php
/**
 * 基本函数，全局对象$php的构造
 * @package SwooleSystem
 * @author 韩天峰
 */
define("LIBPATH",str_replace("\\","/",dirname(__FILE__)));
/**
 * 产生类库的全局变量
 */
$php = new Swoole;

/**
*函数的命名空间
*/
function namespace($space_name)
{
	require_once(LIBPATH.'/function/'.$space_name.'.php');
}

/**
*生产一个model接口
*/
function createModel($model_name)
{
	global $php;
	return $php->model->$model_name;
}

/**
 * 导入类库
 */
function import($lib_name)
{
	$file = str_replace('.','/',$lib_name);
	if($file{0}=='@')
	{
		require_once(WEBPATH.'/class/'.$file.'.class.php');
		return true;
	}
	$lib_file = LIBPATH.'/class/'.$file.".class.php";
	if(file_exists($lib_file))
	{
		require_once($lib_file);
		return true;
	}
	else
	{
		Error::info("Import Error!","Class <b>$lib_file</b> not fountd!<br />\n $lib_name load fail!<br />\n");
		return false;
	}
}
/**
 * 工厂方法，产生一个类的对象
 * @param $name
 * @return unknown_type
 */
function create($name)
{
	import($name);
	$classinfo = explode('.',$name);
	$classname = $classinfo[-1];
	if(func_num_args()!=1)
	{
		$args=func_get_args();
		for($i=1;$i<count($args);$i++) $el[]='$args['.$i.']';
		$object=eval("return new $classname(".implode(",",$el).");");
		return $object;
	}
	else return new $classname;
}
/**
 * 开启会话
 * @return None
 */
function session()
{
    if(!defined('SESSION_CACHE'))
    {
        session_start();
        return true;
    }
    $session_cache = new Cache(SESSION_CACHE);
    $mSess = new MSession($session_cache);
    $mSess->initSess();
}

/**
 * 导入一个数组格式的数据
 */
function load_data($data_name)
{
	require(LIBPATH.'/data/'.$data_name.'.php');
	return $$data_name;
}
/**
 * 调试数据，终止程序的运行
 * @param $var
 * @return unknown_type
 */
function debug($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit;
}
/**
*自动导入类
*/
function __autoload($class_name)
{
	if(file_exists(LIBPATH.'/system/'.$class_name.'.php'))
		require(LIBPATH.'/system/'.$class_name.'.php');
	elseif(file_exists(WEBPATH.'/class/'.$class_name.'.class.php'))
		require(WEBPATH.'/class/'.$class_name.'.class.php');
}
?>