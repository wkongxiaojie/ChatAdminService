import{I as O,r as d,J as k,K as C,M,u as B,N as z}from"./entry.834fe740.js";const A=()=>null;function I(...o){var h,m,v,_,D,g,b,w,x;const u=typeof o[o.length-1]=="string"?o.pop():void 0;typeof o[0]!="string"&&o.unshift(u);let[t,l,e={}]=o;if(typeof t!="string")throw new TypeError("[nuxt] [asyncData] key must be a string.");if(typeof l!="function")throw new TypeError("[nuxt] [asyncData] handler must be a function.");e.server=(h=e.server)!=null?h:!0,e.default=(m=e.default)!=null?m:A,e.defer&&console.warn("[useAsyncData] `defer` has been renamed to `lazy`. Support for `defer` will be removed in RC."),e.lazy=(_=(v=e.lazy)!=null?v:e.defer)!=null?_:!1,e.initialCache=(D=e.initialCache)!=null?D:!0,e.immediate=(g=e.immediate)!=null?g:!0;const a=O(),c=()=>(a.isHydrating||e.initialCache)&&a.payload.data[t]!==void 0;a._asyncData[t]||(a._asyncData[t]={data:d(c()?a.payload.data[t]:(w=(b=e.default)==null?void 0:b.call(e))!=null?w:null),pending:d(!c()),error:d((x=a.payload._errors[t])!=null?x:null)});const n={...a._asyncData[t]};n.refresh=n.execute=(i={})=>a._asyncDataPromises[t]?a._asyncDataPromises[t]:i._initial&&c()?a.payload.data[t]:(n.pending.value=!0,a._asyncDataPromises[t]=new Promise((s,r)=>{try{s(l(a))}catch(f){r(f)}}).then(s=>{e.transform&&(s=e.transform(s)),e.pick&&(s=E(s,e.pick)),n.data.value=s,n.error.value=null}).catch(s=>{var r,f;n.error.value=s,n.data.value=B((f=(r=e.default)==null?void 0:r.call(e))!=null?f:null)}).finally(()=>{n.pending.value=!1,a.payload.data[t]=n.data.value,n.error.value&&(a.payload._errors[t]=!0),delete a._asyncDataPromises[t]}),a._asyncDataPromises[t]);const y=()=>n.refresh({_initial:!0}),P=e.server!==!1&&a.payload.serverRendered;{const i=z();if(i&&!i._nuxtOnBeforeMountCbs){i._nuxtOnBeforeMountCbs=[];const r=i._nuxtOnBeforeMountCbs;i&&(k(()=>{r.forEach(f=>{f()}),r.splice(0,r.length)}),C(()=>r.splice(0,r.length)))}P&&a.isHydrating&&t in a.payload.data?n.pending.value=!1:i&&(a.payload.serverRendered&&a.isHydrating||e.lazy)&&e.immediate?i._nuxtOnBeforeMountCbs.push(y):e.immediate&&y(),e.watch&&M(e.watch,()=>n.refresh());const s=a.hook("app:data:refresh",r=>{if(!r||r.includes(t))return n.refresh()});i&&C(s)}const p=Promise.resolve(a._asyncDataPromises[t]).then(()=>n);return Object.assign(p,n),p}function E(o,u){const t={};for(const l of u)t[l]=o[l];return t}export{I as u};
