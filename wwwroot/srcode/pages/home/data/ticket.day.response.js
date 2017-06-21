export default {
    title: {
        text: '每日花费'
    },
    tooltip: {},
    legend: {
        data:['vking','rose','lee']
    },
    xAxis: {
        data: ["4.20","4.21","4.22","4.23","4.24","4.25"]
    },
    yAxis: {
        axisLabel: {show: true}
    },
    series: [{
        name: 'vking',
        //type: 'bar',
        type: 'line',
        data: [5, 20, 36, 10, 10, 20]
    },{
        name: 'rose',
        //type: 'bar',
        type: 'line',
        data: [12, 34, 33, 34, 23, 56]
    },{
        name: 'lee',
        //type: 'bar',
        type: 'line',
        data: [67, 6, 23, 44, 89, 67]
    }],
    init:function(data){
        console.log('ticket_day_response',data)
        this.legend.data=data.legend;
        this.xAxis.data=data.xAxis;
        this.series=data.series;
    }
};