<?php

use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use App\Models\Tag;

new class extends Component {
    public $player;
    public $rootNodeIds;
    public $rootNode;
    public $selectedTree;
    public $selectedRootNodeIndex;
    public $currentlySelectedNode;
    public $currentlySelectedNodeId;

    public function mount()
    {
        $this->player = auth()->user()->player;
        $rootNodes = $this->player->topMostAdminTags;
        $this->rootNodeIds = array_map(fn($node) => $node->id, $rootNodes);
        if (empty($this->rootNodeIds)) {
            return;
        }
        $this->selectedRootNodeIndex = 0;
        $this->rootNode = Tag::find($this->rootNodeIds[0]);
        $this->selectedTree = $this->rootNode->getNodeWithChildren();
        $this->currentlySelectedNode = $this->rootNode;
        $this->currentlySelectedNodeId = $this->rootNode->id;
    }

    public function updatedSelectedRootNodeIndex()
    {
        $rootNodeId = $this->rootNodeIds[$this->selectedRootNodeIndex];
        $this->rootNode = Tag::find($rootNodeId);
        $this->selectedTree = $this->rootNode->getNodeWithChildren();
        $this->currentlySelectedNode = $this->rootNode;
        $this->currentlySelectedNodeId = $this->rootNode->id;
        $this->dispatch('selectedRootNodeChanged', tree: $this->selectedTree, currentlySelectedNodeId: $this->currentlySelectedNodeId);
    }

    #[On('node-selected')]
    public function handleSelectedNode($nodeId)
    {
        $node = Tag::find($nodeId);
        $this->currentlySelectedNode = $node;
        $this->currentlySelectedNodeId = $nodeId;
        $this->dispatch('selectedRootNodeChanged', tree: $this->selectedTree, currentlySelectedNodeId: $nodeId);
    }

    #[On('node-updated')]
    public function handleNodeUpdated($nodeId)
    {
        $node = Tag::find($nodeId);
        $this->currentlySelectedNode = $node;
        $this->currentlySelectedNodeId = $nodeId;
        $this->selectedTree = Tag::find($this->rootNodeIds[$this->selectedRootNodeIndex])->getNodeWithChildren();
        $this->dispatch('selectedRootNodeChanged', tree: $this->selectedTree, currentlySelectedNodeId: $nodeId);
    }
}; ?>

