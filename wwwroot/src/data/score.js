import vk from '../vk'
export default {
    kpi:{
        ca:'Knowledge',
        cb:'Friendliness',
        cc:'Responsiveness',
        cd:'Overall'
    },
    title: {
        text: '客户反馈得分'
    },
    tooltip: {},
    legend: {
        data: ['vking', 'rose']
    },
    radar: {
        // shape: 'circle',
        indicator: [
           { name: 'this.kpi.ca', max: 5},
           { name: 'this.kpi.cb', max: 5},
           { name: 'this.kpi.cc', max: 5},
           { name: 'this.kpi.cd', max: 5}
        ]
    },
    series: [{
        name: '客户反馈得分',
        type: 'radar',
        // areaStyle: {normal: {}},
        data : [
            {
                value : [3, 3, 3, 3],
                name : 'vking'
            },
             {
                value : [4, 5, 2, 1, 0, 0],
                name : 'rose'
            }
        ]
    }],
    init:function(data=""){
        this.radar={indicator: [
            { name: this.kpi.ca, max: 5},
            { name: this.kpi.cb, max: 5},
            { name: this.kpi.cc, max: 5},
            { name: this.kpi.cd, max: 5}
            ]
        }
        if(data){
            console.log('this.series.data',data);
            this.series[0].data=data;
            this.legend.data=vk.getArrObj2Arr(data,'name');
        }
    }
};