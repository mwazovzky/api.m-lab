<?php

namespace App\Trees;

class Tree
{
    /** @var array $tree */
    protected $tree;

    public function __construct(array $nodes)
    {
        $this->tree = $this->buildTree($nodes);
    }

    protected function buildTree(array $nodes, $parentId = null)
    {
        $branch = [];

        foreach ($nodes as $node) {
            if ($node['parent_id'] == $parentId) {
                $children = $this->buildTree($nodes, $node['id']);
                $node['children'] = $children;
                $branch[] = $node;
            }
        }

        return $branch;
    }

    public function get(): array
    {
        return $this->tree;
    }

    public function find(string $key, $value)
    {
        return $this->findNode($this->tree, $key, $value);
    }

    protected function findNode(array $tree, string $key, $value): ?array
    {
        foreach ($tree as $node) {
            if ($node[$key] === $value) {
                return $node;
            }

            if (isset($node['children']) && $match = $this->findNode($node['children'], $key, $value)) {
                return $match;
            }
        }

        return null;
    }

    public function isDescendent(int $nodeId, int $rootId): bool
    {
        $root = $this->find('id', $rootId);

        $res = $this->findNode($root['children'] ?? [], 'id', $nodeId);

        return $res !== null;
    }

    public function iterate(callable $callback)
    {
        $this->iterateNodes($this->tree, $callback, $depth = 0);
    }

    public function iterateNodes(array $nodes, callable $callback, int $depth)
    {
        foreach ($nodes as $node) {
            $callback($node, $depth);

            if (isset($node['children'])) {
                $this->iterateNodes($node['children'], $callback, $depth + 1);
            }
        }
    }
}
