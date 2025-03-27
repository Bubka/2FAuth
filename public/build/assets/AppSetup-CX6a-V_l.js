import{_ as z}from"./AdminTabs-1MNcYwxv.js";import{a1 as P,a7 as I,r as f,l as p,m as d,d as u,e,n as T,f as s,t as n,j as l,k as F,g as v,i as H,U as W,u as j,a as E,a8 as q,a0 as G,o as J,L as K,F as O,D as Q,H as X}from"./app-DivTFvlm.js";import{u as w}from"./appSettingsUpdater-CDlP5X7v.js";import{_ as Y}from"./CopyButton-DCuqnyXI.js";/*! 2FAuth version 5.5.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const k=P("web"),C={getSystemInfos(i={}){return k.get("system/infos",{...i})},getLastRelease(i={}){return k.get("system/latestRelease",{...i})},sendTestEmail(i={}){return k.post("system/test-email",{...i})},clearCache(i={}){return k.get("system/clear-cache",{...i})},optimize(i={}){return k.get("system/optimize",{...i})}},Z={class:"columns is-mobile is-vcentered"},ee={class:"column is-narrow"},se={class:"column"},te={class:"is-size-7",href:"https://github.com/Bubka/2FAuth/releases"},ne={key:1,class:"has-text-grey"},ae={key:2,class:"has-text-grey"},oe={__name:"VersionChecker",setup(i){const _=I(),b=f(!1),h=f();async function c(){b.value=!0,h.value=void 0,await C.getLastRelease({returnError:!0}).then(r=>{_.latestRelease=r.data.newRelease,h.value=r.data.newRelease===null?null:r.data.newRelease===!1}).catch(()=>{h.value=null}),b.value=!1}return(r,m)=>{const g=p("FontAwesomeIcon");return d(),u("div",Z,[e("div",ee,[e("button",{type:"button",class:T([s(b)?"is-loading":"","button is-link is-rounded is-small"]),onClick:c},n(r.$t("admin.check_now")),3)]),e("div",se,[l(s(W),null,{default:F(({mode:y})=>[s(_).latestRelease?(d(),u("span",{key:0,class:T(["mt-2",y=="dark"?"has-text-warning":"has-text-warning-dark"])},[m[0]||(m[0]=e("span",{class:"release-flag"},null,-1)),v(n(r.$t("admin.x_is_available",{version:s(_).latestRelease}))+" ",1),e("a",te,n(r.$t("admin.view_on_github")),1)],2)):H("",!0),s(h)?(d(),u("span",ne,[l(g,{icon:["fas","check"],class:"mr-1 has-text-success"}),v(" "+n(r.$t("commons.you_are_up_to_date")),1)])):s(h)===null?(d(),u("span",ae,[l(g,{icon:["fas","times"],class:"mr-1 has-text-danger"}),v(n(r.$t("errors.check_failed_try_later")),1)])):H("",!0)]),_:1})])])}}},le={class:"options-tabs"},ie={class:"title is-4 pt-4 has-text-grey-light"},re={class:"field"},ce=["innerHTML"],de=["innerHTML"],ue=["innerHTML"],me={class:"columns is-mobile is-vcentered"},_e={class:"column is-narrow"},he={class:"icon is-small"},pe={class:"field"},fe=["innerHTML"],be=["innerHTML"],ge={class:"title is-4 pt-5 has-text-grey-light"},ye={class:"title is-4 pt-5 has-text-grey-light"},ke={class:"title is-4 pt-5 has-text-grey-light"},ve={class:"field"},Ce=["innerHTML"],Te=["innerHTML"],$e={class:"field mb-5 is-grouped"},Le={class:"control"},we={class:"field"},Fe=["innerHTML"],He={key:0,class:"about-debug box is-family-monospace is-size-7"},Me=["value"],Ve={class:"has-text-grey"},Ee={key:1,class:"about-debug box is-family-monospace is-size-7 has-text-warning-dark"},Be={__name:"AppSetup",setup(i){const _=K("2fauth"),b=j(),h=E(),c=I(),r=q(_.prefix+"returnTo","accounts"),m=f(),g=f(null),y=f(!1),$=f(!1),S=_.config.subdirectory+"/up",U=location.hostname+_.config.subdirectory+"/up";function R(){y.value=!0,C.sendTestEmail().finally(()=>{y.value=!1})}function B(){$.value=!0,C.clearCache().then(t=>{E().success({type:"is-success",text:X("admin.cache_cleared")})}).finally(()=>{$.value=!1})}return G(t=>{t.name.startsWith("admin.")||h.clear()}),J(async()=>{await c.fetch(),C.getSystemInfos({returnError:!0}).then(t=>{m.value=t.data.common}).catch(()=>{m.value=null})}),(t,a)=>{const L=p("FormCheckbox"),A=p("FontAwesomeIcon"),N=p("FormWrapper"),D=p("ButtonBackCloseCancel"),x=p("VueFooter");return d(),u("div",null,[l(z,{activeTab:"admin.appSetup"}),e("div",le,[l(N,null,{default:F(()=>{var M;return[e("form",null,[e("h4",ie,n(t.$t("settings.general")),1),l(L,{modelValue:s(c).checkForUpdate,"onUpdate:modelValue":[a[0]||(a[0]=o=>s(c).checkForUpdate=o),a[1]||(a[1]=o=>s(w)("checkForUpdate",o))],fieldName:"checkForUpdate",label:"commons.check_for_update",help:"commons.check_for_update_help"},null,8,["modelValue"]),l(oe),e("div",re,[e("label",{class:"label",for:"btnTestEmail",innerHTML:t.$t("admin.forms.test_email.label")},null,8,ce),e("p",{class:"help",innerHTML:t.$t("admin.forms.test_email.help")},null,8,de),e("p",{class:"help",innerHTML:t.$t("admin.forms.test_email.email_will_be_send_to_x",{email:s(b).email})},null,8,ue)]),e("div",me,[e("div",_e,[e("button",{id:"btnTestEmail",type:"button",class:T([s(y)?"is-loading":"","button is-link is-rounded is-small"]),onClick:R},[e("span",he,[l(A,{icon:["far","paper-plane"]})]),e("span",null,n(t.$t("commons.send")),1)],2)])]),e("div",pe,[e("label",{class:"label",for:"lnkHealthCheck",innerHTML:t.$t("admin.forms.health_endpoint.label")},null,8,fe),e("p",{class:"help",innerHTML:t.$t("admin.forms.health_endpoint.help")},null,8,be)]),e("div",{class:"field mb-5"},[e("a",{id:"lnkHealthCheck",target:"_blank",href:S},n(U))]),e("h4",ge,n(t.$t("admin.storage")),1),l(L,{modelValue:s(c).storeIconsInDatabase,"onUpdate:modelValue":[a[2]||(a[2]=o=>s(c).storeIconsInDatabase=o),a[3]||(a[3]=o=>s(w)("storeIconsInDatabase",o))],fieldName:"storeIconsInDatabase",label:"admin.forms.store_icon_to_database.label",help:"admin.forms.store_icon_to_database.help"},null,8,["modelValue"]),e("h4",ye,n(t.$t("settings.security")),1),l(L,{modelValue:s(c).useEncryption,"onUpdate:modelValue":[a[4]||(a[4]=o=>s(c).useEncryption=o),a[5]||(a[5]=o=>s(w)("useEncryption",o))],fieldName:"useEncryption",label:"admin.forms.use_encryption.label",help:"admin.forms.use_encryption.help"},null,8,["modelValue"])]),e("h4",ke,n(t.$t("commons.environment")),1),e("div",ve,[e("label",{for:"btnClearCache",class:"label",innerHTML:t.$t("admin.forms.cache_management.label")},null,8,Ce),e("p",{class:"help",innerHTML:t.$t("admin.forms.cache_management.help")},null,8,Te)]),e("div",$e,[e("p",Le,[e("button",{id:"btnClearCache",type:"button",class:T([s($)?"is-loading":"","button is-link is-rounded is-small"]),onClick:B},n(t.$t("commons.clear")),3)])]),e("div",we,[e("label",{for:"btnCopyEnvVars",class:"label",innerHTML:t.$t("admin.variables")},null,8,Fe)]),s(m)?(d(),u("div",He,[l(Y,{id:"btnCopyEnvVars",token:(M=s(g))==null?void 0:M.innerText},null,8,["token"]),e("ul",{ref_key:"listInfos",ref:g,id:"listInfos"},[(d(!0),u(O,null,Q(s(m),(o,V)=>(d(),u("li",{value:o,key:V},[e("b",null,n(V),1),a[6]||(a[6]=v(": ")),e("span",Ve,n(o),1)],8,Me))),128))],512)])):s(m)===null?(d(),u("div",Ee,n(t.$t("errors.error_during_data_fetching")),1)):H("",!0)]}),_:1})]),l(x,{showButtons:!0},{default:F(()=>[l(D,{returnTo:{name:s(r)},action:"close"},null,8,["returnTo"])]),_:1})])}}};export{Be as default};
//# sourceMappingURL=AppSetup-CX6a-V_l.js.map
