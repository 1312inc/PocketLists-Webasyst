import{d as M,Y as N,y as B,Z as E,r as R,$ as S,i as V,j as w,x as p,c as h,b as e,h as f,w as i,I as b,e as v,a0 as A,l as T,t as q,a1 as z,o as r,a2 as g,K as D,a3 as k}from"./index-S31yj8BE.js";import{_ as P}from"./TaskList.vue_vue_type_script_setup_true_lang-Ilr7zxvO.js";import{_ as Y}from"./ChooseLocation.vue_vue_type_script_setup_true_lang-cCW0KCZM.js";import{C as j}from"./ContenteditableBlock-DTqsoSYe.js";import"./rangy-5rxgwlWZ.js";import"./LazyRender.vue_vue_type_script_setup_true_lang-c1okelhM.js";const G={key:0,class:"tw-absolute tw-pointer-events-auto tw-flex tw-flex-col tw-overflow-hidden tw-z-10 tw-right-2 tw-top-12 tw-bottom-26 tw-bg-white tw-p-4 tw-w-64"},O={class:"tw-flex-none tw-flex tw-gap-8 tw-justify-between"},U={class:"tw-flex-auto"},K={class:"tw-absolute tw-z-10 tw-bottom-4 tw-left-1/2 tw-bg-white tw-p-4 tw-flex tw-gap-2"},Z={class:"tw-absolute tw-top-2 tw-right-2 tw-bg-white"},et=M({__name:"MapView",setup(F){N(`
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
`);const s=B(),L=g(()=>k(()=>import("./MapGoogle-U1Ay65ZN.js"),__vite__mapDeps([0,1,2]),import.meta.url)),c=g(()=>k(()=>import("./MapYandex-Rb9HMFYY.js"),__vite__mapDeps([3,1,2]),import.meta.url)),d=E(c),m=R(!1),n=S();V();const u=w(()=>D(s.currentRoute.value.params.id)),l=w(()=>u.value?n.getItemById(u.value):void 0),_=o=>{s.push(`/map/${o}`)},y=()=>{const o={...s.currentRoute.value.query};o.addLocation==="true"?delete o.addLocation:o.addLocation="true",s.push({path:"/map",query:o})},C=(o,t)=>{n.updateItemName(o,t)},$=o=>{n.deleteItem(o),s.push("/map/")};return(o,t)=>(r(),p(z,{to:"#app"},[l.value?(r(),h("div",G,[e("div",O,[f(j,{editable:m.value,focusable:!0,value:l.value.name,tag:"div",onUpdate:t[0]||(t[0]=a=>C(l.value,a))},null,8,["editable","value"]),e("div",null,[e("a",{onClick:t[1]||(t[1]=i(a=>m.value=!0,["prevent"]))},t[6]||(t[6]=[e("i",{class:"fas fa-pen"},null,-1)]))]),e("div",null,[e("a",{onClick:t[2]||(t[2]=i(a=>$(l.value),["prevent"]))},t[7]||(t[7]=[e("i",{class:"fas fa-trash"},null,-1)]))]),e("div",null,[e("a",{onClick:t[3]||(t[3]=i(a=>o.$router.push("/map"),["prevent"]))},t[8]||(t[8]=[e("i",{class:"fas fa-times"},null,-1)]))])]),e("div",U,[f(P,{"location-id":l.value.id,"addable-props":{location_id:l.value.id}},null,8,["location-id","addable-props"])])])):b("",!0),e("div",K,[e("button",{onClick:t[4]||(t[4]=a=>d.value=v(L))}," Google "),e("button",{onClick:t[5]||(t[5]=a=>d.value=v(c))}," Yandex ")]),(r(),p(A(d.value),{places:v(n).items,"selected-location-id":u.value,onSelectLocation:_},{default:T(({lng:a,lat:x,zoom:I})=>[o.$route.query.addLocation==="true"?(r(),p(Y,{key:0,lng:a,lat:x,zoom:I},null,8,["lng","lat","zoom"])):b("",!0),t[9]||(t[9]=e("div",{id:"map",class:"tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-z-0"},null,-1))]),_:1},40,["places","selected-location-id"])),e("div",Z,[e("button",{onClick:i(y,["prevent"])},q(o.$route.query.addLocation==="true"?"Close":"Add Location"),1)])]))}});export{et as default};
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["./MapGoogle-U1Ay65ZN.js","./index-S31yj8BE.js","./index-cY_Y8EUy.css","./MapYandex-Rb9HMFYY.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
