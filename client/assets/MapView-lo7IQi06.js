import{d as _,a5 as k,y as x,a6 as g,a7 as y,i as h,j as C,x as w,c as I,b as e,t as L,e as a,w as S,h as R,J as T,N as V,l as B,a8 as E,o as l,a9 as c,L as M,aa as m}from"./index-dkudGc25.js";import{_ as N}from"./TaskList.vue_vue_type_script_setup_true_lang-HK1smFOe.js";import"./rangy-5rxgwlWZ.js";import"./SortableList.vue_vue_type_style_index_0_lang-_d_xV-KO.js";const $={key:0,class:"tw-absolute tw-pointer-events-auto tw-flex tw-flex-col tw-overflow-hidden tw-z-10 tw-right-2 tw-top-12 tw-bottom-26 tw-bg-white tw-p-4 tw-w-64"},D={class:"tw-flex-none tw-flex tw-justify-between"},z={class:"tw-flex-auto"},A={class:"tw-absolute tw-z-10 tw-bottom-4 tw-left-1/2 tw-bg-white tw-p-4 tw-flex tw-gap-2"},J=_({__name:"MapView",setup(P){k(`
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
`);const o=x(),v=c(()=>m(()=>import("./MapGoogle-GNgCzJop.js"),__vite__mapDeps([0,1,2]),import.meta.url)),i=c(()=>m(()=>import("./MapYandex-g2AeOoq2.js"),__vite__mapDeps([3,1,2,4]),import.meta.url)),n=g(i),r=y(),f=h(),s=C(()=>o.currentRoute.value.params.id&&M(o.currentRoute.value.params.id)),b=p=>{o.push(`/map/${p}`)};return(p,t)=>{var d;return l(),w(E,{to:"#app"},[s.value?(l(),I("div",$,[e("div",D,[e("div",null,L((d=a(r).getItemById(s.value))==null?void 0:d.name),1),e("div",null,[e("a",{onClick:t[0]||(t[0]=S(u=>a(o).push("/map"),["prevent"]))},t[3]||(t[3]=[e("i",{class:"fas fa-times"},null,-1)]))])]),e("div",z,[R(N,{items:a(f).getTasksInLocation(s.value),"addable-props":{location_id:s.value}},null,8,["items","addable-props"])])])):T("",!0),e("div",A,[e("button",{onClick:t[1]||(t[1]=u=>n.value=a(v))}," Google "),e("button",{onClick:t[2]||(t[2]=u=>n.value=a(i))}," Yandex ")]),(l(),w(V(n.value),{places:a(r).items,onSelectLocation:b},{default:B(()=>t[4]||(t[4]=[e("div",{id:"map",class:"tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-z-0"},null,-1)])),_:1},40,["places"]))])}}});export{J as default};
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["./MapGoogle-GNgCzJop.js","./index-dkudGc25.js","./index-3GJYBzeD.css","./MapYandex-g2AeOoq2.js","./ChooseLocation.vue_vue_type_script_setup_true_lang-iaaYSdsN.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
