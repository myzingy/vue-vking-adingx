export default {
    title: {
        text: 'Ticket 结案率'
    },
    tooltip: {},
    legend: {
        data:['vking','rose','lee']
    },
    radar: {
        indicator: [
            {name: '3天结案率', max: 100},
            {name: '7天结案率', max: 100},
            {name: '15天结案率', max: 100},
            {name: '自动结案率', max: 100},
        ]
    },
    series: [{
            name: 'Ticket 结案率',
            type: 'radar',
            //data : [{value: scores.map(({value}) => value)}]
            data:[
                {
                    name: 'rose',
                    value:[10.00,20,40,60,70]
                },{
                    name: 'lee',
                    value:[30,40,60,80,90]
                },{
                    name: 'vking',
                    value:[3,7,20,40,90]
                }
            ]
        }],
    init:function(data){
        console.log('ticket_closed',data)
        this.legend.data=data.legend;
        this.series[0].data=data.series;
    }
}