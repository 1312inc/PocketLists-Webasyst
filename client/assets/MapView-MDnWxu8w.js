import{d as _,a4 as b,a5 as g,a6 as k,i as x,r as y,x as u,c as C,b as e,t as h,e as o,w as S,h as I,J as L,N as T,l as V,a7 as B,o as l,a8 as w,a9 as v}from"./index-DKxeS6my.js";import{_ as E}from"./TaskList.vue_vue_type_script_setup_true_lang-S2WO79ae.js";import"./rangy-5rxgwlWZ.js";import"./SortableList.vue_vue_type_style_index_0_lang-P00vVVyM.js";const M={key:0,class:"tw-absolute tw-pointer-events-auto tw-flex tw-flex-col tw-overflow-hidden tw-z-10 tw-right-12 tw-top-12 tw-bottom-26 tw-bg-white tw-p-4 tw-w-64"},D={class:"tw-flex-none tw-flex tw-justify-between"},N={class:"tw-flex-auto"},$={class:"tw-absolute tw-z-10 tw-bottom-4 tw-left-1/2 tw-bg-white tw-p-4 tw-flex tw-gap-2"},O=_({__name:"MapView",setup(z){b(`
  [data-v-app] > div:first-child { 
    pointer-events: none;
  } 

  [data-v-app] .sidebar { 
    background-color: transparent; 
  } 

  [data-v-app] .sidebar > div > div {
    pointer-events: auto;
  }
  
  [data-v-app] .sidebar-body > *, 
  [data-v-app] .sidebar-footer { 
    background-color: var(--semi-white); 
    backdrop-filter: blur(10px); 
  }
`);const c=w(()=>v(()=>import("./MapGoogle-7jDbkhZA.js"),__vite__mapDeps([0,1,2]),import.meta.url)),n=w(()=>v(()=>import("./MapYandex-t2yIk6PA.js"),__vite__mapDeps([3,1,2]),import.meta.url)),s=g(n),i=k(),m=x(),a=y(null),f=r=>{a.value=r};return(r,t)=>{var d;return l(),u(B,{to:"#app"},[a.value?(l(),C("div",M,[e("div",D,[e("div",null,h((d=o(i).getItemById(a.value))==null?void 0:d.name),1),e("div",null,[e("a",{class:"gray",href:"#",onClick:t[0]||(t[0]=S(p=>a.value=null,["prevent"]))},t[3]||(t[3]=[e("i",{class:"fas fa-times"},null,-1)]))])]),e("div",N,[I(E,{items:o(m).getTasksInLocation(a.value),"addable-props":{location_id:a.value}},null,8,["items","addable-props"])])])):L("",!0),e("div",$,[e("button",{onClick:t[1]||(t[1]=p=>s.value=o(c))}," Google "),e("button",{onClick:t[2]||(t[2]=p=>s.value=o(n))}," Yandex ")]),(l(),u(T(s.value),{places:o(i).items,onSelectLocation:f},{default:V(()=>t[4]||(t[4]=[e("div",{id:"map",class:"tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-z-0"},null,-1)])),_:1},40,["places"]))])}}});export{O as default};
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["./MapGoogle-7jDbkhZA.js","./index-DKxeS6my.js","./index-PqKbIIoi.css","./MapYandex-t2yIk6PA.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
