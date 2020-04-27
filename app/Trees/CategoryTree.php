<?php

namespace App\Trees;

use App\Models\Category;

class CategoryTree
{
    /** 
     * @var App\Trees\Tree $tree 
     */
    protected $tree;

    public function __construct()
    {
        $nodes = $this->getNodes();

        $this->tree = new Tree($nodes);
    }

    protected function getNodes(): array
    {
        return Category::query()
            ->select('id', 'name', 'parent_id')
            ->orderBy('parent_id', 'DESC')
            ->get()
            ->toArray();
    }

    public static function create(array $data)
    {
        self::createCategories($data);

        return new self;
    }

    protected static function createCategories(array $data, int $parentId = null)
    {
        foreach ($data as $item) {
            $category = Category::create([
                'name' => $item['name'],
                'parent_id' => $parentId,
            ]);

            if (isset($item['children'])) {
                self::createCategories($item['children'], $category->id);
            }
        }
    }

    /**
     * Delegate to \App\Library\Tree\Tree method.
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->tree, $name], $arguments);
    }
}
