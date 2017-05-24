function getCurrentDate(){ 
        return new Date(); 
    }; 
    function getPriorMonthFirstDay(year,month){ 
        //年份为0代表,是本年的第一月,所以不能减  
        if(month==0){ 
            month=11;//月份为上年的最后月份  
            year--;//年份减1  
            return new Date(year,month,1); 
        } 
        //否则,只减去月份  
        month--; 
        return new Date(year,month,1);; 
    }; 
    function getMonthDays(year,month){ 
        //本月第一天 1-31  
        var relativeDate=new Date(year,month,1); 
        //获得当前月份0-11  
        var relativeMonth=relativeDate.getMonth(); 
        //获得当前年份4位年  
        var relativeYear=relativeDate.getFullYear(); 
         
        //当为12月的时候年份需要加1  
        //月份需要更新为0 也就是下一年的第一个月  
        if(relativeMonth==11){ 
            relativeYear++; 
            relativeMonth=0; 
        }else{ 
            //否则只是月份增加,以便求的下一月的第一天  
            relativeMonth++; 
        } 
        //一天的毫秒数  
        var millisecond=1000*60*60*24; 
        //下月的第一天  
        var nextMonthDayOne=new Date(relativeYear,relativeMonth,1); 
        //返回得到上月的最后一天,也就是本月总天数  
        return new Date(nextMonthDayOne.getTime()-millisecond).getDate(); 
    }; 
function getPreviousWeek(){ 
        //起止日期数组  
        var startStop={}; 
        //获取当前时间  
        var currentDate=getCurrentDate(); 
        //返回date是一周中的某一天  
        var week=currentDate.getDay(); 
        //返回date是一个月中的某一天  
        var month=currentDate.getDate(); 
        //一天的毫秒数  
        var millisecond=1000*60*60*24; 
        //减去的天数  
        var minusDay=week!=0?week-1:6; 
        //获得当前周的第一天  
        var currentWeekDayOne=new Date(currentDate.getTime()-(millisecond*minusDay)); 
        //上周最后一天即本周开始的前一天  
        var priorWeekLastDay=new Date(currentWeekDayOne.getTime()-millisecond); 
        //上周的第一天  
        var priorWeekFirstDay=new Date(priorWeekLastDay.getTime()-(millisecond*6)); 
        //priorWeekFirstDay = priorWeekFirstDay.getYear()+"-"+(priorWeekFirstDay.getMonth()+1)+"-"+priorWeekFirstDay.getDate();
        //priorWeekLastDay = priorWeekLastDay.getYear()+"-"+(priorWeekLastDay.getMonth()+1)+"-"+priorWeekLastDay.getDate();
        //添加至数组  
        startStop={start:priorWeekFirstDay,end:priorWeekLastDay}; 
        return startStop; 
    };
    function getPreviousMonth(){ 
        //起止日期数组  
        var startStop={}; 
        //获取当前时间  
        var currentDate=getCurrentDate(); 
        //获得当前月份0-11  
        var currentMonth=currentDate.getMonth(); 
        //获得当前年份4位年  
        var currentYear=currentDate.getFullYear(); 
        //获得上一个月的第一天  
        var priorMonthFirstDay=getPriorMonthFirstDay(currentYear,currentMonth); 
        //获得上一月的最后一天  
        var priorMonthLastDay=new Date(priorMonthFirstDay.getFullYear(),priorMonthFirstDay.getMonth(),getMonthDays(priorMonthFirstDay.getFullYear(), priorMonthFirstDay.getMonth())); 
        //添加至数组  
        //priorMonthFirstDay = priorMonthFirstDay.getYear()+"-"+(priorMonthFirstDay.getMonth()+1)+"-"+priorMonthFirstDay.getDate();
        //priorMonthLastDay = priorMonthLastDay.getYear()+"-"+(priorMonthLastDay.getMonth()+1)+"-"+priorMonthLastDay.getDate();
        startStop={start:priorMonthFirstDay,end:priorMonthLastDay}; 
        //返回  
        return startStop; 
    }; 
export default {
    shortcuts: [
        {
            text: '最近一周',
            onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: '最近一个月',
            onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: '最近三个月',
            onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: '上周',
            onClick(picker) {
                var tn=getPreviousWeek();
                picker.$emit('pick', [tn.start, tn.end]);
            }
        }, {
            text: '上个月',
            onClick(picker) {
                 var tn=getPreviousMonth();
                picker.$emit('pick', [tn.start, tn.end]);
            }
        }
    ]
};