<?php

namespace Tests\Unit\Trees;

use App\Trees\Tree;
use Tests\TestCase;

class TreeTest extends TestCase
{
    protected $categories = [
        [
            'id' => 1,
            'parent_id' => null,
            'name' => 'Hardware',
        ],
        [
            'id' => 2,
            'parent_id' => 1,
            'name' => 'Computers',
        ],
        [
            'id' => 3,
            'parent_id' => 2,
            'name' => 'Apple',
        ],
        [
            'id' => 4,
            'parent_id' => 2,
            'name' => 'Dell',
        ],
        [
            'id' => 5,
            'parent_id' => 2,
            'name' => 'Lenovo',
        ],
        [
            'id' => 6,
            'parent_id' => 1,
            'name' => 'Monitors',
        ],
        [
            'id' => 7,
            'parent_id' => null,
            'name' => 'Software',
        ],
        [
            'id' => 8,
            'parent_id' => null,
            'name' => 'Services',
        ],
        [
            'id' => 9,
            'parent_id' => 8,
            'name' => 'Maintenance'
        ],
    ];

    protected $tree;

    public function setUp(): void
    {
        $this->tree = new Tree($this->categories);
    }

    /**
     * @test
     */
    public function it_can_create_tree()
    {
        $this->assertCount(3, $this->tree->get());
    }

    /**
     * @test
     */
    public function it_can_find_tree_node_by_key()
    {
        $node = $this->tree->find('name', 'Computers');

        $this->assertEquals(2, $node['id']);

        tap(collect($node['children']), function ($children) {
            $this->assertCount(3, $children);
            $this->assertCount(0, array_diff(['Apple', 'Dell', 'Lenovo'], $children->pluck('name')->toArray()));
        });
    }

    /**
     * @test
     */
    public function it_return_null_if_tree_node_does_not_exist()
    {
        $node = $this->tree->find('name', 'NON-EXISTING');

        $this->assertNull($node);
    }

    /**
     * @test
     * @dataProvider pairsDataProvider
     */
    public function it_can_check_if_tree_node_is_descendent_of_root($nodeName, $rootName, $isDescendent)
    {
        $node = $this->tree->find('name', $nodeName);
        $root = $this->tree->find('name', $rootName);

        $this->assertEquals($isDescendent, $this->tree->isDescendent($node['id'], $root['id']));
    }

    public function pairsDataProvider()
    {
        return [
            ['Computers', 'Hardware', true],     // root child
            ['Apple', 'Hardware', true],         // root grand child
            ['Apple', 'Computers', true],        // non-root child
            ['Services', 'Computers', false],    // other root
            ['Maintenance', 'Computers', false], // other root child
        ];
    }

    /**
     * @test
     */
    public function it_may_itereate_over_tree()
    {
        $data = [];

        $this->tree->iterate(function ($item) use (&$data) {
            $data[] = $item['name'];
        });

        $this->assertCount(9, $data);

        $this->assertCount(0, array_diff($data, [
            'Hardware', 'Computers', 'Apple', 'Dell', 'Lenovo', 'Monitors', 'Software', 'Services', 'Maintenance',
        ]));
    }
}
