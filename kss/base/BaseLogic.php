<?php


namespace kss\base;


use kss\traits\CacheTrait;
use kss\traits\ErrorTrait;
use kss\traits\LogicTrait;

use think\facade\Db;
use think\helper\Str;

class BaseLogic
{
    use LogicTrait;
    use ErrorTrait;
    use CacheTrait;


    /**
     * 获取当前模型
     * @return string
     */
    protected function setModel()
    {
        return '';
    }

    /**
     * 读取数据条数
     * @param array $where
     * @return int
     */
    public function count(array $where = []): int
    {
        return $this->getModel()->where($where)->count();
    }


    /**
     * 获取某些条件总数
     * @param array $where
     * @return int
     */
    public function getCount(array $where)
    {
        return $this->getModel()->where($where)->count();
    }

    /**
     * 获取某些条件去重总数
     * @param array $where
     * @param $field
     * @param bool $search
     * @return int|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDistinctCount(array $where, $field, $search = true)
    {
        if ($search) {
            return $this->search($where)->field('COUNT(distinct(' . $field . ')) as count')->select()->toArray()[0]['count'] ?? 0;
        } else {
            return $this->getModel()->where($where)->field('COUNT(distinct(' . $field . ')) as count')->select()->toArray()[0]['count'] ?? 0;
        }
    }

    /**
     * 获取模型
     * @return BaseModel
     */
    protected function getModel()
    {
        return app()->make($this->setModel());
    }

    /**
     * 获取主键
     * @return mixed
     */
    protected function getPk()
    {
        return $this->getModel()->getPk();
    }

    /**
     * 获取一条数据
     * @param int|array $id
     * @param array|null $field
     * @param array|null $with
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get($id, ?array $field = [], ?array $with = [])
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [$this->getPk() => $id];
        }
        return $this->getModel()::where($where)->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->field($field ?? ['*'])->find();
    }

    /**
     * 查询一条数据是否存在
     * @param $map
     * @param string $field
     * @return bool 是否存在
     */
    public function be($map, string $field = '')
    {
        if (!is_array($map) && empty($field)) $field = $this->getPk();
        $map = !is_array($map) ? [$field => $map] : $map;
        return 0 < $this->getModel()->where($map)->count();
    }

    /**
     * 根据条件获取一条数据
     * @param array|int $where
     * @param string|null $field
     * @param array $with
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOne($where, ?string $field = '*', array $with = [])
    {
        $field = explode(',', $field);

        return $this->get($where, $field, $with);
    }

    /**
     * 获取单个字段值
     * @param array $where
     * @param string|null $field
     * @return mixed
     */
    public function value(array $where, ?string $field = '')
    {
        $pk = $this->getPk();
        return $this->getModel()::where($where)->value($field ?: $pk);
    }

