<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午6:06
 */

namespace app\common\repository;


use think\Model;

use app\common\exception\ResponseException;

class Base
{

    /** @var Model $model */
    protected  $model;

    /**
     * 保存单个数据
     *
     * @param array $input
     * @return mixed
     */
    public function store($input)
    {
        return $this->model->create($input);
    }

    /**
     * 保存多个数据
     *
     * @param array $input
     * @return mixed
     */
    public function storeAll($input)
    {
        return $this->model->saveAll($input);
    }

    /**
     * 根据主键删除信息
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->model = $this->getOne($id);
        if($this->model){
            return $this->model->delete();
        }
        return false;
    }


    /**
     * 根据主键更新信息
     *
     * @param $id
     * @param array $input
     * @return mixed
     */
    public function update($id, $input = [])
    {
        $this->model = $this->getOne($id);

        foreach ($input as $field => $value) {
            $this->model->$field = $value;
        }

        $this->model->save();

        return $this->getOne($id);
    }



    /**
     * 根据主键/条件获得单个信息
     *
     * @param $id
     * @return mixed
     */
    public function getOne($id)
    {
        return $this->model->get($id);
    }

    /**
     * 开始事务
     */
    public function startTrans()
    {
        $this->model->startTrans();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->model->commit();
    }

    /**
     * 回滚
     */
    public function rollback()
    {
        $this->model->rollback();
    }

    /**
     * 根据条件找到一条数据,不存在直接返回前端
     *
     * @param array $filter
     * @return mixed
     * @throws ResponseException
     */
    public function firstOrFail($filter = [])
    {
        $data = $this->model->where($filter)->find();
        if (!$data) {
            throw new ResponseException('数据不存在', -1);
        }

        return $data;
    }
}