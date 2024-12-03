import{d as x,a2 as C,y as $,a3 as I,a4 as S,i as M,j as R,x as d,c as T,b as t,t as w,e as s,w as m,h as V,I as v,N as A,l as B,a5 as E,o as i,a6 as f,K as N,a7 as b}from"./index-FRWD2OZN.js";import{_ as q}from"./TaskList.vue_vue_type_script_setup_true_lang-OrYc4Vvy.js";import{_ as z}from"./ChooseLocation.vue_vue_type_script_setup_true_lang-y2MMR-IT.js";import"./rangy-5rxgwlWZ.js";import"./SortableList.vue_vue_type_style_index_0_lang-kigiOvpI.js";const D={key:0,class:"tw-absolute tw-pointer-events-auto tw-flex tw-flex-col tw-overflow-hidden tw-z-10 tw-right-2 tw-top-12 tw-bottom-26 tw-bg-white tw-p-4 tw-w-64"},P={class:"tw-flex-none tw-flex tw-justify-between"},j={class:"tw-flex-auto"},G={class:"tw-absolute tw-z-10 tw-bottom-4 tw-left-1/2 tw-bg-white tw-p-4 tw-flex tw-gap-2"},O={class:"tw-absolute tw-top-2 tw-right-2 tw-bg-white"},U=x({__name:"MapView",setup(Y){C(`
  [data-v-app] > div:first-child { 
    pointer-events: none;
  } 

  [data-v-app] .sidebar { 
    background-color: transparent; 
  } 

  [data-v-app] .sidebar > div {
    pointer-events: auto;
  }
  
  [data-v-app] .sidebar-body > *, 
  [data-v-app] .sidebar-footer { 
    background-color: var(--semi-white); 
    backdrop-filter: blur(10px); 
  }
`);const n=$(),_=f(()=>b(()=>import("./MapGoogle-_OUgGyA6.js"),__vite__mapDeps([0,1,2]),import.meta.url)),u=f(()=>b(()=>import("./MapYandex-7b83j7as.js"),__vite__mapDeps([3,1,2]),import.meta.url)),r=I(u),p=S(),g=M(),a=R(()=>N(n.currentRoute.value.params.id)),k=e=>{n.push(`/map/${e}`)},y=()=>{const e={...n.currentRoute.value.query};e.addLocation==="true"?delete e.addLocation:e.addLocation="true",n.push({path:"/map",query:e})};return(e,o)=>{var c;return i(),d(E,{to:"#app"},[a.value?(i(),T("div",D,[t("div",P,[t("div",null,w((c=s(p).getItemById(a.value))==null?void 0:c.name),1),t("div",null,[t("a",{onClick:o[0]||(o[0]=m(l=>e.$router.push("/map"),["prevent"]))},o[3]||(o[3]=[t("i",{class:"fas fa-times"},null,-1)]))])]),t("div",j,[V(q,{items:s(g).getTasksInLocation(a.value),"addable-props":{location_id:a.value}},null,8,["items","addable-props"])])])):v("",!0),t("div",G,[t("button",{onClick:o[1]||(o[1]=l=>r.value=s(_))}," Google "),t("button",{onClick:o[2]||(o[2]=l=>r.value=s(u))}," Yandex ")]),(i(),d(A(r.value),{places:s(p).items,"selected-location-id":a.value,onSelectLocation:k},{default:B(({lng:l,lat:h,zoom:L})=>[e.$route.query.addLocation==="true"?(i(),d(z,{key:0,lng:l,lat:h,zoom:L},null,8,["lng","lat","zoom"])):v("",!0),o[4]||(o[4]=t("div",{id:"map",class:"tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-z-0"},null,-1))]),_:1},40,["places","selected-location-id"])),t("div",O,[t("button",{onClick:m(y,["prevent"])},w(e.$route.query.addLocation==="true"?"Close":"Add Location"),1)])])}}});export{U as default};
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["./MapGoogle-_OUgGyA6.js","./index-FRWD2OZN.js","./index-yni2lSK1.css","./MapYandex-7b83j7as.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
