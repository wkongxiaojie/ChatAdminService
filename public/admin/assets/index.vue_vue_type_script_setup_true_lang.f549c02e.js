import{O as i}from"./element-plus.317dd699.js";import{d as c,e as d,o as m,c as z,U as f,I as h,u as a}from"./@vue.e8706010.js";const C={class:"pagination"},V=c({__name:"index",props:{modelValue:{default:()=>({})},pageSizes:{default:()=>[15,20,30,40]},layout:{default:"total, sizes, prev, pager, next, jumper"}},emits:["change","update:modelValue"],setup(n,{emit:o}){const p=n,e=d({get(){return p.modelValue},set(g){o("update:modelValue",g)}}),l=()=>{e.value.page=1,o("change")},r=()=>{o("change")};return(g,t)=>{const u=i;return m(),z("div",C,[f(u,h(p,{"pager-count":5,currentPage:a(e).page,"onUpdate:currentPage":t[0]||(t[0]=s=>a(e).page=s),pageSize:a(e).size,"onUpdate:pageSize":t[1]||(t[1]=s=>a(e).size=s),"page-sizes":n.pageSizes,layout:n.layout,total:a(e).count,"hide-on-single-page":!1,onSizeChange:l,onCurrentChange:r}),null,16,["currentPage","pageSize","page-sizes","layout","total"])])}}});export{V as _};
