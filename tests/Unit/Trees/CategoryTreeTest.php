<?php

namespace Tests\Unit\Trees;

use App\Trees\CategoryTree;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTreeTest extends TestCase
{
    use RefreshDatabase;

    protected $categories = [
        [
            'name' => 'Hardware',
            'children' => [
                [
                    'name' => 'Computers',
                    'children' => [
                        ['name' => 'Apple'],
                        ['name' => 'Dell'],
                        ['name' => 'Lenovo'],
                    ],
                ],
                ['name' => 'Monitors'],
            ],
        ],
        [
            'name' => 'Software',
        ],
        [
            'name' => 'Services',
            'children' => [
                ['name' => 'Maintenance'],
            ],
        ],
    ];

    protected $categoryTree;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoryTree = CategoryTree::create($this->categories);
    }

    /**
     * @test
     */
    public function it_can_create_categories_tree()
    {
        $this->assertCount(9, Category::all());
    }

    /**
     * @test
     */
    public function it_can_get_categories_as_tree()
    {
        $this->assertCount(3, $this->categoryTree->get());
    }

    /**
     * @test
     */
    public function it_can_find_node_by_key()
    {
        $node = $this->categoryTree->find('name', 'Computers');

        tap(collect($node['children']), function ($children) {
            $this->assertCount(3, $children);
            $this->assertCount(0, array_diff(['Apple', 'Dell', 'Lenovo'], $children->pluck('name')->toArray()));
        });
    }

    /**
     * @test
     */
    public function it_return_null_if_node_does_not_exist()
    {
        $node = $this->categoryTree->find('name', 'NON-EXISTING');

        $this->assertNull($node);
    }

    /**
     * @test
     * @dataProvider pairsDataProvider
     */
    public function it_can_check_if_node_is_descendent_of_root($nodeName, $rootName, $isDescendent)
    {
        $node = $this->categoryTree->find('name', $nodeName);
        $root = $this->categoryTree->find('name', $rootName);

        $this->assertEquals($isDescendent, $this->categoryTree->isDescendent($node['id'], $root['id']));
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

        $this->categoryTree->iterate(function ($item) use (&$data) {
            $data[] = $item['name'];
        });

        $this->assertCount(9, $data);

        $this->assertCount(0, array_diff($data, [
            'Hardware', 'Computers', 'Apple', 'Dell', 'Lenovo', 'Monitors', 'Software', 'Services', 'Maintenance',
        ]));
    }
}
