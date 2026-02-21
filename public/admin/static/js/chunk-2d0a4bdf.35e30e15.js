/*!
 *  build: kasushou v2 
 *  copyright: kasushou.com xinmengnet 
 *  time: 2026-2-6 12:34:34
 */
/*!
 *  build: kasushou v2 
 *  copyright: kasushou.com xinmengnet 
 *  time: 2026-2-6 12:34:34
 */
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0a4bdf","chunk-2d0a4bdf"],{"0878":function(e,t,i){"use strict";i.r(t);var o=function(){var e=this,t=e._self._c;return t("el-dialog",{attrs:{"append-to-body":"",title:e.title,visible:e.dialogFormVisible,width:"520px"},on:{"update:visible":function(t){e.dialogFormVisible=t},close:e.close,open:e.open}},[t("div",[t("div",{staticClass:"acea-row row-around code"},[t("div",{staticClass:"acea-row row-column-around row-between-wrapper"},[t("div",{staticClass:"QRpic"},[e.qrCodeUrl?t("vueQr",{ref:"qrCodeDiv",attrs:{margin:10,size:160,text:e.qrCodeUrl}}):e._e()],1),t("span",{staticClass:"mt10"},[e._v("手机扫码预览")])])])])])},a=[],s=i("6e45"),l=i.n(s),r={name:"",components:{vueQr:l.a},props:{visible:{type:Boolean,default:!1},qrCodeUrl:{type:String,default:""},qrcodeImg:{type:String,default:""}},data(){return{title:"预览",dialogFormVisible:!1}},computed:{},watch:{visible(e){this.dialogFormVisible=e}},created(){},mounted(){},methods:{close(){this.$emit("update:visible",this.dialogFormVisible)},open(){}}},d=r,n=i("9d41"),c=Object(n["a"])(d,o,a,!1,null,"8a1d42e8",null);t["default"]=c.exports}}]);