<div class="p-4">
    @if (empty($rootNodeIds))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">No tags found!</strong>
            <span class="block sm:inline">You do not have any tags to manage.</span>
        </div>
    @else
        <div class="flex justify-center">
            <select wire:model.live="selectedRootNodeIndex" class="p-2 border border-gray-300 rounded-lg text-gray-700">
                @foreach ($rootNodeIds as $index => $rootNodeId)
                    <option value="{{ $index }}">{{ Tag::find($rootNodeId)->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4">
            <div wire:ignore id="tree-container" style="width: 100%; height: 100%; min-height: 80vh;"
                class="order-2 lg:order-1 lg:col-span-2 bg-gradient-to-br {{ \App\Utils\UiUtils::getGradientClasses('neutral') }} rounded-xl shadow-2xl p-8">
            </div>
            <div class="order-1 lg:order-2 col-span-1">
                <livewire:nodeeditor :currentlySelectedNode="$currentlySelectedNode" />
            </div>
        </div>


        @script
            <script>
                let getScopeIcon = function(scope) {
                    switch (scope) {
                        case 'public_use':
                            return '🌐';
                        case 'public_viewing':
                            return '👁️';
                        case 'private':
                            return '🔒';
                        default:
                            return '❓';
                    }
                }

                document.addEventListener('livewire:init', () => {
                    let getGraphOptions = function(tagHierarchy, selectedNodeId) {
                        function findNodeAndStyleIt(node) {
                            if (node.id === selectedNodeId) {
                                node.itemStyle = {
                                    color: '#f00'
                                };
                            }
                            //node.label = {
                            //    color: '#fff',
                            //    backgroundColor: '#222',
                            //    borderColor: node.color || '#333',
                            //    borderWidth: node.id === selectedNodeId ? 4 : 1,
                            //    borderRadius: 4,
                            //    padding: [4, 8],
                            //    borderRadius: 4
                            //};
                            console.log(node.icon_url != "");
                            const formatterList = [
                                '{title|' + node.name + getScopeIcon(node.scope) + '}{abg|}',
                                node.icon_url != "" ? '{icon|}' : '',
                            ].join('\n')
                            node.label = {
                                formatter: formatterList,
                                backgroundColor: '#eee',
                                borderColor: node.color || '#333',
                                borderWidth: node.id === selectedNodeId ? 4 : 1,
                                borderRadius: 4,
                                z: 10,
                                rich: {
                                    title: {
                                        color: '#eee',
                                        padding: [0, 10],
                                        align: 'center'
                                    },
                                    abg: {
                                        backgroundColor: '#333',
                                        width: '100%',
                                        align: 'right',
                                        height: 25,
                                        borderRadius: [4, 4, 0, 0]
                                    },
                                    icon: {
                                        height: 100,
                                        align: 'center',
                                        backgroundColor: {
                                            image: node.icon_url,
                                        }
                                    },
                                    name: {
                                        color: '#333',
                                        padding: [0, 10, 0, 10],
                                        align: 'left'
                                    },

                                    hr: {
                                        borderColor: '#777',
                                        width: '100%',
                                        borderWidth: 0.5,
                                        height: 0
                                    }
                                }
                            };
                            if (node.children) {
                                node.children.forEach(findNodeAndStyleIt);
                            }
                        }
                        findNodeAndStyleIt(tagHierarchy);
                        return {
                            tooltip: {
                                trigger: 'item',
                                triggerOn: 'mousemove',
                                formatter: function(params) {
                                    var data = params.data;
                                    var tooltip = '<b>' + data.name + '</b><br/>';
                                    tooltip += 'Description: ' + data.description + '<br/>';

                                    return tooltip;
                                }
                            },
                            series: [{
                                type: 'tree',
                                roam: true,
                                data: [tagHierarchy],
                                top: '5%',
                                left: '5%',
                                bottom: '5%',
                                right: '5%',
                                symbol: 'roundRect',
                                symbolSize: 10,
                                orient: 'vertical',
                                expandAndCollapse: false,
                                label: {
                                    position: 'inside',
                                    rotate: 0,
                                    verticalAlign: 'middle',
                                    align: 'center',
                                    fontSize: 12,
                                    fontWeight: 'bold'
                                },
                                leaves: {
                                    label: {
                                        position: 'inside',
                                        rotate: 0,
                                        verticalAlign: 'middle',
                                        align: 'center'
                                    }
                                },
                                layout: 'orthogonal',
                                animationDurationUpdate: 500,
                                emphasis: {
                                    focus: 'descendant',
                                    itemStyle: {
                                        color: '#c9f'
                                    }
                                }
                            }]
                        };
                    }
                    let chartDom = document.getElementById('tree-container');
                    let myChart = echarts.init(chartDom);
                    let currentlySelectedNodeId = @json($currentlySelectedNodeId);
                    myChart.setOption(getGraphOptions(@json($selectedTree), currentlySelectedNodeId));
                    $wire.on('selectedRootNodeChanged', ({
                        tree,
                        currentlySelectedNodeId
                    }) => {
                        myChart.setOption(getGraphOptions(tree, currentlySelectedNodeId));
                    });

                    window.addEventListener('resize', function() {
                        myChart.resize();
                    });
                    myChart.on('click', 'series.tree', e => {
                        let node = e.data;
                        $wire.dispatch('node-selected', {
                            nodeId: node.id
                        });
                    });
                });
            </script>
        @endscript
    @endif
</div>
