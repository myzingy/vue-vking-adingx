import Vue  from 'vue'
import VueResource  from 'vue-resource'
import { Message,MessageBox,Loading,Alert } from 'element-ui';
import store from './store/'
import URI from './uri.js'
Vue.use(VueResource);
let vk={
    isProduction:function(){
        return process.env.NODE_ENV === 'production';
    },
    cgi:function(uri=""){
        var base_url=this.isProduction()?"/wwwroot/apido":"/apido";
        if(typeof uri =='string') return base_url.replace('apido','')+uri;
        base_url+='/'+uri.act;
        console.log('isProduction',this.isProduction(),base_url);
        return base_url;
    },
    toast:function(msg,type='error'){
        if(type=='error')
            return Message.error(msg);
        Message(msg);
    },
    then:function(data,uri,callback){
        this.loading(false);
        console.log('vk-then',data,uri.code);
        if(data.code==-1){
            this.toast(data.message);
            sessionStorage.clear();
            window.localStorage.clear();
            location.hash='#/login';
            return;
        }
        if(data.code!=200 && data.code!=404){
            this.toast(data.message);
            return;
        }
        this.setCache(uri,data);
        if(callback) callback(data,uri.code);
    },
    http:function(uri,data,callback){
        this.loading();
        var cdata=this.getCache(uri);
        if(cdata){
            console.log('cacheData',cdata);
            return this.then(cdata,uri,callback);
        }
        var url=this.cgi(uri);
        var that=this;
        try{
            var ac_id = store.state.data.ac_id;
            data.ac_id=ac_id;
        }catch(e){}
        if(typeof data.ac_id=='undefined'){
            var cecode=[10000,10001,10002,11002];
            if(cecode.indexOf(uri.code)>-1){
                this.alert('Please select an AdAccount to start');
                return;
            }
        }
        if(!data.token){
            try{
                var token = store.state.user.token;
                data.token=token;
            }catch(e){}
        }
        console.log('postdata',data);
        Vue.http.post(url,data,{emulateJSON: true}).then(
            (response) => {
                that.then(response.body,uri,callback);
            },
            (response) => {
                that.then(response.body,uri,callback);
            }
        );
    },
    catchRule(uri){
        var rules={
            10003:{timeout:86400},
            12001:{timeout:86400},
            16000:{timeout:86400},//getFeeds
        };
        var line=rules[uri.code];
        if(line){
            line.key=uri.act+'_'+uri.code;
        }
        return line;
    },
    setCache(uri,data){
        var rule=this.catchRule(uri);
        if(rule){
            this.ls(rule.key,data,rule.timeout);
        }
    },
    getCache(uri,callback){
        var rule=this.catchRule(uri);
        if(rule){
            return this.ls(rule.key);
        }
        return false;
    },
    ls:function(key,val=false,timeout=-1){
        var old=window.localStorage.getItem(key);
        var time=new Date().getTime();
        if(old){
            old=JSON.parse(old);
            if(val===false){
                if(old.time>time || old.time==-1){
                    return old.data;
                }
                return "";
            } 
        }
        if(val===false) return "";
        old={time:timeout==-1?-1:(time+timeout*1000),data:val};
        window.localStorage.setItem(key,JSON.stringify(old));
    },
    getArrObj2Arr:function(arr,key){
        var d=[];
        arr.map(function(r,i){
            d.push(r[key])
        })
        return d;
    },
    alert:function(title,confirm){
        MessageBox.alert(title, 'Message', {
            confirmButtonText: 'Ok',
            callback: action => {
                if(confirm) confirm();
            }
        });
    },
    confirm:function(title,confirm,cancel){
        MessageBox.confirm(title, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        }).then(() => {
            if(confirm) confirm();
        }).catch(() => {
            if(cancel) cancel();
        });
    },
    numberFormat(num,floatNumber=2,pre='$'){
        if(!isFinite(num)) return num;
        var numberSplit=',';
        var number=(num/1).toFixed(floatNumber).toString();
        return number.replace(/([\d]{1,3})([\d]{3})?([\d]{3})?([\d]{3})?(\.[\d]{2})/,function(){
            var str=pre;
            for (var i=1;i<5;i++){
                str+=typeof arguments[i]!='undefined'?(arguments[i]+numberSplit):'';
            }
            str=str.substr(0,str.length-1);
            return str+arguments[5];
        });
    },
    loading(flag=true){
        var load=Loading.service({ fullscreen: true });
        if(!flag) setTimeout(function(){load.close();},0);
    },
    date(tpl,timespace){
        tpl=tpl || "YYYY年MM月DD日 HH时II分SS秒";
        var d=new Date(timespace*1000);
        tpl=tpl.toUpperCase();
        tpl=tpl.replace('YYYY',d.getFullYear());
        var mm=d.getMonth()+1;
        tpl=tpl.replace('MM',mm>9?mm:'0'+mm);
        var dd=d.getDate();
        tpl=tpl.replace('DD',dd>9?dd:'0'+dd);
        var hh=d.getHours();
        tpl=tpl.replace('HH',hh>9?hh:'0'+hh);
        var ii=d.getMinutes();
        tpl=tpl.replace('II',ii>9?ii:'0'+ii);
        var ss=d.getSeconds();
        tpl=tpl.replace('SS',ss>9?ss:'0'+ss);
        return tpl;
    },
};
export default vk;