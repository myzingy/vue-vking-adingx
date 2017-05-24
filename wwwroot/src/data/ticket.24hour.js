export default {
    title: {
        text: 'Ticket 24小时回复率'
    },
    tooltip : {
        formatter: "{a} : {c}%"
    },
    legend: {
        data:['vking','rose','lee']
    },
    toolbox: {
        feature: {
            restore: {},
            saveAsImage: {}
        }
    },
    series: [
        {
            name: 'vking',
            type: 'gauge',
            detail: {formatter:'\n'},
            data: [{value: 50}]
        },
        {
            name: 'rose',
            type: 'gauge',
            detail: {formatter:'\n'},
            data: [{value: 50.1}]
        },
        {
            name: 'lee',
            type: 'gauge',
            detail: {formatter:'\n'},
            data: [{value: 90}]
        }
    ],
    init:function(data){
        console.log('ticket_24hour',data)
        this.legend.data=data.legend;
        this.series=data.series;
    }
};