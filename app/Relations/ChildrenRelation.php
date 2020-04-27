<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class ChildrenRelation extends Relation
{
    /** @var Illuminate\Database\Eloquent\Builder */
    protected $query;

    /** @var App\Models\Model */
    protected $parent;

    public function __construct(Model $parent)
    {
        parent::__construct(get_class($parent)::query(), $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        $this->query->where('parent_id', $this->parent->id);
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param array $models
     *
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        $this->query->whereIn('parent_id', collect($models)->pluck('id'));
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param array $models
     * @param string $relation
     *
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation(
                $relation,
                $this->related->newCollection()
            );
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param array $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string $relation
     *
     * @return array
     */
    public function match(array $parents, Collection $children, $relation)
    {
        if ($children->isEmpty()) {
            return $parents;
        }

        foreach ($parents as $parent) {
            $parent->setRelation(
                $relation,
                $children->filter(function ($child) use ($parent) {
                    return $child->parent_id == $parent->id;
                })
            );
        }

        return $parents;
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->query->get();
    }
}
