import{e as d,S as m,E as f,c as _,h as p,i as s,A as x,P as h,j as a,k as n,N as g,o,V as v,p as l,C as y,J as k}from"./index-RCxfeuTg.js";const C={class:"tw-flex tw-justify-between tw-w-full tw-h-full lg:tw-gap-4 max-lg:tw-rounded-xl"},S={class:"tw-flex-none tw-w-full lg:tw-w-64 tw-justify-self-end empty:tw-w-0 tw-sticky tw-top-0 -tw-translate-x-full lg:tw-translate-x-0 tw-h-screen tw-transition"},V=d({__name:"TaskListLayout",setup(j){const t=m(),{isMobileScreen:i,isExtraLargeScreen:r}=f("mediaQuery"),w=_(()=>t.matched.findIndex(e=>e.meta.isListView)+1<t.matched.length);return(e,B)=>{const c=g("router-view");return o(),p("div",C,[s("div",{class:h(["tw-flex-none lg:tw-flex-auto tw-w-full",{"max-lg:tw-h-screen max-lg:tw-overflow-hidden max-lg:tw-opacity-0 max-lg:tw-transition":w.value}])},[x(e.$slots,"default")],2),s("div",S,[a(c,null,{default:n(({Component:u})=>[a(v,{css:l(i)||l(r),name:"slide-right"},{default:n(()=>[(o(),y(k(u)))]),_:2},1032,["css"])]),_:1})])])}}});export{V as _};