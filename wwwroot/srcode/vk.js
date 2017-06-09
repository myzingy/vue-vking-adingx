import Vue  from 'vue'
import VueResource  from 'vue-resource'
import { Message } from 'element-ui';

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
    then:function(data,code,callback){
        console.log('vk-then',data,code);
        if(data.code!=200){
            this.toast(data.message);
            return;
        }
        if(callback) callback(data,code);
    },
    http:function(uri,data,callback){
        var url=this.cgi(uri);
        var that=this;
        console.log('postdata',data);
        Vue.http.post(url,data,{emulateJSON: true}).then(
            (response) => {
                that.then(response.body,uri.code,callback);
            },
            (response) => {

            }
        );
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
    }
};
export default vk;