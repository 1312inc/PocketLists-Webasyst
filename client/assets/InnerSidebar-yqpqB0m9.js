import{_ as I,o as c,h as u,I as B,e as k,r as f,J as M,F as b,m as L,C as $,k as m,i as s,A as T,K as y,w as N,n as x,L as D,M as w,N as V,z as P,O as z,P as g,j as v,G as C,t as E,Q as h,p,R as F}from"./index-K6II-ufU.js";import{_ as H}from"./VerticalGap.vue_vue_type_script_setup_true_lang-1lqX3BGn.js";import{u as W}from"./task-E_cC_ALL.js";const J={},O={class:"bricks"},j=B('<div class="brick pl-bg-block" data-v-53ea13ed><span class="icon" data-v-53ea13ed><i class="fas fa-star" data-v-53ea13ed></i></span><span class="count" data-v-53ea13ed>495</span> Star </div><div class="brick pl-bg-block" data-v-53ea13ed><span class="icon" data-v-53ea13ed><i class="fas fa-bolt" data-v-53ea13ed></i></span><span class="count" data-v-53ea13ed>33</span> Bolt </div>',2),A=[j];function G(d,t){return c(),u("div",O,A)}const K=I(J,[["render",G],["__scopeId","data-v-53ea13ed"]]),Q=["data-type","data-id"],U=["onClick"],Y=k({__name:"WaMenu",props:{items:{},routeName:{},large:{type:Boolean}},setup(d){const t=d,l=f([]);return(a,n)=>{const r=M("RouterLink");return c(),u("ul",{class:y(["menu",{large:t.large}])},[(c(!0),u(b,null,L(a.items,(i,_)=>(c(),$(r,{key:i.name,to:{name:t.routeName,params:{id:i.id}},custom:""},{default:m(({navigate:e,isActive:o})=>[s("li",{ref_for:!0,ref_key:"menuItemElementRef",ref:l,class:y({selected:o}),"data-type":t.routeName,"data-id":i.id},[s("a",{onClick:e},[T(a.$slots,"default",{item:i,menuItemElementRef:l.value[_]},void 0,!0)],8,U)],10,Q)]),_:2},1032,["to"]))),128))],2)}}}),q=I(Y,[["__scopeId","data-v-8b167023"]]),R=k({__name:"ContenteditableBlock",props:{editable:{type:Boolean},focusable:{type:Boolean},tag:{}},emits:["update","blur"],setup(d,{emit:t}){const l=t,a=d,n=f(),r=f(""),i=e=>{r.value=e.target.innerText},_=e=>{l("blur");const o=e.target.innerText;o!==r.value&&l("update",o)};return N(()=>[a.editable,a.focusable],([e,o])=>{e&&o&&n.value&&x(()=>{n.value.focus()})}),(e,o)=>(c(),$(D(a.tag),{ref_key:"contenteditableRef",ref:n,contenteditable:a.editable,onFocus:i,onBlur:_},{default:m(()=>[T(e.$slots,"default")]),_:3},40,["contenteditable"]))}}),X={class:"icon size-32"},Z=["src"],ee=s("i",{class:"fas fa-pen"},null,-1),te=[ee],se=s("i",{class:"fas fa-trash-alt"},null,-1),ae=[se],ne=s("span",{class:"count"},"88",-1),oe=k({__name:"ListsListItem",props:{item:{},menuItemElementRef:{}},setup(d){const t=d,l=W(),a=w(),n=V(),r=f(!1);P(async()=>{await z(()=>t.menuItemElementRef).toBeTruthy(),g(t.menuItemElementRef,"dragenter",e=>{e.currentTarget instanceof HTMLElement&&e.currentTarget.classList.add(n.dragover)}),g(t.menuItemElementRef,"dragleave",e=>{e.currentTarget instanceof HTMLElement&&e.target===e.currentTarget&&e.currentTarget.classList.remove(n.dragover)}),g(t.menuItemElementRef,"dragover",e=>{e.preventDefault()}),g(t.menuItemElementRef,"drop",e=>{if(e.currentTarget instanceof HTMLElement&&(e.preventDefault(),e.currentTarget.classList.remove(n.dragover),e.dataTransfer))try{const o=JSON.parse(e.dataTransfer.getData("Text")).taskIds;l.sort(t.item.id,o)}catch(o){console.error(o)}})});const i=e=>{a.updateItemName(t.item,e)},_=()=>{a.deleteItem(t.item)};return(e,o)=>(c(),u(b,null,[s("span",X,[s("img",{src:e.item.icon,alt:""},null,8,Z)]),v(R,{editable:r.value,focusable:!0,tag:"span",onBlur:o[0]||(o[0]=S=>r.value=!1),onUpdate:i},{default:m(()=>[C(E(e.item.name),1)]),_:1},8,["editable"]),s("a",{class:"count",onClick:o[1]||(o[1]=h(S=>r.value=!r.value,["prevent"]))},te),s("a",{class:"count",onClick:h(_,["prevent"])},ae),ne],64))}}),re="_dragover_3d86c_1",le={dragover:re},ce={$style:le},ie=I(oe,[["__cssModules",ce]]),de={class:"heading black tw-m-0 tw-mb-2"},ue=s("i",{class:"fas fa-pen"},null,-1),me=[ue],_e=s("i",{class:"fas fa-plus-circle"},null,-1),pe=[_e],fe={class:"pl-bg-block tw-rounded-xl tw-overflow-hidden"},ve={key:1,class:"tw-px-4 tw-py-3 gray"},ke=k({__name:"ListsList",props:{pocketId:{},title:{}},setup(d){const t=w(),l=f(!1);return(a,n)=>(c(),u(b,null,[s("h5",de,[v(R,{tag:"span",editable:l.value,focusable:!0},{default:m(()=>[C(E(a.title),1)]),_:1},8,["editable"]),s("a",{class:"count",onClick:n[0]||(n[0]=h(r=>l.value=!l.value,["prevent"]))},me),s("a",{class:"count action",onClick:n[1]||(n[1]=h(r=>p(t).createList({pocket_id:a.pocketId}),["prevent"]))},pe)]),s("div",fe,[p(t).getListsInPocket(a.pocketId).length?(c(),$(q,{key:0,large:!0,items:p(t).getListsInPocket(a.pocketId),"route-name":"list"},{default:m(({item:r,menuItemElementRef:i})=>[v(ie,{item:r,"menu-item-element-ref":i},null,8,["item","menu-item-element-ref"])]),_:1},8,["items"])):(c(),u("div",ve," No Lists Yet "))])],64))}}),ge=s("form",{class:"state-with-inner-icon right tw-w-full"},[s("input",{type:"search",placeholder:"Search",class:"tw-w-full"}),s("button",{class:"icon"},[s("i",{class:"fas fa-search"})])],-1),he={class:"align-center"},be=s("i",{class:"fa fa-plus"},null,-1),$e=[be],Te=k({__name:"InnerSidebar",setup(d){const t=F();return(l,a)=>(c(),$(H,{space:4},{default:m(()=>[ge,v(K),(c(!0),u(b,null,L(p(t).pockets,n=>(c(),u("div",{key:n.id},[v(ke,{title:n.name,"pocket-id":n.id},null,8,["title","pocket-id"])]))),128)),s("div",he,[s("button",{class:"gray",onClick:a[0]||(a[0]=n=>p(t).createPocket())},$e)])]),_:1}))}});export{Te as default};