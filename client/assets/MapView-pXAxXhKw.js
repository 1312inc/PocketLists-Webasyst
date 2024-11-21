import{d as b,a5 as k,y as x,a6 as g,a7 as h,i as y,j as C,k as I,x as c,c as S,b as e,t as L,e as a,w as R,h as T,I as V,N as B,l as E,a8 as M,o as i,a9 as m,K as N,aa as v}from"./index-Vv1B7g7g.js";import{_ as $}from"./TaskList.vue_vue_type_script_setup_true_lang-bpGOwfrF.js";import"./rangy-5rxgwlWZ.js";import"./SortableList.vue_vue_type_style_index_0_lang-38DUP1RI.js";const D={key:0,class:"tw-absolute tw-pointer-events-auto tw-flex tw-flex-col tw-overflow-hidden tw-z-10 tw-right-2 tw-top-12 tw-bottom-26 tw-bg-white tw-p-4 tw-w-64"},z={class:"tw-flex-none tw-flex tw-justify-between"},A={class:"tw-flex-auto"},P={class:"tw-absolute tw-z-10 tw-bottom-4 tw-left-1/2 tw-bg-white tw-p-4 tw-flex tw-gap-2"},q=b({__name:"MapView",setup(j){k(`
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
`);const o=x(),f=m(()=>v(()=>import("./MapGoogle-W800MCav.js"),__vite__mapDeps([0,1,2]),import.meta.url)),r=m(()=>v(()=>import("./MapYandex-XNXS965h.js"),__vite__mapDeps([3,1,2,4]),import.meta.url)),l=g(r),p=h(),d=y(),s=C(()=>N(o.currentRoute.value.params.id));I(()=>o.currentRoute.value.params.id,n=>{d.fetch({location_id:n})},{immediate:!0});const _=n=>{o.push(`/map/${n}`)};return(n,t)=>{var u;return i(),c(M,{to:"#app"},[s.value?(i(),S("div",D,[e("div",z,[e("div",null,L((u=a(p).getItemById(s.value))==null?void 0:u.name),1),e("div",null,[e("a",{onClick:t[0]||(t[0]=R(w=>a(o).push("/map"),["prevent"]))},t[3]||(t[3]=[e("i",{class:"fas fa-times"},null,-1)]))])]),e("div",A,[T($,{items:a(d).getTasksInLocation(s.value),"addable-props":{location_id:s.value}},null,8,["items","addable-props"])])])):V("",!0),e("div",P,[e("button",{onClick:t[1]||(t[1]=w=>l.value=a(f))}," Google "),e("button",{onClick:t[2]||(t[2]=w=>l.value=a(r))}," Yandex ")]),(i(),c(B(l.value),{places:a(p).items,onSelectLocation:_},{default:E(()=>t[4]||(t[4]=[e("div",{id:"map",class:"tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-z-0"},null,-1)])),_:1},40,["places"]))])}}});export{q as default};
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["./MapGoogle-W800MCav.js","./index-Vv1B7g7g.js","./index-CjuXG0is.css","./MapYandex-XNXS965h.js","./ChooseLocation.vue_vue_type_script_setup_true_lang-vdbJn5OM.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
