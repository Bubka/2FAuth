import{r as m,l as n,m as t,d as s,j as a,k as i,e as o,F as v,D as _,f as h,n as g,g as k,t as w}from"./app-DivTFvlm.js";/*! 2FAuth version 5.5.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const f={class:"options-header"},b={class:"tabs is-centered is-fullwidth"},R={__name:"SettingTabs",props:{activeTab:{type:String,default:""}},setup(r){const c=m([{name:"settings.options",view:"settings.options",id:"lnkTabOptions"},{name:"settings.account",view:"settings.account",id:"lnkTabAccount"},{name:"settings.oauth",view:"settings.oauth.tokens",id:"lnkTabOAuth"},{name:"settings.webauthn",view:"settings.webauthn.devices",id:"lnkTabWebauthn"}]),l=r;return(d,T)=>{const u=n("RouterLink"),p=n("ResponsiveWidthWrapper");return t(),s("div",f,[a(p,null,{default:i(()=>[o("div",b,[o("ul",null,[(t(!0),s(v,null,_(h(c),e=>(t(),s("li",{key:e.view,class:g({"is-active":e.view===l.activeTab})},[a(u,{id:e.id,to:{name:e.view}},{default:i(()=>[k(w(d.$t(e.name)),1)]),_:2},1032,["id","to"])],2))),128))])])]),_:1})])}}};export{R as _};
//# sourceMappingURL=SettingTabs-flxnbpcs.js.map
