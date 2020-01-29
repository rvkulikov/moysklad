<?php

namespace MoySklad\Components\Query;

use MoySklad\Components\Expand;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Lists\EntityList;
use MoySklad\Registers\ApiUrlRegistry;
use Throwable;

/**
 *
 */
class EntityQuery extends AbstractQuery
{
    protected static $entityListClass = EntityList::class;

    /**
     * Get entity by id
     *
     * @param             $id
     * @param Expand|null $expand Deprecated, use withExpand()
     *
     * @return AbstractEntity
     * @throws Throwable
     */
    public function byId($id, Expand $expand = null)
    {
        if ($expand === null) {
            $expand = $this->expand;
        }

        $data = array_filter([
            $expand ? $expand->flatten() : [],
            $this->querySpecs->toArray()
        ]);

        $data = !empty($data)
            ? array_merge(...$data)
            : [];

        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getByIdUrl($this->entityName, $id),
            $data,
            $this->requestOptions
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }

    /**
     * Get entity by syncid
     *
     * @param             $id
     * @param Expand|null $expand Deprecated, use withExpand()
     *
     * @return AbstractEntity
     * @throws Throwable
     */
    public function bySyncId($id, Expand $expand = null)
    {
        if ($expand === null) {
            $expand = $this->expand;
        }

        $data = array_filter([
            $expand ? $expand->flatten() : [],
            $this->querySpecs->toArray()
        ]);

        $data = !empty($data)
            ? array_merge(...$data)
            : [];


        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getBySyncIdUrl($this->entityName, $id),
            $data,
            $this->requestOptions
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }
}
