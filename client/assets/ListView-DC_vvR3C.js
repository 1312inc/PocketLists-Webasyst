import{_ as p}from"./TaskListLayout.vue_vue_type_script_setup_true_lang-hhrEB1vb.js";import{_ as f}from"./TaskList.vue_vue_type_script_setup_true_lang-i_zDL_hB.js";import{u as k}from"./task-UKSQ5NvL.js";import{e as g,M as L,S as h,c as l,w as v,C as c,k as d,o as i,j as I,h as S,m as b,p as B,F as y,i as o}from"./index-xs7JT_ZC.js";import{_ as w}from"./VerticalGap.vue_vue_type_script_setup_true_lang--xZQaFfV.js";import"./dayjs.min-68-sMKMq.js";const C=r=>typeof r<"u",x=o("i",{class:"fas fa-plus"},null,-1),V=[x],R=g({__name:"ListView",setup(r){const n=k(),s=L(),u=h(),t=l(()=>String(u.params.id)),_=l(()=>[s.getItemById(t.value),...s.getSublistsInList(t.value)].filter(C)),m=()=>{var e;s.createList({pocket_id:(e=s.getItemById(t.value))==null?void 0:e.pocket_id,parent_list_id:t.value})};return v(()=>t.value,e=>{n.fetch({list_id:e})},{immediate:!0}),(e,$)=>(i(),c(p,null,{default:d(()=>[I(w,{space:4},{default:d(()=>[(i(!0),S(y,null,b(_.value,a=>(i(),c(f,{key:a.id,items:B(n).getTasksInList(a.id),"sortable-list-id":a.id,"addable-props":{list_id:a.id}},null,8,["items","sortable-list-id","addable-props"]))),128)),o("div",{class:"align-center"},[o("button",{class:"gray",onClick:m},V)])]),_:1})]),_:1}))}});export{R as default};