    /**
     * 删除
     * @param int|string|array $id
     * @return mixed
     */
    public function delete($id, ?string $key = null)
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->getModel()::where($where)->delete();
    }

    /**
     * 获取某个字段数组
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */
    public function getColumn(array $where, string $field = '*', string $key = '')
    {
        return $this->getModel()->where($where)->column($field, $key);
    }

    /**
     * 更新数据
     * @param int|string|array $id
     * @param array $data
     * @param string|null $key
     * @return mixed
     */
    public function update($id, array $data, ?string $key = null)
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->getModel()::update($data, $where);
    }

    /**
     * 批量更新数据
     * @param array $ids
     * @param array $data
     * @param string|null $key
     * @return BaseModel
     */
    public function batchUpdate(array $ids, array $data, ?string $key = null)
    {
        return $this->getModel()::whereIn(is_null($key) ? $this->getPk() : $key, $ids)->update($data);
    }

    /**
     * 插入数据
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        return $this->getModel()::create($data);
    }

    /**
     * 插入数据
     * @param array $data
     * @return mixed
     */
    public function saveAll(array $data)
    {
        return $this->getModel()->saveAll($data);
    }


    /**
     * 获取某个字段内的值
     * @param $value
     * @param string $filed
     * @param string $valueKey
     * @param array|string[] $where
     * @return mixed
     */
    public function getFieldValue($value, string $filed, ?string $valueKey = '', ?array $where = [])
    {
        return $this->getModel()->getFieldValue($value, $filed, $valueKey, $where);
    }

    /**
     * 获取搜索器和搜索条件key
     * @param array $withSearch
     * @return array[]
     * @throws \ReflectionException
     */
    private function getSearchData(array $withSearch)
    {
        $with = [];
        $whereKey = [];
        $respones = new \ReflectionClass($this->setModel());
        foreach ($withSearch as $fieldName) {
            $method = 'search' . Str::studly($fieldName) . 'Attr';
            if ($respones->hasMethod($method)) {
                $with[] = $fieldName;
            } else {
                $whereKey[] = $fieldName;
            }
        }
        return [$with, $whereKey];
    }

    /**
     * 根据搜索器获取搜索内容
     * @param array $withSearch
     * @param array|null $data
     * @return Model
     */
    protected function withSearchSelect(array $withSearch, ?array $data = [])
    {

        [$with] = $this->getSearchData($withSearch);
        return $this->getModel()->withSearch($with, $data);
    }

    /**
     * 搜索
     * @param array $where
     * @return BaseModel|mixed
     */
    protected function search(array $where = [])
    {
        if ($where) {
            return $this->withSearchSelect(array_keys($where), $where);
        } else {
            return $this->getModel();
        }
    }

    /**
     * 求和
     * @param array $where
     * @param string $field
     * @param bool $search
     * @return float
     */
    public function sum(array $where, string $field, bool $search = false)
    {
        if ($search) {
            return $this->search($where)->sum($field);
        } else {
            return $this->getModel()::where($where)->sum($field);
        }
    }

    /**
     * 高精度加法
     * @param int|string $key
     * @param string $incField
     * @param string $inc
     * @param string|null $keyField
     * @param int $acc
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function bcInc($key, string $incField, string $inc, string $keyField = null, int $acc = 2)
    {
        return $this->bc($key, $incField, $inc, $keyField, 1);
    }

    /**
     * 高精度 减法
     * @param $key
     * @param string $decField 相减的字段
     * @param string $dec 减的值
     * @param string $keyField id的字段
     * @param int $acc 精度
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function bcDec($key, string $decField, string $dec, string $keyField = null, int $acc = 2)
    {
        return $this->bc($key, $decField, $dec, $keyField, 2);
    }

    /**
     * 高精度计算并保存
     * @param $key
     * @param string $incField
     * @param string $inc
     * @param string|null $keyField
     * @param int $type
     * @param int $acc
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function bc($key, string $incField, string $inc, string $keyField = null, int $type = 1, int $acc = 2)
    {
        if ($keyField === null) {
            $result = $this->get($key);
        } else {
            $result = $this->getOne([$keyField => $key]);
        }
        if (!$result) return false;
        $new = 0;
        if ($type === 1) {
            $new = bcadd($result[$incField], $inc, $acc);
        } else if ($type === 2) {
            if ($result[$incField] < $inc) return false;
            $new = bcsub($result[$incField], $inc, $acc);
        }
        $result->{$incField} = $new;
        return false !== $result->save();
    }


    /**
     * 获取分页配置
     * @return array
     */
    public function getPageConfig($isPage = true, $isMax = true, $limitMax = 100)
    {
        $page = $limit = 0;
        if ($isPage) {
            $page = app()->request->param('page' . '/d', 1);
            $limit = app()->request->param('limit' . '/d', 10);
            if ($page == 0) {
                $page = 1;
            }
        }
        if ($limit > $limitMax && $isMax) {
            $limit = $limitMax;
        } else {
            !$limit && $limit = 10;
        }

        return [(int)$page, (int)$limit];
    }


    /**
     * 通用列表
     * @param array $where
     * @param string $field
     * @param string $order
     * @param string $group
     * @param bool $isPage
     * @param bool $isCount
     * @param array $withoutField
     * @param array $hiddenField
     * @return array|BaseModel[]|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($where = [], $field = '*', $order = '', $group = '', $isPage = true, $isCount = true, $withoutField = [], $hiddenField = [], $pageLimit = 0)
    {
        !$order && $order = 'id desc';
        if ($isPage) {
            [$page, $limit] = $this->getPageConfig();


            if ($pageLimit) {
                $limit = $pageLimit;
            }

            if ($withoutField) {
                $list = $this->getModel()->where($where)->withoutField($withoutField)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
            } else {

//                clog($this->getModel()->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->buildSql(), '查询语句');
                $list = $this->getModel()->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
            }
        } else {
            if ($withoutField) {
                $list = $this->getModel()->withoutField($withoutField);
            } else {
                $list = $this->getModel()->field($field);
            }
            if (is_numeric($isCount) && $isCount) {
                $list = $list->limit($isCount);
            }
//            clog($list->where($where)->order($order)->hidden($hiddenField)->group($group)->buildSql(), '查询语句');
            $list = $list->where($where)->order($order)->hidden($hiddenField)->group($group)->select();
        }

        if ($isCount === true) {
//            clog($this->getModel()->where($where)->buildSql(), '查询语句');

            $total = $this->getModel()->where($where)->count();
            return ['list' => $list, 'total' => $total];
        }

        return $list;
    }

    /**
     * 通用关联列表
     * @param $with
     * @param $where
     * @param $field
     * @param $order
     * @param $group
     * @param $isPage
     * @param $isCount
     * @param $withoutField
     * @param array $hiddenField
     * @return array
     */
    public function getWithList($with = [], $where = [], $field = '*', $order = 'id desc', $group = '', $isPage = true, $isCount = true, $withoutField = [], array $hiddenField = [], $pageLimit = 0)
    {
        if ($isPage) {
            [$page, $limit] = $this->getPageConfig(true, true, is_numeric($isCount) ? $isCount : 100);
            if ($pageLimit) {
                $limit = $pageLimit;
            }

            if ($withoutField) {
                $list = $this->getModel()->with($with)->where($where)->withoutField($withoutField)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
            } else {
                $list = $this->getModel()->with($with)->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();

//                clog($this->getModel()->with($with)->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->buildSql(), '查询语句');
            }
        } else {


            if ($withoutField) {
                $list = $this->getModel()->with($with)->withoutField($withoutField);
            } else {
                $list = $this->getModel()->with($with)->field($field);
            }
            if (is_numeric($isCount) && $isCount) {
                $list = $list->limit($isCount);
            }

            $list = $list->where($where)->order($order)->hidden($hiddenField)->group($group)->select();
        }

        if ($isCount === true) {
            $total = $this->getModel()->with($with)->where($where)->count();
            return ['list' => $list, 'total' => $total??0];
        }
        return $list;

    }

