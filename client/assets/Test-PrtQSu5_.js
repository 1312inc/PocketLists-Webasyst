import{d as U,u as I,f as b,l as C,a as B,c as p,_ as N,b as q,g as K,e as y,h as O,o as h,i as x,j as e,w as j,t as g,k as T,O as z,r as k,m as S,n as $,p as G,T as H,q as Q,v as J,s as P,x as W,F as X,y as Y,z as Z,A as L}from"./index-Pg7ADOuv.js";import{u as ee}from"./task--4_ZpFnT.js";const A=U("comments",()=>{const u=ee(),t=I(b(C(()=>B.comments.toArray())),{initialValue:[]}),o=p(()=>t.value.filter(n=>!n.deleted)),c=p(()=>n=>o.value.find(s=>s.id===n)),i=p(()=>n=>I(b(C(()=>B.comments.where({item_id:n}).sortBy("create_datetime"))),{initialValue:[]})),a=p(()=>n=>{const s=u.getItemById(n);return N.sortBy(o.value.filter(l=>l.item_id===(s==null?void 0:s.uuid)||l.item_id===(s==null?void 0:s.id)),["create_datetime"])}),{defineFetch:r,defineFetchAdd:d,defineFetchUpdate:_,defineFetchDelete:v,commit:f,deleteItem:m}=q("comments"),R=r("pocketlists.comment.getList"),D=d("pocketlists.comment.add"),F=_("pocketlists.comment.update"),V=v("pocketlists.comment.delete"),M=(n,s)=>{const l=K(n,s);l&&f(l)};return u.$onAction(({name:n,after:s})=>{n==="fetchAdd"&&s(l=>{l==null||l.forEach(w=>{w.uuid&&a.value(w.uuid).forEach(E=>{updateItem(E,{item_id:w.id},{quietly:!0})})})}),n==="deleteItem"&&s(l=>{a.value(l).forEach(w=>{m(w,{force:!0})})})}),{allItems:t,items:o,getItemById:c,getMessgesByTaskId:i,insert:M,deleteItem:m,fetch:R,fetchAdd:D,fetchUpdate:F,fetchDelete:V}}),te={class:"tw-flex tw-flex-col tw-gap-2 tw-bg-background tw-p-4 tw-rounded-lg tw-rounded-tl-0 tw-text-sm tw-mr-4"},se={class:"tw-flex tw-gap-2"},oe={class:"tw-truncate"},ae={class:"hint"},ne={class:"hint"},le=y({__name:"TaskChatItem",props:{message:{}},setup(u){const t=u,o=O(),c=A(),i=()=>{c.deleteItem(t.message)};return(a,r)=>{var d;return h(),x("div",te,[e("a",{onClick:j(i,["prevent"])},r[0]||(r[0]=[e("i",{class:"fas fa-times"},null,-1)])),e("div",se,[e("div",oe,g((d=T(o).getItemById(t.message.contact_id))==null?void 0:d.name),1),e("div",ae,g(T(z)(t.message.create_datetime).format("ll")),1)]),e("div",null,g(t.message.comment),1),e("div",ne,g(typeof t.message=="number"?"delivered":"not delivered"),1)])}}}),ce={class:"tw-h-full tw-flex tw-flex-col"},ie={class:"tw-flex-none tw-sticky tw-py-3 pl-bg-block"},re={class:"state-with-inner-icon right tw-w-full"},de=y({__name:"TaskChat",props:{taskId:{}},setup(u){const t=u,o=A(),c=k(),i=k(),a=p(()=>o.getMessgesByTaskId(t.taskId)),r=k(!1),d=k("");S(()=>t.taskId,v=>{o.fetch({item_id:v})},{immediate:!0}),S(a.value,async()=>{await W(),c.value&&(c.value.scrollTop=c.value.scrollHeight),r.value=!0});const _=async()=>{await o.insert(t.taskId,d.value),d.value="",i.value.focus()};return(v,f)=>(h(),x("div",ce,[e("div",{ref_key:"chatContainerRef",ref:c,class:"tw-flex-auto tw-flex tw-flex-col tw-gap-3 tw-overflow-x-hidden tw-overflow-y-auto"},[$(H,{name:"slide-right",css:r.value},{default:G(()=>[(h(!0),x(X,null,Y(a.value.value,m=>(h(),Z(le,{key:m.id,message:m},null,8,["message"]))),128))]),_:1},8,["css"])],512),e("div",ie,[e("div",re,[Q(e("input",{ref_key:"inputRef",ref:i,"onUpdate:modelValue":f[0]||(f[0]=m=>d.value=m),type:"text",placeholder:"Type a message",class:"tw-w-full",onKeydown:P(_,["enter"])},null,544),[[J,d.value]]),e("button",{class:"icon",onClick:_},f[1]||(f[1]=[e("i",{class:"fas fa-arrow-right"},null,-1)]))])])]))}}),ue={class:"pl-bg-block tw-rounded-xl lg:tw-rounded-r-0 tw-h-full tw-h-full tw-flex tw-flex-col"},me={class:"tw-flex-auto tw-flex tw-flex-col tw-gap-4 tw-overflow-hidden tw-p-3 tw-pb-0"},fe={class:"tw-flex-auto tw-min-h-0"},he=y({__name:"Test",props:{taskId:{}},setup(u){const t=u,o=L(),c=()=>{var a;const i=(a=o.currentRoute.value.matched.find(r=>r.meta.isListView))==null?void 0:a.name;i?o.push({name:i}):console.error("Cant find back route")};return(i,a)=>(h(),x("div",ue,[e("div",{class:"tw-flex-none tw-p-3"},[e("button",{class:"circle gray",onClick:c},a[0]||(a[0]=[e("i",{class:"fas fa-times"},null,-1)]))]),e("div",me,[e("div",fe,[$(de,{"task-id":t.taskId},null,8,["task-id"])])])]))}});export{he as default};