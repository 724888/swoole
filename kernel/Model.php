<?php
namespace Kernel;
use Illuminate\Container\Container;
use Itxiao6\Database\Capsule\Manager as DB;
use Itxiao6\Database\Eloquent\Model as Eloquent;
use Itxiao6\Database\Eloquent\SoftDeletes;
use Kernel\Config;
/**
* 模型父类
*/
class Model extends Eloquent
{
    use SoftDeletes;

    /**
    * 应该被调整为日期的属性
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
    /**
    * 这是模型的表定义
    *
    * @var string
    */
    protected $table;

    /**
    * 这里是表主键的定义
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
    * 自动自增的主键类型
    *
    * @var string
    */
    protected $keyType = 'int';

    /**
    * The number of models to return for pagination.
    *
    * @var int
    */
    protected $perPage = 15;

    /**
    * id是否自动自增
    *
    * @var bool
    */
    public $incrementing = true;

    /**
    * 是否创建模型
    *
    * @var bool
    */
    public $timestamps = true;


    /**
    * 要隐藏的字段
    *
    * @var array
    */
    protected $hidden = ['deleted_at'];

    /**
    * 要显示的字段
    *
    * @var array
    */
    protected $visible = [];


    /**
    * 日期字段的储存格式
    *
    * @var string
    */
    protected $dateFormat;

    /**
    * 获取当前时间
    *
    * @return int
    */
    public function freshTimestamp()
    {
        return time();
    }

    /**
    * 避免转换时间戳为时间字符串
    *
    * @param DateTime|int $value
    * @return DateTime|int
    */
    public function fromDateTime($value)
    {
        return $value;
    }

    /**
    * select的时候避免转换时间为Carbon
    *
    * @param mixed $value
    * @return mixed
    */
    #  protected function asDateTime($value)
    # {
    #     return $value;
    #  }


    /**
    * 从数据库获取的为获取时间戳格式
    *
    * @return string
    */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
    * @param String 表名(可为空)
    */
    public function __construct($tableName='')
    {
        # 判断数据库是否已经连接
        if ( defined('DATABASES_STATUS') === false) {
            # 连接数据库
            $database = new DB;
            # 载入数据库配置
            $database->addConnection(Config::get('database'));
            # 设置全局静态可访问
            $database->setAsGlobal();
            # 启动Eloquent
            $database -> bootEloquent();
            # 判断是否开启LOG日志
            if(Config::get('sys','database_log')){
                DB::connection()->enableQueryLog();
            }
            # 定义数据库已经连接
            define('DATABASES_STATUS',true);
        }
        # 调用父类构造方法
        parent::__construct();
    }
}