//    /**
//     * 通用Join关联列表
//     * @param array $with
//     * @param array $where
//     * @param string $field
//     * @param string $order
//     * @param string $group
//     * @param bool $isPage
//     * @param bool $isCount
//     * @param array $withoutField
//     * @param array $hiddenField
//     * @return array|BaseModel[]|\think\Collection
//     * @throws \think\db\exception\DataNotFoundException
//     * @throws \think\db\exception\DbException
//     * @throws \think\db\exception\ModelNotFoundException
//     */
//    public function getJoinWithList($with = [], $where = [], $field = '*', $order = 'id desc', $group = '', $isPage = true, $isCount = true, $withoutField = [], array $hiddenField = [])
//    {
//
//        if ($isPage) {
//            [$page, $limit] = $this->getPageConfig();
//            if ($withoutField) {
//                $list = $this->getModel()->withJoin($with)->where($where)->withoutField($withoutField)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
//            } else {
//                $list = $this->getModel()->withJoin($with)->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
//            }
//        } else {
//            if ($withoutField) {
//                $list = $this->getModel()->withJoin($with)->where($where)->withoutField($withoutField)->order($order)->hidden($hiddenField)->group($group)->select();
//            } else {
////                $list = $this->getModel()->withJoin($with)->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->select();
//                $list = $this->getModel()->withJoin($with,'LEFT')->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->buildSql();
//            }
//        }
//
//        if ($isCount) {
//            $total = $this->getModel()->withJoin($with)->where($where)->count();
//            return ['list' => $list, 'total' => $total];
//        }
//        return $list;
//
//    }


    /**
     * 下拉选择
     */
    public function getSelectList($where = [], $field = 'name label,id value', $order = 'id desc', $group = '', $isPage = false, $isCount = false)
    {
        if ($isPage) {
            [$page, $limit] = $this->getPageConfig();
            $list = $this->getModel()->where($where)->order($order)->field($field)->group($group)->page($page)->limit($limit)->select();
        } else {
            $list = $this->getModel()->where($where)->order($order)->field($field)->group($group)->select();
        }

        if ($isCount) {
            $total = $this->getModel()->where($where)->count();
            return ['list' => $list, 'total' => $total];
        } else {
            return $list->toArray();
        }

    }

    /**
     * 获取树形列表
     */
    public function getIndexTree($where = [], $field = 'id,name,pid', $order = 'id', $keyName = 'id', $pidName = 'pid')
    {
        $list = $this->getModel()->where($where)->order($order)->field($field)->select()->toArray();
        return get_tree_children($list ?? [], 'children', $keyName, $pidName);
    }


    /**
     * 数据库事务操作
     * @param callable $closure
     * @return mixed
     */
    public function transaction(callable $closure, bool $isTran = true)
    {
        return $isTran ? Db::transaction($closure) : $closure();
    }


    /**
     * 通用关联列表
     * @param array $with
     * @param array $where
     * @param string $field
     * @param string $order
     * @param string $group
     * @param bool $isPage
     * @param bool $isCount
     * @param array $withoutField
     * @param array $hiddenField
     * @return array|BaseModel[]|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getJoinList($with, $where = [], $field = '*', $order = 'a.id desc', $group = '', $isPage = true, $isCount = true, $withoutField = [], array $hiddenField = [])
    {
        $model = $this->getJoinModel($with[0], $with[1], $with[2], $with[3] ?? 'id', $with[4] ?? 'a', $with[5] ?? 'b', $with[6] ?? 'INNER');
        if ($isPage) {
            [$page, $limit] = $this->getPageConfig();
            if ($withoutField) {
                $list = $model->where($where)->withoutField($withoutField)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
            } else {
//                $sql = $model->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->buildSql();
//                clog($model->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->buildSql(),'查询语句');
                $list = $model->where($where)->field($field)->order($order)->hidden($hiddenField)->group($group)->page($page)->limit($limit)->select();
            }
        } else {

            if ($withoutField) {
                $list = $model->withoutField($withoutField);
            } else {
                $list = $model->field($field);
            }
            if (is_numeric($isCount) && $isCount) {
                $list = $list->limit($isCount);
            }
//            $sql = $model->where($where)->order($order)->buildSql();

//            dump($sql);
            $list = $list->where($where)->order($order)->hidden($hiddenField)->group($group)->select();
        }

//        dump($sql);
        if ($isCount === true) {
            $total = $model->where($where)->count();
            return ['list' => $list, 'total' => $total
//                , 'sql' => $sql ?? ''
            ];
        }
        return $list;

    }

    /**
     * 获取关联模型进行查询
     */
    protected function getJoinModel($model, $joinModel, $fk, string $lk = 'id', string $alias = 'a', string $joinAlias = 'b', string $join = 'INNER')
    {
        $table = $joinModel->getName();

        if (is_array($fk)) {
            $condition = $fk[0];
        } else {
            $condition = "$alias.$fk = $joinAlias.$lk";
        }
        return $model->alias($alias)->join("$table $joinAlias", $condition, $join);
    }
}