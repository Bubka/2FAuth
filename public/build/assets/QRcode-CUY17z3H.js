import{u as _,$ as m,r as p,a0 as f,J as h,e as c,f as n,g as r,h as a,i as t,l as B,m as s,p as g}from"./app-BsP-5XS6.js";import{S as k}from"./Spinner-CewaMSJd.js";/*! 2FAuth version 5.2.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const v={class:"modal modal-otp is-active"},C=a("div",{class:"modal-background"},null,-1),V={class:"modal-content"},w={class:"has-text-centered m-5"},b=["src","alt"],F={__name:"QRcode",setup(y){_();const l=m(),e=p();f(()=>{i()});async function i(){const{data:o}=await h.getQrcode(l.params.twofaccountId);e.value=o.qrcode}return(o,R)=>{const u=c("ButtonBackCloseCancel"),d=c("VueFooter");return n(),r("div",v,[C,a("div",V,[a("p",w,[t(e)?(n(),r("img",{key:0,src:t(e),class:"has-background-light",alt:o.$t("commons.image_of_qrcode_to_scan")},null,8,b)):B("",!0),s(k,{isVisible:!t(e),type:"raw",class:"is-size-1"},null,8,["isVisible"])])]),s(d,{showButtons:!0,internalFooterType:"modal"},{default:g(()=>[s(u,{returnTo:{name:"accounts"},action:"close"})]),_:1})])}}};export{F as default};
