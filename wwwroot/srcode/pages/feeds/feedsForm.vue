<style lang="stylus" rel="stylesheet/scss">
    canvas{ border: 1px solid #ccc;}
</style>
<template>
    <el-form ref="form" :model="form" label-width="120px" :rules="rules">

        <el-form-item label="品牌" prop="brand">
            <el-select v-model="form.brand" placeholder="请选择品牌">
                <el-option
                        v-for="item in brands"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                </el-option>
            </el-select>
        </el-form-item>
        <el-form-item label="Feed URL" prop="url">
            <el-input v-model="form.url"></el-input>
        </el-form-item>
        <el-form-item label="utm_source">
            <el-input v-model="form.utm_source"></el-input>
        </el-form-item>
        <el-form-item label="utm_medium">
            <el-input v-model="form.utm_medium"></el-input>
        </el-form-item>
        <el-form-item label="utm_campaign">
            <el-input v-model="form.utm_campaign"></el-input>
        </el-form-item>
        <el-form-item label="utm_content">
            <el-input v-model="form.utm_content"></el-input>
        </el-form-item>
        <el-form-item label="utm_term">
            <el-input v-model="form.utm_term"></el-input>
        </el-form-item>
    </el-form>
</template>

<script>
    import Vue from 'vue'
    import vk from '../../vk.js';
    import uri from '../../uri.js';
    export default {
        data:function(){
            return {
                form:{
                    brand:"",
                    url:"",
                },
                brands: {
                    jeulia: {label: "Jeulia", value: "jeulia"},
                    gnoce: {label: "Gnoce", value: "gnoce"},
                    amarley: {label: "Amarley",value: "amarley"},
                },
                rules:{
                    brand:[
                        { required: true, message: '请选择品牌', trigger: 'blur' }
                    ],
                    url:[
                        { required: true, message: '请填写url', trigger: 'blur' }
                    ],
                },
            }
        },
        mounted(){

        },
        methods:{
            getFormData(){
                var flag=false;
                this.$refs['form'].validate((valid,err) => {
                    if (valid) {
                        flag=true;
                    }
                });
                if(flag){
                    return this.form;
                }
                vk.toast('请完善信息');
                return false;
            }
        },
    }
</script>