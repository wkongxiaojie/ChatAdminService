import{E as k,J as x,y as w,w as B,x as D}from"./element-plus.317dd699.js";import{f as r,b as T}from"./index.ffa405f0.js";import{u as N}from"./vue-clipboard3.9796a14d.js";import{d as S,r as U,e as $,a4 as j,o as s,c as m,U as o,L as t,V as F,a7 as L,u as J,k as K,K as P,a as i,S as R}from"./@vue.e8706010.js";const q={class:"code-preview"},z={class:"flex",style:{height:"50vh"}},A=R(" \u590D\u5236 "),Q=S({__name:"code-preview",props:{modelValue:{type:Boolean},code:null},emits:["update:modelValue"],setup(c,{emit:p}){const _=c,{toClipboard:f}=N(),u=U("index0"),b=async l=>{try{await f(l),r.msgSuccess("\u590D\u5236\u6210\u529F")}catch{r.msgError("\u590D\u5236\u5931\u8D25")}},n=$({get(){return _.modelValue},set(l){p("update:modelValue",l)}});return(l,a)=>{const h=j("highlightjs"),V=k,v=T,g=B,y=D,C=w,E=x;return s(),m("div",q,[o(E,{modelValue:J(n),"onUpdate:modelValue":a[1]||(a[1]=e=>K(n)?n.value=e:null),width:"900px",title:"\u4EE3\u7801\u9884\u89C8"},{default:t(()=>[o(C,{modelValue:u.value,"onUpdate:modelValue":a[0]||(a[0]=e=>u.value=e)},{default:t(()=>[(s(!0),m(F,null,L(c.code,(e,d)=>(s(),P(y,{label:e.name,name:`index${d}`,key:d},{default:t(()=>[i("div",z,[o(V,{class:"flex-1"},{default:t(()=>[o(h,{autodetect:"",code:e.content},null,8,["code"])]),_:2},1024),i("div",null,[o(g,{onClick:G=>b(e.content),type:"primary",link:""},{icon:t(()=>[o(v,{name:"el-icon-CopyDocument"})]),default:t(()=>[A]),_:2},1032,["onClick"])])])]),_:2},1032,["label","name"]))),128))]),_:1},8,["modelValue"])]),_:1},8,["modelValue"])])}}});export{Q as _};
