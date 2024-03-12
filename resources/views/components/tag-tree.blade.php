<?php

use function Livewire\Volt\{mount, state, computed};
use App\Models\Tag;

state(['tree' => fn() => Tag::whereNull('parent_id')->first()->getNodeWithChildren()]);

?>
@volt
    <div class="flex justify-center p-4">
        <div id="tree-container" style="width: 600px; height: 400px;"></div>
        <script>
            var tagHierarchy = @json($tree);

            var chartDom = document.getElementById('tree-container');
            var myChart = echarts.init(chartDom);

            var option = {
                tooltip: {
                    trigger: 'item',
                    triggerOn: 'mousemove',
                    formatter: function(params) {
                        var data = params.data;
                        var tooltip = '<b>' + data.name + '</b><br/>';
                        tooltip += 'Description: ' + data.description + '<br/>';
                        tooltip += 'ID: ' + data.id + '<br/>';
                        tooltip += 'Parent ID: ' + (data.parent_id || 'null') + '<br/>';
                        tooltip += 'Created At: ' + data.created_at + '<br/>';
                        tooltip += 'Updated At: ' + data.updated_at;
                        return tooltip;
                    }
                },
                series: [{
                    type: 'tree',
                    data: [tagHierarchy],
                    top: '5%',
                    left: '15%',
                    bottom: '5%',
                    right: '15%',
                    symbol: 'roundRect',
                    symbolSize: 10,
                    orient: 'vertical',
                    expandAndCollapse: true,
                    label: {
                        position: 'top',
                        rotate: 0,
                        verticalAlign: 'middle',
                        align: 'right',
                        fontSize: 12
                    },
                    leaves: {
                        label: {
                            position: 'bottom',
                            rotate: 0,
                            verticalAlign: 'middle',
                            align: 'center'
                        }
                    },
                    animationDurationUpdate: 750,
                    emphasis: {
                        focus: 'descendant'
                    }
                }]
            };

            myChart.setOption(option);
        </script>
    </div>
@endvolt
