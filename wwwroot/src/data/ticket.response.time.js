export default {
  title: {
    text: 'Ticket 响应时长'
  },
  tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    legend: {
        data:['最大时长','最小时长','平均时长']
    },
    xAxis: [
        {
            type: 'category',
            data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
            axisPointer: {
                type: 'shadow'
            }
        }
    ],
    yAxis: [
        {
            type: 'value',
            name: '最大时长',
            min: 0,
            max: 240,
            interval: 24,
            axisLabel: {
                formatter: '{value} h'
            }
        },
        {
            type: 'value',
            name: '最小时长',
            min: 0,
            max: 240,
            interval: 24,
            axisLabel: {
                formatter: '{value} h'
            }
        }
    ],
    series: [
        {
            name:'最大时长',
            type:'bar',
            data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3]
        },
        {
            name:'最小时长',
            type:'bar',
            data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3]
        },
        {
            name:'平均时长',
            type:'line',
            yAxisIndex: 1,
            data:[2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4, 23.0, 16.5, 12.0, 6.2]
        }
    ],
    init:function(data){
        console.log('ticket_response_time',data)
        this.xAxis[0].data=data.xAxis;
        this.series[0].data=data.max;
        this.series[1].data=data.min;
        this.series[2].data=data.avg;
    }
}
