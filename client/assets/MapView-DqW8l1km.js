import{h as E,a4 as M,E as N,a5 as R,r as h,a6 as B,u as S,c as w,D as p,j as V,k as e,q as f,w as i,P as b,m as v,a7 as q,v as A,t as D,a8 as T,o as r,a9 as g,R as z,aa as k}from"./index-NouRpb7z.js";import{_ as P}from"./TaskList.vue_vue_type_script_setup_true_lang-UsEihBys.js";import{_ as j}from"./ChooseLocation.vue_vue_type_script_setup_true_lang-GFEtcrGy.js";import{C as G}from"./ContenteditableBlock-WdaUiD-l.js";import"./rangy-5rxgwlWZ.js";import"./LazyRender.vue_vue_type_script_setup_true_lang-Qj3Jas41.js";const O={key:0,class:"tw-absolute tw-pointer-events-auto tw-flex tw-flex-col tw-overflow-hidden z-10 tw-right-2 tw-top-12 tw-bottom-26 tw-bg-white custom-p-16 tw-w-64"},U={class:"tw-flex-none tw-flex tw-gap-8 tw-justify-between"},Y={class:"tw-flex-auto"},F={class:"tw-absolute z-10 tw-bottom-4 tw-left-1/2 tw-bg-white custom-p-16 tw-flex tw-gap-2"},H={class:"tw-absolute tw-top-2 tw-right-2 tw-bg-white"},et=E({__name:"MapView",setup(J){M(`
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
`);const l=N(),L=g(()=>k(()=>import("./MapGoogle-yCB-fADv.js"),__vite__mapDeps([0,1,2]),import.meta.url)),m=g(()=>k(()=>import("./MapYandex-p0lDMH0j.js"),__vite__mapDeps([3,1,2]),import.meta.url)),d=R(m),c=h(!1),n=B();S();const u=w(()=>z(l.currentRoute.value.params.id)),s=w(()=>u.value?n.getItemById(u.value):void 0),_=o=>{l.push(`/map/${o}`)},C=()=>{const o={...l.currentRoute.value.query};o.addLocation==="true"?delete o.addLocation:o.addLocation="true",l.push({path:"/map",query:o})},y=(o,t)=>{n.updateItemName(o,t)},$=o=>{n.deleteItem(o),l.push("/map/")};return(o,t)=>(r(),p(T,{to:"#app"},[s.value?(r(),V("div",O,[e("div",U,[f(G,{editable:c.value,focusable:!0,value:s.value.name,tag:"div",onUpdate:t[0]||(t[0]=a=>y(s.value,a))},null,8,["editable","value"]),e("div",null,[e("a",{onClick:t[1]||(t[1]=i(a=>c.value=!0,["prevent"]))},t[6]||(t[6]=[e("i",{class:"fas fa-pen"},null,-1)]))]),e("div",null,[e("a",{onClick:t[2]||(t[2]=i(a=>$(s.value),["prevent"]))},t[7]||(t[7]=[e("i",{class:"fas fa-trash"},null,-1)]))]),e("div",null,[e("a",{onClick:t[3]||(t[3]=i(a=>o.$router.push("/map"),["prevent"]))},t[8]||(t[8]=[e("i",{class:"fas fa-times"},null,-1)]))])]),e("div",Y,[f(P,{"location-id":s.value.id,"addable-props":{location_id:s.value.id}},null,8,["location-id","addable-props"])])])):b("",!0),e("div",F,[e("button",{onClick:t[4]||(t[4]=a=>d.value=v(L))}," Google "),e("button",{onClick:t[5]||(t[5]=a=>d.value=v(m))}," Yandex ")]),(r(),p(q(d.value),{places:v(n).items,"selected-location-id":u.value,onSelectLocation:_},{default:A(({lng:a,lat:x,zoom:I})=>[o.$route.query.addLocation==="true"?(r(),p(j,{key:0,lng:a,lat:x,zoom:I},null,8,["lng","lat","zoom"])):b("",!0),t[9]||(t[9]=e("div",{id:"map",class:"tw-absolute width-100 tw-h-full tw-top-0 tw-left-0 z-0"},null,-1))]),_:1},40,["places","selected-location-id"])),e("div",H,[e("button",{onClick:i(C,["prevent"])},D(o.$route.query.addLocation==="true"?"Close":"Add Location"),1)])]))}});export{et as default};
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["./MapGoogle-yCB-fADv.js","./index-NouRpb7z.js","./index-mveRNJY6.css","./MapYandex-p0lDMH0j.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
