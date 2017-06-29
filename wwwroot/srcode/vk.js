import Vue  from 'vue'
import VueResource  from 'vue-resource'
import { Message,MessageBox } from 'element-ui';
import store from './store/'
import URI from './uri.js'
Vue.use(VueResource);
let vk={
    isProduction:function(){
        return process.env.NODE_ENV === 'production';
    },
    cgi:function(uri){
        var base_url=this.isProduction()?"/facebook/wwwroot/apido":"/apido";
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
        console.log('vk-then',data,uri.code);
        if(data.code==-1){
            this.toast(data.message);
            sessionStorage.clear();
            window.localStorage.clear();
            location.hash='#/login';
            return;
        }
        if(data.code!=200){
            this.toast(data.message);
            return;
        }
        this.setCache(uri,data);
        if(callback) callback(data,uri.code);
    },
    http:function(uri,data,callback){
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
    }
};
export default vk;