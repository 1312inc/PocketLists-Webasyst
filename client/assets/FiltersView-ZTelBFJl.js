import{a as u,b as s,e as o,F as l,i as m,j as p,g as d,y as c,o as r,m as _,t as b,O as k}from"./index-FBA1Gy56.js";const y={class:"tw-mb-4"},C={class:"tw-mb-4"},v=["onClick"],V=u({__name:"FiltersView",setup($){return(n,t)=>{const a=c("RouterView");return r(),s("div",null,[o("div",y,[o("button",{onClick:t[0]||(t[0]=e=>n.$router.push({name:"todosUpnext"}))}," Upnext "),o("button",{onClick:t[1]||(t[1]=e=>n.$router.push({name:"todosNearby"}))}," Nearby ")]),o("div",C,[(r(!0),s(l,null,m(p(k),(e,i)=>(r(),s("button",{key:i,style:_(`background: ${e}`),onClick:f=>n.$router.push({name:"todosCriteria",params:{criteria:"color",query:e}})},b(e),13,v))),128))]),d(a)])}}});export{V as default};