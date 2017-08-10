<style lang="stylus" rel="stylesheet/scss">
	.login
		padding 50px
		text-align center
		.line
			padding 5px
			input
				padding 0 10px
				line-height 28px


		button
			padding 0 20px
			margin-top 20px
			line-height 28px
	.fb-signin-button {
		display: inline-block;
		padding: 20px 20px;
		border-radius: 5px;
		background-color: #4267b2;
		color: #fff;
	}
</style>
<template>
	<div>
		<v-header title="登录">
			<router-link slot="left" to="?"></router-link>
		</v-header>
		<div class="login" v-show="!btn && !emailInputFlag">
			<fb-signin-button
					:params="fbSignInParams"
					@success="onSignInSuccess"
					@error="onSignInError">
				Sign in with Facebook
			</fb-signin-button>
		</div>
		<form class="login" v-on:submit.prevent="submit" v-show="emailInputFlag">
			<div class="line">
				<div>Facebook Email 获取失败,请手动填写</div>
				<input type="text" placeholder="输入你的Facebook Email" v-model="form.email">
			</div>
			<button>登录</button>
		</form>
	</div>
</template>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '104147746842860',
            cookie     : true,  // enable cookies to allow the server to access the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.9', // use graph api version 2.8
        });
    };
    window.onerror=function(){
        vk.toast('Fackbook login fail,Please try again or refresh the page!');
    };
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    import Vue from 'vue'
    import { mapActions } from 'vuex'
    import { USER_SIGNIN } from '../../store/user.js'
    import FBSignInButton from 'vue-facebook-signin-button'
    Vue.use(FBSignInButton)
	import vk from '../../vk.js'
	import uri from '../../uri.js'
    export default {
        data() {
			return {
				btn: false, //true 已经提交过， false没有提交过
				form: {
					id: '',
					name: '',
					email:'',
					token:'',
				},
                fbSignInParams: {
                    scope: 'email,ads_management,ads_read,manage_pages,read_insights',
                    return_scopes: true
                },
                emailInputFlag:false,
			}
		},
		methods: {
			...mapActions([USER_SIGNIN]),
			then(json,code){
			    switch (code){
					case uri.login.code:
					    if(json.code==404){
                            this.emailInputFlag=true;
					        return;
						}
                        this.USER_SIGNIN(this.form)
                        this.$router.replace({ path: '/home' })
					break;
				}
			},
            submit() {
				this.btn = true
				//console.log('submit',JSON.stringify(this.form));
				if(!(this.form.id && this.form.name  && this.form.email && this.form.token)) {
				    vk.alert('Facebook 授权信息错误');
				    return;
                }
                if(this.form.email.indexOf('@')<0){
                    vk.alert('email 格式错误');
                    return;
				}
				vk.http(uri.login,this.form,this.then);
			},
            onSignInSuccess (response) {
                console.log('login', response)
				var token=response.authResponse.accessToken;
                FB.api('/me?fields=id,name,email', dude => {
                    console.log(`Good to see you, ${dude.name}.`,dude)
                    this.form=dude;
                    this.form.token= token;
//					if(!dude.email){
//						this.emailInputFlag=true;
//                        return;
//					}
                    //this.submit();
                    vk.http(uri.login,this.form,this.then);
                })

            },
            onSignInError (error) {
                console.log('OH NOES', error)
            },
            logout(){
                FB.logout(function(response) {
                    console.log('logout', response)
                });
			}
		}
    }
</script>