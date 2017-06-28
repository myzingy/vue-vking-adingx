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
		<div class="login" v-show="!btn">
			<fb-signin-button
					:params="fbSignInParams"
					@success="onSignInSuccess"
					@error="onSignInError">
				Sign in with Facebook
			</fb-signin-button>
		</div>
		<!--
		<form class="login" v-on:submit.prevent="submit">
			<div class="line">	
				<div v-show="btn && !form.id">id不能为空</div>
				<input type="number" placeholder="输入你的id" v-model="form.id">
			</div>
			<div class="line">
				<div v-show="btn && !form.name">用户名不能为空</div>
				<input type="text" placeholder="输入你的用户名" v-model="form.name">
			</div>
			<button>登录</button>
		</form>
		-->
	</div>
</template>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '104147746842860',
            cookie     : true,  // enable cookies to allow the server to access the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.8', // use graph api version 2.8
        });
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
                }
			}
		},
		methods: {
			...mapActions([USER_SIGNIN]),
			then(json,code){
			    switch (code){
					case uri.login.code:
                        this.USER_SIGNIN(this.form)
                        this.$router.replace({ path: '/home' })
					break;
				}
			},
            submit() {
				this.btn = true
				if(!this.form.id || !this.form.name) return
				vk.http(uri.login,this.form,this.then);
			},
            onSignInSuccess (response) {
                console.log('login', response)
				var token=response.authResponse.accessToken;
                FB.api('/me?fields=id,name,email', dude => {
                    console.log(`Good to see you, ${dude.name}.`,dude)
                    this.form=dude;
                    this.form.token= token;
                    this.submit();
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