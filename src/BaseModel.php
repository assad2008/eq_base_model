<?php

/**
 * @Filename:  BaseModel.php
 * @Author: assad
 * @Date:   2022-11-03 09:32:25
 * @Synopsis:
 * @Version: 1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2023-09-07 17:34:34
 * @Email: rlk002@gmail.com
 */

namespace EqBaseModel;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * belongsto BaseModel.php
 * model基类
 *
 * @author     assad
 * @since      2019-07-09T16:07
 */
class BaseModel extends EloquentModel {

    /**
     * belongsto BaseModel.php
     * 得到主键
     *
     * @return     string
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    protected static function keyName() {
        return (new static )->getKeyName();
    }

    /**
     * belongsto BaseModel.php
     * 获得到表名
     *
     * @return     string
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    protected static function tableName() {
        return (new static )->getTable();
    }

    /**
     * belongsto BaseModel.php
     * 根据主键获得一条数据
     *
     * @param      integer   $id     The identifier
     * @param      integer  $type   The type  1数组 2对象
     *
     * @return     array    ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-19T10:01
     */
    public static function one($id, $type = 1) {
        $res = self::find($id);
        if (!$res) {
            return [];
        }
        if ($type == 1) {
            return $res->toArray();
        } else {
            return $res;
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据条件获取一条数据
     *
     * @param      array  $where  条件
     * @param      integer  $type   返回数据类型 1数组 2对象
     *
     * @return     array    ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-19T10:06
     */
    public static function getRow($where, $type = 1) {
        $res = self::where($where)->first();
        if (!$res) {
            return [];
        }
        if ($type == 1) {
            return $res->toArray();
        } else {
            return $res;
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据条件获得结果
     *
     * @param      array  $where  The where
     * @param      array  $order  The order
     *
     * @return     array  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2020-04-07T15:19
     */
    public static function getRows($where = [], $order = [], $limit = 0, $colName = []) {
        if (!$where) {
            $rowsObj = self::whereRaw('1=1');
        } else {
            $rowsObj = self::whereRaw('1=1');
            foreach ($where as $key => $value) {
                $inStr = substr($key, -3);
                if ($inStr == ' in' && is_array($value)) {
                    $newKey = substr($key, 0, -3);
                    $rowsObj->whereIn($newKey, $value);
                } elseif ($inStr == ' ni' && is_array($value)) {
                    $newKey = substr($key, 0, -3);
                    $rowsObj->whereNotIn($newKey, $value);
                } elseif ($inStr == ' or') {
                    $newKey = substr($key, 0, -3);
                    $rowsObj->orWhere($newKey, $value);
                } elseif ($inStr == ' bt') {
                    $newKey = substr($key, 0, -3);
                    $lists->whereBetween($newKey, $value);
                } else {
                    $rowsObj->where($key, $value);
                }
            }
        }
        foreach ($order as $key => $val) {
            $rowsObj->orderBy($key, $val);
        }
        if ($limit) {
            $rowsObj->take($limit);
        }
        if ($colName) {
            $rowsObj->select($colName);
        }
        $rowsObj = $rowsObj->get();
        if ($rowsObj) {
            $rows = $rowsObj->toArray();
            return $rows ?: [];
        } else {
            return [];
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据条件获取列表，带分页
     *
     * @param      array    $where    条件
     * @param      array    $order    排序
     * @param      integer  $start    起始
     * @param      integer  $perpage  取多少条数据
     *
     * @return     array    ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-23T15:08
     */
    public static function getList($where = [], $order = [], $start = 0, $perpage = 10, $colName = []) {
        if (!$where) {
            $lists = self::whereRaw('1=1');
        } else {
            $lists = self::whereRaw('1=1');
            foreach ($where as $key => $value) {
                $inStr = substr($key, -3);
                if ($inStr == ' in' && is_array($value)) {
                    $newKey = substr($key, 0, -3);
                    $lists->whereIn($newKey, $value);
                } elseif ($inStr == ' ni' && is_array($value)) {
                    $newKey = substr($key, 0, -3);
                    $lists->whereNotIn($newKey, $value);
                } elseif ($inStr == ' or') {
                    $newKey = substr($key, 0, -3);
                    $lists->orWhere($newKey, $value);
                } elseif ($inStr == ' bt') {
                    $newKey = substr($key, 0, -3);
                    $lists->whereBetween($newKey, $value);
                } else {
                    if (is_array($value)) {
                        $lists->where([$value]);
                    } else {
                        $lists->where($key, $value);
                    }
                }
            }
        }
        foreach ($order as $key => $val) {
            $lists->orderBy($key, $val);
        }
        $lists->skip($start)->take($perpage);
        if ($colName) {
            $lists->select($colName);
        }
        $lists = $lists->get();
        if ($lists) {
            return $lists->toArray();
        } else {
            return [];
        }
    }

    /**
     * 计算数量
     *
     * @param      array   $where  The where
     *
     * @return     <type>  The count.
     */
    public static function getCount($where = []) {
        if (!$where) {
            $query = self::whereRaw('1=1');
        } else {
            $query = self::whereRaw('1=1');
            foreach ($where as $key => $value) {
                $inStr = substr($key, -3);
                if ($inStr == ' in' && is_array($value)) {
                    $newKey = substr($key, 0, -3);
                    $query->whereIn($newKey, $value);
                } elseif ($inStr == ' or') {
                    $newKey = substr($key, 0, -3);
                    $query->orWhere($newKey, $value);
                } elseif ($inStr == ' ni' && is_array($value)) {
                    $newKey = substr($key, 0, -3);
                    $query->whereNotIn($newKey, $value);
                } elseif ($inStr == ' bt') {
                    $newKey = substr($key, 0, -3);
                    $query->whereBetween($newKey, $value);
                } else {
                    if (is_array($value)) {
                        $query->where([$value]);
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }
        return $query->count() ?: 0;
    }

    /**
     * belongsto BaseModel.php
     * 根据主键更新信息
     *
     * @param      array    $where  The update data
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    public static function deleteBywhere($where = []) {
        if (!$where) {
            return false;
        }
        return self::where($where)->delete();
    }

    /**
     * belongsto BaseModel.php
     * 根据ID批量查询
     *
     * @param      array  $ids    一维ID数组
     * @param      bool   $isRowMap 是否重组为映射
     *
     * @return     array   The lists by identifiers.
     *
     * @author     doufa
     * @since      2019-10-31T16:10
     */
    public static function getRowsById($ids = [], $isRowMap = false) {
        if (!$ids) {
            return [];
        }
        $ids = array_unique($ids);
        $query = self::whereIn(self::keyName(), $ids)->get();
        if (!$query) {
            return [];
        }
        $rows = $query->toArray();
        if (!$rows) {
            return [];
        }
        if ($isRowMap) {
            $rowMap = array_combine(array_column($rows, self::keyName()), $rows);
            return $rowMap;
        } else {
            return $rows;
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据主键更新信息
     *
     * @param      int   $id          主键
     * @param      array    $updateData  The update data
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    public static function updateById($id, $updateData = []) {
        if (!$id || !$updateData) {
            return false;
        }
        return self::where(self::keyName(), $id)->update($updateData);
    }

    /**
     *  执行原生SQL
     *
     * @param      string  $sql      The sql
     * @param      string  $connect  The connect
     */
    public static function executeSql($sql = '', $connection = 'default') {
        if (!$sql) {
            return [];
        }
        $query = \Illuminate\Database\Capsule\Manager::connection($connection)->select($sql);

        $data = [];
        if ($query) {
            foreach ($query as $key => $value) {
                $data[] = (array) $value;
            }
            $data = json_decode(json_encode($data), 1);
        }
        return $data;
    }

    /**
     * 自增或者自减
     *
     * @param      int     $id          ID
     * @param      array   $updateData  The update data
     */
    public static function changeMent($id, $updateData = []) {
        $query = self::where(self::keyName(), $id);
        foreach ($updateData as $val) {
            if ($val[0] == '+') {
                $query->increment($val[1], $val[2] ?? 1);
            } elseif ($val[0] == '-') {
                $query->decrement($val[1], $val[2] ?? 1);
            }
        }
    }

    /**
     * belongsto BaseModel.php
     * 缓存实例，如果没有，则返回空对象
     *
     * @return     object  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:09
     */
    public static function cache() {
        if (!class_exists('Yaf\Registry')) {
            return object();
        }
        return \Yaf\Registry::get('cache') ?: object();
    }

    /**
     * belongsto BaseModel.php
     * 系统配置参数
     *
     * @return     array  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-20T15:11
     */
    public static function config() {
        if (!class_exists('Yaf\Registry')) {
            return [];
        }
        return \Yaf\Registry::get('di')['config'] ?: [];
    }

    /**
     * belongsto BaseModel.php
     * 得到执行的SQL语句
     *
     * @param      string  $query  The query
     *
     * @return     string  The sql.
     *
     * @author     assad
     * @since      2019-09-16T15:22
     */
    public static function getSql($query) {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

}